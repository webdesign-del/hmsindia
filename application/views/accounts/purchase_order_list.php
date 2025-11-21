<?php $all_method =&get_instance(); ?>
<style>
    .approver-status-list {
        max-width: 100%;
    }

    .approver-item {
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
    }

    .approver-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .approver-item .status-icon {
        font-size: 16px;
        margin-right: 8px;
    }

    .approver-item .status-text {
        font-weight: 600;
        font-size: 12px;
    }

    .approver-item .approver-email {
        font-size: 11px;
        color: #6c757d;
        word-break: break-all;
        margin-top: 4px;
    }

    .approver-item .approval-time {
        font-size: 10px;
        margin-top: 2px;
    }

    .approver-item .rejection-remarks {
        font-size: 10px;
        margin-top: 2px;
        font-style: italic;
    }

    .approval-summary {
        margin-top: 8px;
        padding: 6px;
        background-color: #e9ecef;
        border-radius: 4px;
        text-align: center;
        font-size: 11px;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .legacy-approvers {
        color: #6c757d;
        font-size: 12px;
        padding: 8px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    .no-approvers {
        color: #6c757d;
        font-style: italic;
        text-align: center;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .approver-item {
            margin-bottom: 6px;
            padding: 4px;
        }
        
        .approver-item .status-text {
            font-size: 11px;
        }
        
        .approver-item .approver-email {
            font-size: 10px;
        }
    }
</style>
<div class="col-md-12">
    <div class="card" style="margin-bottom:20px;">
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="col-sm-12 col-xs-12" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #155724;"><?php echo $this->session->flashdata('success'); ?></h4>
            </div>
        <?php endif; ?>
        <!-- <?php if($this->session->flashdata('error')): ?>
            <div class="col-sm-12 col-xs-12" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #721c24;"><?php echo $this->session->flashdata('error'); ?></h4>
            </div>
        <?php endif; ?> -->
        <!-- <?php if($this->session->flashdata('warning')): ?>
            <div class="col-sm-12 col-xs-12" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #856404;"><?php echo $this->session->flashdata('warning'); ?></h4>
            </div>
        <?php endif; ?> -->
        
         <div class="col-md-12"><h3> Purchase Orders </h3></div>
         
         <!-- <?php if ($user_role == 'administrator'): ?>
         <div class="col-md-12">
             <div class="alert alert-info" style="background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px;">
                 <i class="glyphicon glyphicon-info-sign"></i> 
                 <strong>Administrator Note:</strong> You can manually override the approval status using the dropdown in the Status column. 
                 This will bypass the email approval workflow and allow immediate status changes.
             </div>
         </div>
         <?php endif; ?> -->
        
        <div class="col-md-12 mt-3">
            <a href="<?php echo base_url('accounts/purchase_order'); ?>" class="btn btn-primary pull-right mr-2">Add Purchase Order</a>
            <a href="<?php echo base_url('user-approval-stats'); ?>" class="btn btn-info pull-right mr-2">User Approval Stats</a>
        </div>
        <hr>
        <div class="clearfix"></div>
        <form action="<?php echo base_url('accounts/purchase_order_list'); ?>" method="get" style="margin:10px">
            <div class="row">
                <!-- Centre -->
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Centre</label>
                    <select name="po_centre" id="po_centre"  class="form-control"  required>
                        <option value="">-- Select Centre --</option>
                        <?php 
                            $all_method =&get_instance();
                            $all_method->load->model('center_model');
                            $centers = $all_method->center_model->get_centers();
                            if(!empty($centers)){
                                foreach ($centers as $c) {
                                    $sel = (isset($filters['po_centre']) && $filters['po_centre'] == $c['center_number']) ? "selected" : "";
                                    echo "<option value='$c[center_number]' $sel>$c[center_name]</option>";
                                }
                            }
                            ?>
                    </select>
                </div>

                <!-- Department -->
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Department</label>
                    <select class="form-control" name="po_department">
                        <option value="">-- Select Department --</option>
                        <?php 
                        $departments = ["Director","Finance & Accounts","Human Resource","IT","Operations","Telesales","B2B","Sales & Marketing","Business Expansion","Digital marketing","Clinical-Operations","Clinical-Pharmacy","Clinical-IVF Coordinator","Clinical-OT","Clinical-Embryologist"];
                        foreach ($departments as $d) {
                            $sel = (isset($filters['po_department']) && $filters['po_department'] == $d) ? "selected" : "";
                            echo "<option value='$d' $sel>$d</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Nature of Expenditure -->
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Nature of Expenditure</label>
                    <select class="form-control" name="po_nature_of_expenditure">
                        <option value="">-- Select --</option>
                        <option value="Capex" <?php echo (isset($filters['po_nature_of_expenditure']) && $filters['po_nature_of_expenditure']=="Capex") ? "selected" : ""; ?>>Capex</option>
                        <option value="Opex" <?php echo (isset($filters['po_nature_of_expenditure']) && $filters['po_nature_of_expenditure']=="Opex") ? "selected" : ""; ?>>Opex</option>
                    </select>
                </div>

                <!-- Approval Status Filter
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Approval Status</label>
                    <select class="form-control" name="approval_status">
                        <option value="">-- All Statuses --</option>
                        <option value="all_approved" <?php echo (isset($filters['approval_status']) && $filters['approval_status']=='all_approved') ? 'selected' : ''; ?>>All Approved</option>
                        <option value="pending" <?php echo (isset($filters['approval_status']) && $filters['approval_status']=='pending') ? 'selected' : ''; ?>>Has Pending</option>
                        <option value="rejected" <?php echo (isset($filters['approval_status']) && $filters['approval_status']=='rejected') ? 'selected' : ''; ?>>Has Rejected</option>
                        <option value="partial" <?php echo (isset($filters['approval_status']) && $filters['approval_status']=='partial') ? 'selected' : ''; ?>>Partially Approved</option>
                    </select>
                </div>
                -->

                <!-- Status -->
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="">-- Select Status --</option>
                        <option value="1" <?php echo (isset($filters['status']) && $filters['status']=='1') ? 'selected' : ''; ?>>Approved</option>
                        <option value="0" <?php echo (isset($filters['status']) && $filters['status']=='0') ? 'selected' : ''; ?>>Disapproved</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Start Date</label>
                    <input type="text" class="form-control particular_date_filter" id="start_date" name="start_date" value="<?php echo $filters['start_date'] ?? ''; ?>">
                </div>

                <!-- End Date -->
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>End Date</label>
                    <input type="text" class="form-control particular_date_filter" id="end_date" name="end_date" value="<?php echo $filters['end_date'] ?? ''; ?>">
                </div>

                <!-- Buttons -->
                <div class="col-sm-12 mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    <a href="<?php echo base_url('accounts/purchase_order_list'); ?>" class="btn btn-secondary btn-sm">Reset</a>
                    <?php
                    $export_query_string = http_build_query($filters);
                    ?>
                    <a href="<?php echo base_url('accounts/export_purchase_orders?' . $export_query_string); ?>" class="btn btn-info btn-sm">Export</a>
                </div>
            </div>
        </form>
         <div class="clearfix"></div>
        <div class="card-content">
         <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="po-number-cell">Purchase Order</th>
                    <th>Centre</th>
                    <th>Department</th>
                    <th>Nature Of Expenditure</th>
                    <th>Budget Head</th>
                    <th>Budget Item</th>
                    <th>Approved By</th>
                    <th>Vendor</th>
                    <th>Document</th>
                    <th>Basic Amount (Ex GST)</th>
                    <th>GST Amount</th>
                    <th>Other Charges & Taxes</th>
                    <th>PO Total (Inc GST & All Charges)</th>
                    <th class="status-cell">Status</th>
                    <!-- <th>Print</th> -->
                </tr>
                </thead>
                <tbody>
                <!-- Approval Status Legend -->
                <tr style="background-color: #f8f9fa;">
                    <td colspan="13" style="padding: 10px; border: none;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 20px; font-size: 12px; color: #6c757d;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #28a745; font-weight: bold;">✓</span>
                                <span>Approved</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #ffc107; font-weight: bold;">⏳</span>
                                <span>Pending</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #dc3545; font-weight: bold;">✗</span>
                                <span>Rejected</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #6c757d; font-weight: bold;">ℹ</span>
                                <span>Legacy System</span>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php $count=1; if(!empty($purchase_order_result)){ foreach($purchase_order_result as $vl){ ?>
                    <tr>
                    <td><?php echo $count; ?></td>
                    <td>
                        <div class="clearfix">
                            <span class="pull-left" style="line-height:30px;">
                                <?php echo $vl['po_number']; ?>
                            </span>
                            <?php 
                            // Check if all approvers have approved this PO
                            $show_add_payment = false;
                            if (!empty($vl['approver_tokens'])) {
                                $approver_tokens = json_decode($vl['approver_tokens'], true);
                                if ($approver_tokens) {
                                    $all_approved = true;
                                    $any_rejected = false;
                                    
                                    foreach ($approver_tokens as $token_data) {
                                        if ($token_data['status'] === 'pending') {
                                            $all_approved = false;
                                        } elseif ($token_data['status'] === 'rejected') {
                                            $any_rejected = true;
                                        }
                                    }
                                    
                                    // Show Add Payment button only if all approvers approved and none rejected
                                    $show_add_payment = $all_approved && !$any_rejected;
                                }
                            }
                            
                            if ($show_add_payment): ?>
                                <a href="<?php echo base_url('accounts/purchase-order-payment/'.$vl['po_number']); ?>" 
                                    class="btn btn-primary btn-sm pull-right">
                                    <i class="glyphicon glyphicon-plus"></i>Add Payment
                                </a>
                            <?php else: ?>
                                <span class="pull-right" style="color: #6c757d; font-size: 12px; line-height: 30px;">
                                    <?php 
                                    if (!empty($vl['approver_tokens'])) {
                                        $approver_tokens = json_decode($vl['approver_tokens'], true);
                                        if ($approver_tokens) {
                                            $pending_count = 0;
                                            $approved_count = 0;
                                            $rejected_count = 0;
                                            
                                            foreach ($approver_tokens as $token_data) {
                                                if ($token_data['status'] === 'pending') $pending_count++;
                                                elseif ($token_data['status'] === 'approved') $approved_count++;
                                                elseif ($token_data['status'] === 'rejected') $rejected_count++;
                                            }
                                            
                                            if ($rejected_count > 0) {
                                                echo '<span style="color: #dc3545;"><i class="glyphicon glyphicon-remove"></i> Rejected</span>';
                                            } elseif ($pending_count > 0) {
                                                echo '<span style="color: #ffc107;"><i class="glyphicon glyphicon-time"></i> Pending (' . $pending_count . ')</span>';
                                            } else {
                                                echo '<span style="color: #28a745;"><i class="glyphicon glyphicon-ok"></i> Approved</span>';
                                            }
                                        }
                                    } else {
                                        echo '<span style="color: #6c757d;"><i class="glyphicon glyphicon-info-sign"></i> No Approvers</span>';
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo $all_method->get_center_name_by_number($vl['po_centre']); ?></td>
                    <td><?php echo $vl['po_department']; ?></td>
                    <td><?php echo $vl['po_nature_of_expenditure']; ?></td>
                    <td><?php echo $vl['po_budget_head']; ?></td>
                    <td><?php echo $vl['po_budget_item']; ?></td>
                    <td>
                        <?php 
                        // Get current user's email for dashboard approval check
                        $current_user_email = '';
                        $logg = checklogin();
                        if ($logg['status']) {
                            $role = $logg['role'];
                            $session_key = 'logged_' . $role;
                            if (isset($_SESSION[$session_key]['email'])) {
                                $current_user_email = $_SESSION[$session_key]['email'];
                            }
                        }
                        
                        if (!empty($vl['approver_tokens'])) {
                            $approver_tokens = json_decode($vl['approver_tokens'], true);
                            if ($approver_tokens) {
                                echo '<div class="approver-status-list">';
                                
                                // Check if current user is an approver and can approve
                                $user_can_approve = false;
                                $user_approval_status = '';
                                
                                foreach ($approver_tokens as $token_data) {
                                    if ($token_data['email'] === $current_user_email) {
                                        $user_can_approve = true;
                                        $user_approval_status = $token_data['status'];
                                        break;
                                    }
                                }
                                
                                foreach ($approver_tokens as $token_data) {
                                    $email = $token_data['email'];
                                    $status = $token_data['status'];
                                    $approved_at = isset($token_data['approved_at']) ? $token_data['approved_at'] : '';
                                    $remarks = isset($token_data['remarks']) ? $token_data['remarks'] : '';
                                    
                                    // Determine status color and icon
                                    switch ($status) {
                                        case 'approved':
                                            $status_color = '#28a745';
                                            $status_icon = '✓';
                                            $status_text = 'Approved';
                                            break;
                                        case 'rejected':
                                            $status_color = '#dc3545';
                                            $status_icon = '✗';
                                            $status_text = 'Rejected';
                                            break;
                                        case 'pending':
                                        default:
                                            $status_color = '#ffc107';
                                            $status_icon = '⏳';
                                            $status_text = 'Pending';
                                            break;
                                    }
                                    
                                    echo '<div class="approver-item" style="margin-bottom: 8px; padding: 6px; border-radius: 4px; border-left: 3px solid ' . $status_color . '; background-color: #f8f9fa;">';
                                    echo '<div style="display: flex; align-items: center; margin-bottom: 4px;">';
                                    echo '<span class="status-icon" style="color: ' . $status_color . '; font-weight: bold;">' . $status_icon . '</span>';
                                    echo '<span class="status-text" style="color: #333;">' . $status_text . '</span>';
                                    
                                    // Show "You" indicator for current user
                                    if ($email === $current_user_email) {
                                        echo '<span style="margin-left: 8px; background-color: #007bff; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: bold;">YOU</span>';
                                    }
                                    
                                    echo '</div>';
                                    echo '<div class="approver-email">' . $email . '</div>';
                                    
                                    if ($status === 'approved' && $approved_at) {
                                        echo '<div class="approval-time" style="color: #28a745;"><i class="glyphicon glyphicon-time"></i> ' . date('d M Y H:i', strtotime($approved_at)) . '</div>';
                                    }
                                    
                                    if ($status === 'rejected' && $remarks) {
                                        echo '<div class="rejection-remarks" style="color: #dc3545;"><i class="glyphicon glyphicon-comment"></i> ' . htmlspecialchars($remarks) . '</div>';
                                    }
                                    
                                    echo '</div>';
                                }
                                echo '</div>';
                                
                                // Show dashboard approval buttons for current user if they can approve
                                if ($user_can_approve && $user_approval_status === 'pending') {
                                    echo '<div class="dashboard-approval-buttons" style="margin-top: 10px; padding: 10px; background-color: #e3f2fd; border-radius: 4px; border: 1px solid #2196f3;">';
                                    echo '<div style="text-align: center; margin-bottom: 8px;">';
                                    echo '<strong style="color: #1976d2;">Your Approval Required</strong>';
                                    echo '</div>';
                                    echo '<div style="text-align: center;">';
                                    echo '<a href="' . base_url('accounts/dashboard_approve_po/' . $vl['po_number'] . '/approve') . '" class="btn btn-success btn-sm" onclick="return confirm(\'Are you sure you want to APPROVE this Purchase Order?\')" style="margin-right: 10px;">';
                                    echo '<i class="glyphicon glyphicon-ok"></i> Approve';
                                    echo '</a>';
                                    echo '<a href="' . base_url('accounts/dashboard_approve_po/' . $vl['po_number'] . '/reject') . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to REJECT this Purchase Order?\')">';
                                    echo '<i class="glyphicon glyphicon-remove"></i> Reject';
                                    echo '</a>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                
                                // Show summary
                                $pending_count = 0;
                                $approved_count = 0;
                                $rejected_count = 0;
                                $total_approvers = count($approver_tokens);
                                
                                foreach ($approver_tokens as $token_data) {
                                    if ($token_data['status'] === 'pending') $pending_count++;
                                    elseif ($token_data['status'] === 'approved') $approved_count++;
                                    elseif ($token_data['status'] === 'rejected') $rejected_count++;
                                }
                                
                                echo '<div class="approval-summary">';
                                if ($rejected_count > 0) {
                                    echo '<span style="color: #dc3545;"><strong>' . $rejected_count . '</strong> Rejected</span>';
                                } elseif ($pending_count > 0) {
                                    echo '<span style="color: #ffc107;"><strong>' . $approved_count . '/' . $total_approvers . '</strong> Approved</span>';
                                } else {
                                    echo '<span style="color: #28a745;"><strong>All ' . $total_approvers . '</strong> Approved</span>';
                                }
                                echo '</div>';
                                
                            } else {
                                echo '<span class="no-approvers">No approver data</span>';
                            }
                        } else {
                            // Fallback to basic display if no approver tokens
                            if (!empty($vl['po_approved_by'])) {
                                echo '<div class="legacy-approvers">';
                                echo '<i class="glyphicon glyphicon-info-sign"></i> Approvers: ' . $vl['po_approved_by'];
                                echo '<br><small style="color: #adb5bd;">(Legacy system - no detailed status)</small>';
                                echo '</div>';
                            } else {
                                echo '<span class="no-approvers">No approvers assigned</span>';
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo $vl['po_name_of_vendor']; ?></td>
                    <td>
                    <td>
                        <?php 
                        $documents_json = $vl['po_supporting_documents']; 
                        $document_array = json_decode($documents_json, true);
                        if (is_array($document_array) && !empty($document_array)) {
                            foreach ($document_array as $document_filename) {
                                if (empty($document_filename)) continue; // Skip empty entries
                                $file_url = base_url('assets/purchase_orders/' . $document_filename); 
                        ?>
                        <div style="margin-bottom: 8px; padding: 6px; border: 1px solid #eee; border-radius: 4px; background: #f9f9f9;">
                            <span style="font-size: 12px; color: #333; display: block; margin-bottom: 5px; word-wrap: break-word;" title="<?php echo htmlspecialchars($document_filename); ?>">
                                <i class="glyphicon glyphicon-file"></i>
                                <?php 
                                    if (strlen($document_filename) > 25) {
                                        echo substr($document_filename, 0, 12) . '...' . substr($document_filename, -5);
                                    } else {
                                        echo htmlspecialchars($document_filename);
                                    }
                                ?>
                            </span>
                            <a href="<?php echo $file_url; ?>" target="_blank" class="btn btn-info btn-xs">
                                <i class="glyphicon glyphicon-eye-open"></i> View
                            </a>
                            <a href="<?php echo $file_url; ?>" download="<?php echo $document_filename; ?>" class="btn btn-success btn-xs" style="margin-left: 5px;">
                                <i class="glyphicon glyphicon-download-alt"></i> Download
                            </a>
                        </div>
                        <?php 
                            }
                        ?>
                            <span style="color: #6c757d; font-size: 12px;">No Document</span>
                        <?php 
                        }
                        ?>
                    </td>
                    <!-- <td><?php echo $vl['po_remarks_or_comment_or_narration']; ?></td> -->
                    <td><?php echo $vl['po_basic_amount']; ?></td>
                    <td><?php echo $vl['po_gst_amount']; ?></td>
                    <td><?php echo $vl['po_other_charges_and_taxes']; ?></td>
                    <td><?php echo number_format($vl['po_po_total'], 2); ?></td>
                    <?php if ($user_role == 'administrator') { ?>
                    <td>
                        <?php 
                        // Show detailed approval status for administrators
                        if (!empty($vl['approver_tokens'])) {
                            $approver_tokens = json_decode($vl['approver_tokens'], true);
                            if ($approver_tokens) {
                                $pending_count = 0;
                                $approved_count = 0;
                                $rejected_count = 0;
                                $total_approvers = count($approver_tokens);
                                
                                foreach ($approver_tokens as $token_data) {
                                    if ($token_data['status'] === 'pending') $pending_count++;
                                    elseif ($token_data['status'] === 'approved') $approved_count++;
                                    elseif ($token_data['status'] === 'rejected') $rejected_count++;
                                }
                                
                                if ($rejected_count > 0) {
                                    echo '<span style="color: #dc3545; font-weight: bold;"><i class="glyphicon glyphicon-remove"></i> Rejected</span><br>';
                                    echo '<small style="color: #6c757d;">' . $rejected_count . ' of ' . $total_approvers . ' Rejected</small>';
                                } elseif ($pending_count > 0) {
                                    echo '<span style="color: #ffc107; font-weight: bold;"><i class="glyphicon glyphicon-time"></i> Pending</span><br>';
                                    echo '<small style="color: #6c757d;">' . $approved_count . '/' . $total_approvers . ' Approved</small>';
                                } else {
                                    echo '<span style="color: #28a745; font-weight: bold;"><i class="glyphicon glyphicon-ok"></i> Approved</span><br>';
                                    echo '<small style="color: #6c757d;">All ' . $total_approvers . ' Approvers</small>';
                                }
                            } else {
                                echo '<span style="color: #6c757d;">No Approvers</span>';
                            }
                        } else {
                            // Fallback to basic status if no approver tokens
                            if ($vl['status'] == 1) { echo '<span style="color: #28a745; font-weight: bold;">Approved</span>'; }
                            elseif ($vl['status'] == 0) { echo '<span style="color: #dc3545; font-weight: bold;">Disapproved</span>'; }
                            else { echo '<span style="color: #ffc107; font-weight: bold;">Pending</span>'; }
                        }
                        ?>
                        
                        <hr class="status-divider">
                        
                        <!-- Manual Status Override for Administrators -->
                        <div class="manual-override-section">
                            <label class="manual-override-label">Manual Override:</label>
                            <select class="manual-override-select" name="status_<?php echo $vl['id']; ?>" onchange="updateStatus(this,<?php echo $vl['id']; ?>)">
                                <option value="1" <?php echo ($vl['status']=='1') ? 'selected' : ''; ?>>Approved</option>
                                <option value="0" <?php echo ($vl['status']=='0') ? 'selected' : ''; ?>>Disapproved</option>
                                <option value="2" <?php echo ($vl['status']=='2') ? 'selected' : ''; ?>>Pending</option>
                            </select>
                            <small style="color: #6c757d; font-size: 10px; display: block; margin-top: 4px;">
                                <i class="glyphicon glyphicon-info-sign"></i> Override approval workflow
                            </small>
                        </div>
                    </td>
                    <!-- <td>
                        <?php 
                        $can_print = false;
                        if (!empty($vl['approver_tokens'])) {
                            $approver_tokens = json_decode($vl['approver_tokens'], true);
                            if ($approver_tokens) {
                                $all_approved = true;
                                $any_rejected = false;
                                
                                foreach ($approver_tokens as $token_data) {
                                    if ($token_data['status'] === 'pending') {
                                        $all_approved = false;
                                    } elseif ($token_data['status'] === 'rejected') {
                                        $any_rejected = true;
                                    }
                                }
                                
                                $can_print = $all_approved && !$any_rejected;
                            }
                        } else {
                            $can_print = ($vl['status'] == 1);
                        }
                        if ($can_print): ?>
                            <a href="<?php echo base_url(); ?>accounts/print_purchase_order/<?php echo $vl['id']; ?>" target="_blank" class="btn btn-primary btn-sm">Print</a>
                        <?php else: ?>
                            <span style="color: #6c757d; font-size: 12px;">N/A</span>
                        <?php endif; ?>
                    </td>
                   -->
                    <?php } else { ?>
                    <td>
                        <?php 
                        // Show detailed approval status based on approver tokens
                        if (!empty($vl['approver_tokens'])) {
                            $approver_tokens = json_decode($vl['approver_tokens'], true);
                            if ($approver_tokens) {
                                $pending_count = 0;
                                $approved_count = 0;
                                $rejected_count = 0;
                                $total_approvers = count($approver_tokens);
                                
                                foreach ($approver_tokens as $token_data) {
                                    if ($token_data['status'] === 'pending') $pending_count++;
                                    elseif ($token_data['status'] === 'approved') $approved_count++;
                                    elseif ($token_data['status'] === 'rejected') $rejected_count++;
                                }
                                
                                if ($rejected_count > 0) {
                                    echo '<span style="color: #dc3545; font-weight: bold;"><i class="glyphicon glyphicon-remove"></i> Rejected</span>';
                                } elseif ($pending_count > 0) {
                                    echo '<span style="color: #ffc107; font-weight: bold;"><i class="glyphicon glyphicon-time"></i> Pending</span><br>';
                                    echo '<small style="color: #6c757d;">' . $approved_count . '/' . $total_approvers . ' Approved</small>';
                                } else {
                                    echo '<span style="color: #28a745; font-weight: bold;"><i class="glyphicon glyphicon-ok"></i> Approved</span><br>';
                                    echo '<small style="color: #6c757d;">All ' . $total_approvers . ' Approvers</small>';
                                }
                            } else {
                                echo '<span style="color: #6c757d;">No Approvers</span>';
                            }
                        } else {
                            // Fallback to basic status if no approver tokens
                            if ($vl['status'] == 1) { echo '<span style="color: #28a745; font-weight: bold;">Approved</span>'; }
                            elseif ($vl['status'] == 0) { echo '<span style="color: #dc3545; font-weight: bold;">Disapproved</span>'; }
                            else { echo '<span style="color: #ffc107; font-weight: bold;">Pending</span>'; }
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        // Show print button only when all approvers have approved
                        $can_print = false;
                        if (!empty($vl['approver_tokens'])) {
                            $approver_tokens = json_decode($vl['approver_tokens'], true);
                            if ($approver_tokens) {
                                $all_approved = true;
                                $any_rejected = false;
                                
                                foreach ($approver_tokens as $token_data) {
                                    if ($token_data['status'] === 'pending') {
                                        $all_approved = false;
                                    } elseif ($token_data['status'] === 'rejected') {
                                        $any_rejected = true;
                                    }
                                }
                                
                                $can_print = $all_approved && !$any_rejected;
                            }
                        } else {
                            // Fallback to basic status if no approver tokens
                            $can_print = ($vl['status'] == 1);
                        }
                        
                        if ($can_print): ?>
                            <a href="<?php echo base_url(); ?>accounts/print_purchase_order/<?php echo $vl['id']; ?>" target="_blank" class="btn btn-primary btn-sm">Print</a>
                        <?php else: ?>
                            <span style="color: #6c757d; font-size: 12px;">N/A</span>
                        <?php endif; ?>
                    </td>
                    <?php } ?>
                    </tr>
                <?php $count++; } } else { ?>
                    <tr>
                    <td colspan="14" class="text-center">No Purchase Orders Found</td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="14">
                    <p class="custom-pagination"><?php echo $links; ?></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>
      </div>
       <!--End Investigation Tables -->
</div>
<script>
      $( function() {
        $( ".particular_date_filter" ).datepicker({
          dateFormat: 'yy-mm-dd',
		  changeMonth: true,
          changeYear: true,
          onSelect: function(dateStr) {
            $('#loader_div').hide();				
            var startDate = $.datepicker.formatDate("yy-mm-dd h:m:s", $(this).datepicker('getDate'));
            var data = {appointment_date:startDate, type:'particular_date_filter'};
          }
        });
    });

</script>
<script>
        function updateStatus(elem, id) {
        var status = elem.value;
        var statusText = "";
        
        // Get status text for confirmation
        switch(status) {
            case "1": statusText = "Approved"; break;
            case "0": statusText = "Disapproved"; break;
            case "2": statusText = "Pending"; break;
            default: statusText = "Unknown"; break;
        }
        
        if (!confirm("Are you sure you want to change this Purchase Order status to '" + statusText + "'?\n\nThis will override the approval workflow status.")) {
            // Reset to previous value - find the current status from the page
            var currentStatus = "<?php echo isset($vl['status']) ? $vl['status'] : '2'; ?>";
            elem.value = currentStatus;
            return;
        }
        
        // Show loading state
        var originalText = elem.options[elem.selectedIndex].text;
        elem.disabled = true;
        
        $.ajax({
            url: "<?php echo base_url('accounts/update_status'); ?>",
            type: "POST",
            data: { status: status, id: id },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    // Show success message
                    var successDiv = $('<div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                        '<strong>Success!</strong> ' + response.message + 
                        '<button type="button" class="close" data-dismiss="alert" style="float: right; font-size: 18px; font-weight: bold; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; opacity: .5;">&times;</button>' +
                        '</div>');
                    $('body').append(successDiv);
                    
                    // Auto-hide after 3 seconds
                    setTimeout(function() {
                        successDiv.fadeOut();
                    }, 3000);
                    
                    // Reload page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert("Error: " + response.message);
                    elem.disabled = false;
                }
            },
            error: function(xhr) {
                alert("Server Error: " + xhr.responseText);
                elem.disabled = false;
            }
        });
    }
</script>
<style >
    .custom-pagination{
    padding:8px;
    }
    .custom-pagination a{
    padding:10px;
    text-decoration: none;
    }
    .form-control{
    height: 30px!important;
    border: 1px solid #9e9e9e!important;
    }
    .form-control#billing_at{
    height: 40px!important;
    border: 1px solid #9e9e9e!important;
    }
    select {
        display: block !important;
    }

    /* Approval Status Styling */
    .approval-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin: 2px 0;
    }

    .approval-status.pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .approval-status.approved {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .approval-status.rejected {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .approval-status.no-approvers {
        background-color: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }

    .approval-count {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
        display: block;
    }

    .po-number-cell {
        min-width: 200px;
    }

    .status-cell {
        min-width: 180px;
    }

    .manual-override-section {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 8px;
        margin-top: 8px;
    }

    .manual-override-label {
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 4px;
        display: block;
        font-weight: 600;
    }

    .manual-override-select {
        width: 100%;
        height: 28px !important;
        font-size: 11px;
        border: 1px solid #ced4da;
        border-radius: 3px;
        padding: 2px 6px;
    }

    .status-divider {
        margin: 8px 0;
        border-color: #dee2e6;
        border-width: 1px;
    }

    /* Success Alert Styling */
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        padding: 15px;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .alert-success .close {
        background: none;
        border: none;
        font-size: 18px;
        font-weight: bold;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
        cursor: pointer;
    }

    .alert-success .close:hover {
        opacity: .75;
    }

    /* Dashboard Approval Buttons Styling */
    .dashboard-approval-buttons {
        margin-top: 10px;
        padding: 10px;
        background-color: #e3f2fd;
        border-radius: 4px;
        border: 1px solid #2196f3;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(33, 150, 243, 0); }
        100% { box-shadow: 0 0 0 0 rgba(33, 150, 243, 0); }
    }

    .dashboard-approval-buttons .btn {
        margin: 0 5px;
        padding: 8px 16px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .dashboard-approval-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .dashboard-approval-buttons .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .dashboard-approval-buttons .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .dashboard-approval-buttons .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .dashboard-approval-buttons .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
</style>
	