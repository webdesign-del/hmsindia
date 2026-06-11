<?php 
    $all_method =& get_instance(); 
    // Load the helper if not loaded in controller
  //  $this->load->helper('billing');
    
    // Fetch real-time balance using the function we built
    $w = get_final_wallet_balance($patient_data['patient_id']); 
    $balance = $w['balance']; 
?>
<?php 
$all_method =& get_instance();
$CI =& get_instance();

// 1. Current Financial Year nikalyein (2026 ke liye: 2627)
$current_year_suffix = date("y") . (date("y") + 1); 

// 2. Billing manager ke session se center number ke hisab se prefix uthayein
$db_prefix = $all_method->config->item('db_prefix');
$sql2 = "SELECT * FROM `".$db_prefix."centers` WHERE center_number='".$_SESSION['logged_billing_manager']['center']."'"; 
$center_result = run_select_query($sql2);

$center_code = !empty($center_result['state_prefix']) ? $center_result['state_prefix'] : 'CENTER';

// 🎯 [CRITICAL FIX]: SUBSTRING_INDEX mein -1 kiya taaki last 4-digit (0001) mile, aur LIKE filter ko robust kiya
$sql_receipt = "SELECT MAX(CAST(SUBSTRING_INDEX(receipt_number, '/', -1) AS UNSIGNED)) as last_number 
                FROM `hms_wallet_logs` 
                WHERE receipt_number LIKE 'PR/".$center_code."/".$current_year_suffix."/%'";
$query_res = $CI->db->query($sql_receipt)->row_array();

// 4. Agar is saal ka pehla receipt hai toh 0, nahi toh purana max number uthayein
$last_receipt_num = (!empty($query_res['last_number'])) ? intval($query_res['last_number']) : 0;

// 5. Agle number ko 4 digit format mein badhayein (e.g., 0002, 0003)
$next_receipt_num = str_pad(($last_receipt_num + 1), 4, '0', STR_PAD_LEFT);

