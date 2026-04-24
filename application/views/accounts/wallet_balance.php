<?php 
    $all_method =& get_instance(); 
    // Load the helper if not loaded in controller
    $this->load->helper('billing');
    
    // Fetch real-time balance using the function we built
    $w = get_final_wallet_balance($patient_data['patient_id']); 
    $balance = $w['balance']; 
?>

 <?php $all_method = &get_instance(); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid #28a745; background: #f8fff9;">
                <div class="card-body">
                    <h6 class="text-success font-weight-bold">Money Wallet</h6>
                    <h2 class="display-5">₹ <?php echo number_format($wallets['wallet_1_balance'], 2); ?></h2>
                    <button type="button" class="btn btn-success btn-sm mt-2" data-toggle="modal" data-target="#addMoneyModal">+ Add Money</button>
                    <button type="button" class="btn btn-warning btn-sm text-white mt-2" data-toggle="modal" data-target="#transferModal">⇆ Transfer from Package Wallet</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid #ff9800; background: #fffaf2;">
                <div class="card-body">
                    <h6 class="text-warning font-weight-bold">Package Wallet</h6>
                    <h2 class="display-5">₹ <?php echo number_format($wallets['wallet_2_balance'], 2); ?></h2>
                    <button class="btn btn-success btn-sm mt-2" data-target="#addPackageMoneyModal" data-toggle="modal">+ Add Money</button>
                    <button type="button" class="btn btn-outline-warning btn-sm mt-2" data-toggle="modal" data-target="#transferBackModal">⇆ Transfer from Money Wallet</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMoneyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo base_url('accounts/add_money_to_w1'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-white">Deposit to Money Wallet</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="<?php echo $paitent_id; ?>">
                    <div class="form-group">
                        <label>Deposit Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Mode</label>
                        <select name="mode" class="form-control">
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload Screenshot (Optional)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*">
                        <small class="text-muted">Max size: 2MB (JPG/PNG)</small>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Amount</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo base_url('accounts/transfer_wallet_money'); ?>" method="POST">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Transfer funds from Money Wallet to Package Wallet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="<?php echo $paitent_id; ?>">
                    <div class="form-group">
                        <label>Amount to Transfer Package Wallet</label>
                        <input type="number" name="amount" class="form-control" max="<?php echo $wallets['wallet_1_balance']; ?>" step="0.01" required placeholder="0.00">
                        <small class="text-danger font-weight-bold">Available in Money Wallet: ₹<?php echo number_format($wallets['wallet_1_balance'], 2); ?></small>
                    </div>
                    <div class="form-group">
                        <label>Purpose/Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Move money for IVF Cycle" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white">Confirm Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="transferBackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('accounts/request_w2_to_w1'); ?>" method="POST">
                <div class="modal-header bg-primary text-white"><h5>Transfer funds from Package Wallet To Money Wallet</h5></div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="<?php echo $paitent_id; ?>">
                    <div class="form-group">
                        <label>Amount to Transfer Money Wallet</label>
                        <input type="number" name="amount" class="form-control" max="<?php echo $wallets['wallet_2_balance']; ?>" step="0.01" required>
                        <small>Available Balance Package Wallet: ₹<?php echo $wallets['wallet_2_balance']; ?></small>
                    </div>
                    <div class="form-group">
                        <label>Reason for Transfer Money Wallet</label>
                        <textarea name="remarks" class="form-control" required></textarea>
                    </div>
                    <p class="text-danger small">*This transfer requires Accountant Approval.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addPackageMoneyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo base_url('accounts/add_money_to_package'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Deposit to Package Wallet</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="<?php echo $paitent_id; ?>">
                    
                    <div class="form-group">
                        <label>Deposit Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label>Payment Mode</label>
                        <select name="mode" class="form-control">
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Upload Screenshot (Optional)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*">
                        <small class="text-muted">Max size: 2MB (JPG/PNG)</small>
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add to Package</button>
                </div>
            </form>
        </div>
    </div>
</div>


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
                        <tr class="info">
                            <th>Receipt #</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Amount Added</th>
                            <th>Status</th>
                            <th>CN Invoice</th>
                        </tr>
                    </thead>
                              <?php

$sql_par = "Select * from medicine_returns where patient_id='".$patient_data['patient_id']."' and status='APPROVED'";

$query = $this->db->query($sql_par);

$select_result_par = $query->result();

foreach ($select_result_par as $res_val_par){ ?>

<tbody id="procedure_result">

<tr class="odd gradeX">

<td><?php echo $res_val_par->receipt_number; ?></td>

<td>Medicine</td>

<td><?php echo date('d-M-y', strtotime($res_val_par->return_date)); ?></td>

<td class="text-green">+ <?php echo number_format($res_val_par->final_return_amount, 2); ?></td>

<td><?php echo $res_val_par->status; ?></td>

<td><?php echo $res_val_par->return_number; ?></td>

</tr>

</tbody>

<?php } ?>
                    <tbody>
                        <?php 
                        $db_prefix = $this->config->item('db_prefix');
                        $inflow_types = [
                            'consultation' => "status='adjust'",
                            'registation'  => "status='adjust'",
                            'patient_procedure' => "status='cancel'",
                            'patient_payments' => "status='3'",
                            'patient_medicine' => "status='cancel'"
                        ];

                        foreach($inflow_types as $tbl => $where):
                            $results = $this->db->query("SELECT * FROM {$db_prefix}{$tbl} WHERE patient_id='{$patient_data['patient_id']}' AND $where")->result();
                            foreach($results as $res): ?>
                            <tr>
                                <td><?php echo $res->receipt_number ?? ($res->billing_id ?? '-'); ?></td>
                                <td><?php echo ucfirst(str_replace('patient_', '', $tbl)); ?></td>
                                <td><?php echo date('d-M-y', strtotime($res->on_date)); ?></td>
                                <td class="text-green">+ <?php echo number_format($res->payment_done, 2); ?></td>
                                <td><span class="label label-info"><?php echo $res->status; ?></span></td>
                                <td><?php echo $res->cn_invoice ?? '-'; ?></td>
                            </tr>
                        <?php endforeach; endforeach; ?>
                    </tbody>
                </table>
            </div>

            <br>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="bg-black" style="background:#333; color:#fff;">
                            <th colspan="7">Wallet Expenditure (Money Used)</th>
                        </tr>
                        <tr class="warning">
                            <th>Receipt #</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Amount Deducted</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <?php

$sql_par = "Select * from sales where patient_id='".$patient_data['patient_id']."' and payment_method='wallet' and status='CONFIRMED'";

$query = $this->db->query($sql_par);

$select_result_par = $query->result();

foreach ($select_result_par as $res_val_par){ ?>

<tbody id="procedure_result">

<tr class="odd gradeX">

<td><?php echo $res_val_par->sale_number; ?></td>

<td>Medicine</td>

<td><?php echo date('d-M-y', strtotime($res->on_date)); ?></td>

<td class="text-red">- <?php echo number_format($res_val_par->total_amount, 2); ?></td>

<td><?php echo $res_val_par->accountant_approval_status; ?></td>

<td><span class="label label-default">Wallet</span></td>

</tr>

</tbody>

<?php } ?>
                    <tbody>
                        <?php 
                        // Tables where wallet is used as a payment method
                        $outflow_tables = ['patient_procedure', 'patient_medicine', 'patient_investigations', 'consultation', 'patient_payments'];
                        $found_outflow = false;

                        foreach($outflow_tables as $tbl):
                            $results = $this->db->query("SELECT * FROM {$db_prefix}{$tbl} WHERE patient_id='{$patient_data['patient_id']}' AND LOWER(payment_method)='wallet'")->result();
                            foreach($results as $res): 
                                $found_outflow = true; ?>
                            <tr>
                                <td><?php echo $res->receipt_number ?? ($res->billing_id ?? '-'); ?></td>
                                <td><?php echo ucfirst(str_replace('patient_', '', $tbl)); ?></td>
                                <td><?php echo date('d-M-y', strtotime($res->on_date)); ?></td>
                                <td class="text-red">- <?php echo number_format($res->payment_done, 2); ?></td>
                                <td><span class="label label-success"><?php echo $res->status; ?></span></td>
                                <td><span class="label label-default">Wallet</span></td>
                            </tr>
                        <?php endforeach; endforeach; 
                        
                        if(!$found_outflow): ?>
                            <tr><td colspan="6" class="text-center text-muted">No wallet deductions found.</td></tr>
                        <?php endif; ?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    console.log("Wallet Script Initialized");

    // Manual click handler
    $(document).on('click', '.btn-open-modal', function(e) {
        e.preventDefault();
        var modalId = $(this).attr('data-id');
        
        console.log("Attempting to open modal: " + modalId);
        
        // Modal ko open karne ke teen alag tarike (Force backup)
        try {
            $('#' + modalId).modal('show');
        } catch (err) {
            console.log("Bootstrap Modal Error, trying alternative...");
            $('#' + modalId).addClass('in').css('display', 'block').show();
            $('body').append('<div class="modal-backdrop fade in"></div>');
        }
    });

    // Close buttons ke liye
    $(document).on('click', '[data-dismiss="modal"]', function() {
        $('.modal').modal('hide').hide();
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
});
</script>