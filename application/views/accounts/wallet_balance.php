<?php 
    $all_method =& get_instance(); 
    $CI =& get_instance();

    // 🎯 1. Safely fetch Patient ID
    $current_patient_id = isset($patient_data['patient_id']) ? $patient_data['patient_id'] : (isset($paitent_id) ? $paitent_id : '');

    // 🎯 2. Fetch real-time wallet balance
    $w = get_final_wallet_balance($current_patient_id); 
    $balance = $w['balance'] ?? 0; 

    // 🎯 3. Freeze Status & Reason Check
    $is_frozen     = !empty($wallets['is_frozen']) && $wallets['is_frozen'] == 1;
    $freeze_reason = $wallets['freeze_reason'] ?? 'No reason provided';

    // 🎯 4. Current Financial Year Suffix (2026 => 2627)
    $current_year_suffix = date("y") . (date("y") + 1); 

    // 🎯 5. Center Code & Receipt Number Generator
    $db_prefix = $all_method->config->item('db_prefix');
    $center_session = $_SESSION['logged_billing_manager']['center'] ?? $_SESSION['logged_accountant']['center'] ?? '';
    
    $sql2 = "SELECT * FROM `".$db_prefix."centers` WHERE center_number='".$center_session."'"; 
    $center_result = run_select_query($sql2);
    $center_code = !empty($center_result['state_prefix']) ? $center_result['state_prefix'] : 'CENTER';

    $sql_receipt = "SELECT MAX(CAST(SUBSTRING_INDEX(receipt_number, '/', -1) AS UNSIGNED)) as last_number 
                    FROM `hms_wallet_logs` 
                    WHERE receipt_number LIKE 'PR/".$center_code."/".$current_year_suffix."/%'";
    $query_res = $CI->db->query($sql_receipt)->row_array();

    $last_receipt_num = (!empty($query_res['last_number'])) ? intval($query_res['last_number']) : 0;
    $next_receipt_num = str_pad(($last_receipt_num + 1), 4, '0', STR_PAD_LEFT);
    $final_receipt_number = "PR/".$center_code."/".$current_year_suffix."/".$next_receipt_num;

    // 🎯 6. Role Checking (Checks Accountant & Billing Manager Sessions)
    $user_role = '';
    if (isset($_SESSION['logged_accountant']['role'])) {
        $user_role = $_SESSION['logged_accountant']['role'];
    } elseif (isset($_SESSION['logged_billing_manager']['role'])) {
        $user_role = $_SESSION['logged_billing_manager']['role'];
    }
?>