// 6. Final String taiyar karein (PR/BSL/2627/0002)
$final_receipt_number = "PR/".$center_code."/".$current_year_suffix."/".$next_receipt_num;
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid #28a745; background: #f8fff9; min-height: 160px;">
                <div class="card-body">
                    <h6 class="text-success font-weight-bold">Money Wallet</h6>
                    <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format($wallets['wallet_1_balance'], 2); ?></h2>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addMoneyModal">+ Add Money</button>
                        <button type="button" class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#transferModal" style="margin-left:5px;">⇆ Transfer</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid #ff9800; background: #fffaf2; min-height: 160px;">
                <div class="card-body">
                    <h6 class="text-warning font-weight-bold">Package Wallet</h6>
                    <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format($wallets['wallet_2_balance'], 2); ?></h2>
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm" data-target="#addPackageMoneyModal" data-toggle="modal">+ Add Money</button>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#transferBackModal" style="margin-left:5px;">⇆ Transfer Back</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm" style="border-left: 5px solid #007bff; background: #f0f7ff; min-height: 160px;">
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
    <div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <div class="container-fluid mt-4">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <table class="table table-bordered" style="background-color: #fff; border-left: 5px solid #5db85d; margin-bottom: 0;">
                <tbody>
                    <tr>
                        <td style="width: 15%; font-weight: bold; border-right: 2px solid #007bff; color:#000;">IIC Id</td>
                        <td style="width: 35%; border-right: 4px solid #b3b3b3; color:#000;">
                            <?php echo $patient_data['patient_id'] ?? $patient_data['uhid'] ?? 'N/A'; ?>
                        </td>
                        <td style="width: 35%; font-weight: bold; border-right: 2px solid #007bff; color:#000;">General Wallet</td>
                        <td style="width: 15%; text-align: right; text-decoration: underline; font-weight: bold; color:#000;">
                            ₹ <?php echo number_format(($wallets['wallet_1_balance'] ?? 0), 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; border-right: 2px solid #007bff; color:#000;">Name</td>
                        <td style="border-right: 4px solid #b3b3b3; color:#000;">
                            <?php 
                                // This line checks all common name keys to avoid the Warning
                                echo $patient_data['patient_name'] ?? $patient_data['name'] ?? $patient_data['p_name'] ?? 'N/A'; 
                            ?>
                        </td>
                        <td style="font-weight: bold; border-right: 2px solid #007bff; color:#000;">Package Wallet</td>
                        <td style="text-align: right; text-decoration: underline; font-weight: bold; color:#000;">
                            ₹ <?php echo number_format(($wallets['wallet_2_balance'] ?? 0), 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; border-right: 2px solid #007bff; color:#000;">Origin Centre</td>
                        <td style="border-right: 4px solid #b3b3b3; color:#000;">
                            <?php echo $patient_data['origin_centre'] ?? 'Main Center'; ?>
                        </td>
                        <td style="font-weight: bold; border-right: 2px solid #007bff; color:#000;">Coupon Wallet*</td>
                        <td style="text-align: right; text-decoration: underline; font-weight: bold; color:#000;">
                            <?php 
                                $cp_count = $this->db->where('status', 1)->where('expiry_date >=', date('Y-m-d'))->count_all_results('hms_coupons');
                                echo "₹ " . number_format($cp_count, 2);
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
        <small class="pull-right text-muted">*Coupon Wallet displays the count of active promo codes available.</small>
    </div>
</div>
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
                        <label>Deposit Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Mode</label>
                        <select name="payment_method" class="form-control" require>
                            <option value=""> - - - Select - - - </option>
                            <option value="UPI">UPI</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload Screenshot (Optional)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*">
                        <small class="text-muted">Max size: 2MB (JPG/PNG)</small>
                    </div>
                     <div class="form-group">
                        <label>Transaction ID</label>
                        <input type="text" name="reference_id" class="form-control" required>
                        <input type="text" name="receipt_number" class="form-control" value="<?php echo $final_receipt_number; ?>" readonly="">
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
                        <select name="payment_method" class="form-control" require>
                            <option value=""> - - - Select - - - </option>
                            <option value="UPI">UPI</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Loan">Loan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Upload Screenshot (Optional)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*">
                        <small class="text-muted">Max size: 2MB (JPG/PNG)</small>
                    </div>
                     <div class="form-group">
                        <label>Transaction ID</label>
                        <input type="text" name="reference_id" class="form-control" required>
                        <input type="text" name="receipt_number" class="form-control" value="<?php echo $final_receipt_number; ?>" readonly="">
                    
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
                                <th style="text-align: center;">Status</th> <th style="text-align: center;">Screenshot</th>
                                <th style="text-align: center; width: 110px;">Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // hms_wallet_logs se is patient ka poora data nikalna (Latest First)
                                $this->db->where('patient_id', $patient_data['patient_id']);
                                $this->db->order_by('log_id', 'DESC');
                                $wallet_history = $this->db->get('hms_wallet_logs')->result_array();

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
                                        
                                        <td>
                                            <?php 
                                                $action = $history['action_type'];
                                                if (strpos($action, 'DEPOSIT') !== false) {
                                                    echo '<span class="label label-success" style="font-size:11px; padding: 4px 8px;"><i class="fa fa-arrow-down"></i> Deposit</span>';
                                                } elseif (strpos($action, 'TRANSFER') !== false) {
                                                    echo '<span class="label label-warning" style="font-size:11px; padding: 4px 8px;"><i class="fa fa-exchange"></i> Transfer</span>';
                                                } else {
                                                    echo '<span class="label label-danger" style="font-size:11px; padding: 4px 8px;"><i class="fa fa-arrow-up"></i> Spent</span>';
                                                }
                                                echo '<br><small class="text-muted" style="display:block; margin-top:3px;">'.str_replace('_', ' ', $action).'</small>';
                                            ?>
                                        </td>
                                        
                                        <td style="text-align: right; vertical-align: middle;">
                                            <span style="font-weight: 600; color: <?php echo ($history['closing_w1'] >= $history['opening_w1']) ? '#27ae60' : '#c0392b'; ?>;">
                                                ₹ <?php echo number_format($history['amount'], 2); ?>
                                            </span>
                                            <br>
                                            <small class="text-muted" style="font-size: 11px;">
                                                Bal: ₹<?php echo number_format($history['closing_w1'], 2); ?>
                                            </small>
                                        </td>
                                        
                                        <td style="text-align: right; vertical-align: middle;">
                                            <?php if(isset($history['opening_w2'])): ?>
                                                <span style="font-weight: 600; color: #2980b9;">
                                                    ₹ <?php echo number_format($history['closing_w2'] - $history['opening_w2'], 2); ?>
                                                </span>
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
                                            <?php echo !empty($history['remarks']) ? $history['remarks'] : '<em class="text-muted">No remarks</em>'; ?>
                                            <br><small class="text-muted">By: <?php echo $history['created_by'] ?? 'Staff'; ?></small>
                                        </td>

                                        <td style="text-align: center; vertical-align: middle;">
                                            <?php 
                                                $status = $history['status'] ?? 'approved'; // Default fallback approved agar value khali ho
                                                if($status == 'pending'): 
                                            ?>
                                                <span class="label label-warning" style="padding: 4px 8px; font-weight: bold;"><i class="fa fa-spinner fa-spin"></i> Pending</span>
                                            <?php elseif($status == 'approved' || $status == 'success'): ?>
                                                <span class="label label-success" style="padding: 4px 8px; font-weight: bold;"><i class="fa fa-check-circle"></i> Approved</span>
                                            <?php elseif($status == 'disapproved' || $status == 'rejected'): ?>
                                                <span class="label label-danger" style="padding: 4px 8px; font-weight: bold;"><i class="fa fa-times-circle"></i> Rejected</span>
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