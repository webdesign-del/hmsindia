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
    <form method="GET" action="<?php echo base_url('accounts/wallet_transfer_requests'); ?>">
        <div class="row">
            <div class="col-md-2 col-sm-6 form-group">
                <label style="color: #333; font-size:12px;">Patient ID:</label>
                <input type="text" name="patient_id" class="form-control input-sm" placeholder="e.g. IIC-0012" value="<?php echo isset($search_patient_id) ? htmlspecialchars($search_patient_id) : ''; ?>">
            </div>

            <div class="col-md-2 col-sm-6 form-group">
                <label style="color: #333; font-size:12px;">Status:</label>
                <select name="status" class="form-control input-sm">
                    <option value="">-- All Status --</option>
                    <option value="pending" <?php echo (isset($search_status) && $search_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo (isset($search_status) && $search_status == 'approved') ? 'selected' : ''; ?>>Approved</option>
                    <option value="disapproved" <?php echo (isset($search_status) && $search_status == 'disapproved') ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-6 form-group">
                <label style="color: #333; font-size:12px;">From Date:</label>
                <input type="date" name="start_date" class="form-control input-sm" value="<?php echo isset($search_start_date) ? htmlspecialchars($search_start_date) : ''; ?>">
            </div>

            <div class="col-md-2 col-sm-6 form-group">
                <label style="color: #333; font-size:12px;">To Date:</label>
                <input type="date" name="end_date" class="form-control input-sm" value="<?php echo isset($search_end_date) ? htmlspecialchars($search_end_date) : ''; ?>">
            </div>

            <div class="col-md-2 col-sm-6 form-group">
                <label style="color: #333; font-size:12px;">Remarks Search:</label>
                <input type="text" name="remarks" class="form-control input-sm" placeholder="Keywords..." value="<?php echo isset($search_remarks) ? htmlspecialchars($search_remarks) : ''; ?>">
            </div>

            <div class="col-md-2 col-sm-6" style="margin-top: 23px;">
                <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 600;"><i class="fa fa-filter"></i> Filter</button>
                <a href="<?php echo base_url('accounts/wallet_transfer_requests'); ?>" class="btn btn-default btn-sm" style="font-weight: 600; margin-left: 5px;"><i class="fa fa-refresh"></i> Clear</a>
            </div>
        </div>
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
                                        
                                        <td><?php echo !empty($log['employee_name']) ? htmlspecialchars($log['employee_name']) : (!empty($log['created_by']) ? $log['created_by'] : 'System'); ?></td>
                                        <td><?php echo date('d M Y, h:i A', strtotime($log['created_at'])); ?><br/><?php echo !empty($log['remarks']) ? htmlspecialchars($log['remarks']) : '<span class="text-muted">N/A</span>'; ?></td>
                                        
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
                                            <?php if($log['status'] != 'disapproved' && $log['status'] != 'rejected'): ?>
                                                <?php $receipt_param = !empty($log['log_id']) ? $log['log_id'] : $log['log_id']; ?>
                                                <a href="<?php echo base_url('accounts/print_invoice/'.$receipt_param); ?>" 
                                                   target="_blank" 
                                                   class="btn btn-info btn-xs" 
                                                   style="font-weight: 600; width: 100%;">
                                                    <i class="fa fa-print"></i> Print Receipt
                                                </a>
                                            <?php else: ?>
                                                <span class="text-danger" style="font-size: 11px; font-style: italic; font-weight: 600;">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                       <td>
    <?php if($log['status'] == 'pending'): ?>
        <!-- Approve Button -->
        <button type="button" class="btn btn-success btn-xs" style="margin-bottom: 5px; width: 100%;"
                onclick="openActionModal('<?php echo base_url('accounts/approve_wallet_transfer/'.$log['log_id']); ?>', 'Approve')">
            <i class="fa fa-check"></i> Approve
        </button>
        <br>
        <!-- Disapprove Button -->
        <button type="button" class="btn btn-danger btn-xs" style="width: 100%;"
                onclick="openActionModal('<?php echo base_url('accounts/disapprove_wallet_transfer/'.$log['log_id']); ?>', 'Disapprove')">
            <i class="fa fa-times"></i> Disapprove
        </button>
    <?php elseif($log['status'] == 'approved' || $log['status'] == 'success'): ?>
        <span class="text-success"><i class="fa fa-check-circle"></i> Approved</span>
    <?php elseif($log['status'] == 'disapproved'): ?>
        <span class="text-danger"><i class="fa fa-times-circle"></i> Disapproved</span>
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

<!-- Action Modal -->
 <button type="button" id="hiddenModalTrigger" data-toggle="modal" data-target="#actionModal" style="display: none;"></button>

<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel">
  <div class="modal-dialog" role="document">
    <!-- Form set to POST method -->
    <form id="actionForm" method="POST" action="">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="actionModalTitle">Action</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <p id="actionWarningMessage" class="text-danger"></p>
          <div class="form-group">
            <label for="remarks">Remarks / Reason (Required):</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks here..." required></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="actionSubmitBtn">Submit</button>
        </div>
        
      </div>
    </form>
  </div>
</div>

<script>
function openActionModal(actionUrl, actionType) {
    // 1. Form ka action URL dynamically set karein
    document.getElementById('actionForm').action = actionUrl;
    
    // 2. Title aur Warning message set karein action ke according
    if(actionType === 'Approve') {
        document.getElementById('actionModalTitle').innerText = 'Approve Transaction';
        document.getElementById('actionWarningMessage').innerText = 'Are you sure you want to approve this transaction?';
        document.getElementById('actionSubmitBtn').className = 'btn btn-success';
    } else if (actionType === 'Disapprove') {
        document.getElementById('actionModalTitle').innerText = 'Disapprove Transaction';
        document.getElementById('actionWarningMessage').innerText = 'Are you sure you want to DISAPPROVE this request? Amount will be deducted from wallet balance.';
        document.getElementById('actionSubmitBtn').className = 'btn btn-danger';
    }
    
    // 3. Purane remarks clear karein
    document.getElementById('remarks').value = '';
    
    // 4. JAVASCRIPT CONFLICT FIX: jQuery ki jagah hidden button pe click karwayein
    document.getElementById('hiddenModalTrigger').click();
}
</script>