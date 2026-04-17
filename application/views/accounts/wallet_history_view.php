<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="alert alert-success">
                <h4>Wallet A (Main Balance)</h4>
               <h2>₹ <?php echo number_format($wallets['wallet_1_balance'], 2); ?></h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-warning">
                <h4>Wallet B (Procedure Balance)</h4>
                <h2>₹ <?php echo number_format($wallets['wallet_2_balance'], 2); ?></h2>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Wallet Transaction History (Passbook)</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Amount</th>
                        <th>W1 Balance</th>
                        <th>W2 Balance</th>
                        <th>Screenshot</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($history)): foreach($history as $row): ?>
                    <tr>
                        <td><?php echo date('d-M-Y H:i', strtotime($row['created_at'])); ?></td>
                        <td>
                            <span class="badge <?php echo ($row['action_type'] == 'DEPOSIT_W1' ? 'badge-success' : 'badge-info'); ?>">
                                <?php echo $row['action_type']; ?>
                            </span>
                        </td>
                        <td class="font-weight-bold text-primary">₹ <?php echo number_format($row['amount'], 2); ?></td>
                        <td>₹ <?php echo number_format($row['closing_w1'], 2); ?></td>
                        <td>₹ <?php echo number_format($row['closing_w2'], 2); ?></td>
                        <td><?php if(!empty($row['screenshot'])): ?>
    <a href="<?php echo base_url('uploads/wallet_screenshots/'.$row['screenshot']); ?>" target="_blank">
        <i class="fa fa-image"></i> View Receipt
    </a>
<?php endif; ?></td>
                        <td><small><?php echo $row['remarks']; ?></small></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center">No transactions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <table class="table table-bordered">
    <thead>
        <tr>
            <th>Patient ID</th>
            <th>Amount</th>
            <th>Reason</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $pending = $this->db->get_where('hms_wallet_logs', ['status' => 'pending'])->result_array();
        foreach($pending as $row): 
        ?>
        <tr>
            <td><?php echo $row['patient_id']; ?></td>
            <td>₹<?php echo $row['amount']; ?></td>
            <td><?php echo $row['remarks']; ?></td>
            <td>
                <a href="<?php echo base_url('accounts/approve_wallet_transfer/'.$row['log_id']); ?>" class="btn btn-success btn-sm">Approve</a>
                <button class="btn btn-danger btn-sm">Reject</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
    </div>
</div>