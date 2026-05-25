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
                                <th>Requested By</th>
                                <th>Requested Time</th>
                                <th>Action By</th>
                                <th>Action Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pending_logs)): ?>
                                <?php $count = 1; foreach($pending_logs as $log): ?>
                                    <tr <?php if($log['status'] == 'pending') echo 'style="background-color: #fcf8e3;"'; ?>>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $log['log_id']; ?></td>
                                        <td><?php echo $log['patient_id']; ?></td>
                                        <td>
                                            <strong style="color: green; font-size: 16px;">
                                                ₹<?php echo number_format($log['amount'], 2); ?>
                                            </strong>
                                        </td>
                                        
                                        <td><?php echo !empty($log['created_by']) ? $log['created_by'] : 'System'; ?></td>
                                        <td><?php echo date('d M Y, h:i A', strtotime($log['created_at'])); ?></td>
                                        
                                        <td>
                                            <?php echo (!empty($log['approved_by'])) ? $log['approved_by'] : '<span class="text-muted">-</span>'; ?>
                                        </td>
                                        <td>
                                            <?php echo ($log['status'] != 'pending' && !empty($log['updated_at'])) ? date('d M Y, h:i A', strtotime($log['updated_at'])) : '<span class="text-muted">-</span>'; ?>
                                        </td>

                                        <td>
                                            <?php if($log['status'] == 'pending'): ?>
                                                <span class="label label-warning">Pending</span>
                                            <?php elseif($log['status'] == 'approved'): ?>
                                                <span class="label label-success">Approved</span>
                                            <?php elseif($log['status'] == 'disapproved'): ?>
                                                <span class="label label-danger">Disapproved</span>
                                            <?php else: ?>
                                                <span class="label label-default"><?php echo ucfirst($log['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <?php if($log['status'] == 'pending'): ?>
                                                <a href="<?php echo base_url('accounts/approve_wallet_transfer/'.$log['log_id']); ?>" 
                                                   class="btn btn-success btn-xs"
                                                   onclick="return confirm('Are you sure you want to approve this transfer? Amount will be deducted from Wallet 2 and added to Wallet 1.');" style="margin-bottom: 5px;">
                                                    <i class="fa fa-check"></i> Approve
                                                </a>
                                                <br>
                                                <a href="<?php echo base_url('accounts/disapprove_wallet_transfer/'.$log['log_id']); ?>" 
                                                   class="btn btn-danger btn-xs"
                                                   onclick="return confirm('Are you sure you want to DISAPPROVE this request? No money will be transferred.');">
                                                    <i class="fa fa-times"></i> Disapprove
                                                </a>
                                            <?php elseif($log['status'] == 'approved'): ?>
                                                <span class="text-success"><i class="fa fa-check-circle"></i> Done</span>
                                            <?php elseif($log['status'] == 'disapproved'): ?>
                                                <span class="text-danger"><i class="fa fa-times-circle"></i> Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No transfers found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>