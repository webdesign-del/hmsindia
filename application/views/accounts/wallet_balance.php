<?php 
    $all_method =& get_instance(); 
    // Load the helper if not loaded in controller
    $this->load->helper('billing');
    
    // Fetch real-time balance using the function we built
    $w = get_final_wallet_balance($patient_data['patient_id']); 
    $balance = $w['balance']; 
?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="well" style="background: #ecf0f5; border-left: 5px solid #00a65a;">
            <h4 style="margin-top:0;">Wallet Summary</h4>
            <p>Total Added: <strong><?php echo number_format($w['total_added'], 2); ?></strong></p>
            <p>Total Spent: <strong><?php echo number_format($w['total_spent'], 2); ?></strong></p>
            <p>Available Balance: <span style="color:green; font-size: 20px; font-weight:bold;">₹<?php echo number_format($balance, 2); ?></span></p>
        </div>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo base_url('accounts/export_wallet_used_history/' . $patient_data['patient_id']); ?>" class="btn btn-success">
           <i class="fa fa-file-excel-o"></i> Download CSV History
        </a>
    </div>
</div>

<form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="action" value="wallet_balance" />
    <input type="hidden" name="patient_id" value="<?php echo $patient_data['patient_id']; ?>" id="patient_id" />

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Patient ID: <?php echo $patient_data['patient_id']; ?></h3>
        </div>
        
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="bg-gray">
                            <th colspan="7">Money Added to Wallet (Refunds/Cancellations)</th>
                        </tr>
                        <tr>
                            <th>Receipt #</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Paid Amount</th>
                            <th>Status</th>
                            <th>CN Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Simplified unified query logic for display
                        $db_prefix = $this->config->item('db_prefix');
                        $types = [
                            'consultation' => "status='adjust'",
                            'registation'  => "status='adjust'",
                            'patient_procedure' => "status='cancel'",
                            'patient_payments' => "status='3'"
                        ];

                        foreach($types as $tbl => $where):
                            $results = $this->db->query("SELECT * FROM {$db_prefix}{$tbl} WHERE patient_id='{$patient_data['patient_id']}' AND $where")->result();
                            foreach($results as $res): ?>
                            <tr>
                                <td><?php echo $res->receipt_number; ?></td>
                                <td><?php echo ucfirst(str_replace('patient_', '', $tbl)); ?></td>
                                <td><?php echo date('d-M-y', strtotime($res->on_date)); ?></td>
                                <td><?php echo $res->payment_done; ?></td>
                                <td><span class="label label-info"><?php echo $res->status; ?></span></td>
                                <td><?php echo $res->cn_invoice; ?></td>
                            </tr>
                        <?php endforeach; endforeach; ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <h4>Deduct from Wallet</h4>
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>Package Code</label>
                    <input type="text" name="package_code" required class="form-control">
                </div>
                <div class="form-group col-sm-4">
                    <label>Consultation Fee</label>
                    <input type="number" id="consultation_fee" name="consultation_fee" value="0" class="form-control calc" oninput="checkTotalAmount()">
                </div>
                <div class="form-group col-sm-4">
                    <label>USG Scan Charge</label>
                    <input type="number" id="usg_scan_charge" name="usg_scan_charge" value="0" class="form-control calc" oninput="checkTotalAmount()">
                </div>
                <div class="form-group col-sm-4">
                    <label>Consumable Charges</label>
                    <input type="number" id="consumable_charges" name="consumable_charges" value="0" class="form-control calc" oninput="checkTotalAmount()">
                </div>
                <div class="form-group col-sm-4">
                    <label>Refund Amount</label>
                    <input type="number" id="refund_amount" name="refund_amount" value="0" class="form-control calc" oninput="checkTotalAmount()">
                </div>
            </div>

            <div id="error_message" class="alert alert-danger" style="display: none;">
                <i class="fa fa-warning"></i> Total exceeds available wallet balance of <strong>₹<?php echo number_format($balance, 2); ?></strong>
            </div>

            <div class="box-footer">
                <button type="submit" id="submitbutton" class="btn btn-primary">Process Wallet Update</button>
            </div>
        </div>
    </div>
</form>

<script>
function checkTotalAmount() {
    var total = 0;
    // Sum up all inputs with class 'calc'
    var inputs = document.getElementsByClassName('calc');
    for(var i=0; i<inputs.length; i++) {
        total += parseFloat(inputs[i].value) || 0;
    }

    var walletBalance = parseFloat('<?php echo $balance; ?>');
    var btn = document.getElementById("submitbutton");
    var err = document.getElementById("error_message");

    if (total > walletBalance) {
        err.style.display = "block";
        btn.disabled = true;
    } else {
        err.style.display = "none";
        btn.disabled = false;
    }
}
</script>