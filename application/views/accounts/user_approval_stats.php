<?php $all_method =&get_instance(); ?>
<style>
    .stats-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stats-number {
        font-size: 2.5em;
        font-weight: bold;
        margin: 10px 0;
    }

    .stats-label {
        font-size: 1.1em;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .stats-approved { color: #28a745; }
    .stats-disapproved { color: #dc3545; }
    .stats-pending { color: #ffc107; }
    .stats-total { color: #007bff; }

    .search-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .po-detail-row {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
    }

    .po-detail-row:last-child {
        border-bottom: none;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-approved { background-color: #d4edda; color: #155724; }
    .status-disapproved { background-color: #f8d7da; color: #721c24; }
    .status-pending { background-color: #fff3cd; color: #856404; }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #6c757d;
        font-style: italic;
    }

    .filter-row {
        margin-bottom: 15px;
    }

    .filter-row .form-control {
        height: 35px;
    }
</style>

<div class="col-md-12">
    <div class="card" style="margin-bottom:20px;">
        <div class="col-md-12"><h3>User Approval Statistics</h3></div>
        
        <!-- Search Section with Filters -->
        <div class="search-section">
            <?php if (!empty($searched_user)): ?>
            <div class="alert alert-info" style="margin-bottom: 15px;">
                <i class="glyphicon glyphicon-info-sign"></i> 
                <strong>Showing statistics for:</strong> <?php echo $searched_user; ?>
                <?php if ($searched_user == $current_user_email): ?>
                <br><small>This is your own approval statistics (automatically selected)</small>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <form method="GET" action="<?php echo base_url('accounts/user_approval_stats'); ?>">
                <div class="row">
                    <!-- User Filter -->
                    <small class="text-muted">(Only users involved in PO approvals)</small>
                    <div class="col-md-3">
                        <label><strong>Select User:</strong></label>
                        <select name="user_email" class="form-control" required>
                            <option value="">-- Select User --</option>
                            <?php 
                            if (!empty($all_users)) {
                                foreach ($all_users as $user) {
                                    $sel = ($searched_user == $user['email']) ? "selected" : "";
                                    echo "<option value='{$user['email']}' $sel>{$user['name']} ({$user['email']})</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No users found in database</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-3 mt-4">
                        <label><strong>Status Filter:</strong></label>
                        <select name="status_filter" class="form-control">
                            <option value="">-- All Statuses --</option>
                            <option value="approved" <?php echo (isset($filters['status_filter']) && $filters['status_filter'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo (isset($filters['status_filter']) && $filters['status_filter'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                            <option value="pending" <?php echo (isset($filters['status_filter']) && $filters['status_filter'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>
                    <!-- PO Number Filter -->
                    <div class="col-md-2">
                        <label><strong>PO Number:</strong></label>
                        <input type="text" name="po_number" class="form-control" placeholder="Enter PO Number" value="<?php echo isset($filters['po_number']) ? $filters['po_number'] : ''; ?>">
                    </div>
                    <!-- Search Button -->
                    <div class="col-md-4 mt-4 d-flex">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary">
                            <i class="glyphicon glyphicon-search"></i> Search
                        </button>
                        <a href="<?php echo base_url('accounts/user_approval_stats'); ?>" class="btn btn-secondary">
                            <i class="glyphicon glyphicon-refresh"></i> Reset
                        </a>
                    </div>
                </div>
                <!-- Debug info -->
                <?php if (empty($all_users)): ?>
                <div class="alert alert-warning" style="margin-top: 10px;">
                    <strong>Debug Info:</strong> No users found who have been involved in PO approvals. This could be because:
                    <ul style="margin: 5px 0;">
                        <li>No purchase orders have been created yet</li>
                        <li>No approver tokens have been set in purchase orders</li>
                        <li>All purchase orders are missing approver email addresses</li>
                    </ul>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!empty($user_stats)): ?>
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <div class="stats-label">Total Involved</div>
                    <div class="stats-number stats-total"><?php echo $user_stats['total_involved']; ?></div>
                    <small>Purchase Orders</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <div class="stats-label">Approved</div>
                    <div class="stats-number stats-approved"><?php echo $user_stats['total_approved']; ?></div>
                    <small>Orders Approved</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <div class="stats-label">Disapproved</div>
                    <div class="stats-number stats-disapproved"><?php echo $user_stats['total_disapproved']; ?></div>
                    <small>Orders Rejected</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <div class="stats-label">Pending</div>
                    <div class="stats-number stats-pending"><?php echo $user_stats['total_pending']; ?></div>
                    <small>Awaiting Action</small>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="stats-card">
            <h4><i class="glyphicon glyphicon-user"></i> User: <?php echo $user_stats['user_email']; ?></h4>
            <p><strong>Summary:</strong> This user has been involved in <strong><?php echo $user_stats['total_involved']; ?></strong> purchase orders as an approver.</p>
        </div>

        <!-- Detailed List -->
        <?php if (!empty($user_stats['approval_details'])): ?>
        <div class="stats-card">
            <h4><i class="glyphicon glyphicon-list"></i> Detailed Approval History</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Centre</th>
                            <th>Department</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>User Status</th>
                            <!-- <th>PO Status</th> -->
                            <th>Action Date</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_stats['approval_details'] as $detail): ?>
                        <tr class="po-detail-row">
                            <td><strong><?php echo $detail['po_number']; ?></strong></td>
                            <td><?php echo $detail['po_centre']; ?></td>
                            <td><?php echo $detail['po_department']; ?></td>
                            <td><?php echo $detail['po_name_of_vendor']; ?></td>
                            <td>₹<?php echo number_format($detail['po_po_total'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $detail['status']; ?>">
                                    <?php echo ucfirst($detail['status']); ?>
                                </span>
                            </td>
                          
                            <td>
                                <?php if ($detail['approved_at']): ?>
                                    <?php echo date('d M Y H:i', strtotime($detail['approved_at'])); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($detail['remarks']): ?>
                                    <small><?php echo htmlspecialchars($detail['remarks']); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="stats-card">
            <div class="no-data">
                <i class="glyphicon glyphicon-info-sign" style="font-size: 3em; margin-bottom: 15px;"></i>
                <h4>No Purchase Orders Found</h4>
                <p>This user has not been involved in any purchase order approvals yet.</p>
            </div>
        </div>
        <?php endif; ?>

        <?php elseif (!empty($searched_user)): ?>
        <!-- No Results -->
        <div class="stats-card">
            <div class="no-data">
                <i class="glyphicon glyphicon-exclamation-sign" style="font-size: 3em; margin-bottom: 15px; color: #dc3545;"></i>
                <h4>User Not Found</h4>
                <p>No purchase order data found for user: <strong><?php echo $searched_user; ?></strong></p>
            </div>
        </div>
        <?php else: ?>
        <!-- Initial State -->
        <div class="stats-card">
            <div class="no-data">
                <i class="glyphicon glyphicon-search" style="font-size: 3em; margin-bottom: 15px; color: #007bff;"></i>
                <h4>Select a User</h4>
                <p>Choose a user from the dropdown above to view their purchase order approval statistics.</p>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-submit form when user selection changes (optional)
    // $('select[name="user_email"]').change(function() {
    //     if ($(this).val()) {
    //         $(this).closest('form').submit();
    //     }
    // });
});
</script>
