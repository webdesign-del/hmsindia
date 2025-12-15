<?php 
// Load helper for login check
$this->load->helper('myhelper');
$login_check = checklogin();
$is_admin = isset($_SESSION['logged_administrator']) && $_SESSION['logged_administrator']['role'] == 'administrator';
$all_method =&get_instance();
$all_method->load->model('center_model');
?>

<style>
.status-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.status-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.status-header h1 {
    margin: 0;
    font-size: 2.5em;
    font-weight: 300;
}

.status-header p {
    margin: 10px 0 0 0;
    font-size: 1.1em;
    opacity: 0.9;
}

.po-details-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
}

.card-header h3 {
    margin: 0;
    color: #495057;
    font-size: 1.4em;
}

.card-body {
    padding: 30px;
}

.detail-row {
    display: flex;
    margin-bottom: 15px;
    align-items: center;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    min-width: 200px;
    margin-right: 20px;
}

.detail-value {
    color: #6c757d;
    flex: 1;
}

.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 2px solid #ffeaa7;
}

.status-approved {
    background-color: #d4edda;
    color: #155724;
    border: 2px solid #c3e6cb;
}

.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
    border: 2px solid #f5c6cb;
}

.approval-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 25px;
    margin-top: 30px;
}

.approval-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.approval-header h4 {
    margin: 0;
    color: #495057;
}

.approver-list {
    margin-bottom: 20px;
}

.approver-item {
    background: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 4px solid #dee2e6;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.approver-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.approver-item.pending {
    border-left-color: #ffc107;
}

.approver-item.approved {
    border-left-color: #28a745;
}

.approver-item.rejected {
    border-left-color: #dc3545;
}

.approver-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.approver-email {
    font-weight: 600;
    color: #495057;
}

.approver-status {
    font-size: 0.9em;
    font-weight: 600;
}

.approver-time {
    font-size: 0.8em;
    color: #6c757d;
    font-style: italic;
}

.approver-remarks {
    font-size: 0.9em;
    color: #dc3545;
    background: #f8d7da;
    padding: 8px;
    border-radius: 4px;
    margin-top: 8px;
}

.admin-actions {
    background: #e3f2fd;
    border: 2px solid #2196f3;
    border-radius: 10px;
    padding: 25px;
    margin-top: 30px;
}

.admin-actions h4 {
    color: #1976d2;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
}

