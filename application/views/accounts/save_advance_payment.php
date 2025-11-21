<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-money-bill-wave"></i> Add Advance Payment</h4>
    </div>

    <div class="card-body">
        <form id="advancePaymentForm" autocomplete="off">

            <div class="row">
                <!-- Patient ID -->
                <div class="col-md-4 form-group">
                    <label>Patient ID / IIC ID <span class="text-danger">*</span></label>
                    <input type="text" name="patient_id" id="patient_id" class="form-control" required>
                    <input type="hidden" name="employee_number" value="<?php echo $this->session->userdata('employee_number'); ?>">
                </div>

                <!-- Payment Date -->
                <div class="col-md-4 form-group">
                    <label>Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Amount -->
                <div class="col-md-4 form-group">
                    <label>Amount (Rs) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <!-- Payment Mode -->
                <div class="col-md-4 form-group">
                    <label>Payment Mode <span class="text-danger">*</span></label>
                    <select name="payment_mode" id="payment_mode" class="form-control" required>
                        <option value="">-- Select Mode --</option>
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="UPI">UPI</option>
                        <option value="NEFT">NEFT / IMPS</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>

                <!-- Transaction / Ref -->
                <div class="col-md-4 form-group" id="txn_div" style="display:none;">
                    <label>Transaction / Ref ID</label>
                    <input type="text" name="transaction_id" class="form-control">
                </div>
            </div>

            <div class="row">
                <!-- Remarks -->
                <div class="col-md-12 form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <hr>

            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check-circle"></i> Save Payment
            </button>

            <div id="responseMessage" class="mt-3"></div>

        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    $("#payment_mode").change(function(){
        var mode = $(this).val();
        if(mode === "Cash" || mode === "") {
            $("#txn_div").hide();
        } else {
            $("#txn_div").show();
        }
    });

    $("#advancePaymentForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url('accounts/save_advance_payment'); ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res){
                if(res.success){
                    $("#responseMessage").html('<div class="alert alert-success">'+res.message+'</div>');
                    $("#advancePaymentForm")[0].reset();
                    $("#txn_div").hide();
                } else {
                    $("#responseMessage").html('<div class="alert alert-danger">'+res.message+'</div>');
                }
            },
            error: function(){
                $("#responseMessage").html('<div class="alert alert-danger">Server Error</div>');
            }
        });
    });
});
</script>
