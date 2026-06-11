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

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <div class="well" style="background-color: #f5f7fa; padding: 15px; border-radius: 4px; margin-bottom: 0; border-left: 4px solid #337ab7;">
            <form method="GET" action="<?php echo base_url('accounts/wallet_transfer_requests'); ?>" class="form-inline">
                
                <div class="form-group" style="margin-right: 15px;">
                    <label style="margin-right: 5px; color: #333;">Patient ID:</label>
                    <input type="text" name="patient_id" class="form-control input-sm" placeholder="e.g. IIC-0012" value="<?php echo isset($search_patient_id) ? htmlspecialchars($search_patient_id) : ''; ?>" style="min-width: 180px;">
                </div>

                <div class="form-group" style="margin-right: 15px;">
                    <label style="margin-right: 5px; color: #333;">Status:</label>
                    <select name="status" class="form-control input-sm" style="min-width: 150px;">
                        <option value="">-- All Status --</option>
                        <option value="pending" <?php echo (isset($search_status) && $search_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo (isset($search_status) && $search_status == 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="disapproved" <?php echo (isset($search_status) && $search_status == 'disapproved') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 600;"><i class="fa fa-filter"></i> Search Filter</button>
                <a href="<?php echo base_url('accounts/wallet_transfer_requests'); ?>" class="btn btn-default btn-sm" style="font-weight: 600; margin-left: 5px;"><i class="fa fa-refresh"></i> Clear All</a>
            </form>
        </div>
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
                                <th style="width: 60px; text-align: center;">S.No.</th>
                                <th>Log ID</th>
                                <th>Patient ID</th>
                                <th>Amount</th>
                                <th>Requested By</th>
                                <th>Requested Time</th>
                                <th>Action By</th>
                                <th>Action Time</th>
                                <th>Status</th>
                                <th>Receipt Link</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pending_logs)): ?>
                                <?php 
                                    // 🎯 Pure PHP continuous Serial Number Calculation based on Query String Offset
                                    $current_offset = $this->input->get('per_page') ? intval($this->input->get('per_page')) : 0;
                                    $count = $current_offset + 1; 
                                    
                                    foreach($pending_logs as $log): 
                                ?>
                                    <tr <?php if($log['status'] == 'pending') echo 'style="background-color: #fcf8e3;"'; ?>>
                                        <td style="text-align: center; font-weight: bold;"><?php echo $count++; ?></td>
                                        <td><?php echo $log['log_id']; ?></td>
                                        <td><strong class="text-primary"><?php echo $log['patient_id']; ?></strong></td>
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
                                            <?php elseif($log['status'] == 'approved' || $log['status'] == 'success'): ?>
                                                <span class="label label-success">Approved</span>
                                            <?php elseif($log['status'] == 'disapproved' || $log['status'] == 'rejected'): ?>
                                                <span class="label label-danger">Disapproved</span>
                                            <?php else: ?>
                                                <span class="label label-default"><?php echo ucfirst($log['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <?php $receipt_param = !empty($log['log_id']) ? $log['log_id'] : $log['log_id']; ?>
                                            <a href="<?php echo base_url('accounts/print_invoice/'.$receipt_param); ?>" 
                                               target="_blank" 
                                               class="btn btn-info btn-xs" 
                                               style="font-weight: 600;">
                                                <i class="fa fa-print"></i> Print Receipt
                                            </a>
                                        </td>
                                        
                                        <td>
                                            <?php if($log['status'] == 'pending'): ?>
                                                <a href="<?php echo base_url('accounts/approve_wallet_transfer/'.$log['log_id']); ?>" 
                                                   class="btn btn-success btn-xs"
                                                   onclick="return confirm('Are you sure you want to approve this transfer? Amount will be deducted from Wallet 2 and added to Wallet 1.');" style="margin-bottom: 5px; width: 100%;">
                                                    <i class="fa fa-check"></i> Approve
                                                </a>
                                                <br>
                                                <a href="<?php echo base_url('accounts/disapprove_wallet_transfer/'.$log['log_id']); ?>" 
                                                   class="btn btn-danger btn-xs"
                                                   onclick="return confirm('Are you sure you want to DISAPPROVE this request? Amount will be deducted from wallet balance.');" style="width: 100%;">
                                                    <i class="fa fa-times"></i> Disapprove
                                                </a>
                                            <?php elseif($log['status'] == 'approved' || $log['status'] == 'success'): ?>
                                                <span class="text-success"><i class="fa fa-check-circle"></i> Approved</span>
                                            <?php elseif($log['status'] == 'disapproved' || $log['status'] == 'rejected'): ?>
                                                <span class="text-danger"><i class="fa fa-times-circle"></i> Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted" style="padding: 20px;">No transfers found matching your filters.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(!empty($pagination_links)): ?>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12 text-center">
                            <div class="pagination-wrapper">
                                <?php echo $pagination_links; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>