.admin-actions h4:before {
    content: "🔒";
    margin-right: 10px;
    font-size: 1.2em;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95em;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn:disabled:hover {
    transform: none;
    box-shadow: none;
}

.alert {
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.loading {
    display: none;
    text-align: center;
    padding: 20px;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.no-access {
    text-align: center;
    padding: 50px;
    background: #f8f9fa;
    border-radius: 10px;
    color: #6c757d;
}

.no-access h3 {
    color: #dc3545;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .status-container {
        padding: 10px;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-label {
        min-width: auto;
        margin-bottom: 5px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>

<div class="status-container">
    <?php if (!$login_check['status']): ?>
        <div class="no-access">
            <h3>Access Denied</h3>
            <p>You must be logged in to view this page.</p>
            <a href="<?php echo base_url(); ?>" class="btn btn-secondary">Go to Login</a>
        </div>
    <?php else: ?>
        
        <!-- Header Section -->
        <div class="status-header">
            <h1>Pending Purchase Orders</h1>
            <p>Administrator Dashboard - Manage pending purchase order approvals</p>
        </div>

        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('warning')): ?>
            <div class="alert alert-warning">
                <strong>Warning!</strong> <?php echo $this->session->flashdata('warning'); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Dashboard -->
        <?php if (isset($pending_count) || isset($approved_count) || isset($rejected_count)): ?>
            <div class="po-details-card">
                <div class="card-header">
                    <h3>Purchase Order Statistics</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px; text-align: center; padding: 20px; background: #fff3cd; border-radius: 8px; border: 2px solid #ffeaa7;">
                            <h3 style="margin: 0; color: #856404; font-size: 2em;"><?php echo isset($pending_count) ? $pending_count : 0; ?></h3>
                            <p style="margin: 5px 0 0 0; color: #856404; font-weight: 600;">Pending Orders</p>
                        </div>
                        <div style="flex: 1; min-width: 200px; text-align: center; padding: 20px; background: #d4edda; border-radius: 8px; border: 2px solid #c3e6cb;">
                            <h3 style="margin: 0; color: #155724; font-size: 2em;"><?php echo isset($approved_count) ? $approved_count : 0; ?></h3>
                            <p style="margin: 5px 0 0 0; color: #155724; font-weight: 600;">Approved Orders</p>
                        </div>
                        <div style="flex: 1; min-width: 200px; text-align: center; padding: 20px; background: #f8d7da; border-radius: 8px; border: 2px solid #f5c6cb;">
                            <h3 style="margin: 0; color: #721c24; font-size: 2em;"><?php echo isset($rejected_count) ? $rejected_count : 0; ?></h3>
                            <p style="margin: 5px 0 0 0; color: #721c24; font-weight: 600;">Rejected Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Pending Purchase Orders List -->
        <?php if (isset($purchase_orders) && !empty($purchase_orders)): ?>
            <div class="po-details-card">
                <div class="card-header">
                    <h3>Pending Purchase Orders (<?php echo isset($total_count) ? $total_count : count($purchase_orders); ?> total)</h3>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions -->
                    <div class="admin-actions" style="margin-bottom: 20px;">
                        <h4>Bulk Actions <span id="selectedCount" style="font-size: 0.8em; color: #666; margin-left: 10px;">0 selected</span></h4>
                        <div class="action-buttons">
                            <button type="button" class="btn btn-approve" onclick="bulkApprove()">
                                ✓ Approve Selected
                            </button>
                            <button type="button" class="btn btn-reject" onclick="bulkReject()">
                                ✗ Reject Selected
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="selectAll()">
                                Select All
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="clearSelection()">
                                Clear Selection
                            </button>
                        </div>
                    </div>

                    <!-- Purchase Orders Table -->
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                            <thead>
                                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllSelection()" style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                    </th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">PO Number</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Department</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Bill To</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Ship To</th> 
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Vendor</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Total Amount</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Created Date</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($purchase_orders as $po): ?>
                                    <tr style="border-bottom: 1px solid #dee2e6;" class="po-row" data-po-id="<?php echo $po['id']; ?>">
                                        <td style="padding: 12px;">
                                            <input type="checkbox" class="po-checkbox" style="left: 0px !important;opacity: 1 !important;position: unset !important;" value="<?php echo $po['id']; ?>">
                                        </td>
                                        <td style="padding: 12px; font-weight: 600;">
                                            <a href="<?php echo base_url('new_purchase_orders/view/' . $po['id']); ?>" style="color: #007bff; text-decoration: none;">
                                                <?php echo $po['po_number']; ?>
                                            </a>
                                        </td>
                                        <td style="padding: 12px;"><?php echo isset($po['department']) ? $po['department'] : 'N/A'; ?></td>
                                        <?php
                                        $bill_to = $all_method->get_center_name($po['bill_to']);
                                        $ship_to = $all_method->get_center_name($po['ship_to']);
                                        ?>
                                        <td style="padding: 12px;"><?php echo isset($bill_to) ? $bill_to : 'N/A'; ?></td>
                                        <td style="padding: 12px;"><?php echo isset($ship_to) ? $ship_to : 'N/A'; ?></td>
                                        <?php
                                        $vendor_name = 'N/A';
                                        if (!empty($po['vendor_number'])) {
                                            $all_method->load->model('Vendors_model');
                                            $vendor_data = $all_method->Vendors_model->get_vendor_data($po['vendor_number']);
                                            if (!empty($vendor_data) && is_array($vendor_data) && isset($vendor_data['name'])) {
                                                $vendor_name = $vendor_data['name'];
                                            }
                                        }
                                        ?>
                                        <td style="padding: 12px;"><?php echo $vendor_name; ?></td>
                                        <td style="padding: 12px; font-weight: 600; color: #28a745;">
                                            ₹<?php echo isset($po['total_amount']) ? number_format($po['total_amount'], 2) : '0.00'; ?>
                                        </td>
                                        <td style="padding: 12px;"><?php echo isset($po['created_at']) ? date('d M Y H:i', strtotime($po['created_at'])) : 'N/A'; ?></td>
                                        <td style="padding: 12px;">
                                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                <button type="button" class="btn btn-approve" style="padding: 6px 12px; font-size: 0.8em;" onclick="approvePO(<?php echo $po['id']; ?>)">
                                                    ✓ Approve
                                                </button>
                                                <button type="button" class="btn btn-reject" style="padding: 6px 12px; font-size: 0.8em;" onclick="rejectPO(<?php echo $po['id']; ?>)">
                                                    ✗ Reject
                                                </button>
                                                <a href="<?php echo base_url('new_purchase_orders/view/' . $po['id']); ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8em; text-decoration: none;">
                                                    👁 View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($total_pages) && $total_pages > 1): ?>
                        <div style="margin-top: 30px; text-align: center;">
                            <div style="display: inline-flex; gap: 10px; align-items: center;">
                                <?php if ($current_page > 1): ?>
                                    <a href="?page=<?php echo $current_page - 1; ?>" class="btn btn-secondary" style="text-decoration: none;">← Previous</a>
                                <?php endif; ?>
                                
                                <span style="padding: 8px 16px; background: #f8f9fa; border-radius: 4px;">
                                    Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                                </span>
                                
                                <?php if ($current_page < $total_pages): ?>
                                    <a href="?page=<?php echo $current_page + 1; ?>" class="btn btn-secondary" style="text-decoration: none;">Next →</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <strong>No Pending Orders:</strong> There are currently no pending purchase orders to review.
            </div>
        <?php endif; ?>

        <!-- Loading Indicator -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Processing your request...</p>
        </div>
    <?php endif; ?>
</div>

<script>
function approvePO(poId) {
    if (!confirm('Are you sure you want to approve this purchase order?\n\nThis action will override the approval workflow.')) {
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: '<?php echo base_url("new_purchase_orders/update_status"); ?>',
        type: 'POST',
        data: {
            status: 1,
            id: poId
        },
        dataType: 'json',
        success: function(response) {
            hideLoading();
            if (response.status === 'success') {
                showAlert('success', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function(xhr) {
            hideLoading();
            showAlert('error', 'Server Error: ' + xhr.responseText);
        }
    });
}

function rejectPO(poId) {
    var remarks = prompt('Please provide a reason for rejection:');
    if (remarks === null) {
        return; // User cancelled
    }
    
    if (!confirm('Are you sure you want to reject this purchase order?\n\nThis action will override the approval workflow.')) {
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: '<?php echo base_url("new_purchase_orders/update_status"); ?>',
        type: 'POST',
        data: {
            status: 0,
            id: poId,
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            hideLoading();
            if (response.status === 'success') {
                showAlert('success', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function(xhr) {
            hideLoading();
            showAlert('error', 'Server Error: ' + xhr.responseText);
        }
    });
}

// Bulk Actions Functions
function selectAll() {
    var checkboxes = document.querySelectorAll('.po-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = true;
    });
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelectedCount();
}

function clearSelection() {
    var checkboxes = document.querySelectorAll('.po-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelectedCount();
}

function toggleAllSelection() {
    var selectAllCheckbox = document.getElementById('selectAllCheckbox');
    var checkboxes = document.querySelectorAll('.po-checkbox');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

// Update selected count display
function updateSelectedCount() {
    var checkedCount = document.querySelectorAll('.po-checkbox:checked').length;
    var countElement = document.getElementById('selectedCount');
    if (countElement) {
        countElement.textContent = checkedCount + ' selected';
    }
}

// Initialize event listeners for individual checkboxes
document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.po-checkbox');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
        });
    });
    
    // Initial count update
    updateSelectedCount();
});

function getSelectedPOs() {
    var selected = [];
    var checkboxes = document.querySelectorAll('.po-checkbox:checked');
    checkboxes.forEach(function(checkbox) {
        selected.push(checkbox.value);
    });
    return selected;
}

function bulkApprove() {
    var selectedPOs = getSelectedPOs();
    if (selectedPOs.length === 0) {
        showAlert('warning', 'Please select at least one purchase order to approve.');
        return;
    }
    
    if (!confirm('Are you sure you want to approve ' + selectedPOs.length + ' selected purchase order(s)?\n\nThis action will override the approval workflow.')) {
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: '<?php echo base_url("new_purchase_orders/bulk_update_status"); ?>',
        type: 'POST',
        data: {
            status: 1,
            ids: selectedPOs
        },
        dataType: 'json',
        success: function(response) {
            hideLoading();
            if (response.status === 'success') {
                showAlert('success', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function(xhr) {
            hideLoading();
            showAlert('error', 'Server Error: ' + xhr.responseText);
        }
    });
}

function bulkReject() {
    var selectedPOs = getSelectedPOs();
    if (selectedPOs.length === 0) {
        showAlert('warning', 'Please select at least one purchase order to reject.');
        return;
    }
    
    var remarks = prompt('Please provide a reason for rejection:');
    if (remarks === null) {
        return; // User cancelled
    }
    
    if (!confirm('Are you sure you want to reject ' + selectedPOs.length + ' selected purchase order(s)?\n\nThis action will override the approval workflow.')) {
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: '<?php echo base_url("new_purchase_orders/bulk_update_status"); ?>',
        type: 'POST',
        data: {
            status: 0,
            ids: selectedPOs,
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            hideLoading();
            if (response.status === 'success') {
                showAlert('success', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function(xhr) {
            hideLoading();
            showAlert('error', 'Server Error: ' + xhr.responseText);
        }
    });
}

function showLoading() {
    document.getElementById('loading').style.display = 'block';
}

function hideLoading() {
    document.getElementById('loading').style.display = 'none';
}

function showAlert(type, message) {
    var alertClass = 'alert-' + type;
    var alertHtml = '<div class="alert ' + alertClass + '" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;">' +
                   '<strong>' + (type === 'success' ? 'Success!' : type === 'warning' ? 'Warning!' : 'Error!') + '</strong> ' + message +
                   '<button type="button" class="close" onclick="this.parentElement.remove()" style="float: right; font-size: 18px; font-weight: bold; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; opacity: .5; background: none; border: none; cursor: pointer;">&times;</button>' +
                   '</div>';
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert[style*="position: fixed"]');
        alerts.forEach(function(alert) {
            alert.remove();
        });
    }, 5000);
}
</script>