<div class="container-fluid mt-4">

    <!-- 🚨 1. PROMINENT FREEZE WARNING BANNER -->
    <?php if ($is_frozen): ?>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12">
                <div class="alert alert-danger shadow-sm" style="border-left: 6px solid #d9534f; background-color: #f2dede; color: #a94442; padding: 15px 20px; border-radius: 6px;">
                    <h4 style="margin-top: 0; font-weight: bold; font-size: 18px;">
                        <i class="fa fa-lock" style="font-size: 22px; margin-right: 8px;"></i> WALLET IS CURRENTLY FROZEN!
                    </h4>
                    <p style="margin-bottom: 0; font-size: 14px;">
                        <b>Reason:</b> <?php echo htmlspecialchars($freeze_reason); ?>
                    </p>
                    <small style="color: #8a6d3b; margin-top: 5px; display: block;">
                        * All add money, transfers, and deductions are blocked until unfrozen by the Accounts Team.
                    </small>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Top Wallet Cards Row -->
    <div class="row">
        <!-- Money Wallet Card -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid <?php echo $is_frozen ? '#d9534f' : '#28a745'; ?>; background: <?php echo $is_frozen ? '#fff5f5' : '#f8fff9'; ?>; min-height: 160px; padding: 15px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-success font-weight-bold" style="display:inline-block;">Money Wallet</h6>
                        <!-- 🏷️ FREEZE BADGE -->
                        <?php if ($is_frozen): ?>
                            <span class="label label-danger pull-right" style="font-size: 12px; padding: 4px 8px;"><i class="fa fa-lock"></i> FROZEN</span>
                        <?php else: ?>
                            <span class="label label-success pull-right" style="font-size: 12px; padding: 4px 8px;"><i class="fa fa-check"></i> ACTIVE</span>
                        <?php endif; ?>
                    </div>

                    <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format(($wallets['wallet_1_balance'] ?? 0), 2); ?></h2>
                    
                    <!-- Buttons Disabled if Frozen -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addMoneyModal" <?php echo $is_frozen ? 'disabled title="Wallet is Frozen"' : ''; ?>>+ Add Money</button>
                        <button type="button" class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#transferModal" style="margin-left:5px;" <?php echo $is_frozen ? 'disabled title="Wallet is Frozen"' : ''; ?>>⇆ Transfer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Wallet Card -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid <?php echo $is_frozen ? '#d9534f' : '#ff9800'; ?>; background: <?php echo $is_frozen ? '#fff5f5' : '#fffaf2'; ?>; min-height: 160px; padding: 15px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-warning font-weight-bold" style="display:inline-block;">Package Wallet</h6>
                        <?php if ($is_frozen): ?>
                            <span class="label label-danger pull-right" style="font-size: 12px; padding: 4px 8px;"><i class="fa fa-lock"></i> FROZEN</span>
                        <?php else: ?>
                            <span class="label label-success pull-right" style="font-size: 12px; padding: 4px 8px;"><i class="fa fa-check"></i> ACTIVE</span>
                        <?php endif; ?>
                    </div>

                    <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format(($wallets['wallet_2_balance'] ?? 0), 2); ?></h2>
                    
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm" data-target="#addPackageMoneyModal" data-toggle="modal" <?php echo $is_frozen ? 'disabled title="Wallet is Frozen"' : ''; ?>>+ Add Money</button>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#transferBackModal" style="margin-left:5px;" <?php echo $is_frozen ? 'disabled title="Wallet is Frozen"' : ''; ?>>⇆ Transfer Back</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coupons Card -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid #007bff; background: #f0f7ff; min-height: 160px; padding: 15px;">
                <div class="card-body">
                    <h6 class="text-primary font-weight-bold">Available Promo Coupons</h6>
                    <?php 
                        $this->db->where('status', 1);
                        $this->db->where('expiry_date >=', date('Y-m-d'));
                        $coupons = $this->db->get('hms_coupons')->result();
                        $coupon_count = count($coupons);
                    ?>
                    <h2 class="display-5" style="margin: 10px 0;"><?php echo $coupon_count; ?> <small style="font-size: 16px; color: #666;">Active Codes</small></h2>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewCouponsModal">
                        <i class="fa fa-ticket"></i> View All Coupons
                    </button>
                    <p class="text-muted small" style="margin-top: 8px; margin-bottom: 0;">Check validity & minimum bill</p>
                </div>
            </div>
        </div> 
    </div>

    <!-- Wallet Freeze / Unfreeze Action Row (Restricted to Accountants / Admins) -->
    <?php if (in_array(strtolower($user_role), ['accountant', 'administrator', 'admin'])) : ?>
        <div class="row" style="margin-top: 10px; margin-bottom: 15px;">
            <div class="col-md-12">
                <div class="wallet-freeze-action">
                    <?php if (!$is_frozen): ?>
                        <button type="button" class="btn btn-warning btn-sm" onclick="toggleWalletFreeze('<?php echo $current_patient_id; ?>', 1)">
                            <i class="fa fa-lock"></i> Freeze Wallet
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-success btn-sm" onclick="toggleWalletFreeze('<?php echo $current_patient_id; ?>', 0)">
                            <i class="fa fa-unlock"></i> Unfreeze Wallet
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
        function toggleWalletFreeze(patientId, statusVal) {
            if (!patientId || patientId.trim() === "") {
                alert("Error: Patient ID is missing!");
                return;
            }

            var actionText = (statusVal == 1) ? "FREEZE" : "UNFREEZE";
            var reason = prompt("Enter reason to " + actionText + " wallet:");
            
            if (reason === null || reason.trim() === "") {
                alert("Reason is required to " + actionText.toLowerCase() + " the wallet!");
                return;
            }

            $.ajax({
                url: "<?php echo site_url('Accounts/toggle_wallet_freeze'); ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    patient_id: patientId,
                    status: statusVal,
                    freeze_reason: reason
                },
                success: function(res) {
                    alert(res.message);
                    if(res.status === 'success') {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Something went wrong or access denied.");
                }
            });
        }
        </script>
    <?php endif; ?>
</div>

