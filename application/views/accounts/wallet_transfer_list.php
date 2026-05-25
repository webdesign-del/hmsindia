<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-exchange"></i> Pending Wallet Transfers
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
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Wallet Transfer Approvals List
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Log ID</th>
                                <th>Patient ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pending_logs)): ?>
                                <?php $count = 1; foreach($pending_logs as $log): ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $log['log_id']; ?></td>
                                        <td><?php echo $log['patient_id']; ?></td>
                                        <td>
                                            <strong style="color: green; font-size: 16px;">
                                                ₹<?php echo number_format($log['amount'], 2); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="label label-warning">Pending</span>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('accounts/approve_wallet_transfer/'.$log['log_id']); ?>" 
                                               class="btn btn-success btn-sm"
                                               onclick="return confirm('Are you sure you want to approve this transfer? Amount will be deducted from Wallet 2 and added to Wallet 1.');">
                                                <i class="fa fa-check"></i> Approve
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No pending transfers found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>