<div class="modal fade" id="viewCouponsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Valid Discount Coupons</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="info">
                                <th>Coupon Code</th>
                                <th>Discount</th>
                                <th>Service Type</th>
                                <th>Min. Bill Amount</th>
                                <th>Valid Until</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($coupon_count > 0): ?>
                                <?php foreach($coupons as $cp): ?>
                                    <tr>
                                        <td><strong class="text-primary" style="font-size: 1.1em; letter-spacing: 1px;"><?php echo $cp->coupon_code; ?></strong></td>
                                        <td>
                                            <?php 
                                                echo ($cp->discount_type == 'fixed') 
                                                     ? '₹' . number_format($cp->discount_value, 2) 
                                                     : $cp->discount_value . '% OFF'; 
                                            ?>
                                        </td>
                                        <td><span class="label label-info"><?php echo ucfirst($cp->service_type); ?></span></td>
                                        <td>₹<?php echo number_format($cp->min_amount, 2); ?></td>
                                        <td>
                                            <?php 
                                                $expiry = strtotime($cp->expiry_date);
                                                echo date('d-M-Y', $expiry);
                                                
                                                // Alert if expiring soon (within 3 days)
                                                if ($expiry < strtotime('+3 days')) {
                                                    echo '<br><small class="text-danger"><b>Expiring Soon!</b></small>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No active coupons available right now.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-warning small">
                    <i class="fa fa-info-circle"></i> These coupons can be applied during Pharmacy Billing or Consultation payments.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        <label>Deposit Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Payment Mode <span class="text-danger">*</span></label>
                        <select name="payment_method" id="paymentModeSelect" class="form-control" required>
                            <option value=""> - - - Select - - - </option>
                            <option value="UPI">UPI</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload Screenshot <span id="screenshotLabelText" class="text-danger">*</span></label>
                        <input type="file" name="screenshot" id="screenshotInput" class="form-control" accept="image/*" required>
                        <small class="text-muted">Max size: 2MB (JPG/PNG)</small>
                    </div>
                    
                     <div class="form-group">
                        <label>Transaction ID / Reference <span class="text-danger">*</span></label>
                        <input type="text" name="reference_id" class="form-control" required>
                        <input type="text" name="receipt_number" class="form-control mt-2" value="<?php echo $final_receipt_number; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Remarks <span class="text-danger">*</span></label>
                        <input type="text" name="remarks" class="form-control" maxlength="100">
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
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Move money for IVF Cycle" maxlength="100"></textarea>
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
                        <textarea name="remarks" class="form-control" maxlength="100"></textarea>
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
                        <label>Deposit Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label>Payment Mode <span class="text-danger">*</span></label>
                        <select name="payment_method" id="packagePaymentModeSelect" class="form-control" required>
                            <option value=""> - - - Select - - - </option>
                            <option value="UPI">UPI</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Loan">Loan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Upload Screenshot <span id="packageScreenshotLabelText" class="text-danger">*</span></label>
                        <input type="file" name="screenshot" id="packageScreenshotInput" class="form-control" accept="image/*" required>
                        <small class="text-muted">Max size: 2MB (JPG/PNG)</small>
                    </div>
                    
                     <div class="form-group">
                        <label>Transaction ID / Reference <span class="text-danger">*</span></label>
                        <input type="text" name="reference_id" class="form-control" required>
                        <input type="text" name="receipt_number" class="form-control mt-2" value="<?php echo $final_receipt_number; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Remarks <span class="text-danger">*</span></label>
                        <input type="text" name="remarks" class="form-control" maxlength="100">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add to Package</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-exchange"></i> Wallet Transfer Requests
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fa fa-times-circle"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="panel panel-primary" style="border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div class="panel-heading" style="background-color: #2c3e50; border-color: #2c3e50; font-weight: bold; font-size: 15px;">
                <i class="fa fa-history"></i> Complete Wallet Transaction History (Ledger)
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background-color: #f5f7fa; color: #333;">
                                <th style="width: 50px; text-align: center;">S.No.</th>
                                <th>Receipt No. / Txn ID</th>
                                <th>Date & Time</th>
                                <th>Type / Action</th>
                                <th style="text-align: right;">Money Wallet (W1)</th>
                                <th style="text-align: right;">Package Wallet (W2)</th>
                                <th>Method</th>
                                <th>Remarks / Notes</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Screenshot</th>
                                <th style="text-align: center; width: 110px;">Action</th> 
                            </tr>
                        </thead>
                       <tbody>
                            <?php 
                                // JOIN Query: hms_wallet_logs aur hms_employees ko jodna
                                $this->db->select('hms_wallet_logs.*, hms_employees.name as employee_name');
                                $this->db->from('hms_wallet_logs');
                                // JOIN condition: created_by = employee_number
                                $this->db->join('hms_employees', 'hms_employees.employee_number = hms_wallet_logs.created_by', 'left');
                                
                                $this->db->where('hms_wallet_logs.patient_id', $patient_data['patient_id']);
                                $this->db->order_by('hms_wallet_logs.log_id', 'DESC');
                                $wallet_history = $this->db->get()->result_array();

                                if(!empty($wallet_history)): 
                                    $sno = 1;
                                    foreach($wallet_history as $history): 
                            ?>
                                    <tr <?php if(($history['status'] ?? '') == 'pending') echo 'style="background-color: #fcf8e3;"'; ?>>
                                        <td style="text-align: center; font-weight: bold;"><?php echo $sno++; ?></td>
                                        
                                        <td>
                                            <span class="text-primary" style="font-weight: 600; display: block;">
                                                <?php echo !empty($history['receipt_number']) ? $history['receipt_number'] : 'N/A'; ?>
                                            </span>
                                            <small class="text-muted">
                                                Ref: <?php echo !empty($history['reference_id']) ? $history['reference_id'] : '-'; ?>
                                            </small>
                                        </td>
                                        
                                        <td>
                                            <i class="fa fa-calendar-o text-muted"></i> 
                                            <?php echo date('d M Y', strtotime($history['created_at'])); ?><br>
                                            <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo date('h:i A', strtotime($history['created_at'])); ?></small>
                                        </td>
                                        
                                       <td style="text-align: right; vertical-align: middle;">
    <?php 
        $amt = (float)$history['amount'];
        $action = strtoupper(trim($history['action_type'])); // Sabko Uppercase me convert kiya
        $status = strtolower(trim($history['status']));
        
        // Math difference check
        $diff_w1 = round((float)$history['closing_w1'] - (float)$history['opening_w1'], 2);
        
        // CONDITION 1: Agar sach mein calculation hui hai
        if (abs($diff_w1) > 0) {
            if ($diff_w1 > 0) {
                echo '<span style="font-weight: 600; color: #27ae60;">+ ₹ ' . number_format($diff_w1, 2) . '</span>';
            } else {
                echo '<span style="font-weight: 600; color: #c0392b;">- ₹ ' . number_format(abs($diff_w1), 2) . '</span>';
            }
        } 
        // CONDITION 2: Agar Math 0 hai, toh Action Type Name se pakdenge
        elseif ($amt > 0) {
            // W1 ME PAISE AANE WALE ACTIONS (+)
            if (in_array($action, ['DEPOSIT_MONEY_WALLET', 'TRANSFER_PACKAGE_WALLET_TO_MONEY_WALLET', 'CREDIT', 'PHARMACY_RETURN_REFUND'])) {
                $color = ($status == 'pending') ? '#f39c12' : '#27ae60';
                echo '<span style="font-weight: 600; color: '.$color.';">+ ₹ ' . number_format($amt, 2) . '</span>';
            } 
            // W1 SE PAISE KATNE WALE ACTIONS (-)
            elseif (in_array($action, ['TRANSFER_MONEY_WALLET_TO_PACKAGE_WALLET', 'INVESTIGATION_USAGE', 'MEDICINE_SALE', 'DEDUCTED_DUE_TO_DISAPPROVAL'])) {
                $color = ($status == 'pending') ? '#f39c12' : '#c0392b';
                echo '<span style="font-weight: 600; color: '.$color.';">- ₹ ' . number_format($amt, 2) . '</span>';
            } 
            else {
                echo '<span style="font-weight: 600; color: #7f8c8d;">₹ 0.00</span>';
            }
        } 
        else {
            echo '<span style="font-weight: 600; color: #7f8c8d;">₹ 0.00</span>';
        }
    ?>
    <br>
    <small class="text-muted" style="font-size: 11px;">
        Bal: ₹<?php echo number_format($history['closing_w1'], 2); ?>
    </small>
</td>
                                        
                                        <td style="text-align: right; vertical-align: middle;">
    <?php if(isset($history['opening_w2'])): ?>
        <?php 
            $diff_w2 = round((float)$history['closing_w2'] - (float)$history['opening_w2'], 2);
            
            if (abs($diff_w2) > 0) {
                if ($diff_w2 > 0) {
                    echo '<span style="font-weight: 600; color: #27ae60;">+ ₹ ' . number_format($diff_w2, 2) . '</span>';
                } else {
                    echo '<span style="font-weight: 600; color: #c0392b;">- ₹ ' . number_format(abs($diff_w2), 2) . '</span>';
                }
            } 
            elseif ($amt > 0) {
                // W2 ME PAISE AANE WALE ACTIONS (+)
                if (in_array($action, ['DEPOSIT_PACKAGE_WALLET', 'TRANSFER_MONEY_WALLET_TO_PACKAGE_WALLET'])) {
                    $color = ($status == 'pending') ? '#f39c12' : '#27ae60';
                    echo '<span style="font-weight: 600; color: '.$color.';">+ ₹ ' . number_format($amt, 2) . '</span>';
                } 
                // W2 SE PAISE KATNE WALE ACTIONS (-)
                elseif (in_array($action, ['TRANSFER_PACKAGE_WALLET_TO_MONEY_WALLET', 'PACKAGE_USAGE'])) {
                    $color = ($status == 'pending') ? '#f39c12' : '#c0392b';
                    echo '<span style="font-weight: 600; color: '.$color.';">- ₹ ' . number_format($amt, 2) . '</span>';
                } 
                else {
                    echo '<span style="font-weight: 600; color: #7f8c8d;">₹ 0.00</span>';
                }
            } 
            else {
                echo '<span style="font-weight: 600; color: #7f8c8d;">₹ 0.00</span>';
            }
        ?>
        <br>
        <small class="text-muted" style="font-size: 11px;">
            Bal: ₹<?php echo number_format($history['closing_w2'], 2); ?>
        </small>
    <?php else: ?>
        <span class="text-muted">-</span>
    <?php endif; ?>
</td>

                                    <td style="text-align: right; vertical-align: middle;">
                                        <?php if(isset($history['opening_w2'])): ?>
                                            <?php 
                                                $diff_w2 = round((float)$history['closing_w2'] - (float)$history['opening_w2'], 2);
                                                
                                                if (abs($diff_w2) > 0) {
                                                    if ($diff_w2 > 0) {
                                                        echo '<span style="font-weight: 600; color: #27ae60;">+ ₹ ' . number_format($diff_w2, 2) . '</span>';
                                                    } else {
                                                        echo '<span style="font-weight: 600; color: #c0392b;">- ₹ ' . number_format(abs($diff_w2), 2) . '</span>';
                                                    }
                                                } 
                                                elseif ($amt > 0) {
                                                    if (in_array($action, ['DEPOSIT_PACKAGE_WALLET', 'TRANSFER_MONEY_WALLET_TO_PACKAGE_WALLET'])) {
                                                        $color = ($status == 'pending') ? '#f39c12' : '#27ae60';
                                                        echo '<span style="font-weight: 600; color: '.$color.';">+ ₹ ' . number_format($amt, 2) . '</span>';
                                                    } 
                                                    elseif (in_array($action, ['TRANSFER_PACKAGE_WALLET_TO_MONEY_WALLET', 'PACKAGE_USAGE'])) {
                                                        $color = ($status == 'pending') ? '#f39c12' : '#c0392b';
                                                        echo '<span style="font-weight: 600; color: '.$color.';">- ₹ ' . number_format($amt, 2) . '</span>';
                                                    } 
                                                    else {
                                                        echo '<span style="font-weight: 600; color: #7f8c8d;">₹ 0.00</span>';
                                                    }
                                                } 
                                                else {
                                                    echo '<span style="font-weight: 600; color: #7f8c8d;">₹ 0.00</span>';
                                                }
                                            ?>
                                            <br>
                                            <small class="text-muted" style="font-size: 11px;">
                                                Bal: ₹<?php echo number_format($history['closing_w2'], 2); ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                        
                                        <td style="vertical-align: middle;">
                                            <span class="badge" style="background-color: #7f8c8d; color:#fff; font-weight: normal; padding: 3px 6px;">
                                                <?php echo !empty($history['payment_method']) ? $history['payment_method'] : 'Wallet'; ?>
                                            </span>
                                        </td>
                                        
                                        <td style="vertical-align: middle; color: #555; max-width: 200px; word-wrap: break-word;">
                                            <?php echo !empty($history['remarks']) ? htmlspecialchars($history['remarks']) : '<em class="text-muted">No remarks</em>'; ?>
                                            
                                            <?php if(!empty($history['update_remarks'])): ?>
                                                <br>
                                                <span class="text-info" style="font-size: 12px;">
                                                    <i class="fa fa-comment"></i> <?php echo htmlspecialchars($history['update_remarks']); ?>
                                                </span>
                                            <?php endif; ?>
                                           
                                            <br>
                                            <small class="text-muted">By: 
                                                <?php 
                                                    // JOIN query ne 'employee_name' nikal liya hai
                                                    if(!empty($history['employee_name'])) {
                                                        echo htmlspecialchars($history['employee_name']);
                                                    } else {
                                                        echo htmlspecialchars($history['created_by'] ?? 'Staff');
                                                    }
                                                ?>
                                            </small>
                                        </td>
                                        
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?php 
                                                $status = $history['status'] ?? 'approved'; 
                                                if($status == 'pending'): 
                                            ?>
                                                <span class="label label-warning" style="padding: 4px 8px; font-weight: bold;"><i class="fa fa-spinner fa-spin"></i> Pending</span>
                                            <?php elseif($status == 'approved' || $status == 'success'): ?>
                                                <span class="label label-success" style="padding: 4px 8px; font-weight: bold;"><i class="fa fa-check-circle"></i> Approved</span>
                                            <?php elseif($status == 'disapproved' || $status == 'disapproved'): ?>
                                                <span class="label label-danger" style="padding: 4px 8px; font-weight: bold;"><i class="fa fa-times-circle"></i> Disapproved</span>
                                            <?php else: ?>
                                                <span class="label label-default" style="padding: 4px 8px; font-weight: bold;"><?php echo ucfirst($status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?php if(!empty($history['screenshot'])): ?>
                                                <a href="<?php echo base_url('uploads/screenshots/'.$history['screenshot']); ?>" target="_blank" class="btn btn-default btn-xs" title="View Attachment">
                                                    <i class="fa fa-picture-o text-primary" style="font-size: 14px;"></i> View
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 12px;">No Doc</span>
                                            <?php endif; ?>
                                        </td>

                                        <td style="text-align: center; vertical-align: middle;">
                                            <a href="<?php echo base_url('accounts/print_invoice/'.$history['log_id']); ?>" 
                                               target="_blank" 
                                               class="btn btn-info btn-xs btn-block" 
                                               style="font-weight: bold; padding: 4px 8px;">
                                                <i class="fa fa-print"></i> Print Receipt
                                            </a>
                                        </td>
                                    </tr>
                            <?php 
                                    endforeach;
                                else: 
                            ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted" style="padding: 20px;">
                                        <i class="fa fa-info-circle" style="font-size: 20px;"></i><br>No transaction history found for this patient.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script>
$(document).ready(function() {
    // Jab payment mode change ho
    $('#paymentModeSelect').on('change', function() {
        var selectedMode = $(this).val();
        
        if (selectedMode === 'Cash') {
            // Agar Cash hai toh Screenshot optional banayein
            $('#screenshotInput').removeAttr('required');
            $('#screenshotLabelText').removeClass('text-danger').addClass('text-muted').text('(Optional)');
        } else {
            // Baki sab (UPI, Bank, Card) ke liye mandatory rakhein
            $('#screenshotInput').attr('required', 'required');
            $('#screenshotLabelText').removeClass('text-muted').addClass('text-danger').html('*');
        }
    });
});
</script>
<script>
$(document).ready(function() {
    
    // Package Wallet Modal ka logic
    $('#packagePaymentModeSelect').on('change', function() {
        var selectedMode = $(this).val();
        
        if (selectedMode === 'Cash') {
            // Agar Cash select kiya hai toh (Optional) dikhayein aur required hata dein
            $('#packageScreenshotInput').removeAttr('required');
            $('#packageScreenshotLabelText').removeClass('text-danger').addClass('text-muted').text('(Optional)');
        } else {
            // Baaki sabhi (Loan, UPI, etc.) ke liye mandatory banayein
            $('#packageScreenshotInput').attr('required', 'required');
            $('#packageScreenshotLabelText').removeClass('text-muted').addClass('text-danger').html('*');
        }
    });

});
</script>