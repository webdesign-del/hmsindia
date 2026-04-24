<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-shopping-cart"></i> Sales Management
                    <small>Medicine sales with FEFO stock allocation</small>
                </h1>
            </div>
        </div>
        
        <?php if(ENVIRONMENT === 'development'): ?>
        <!-- Debug Information -->
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-bug"></i> Debug Information
                    </div>
                    <div class="panel-body">
                        <p><strong>Sales Count:</strong> <?php echo is_array($sales) ? count($sales) : 'Not an array'; ?></p>
                        <p><strong>Centers Count:</strong> <?php echo is_array($centers) ? count($centers) : 'Not an array'; ?></p>
                        <?php if(!empty($sales) && is_array($sales)): ?>
                            <p><strong>First Sale Item:</strong></p>
                            <pre><?php print_r($sales[0]); ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> -->
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus"></i> Quick Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/add_sale'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create New Sale
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-building-o"></i> View Stock Levels
                        </a>
                        <a href="<?php echo base_url('stocks_new/sales_report'); ?>" class="btn btn-success">
                            <i class="fa fa-bar-chart-o"></i> Sales Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Panel -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-search"></i> Search & Filter
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo base_url('stocks_new/sales'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Center:</label>
                                <select name="center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php foreach($centers as $center): ?>
                                        <option value="<?php echo $center->ID; ?>" <?php echo $this->input->get('center_id') == $center->ID ? 'selected' : ''; ?>>
                                            <?php echo $center->center_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Patient ID:</label>
                                <input type="text" name="patient_id" class="form-control" placeholder="Search by ID" value="<?php echo $this->input->get('patient_id'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Patient:</label>
                                <input type="text" name="patient_name" class="form-control" placeholder="Search patient" value="<?php echo $this->input->get('patient_name'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="DRAFT" <?php echo $this->input->get('status') == 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="CONFIRMED" <?php echo $this->input->get('status') == 'CONFIRMED' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="CANCELLED" <?php echo $this->input->get('status') == 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Approval:</label>
                                <select name="approval_status" class="form-control">
                                    <option value="">All Approval</option>
                                    <option value="PENDING" <?php echo $this->input->get('approval_status') == 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="APPROVED" <?php echo $this->input->get('approval_status') == 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="DISAPPROVED" <?php echo $this->input->get('approval_status') == 'DISAPPROVED' ? 'selected' : ''; ?>>Disapproved</option>
                                    <option value="CANCELLED" <?php echo $this->input->get('approval_status') == 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date From:</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo $this->input->get('date_from'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Date To:</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $this->input->get('date_to'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                            <?php if(isset($sales) && !empty($sales)): ?>
                            <div class="btn-group" style="margin-left: 10px;">
                                <button type="button" class="btn btn-success" onclick="exportSalesList('excel')">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-danger" onclick="exportSalesList('pdf')">
                                    <i class="fa fa-file-pdf-o"></i> Export PDF
                                </button>
                                <button type="button" class="btn btn-info" onclick="exportDetailedSales('excel')">
                                    <i class="fa fa-file-excel-o"></i> Export All Items (Excel)
                                </button>
                                <button type="button" class="btn btn-warning" onclick="exportDetailedSales('pdf')">
                                    <i class="fa fa-file-pdf-o"></i> Export All Items (PDF)
                                </button>
                                <button type="button" 
                                        class="btn btn-success btn-sm" 
                                        id="bulk_approve_btn">
                                    <i class="fa fa-check"></i> Approve Selected
                                </button>
                                <span class="ml-3 text-primary">
                                    Selected: <strong id="selected_count">0</strong>
                                </span>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- *** NEW: Alert Container *** -->
        <div class="row">
            <div class="col-md-12" id="alert-container">
                <!-- AJAX Success/Error messages will appear here -->
            </div>
        </div>
        
        <!-- Sales Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Sales List
                        <span class="badge pull-right"><?php echo count($sales); ?> sales</span>
                    </div>
                    <?php
                    $is_accountant = !empty($_SESSION['logged_accountant']);
                    $is_central_stock_manager = !empty($_SESSION['logged_central_stock_manager']);
                    $is_billing_manager = !empty($_SESSION['logged_billing_manager']);
                    $can_view_report = $is_accountant || $is_central_stock_manager || $is_billing_manager;
                    ?>
                    <?php if ($can_view_report): ?>
                        <div class="panel-heading">
                            <a href="<?php echo base_url('/accounts/medicine_patients/?date=12-12-2025'); ?>"
                            class="badge pull-right">
                                old report (12-12-2025)
                            </a>
                        </div>
                    <?php endif; ?>
                    <!-- hide old report link if not an accountant -->
                    <div class="panel-body">
                        <div class="table-responsive fixed-table-wrapper">
                            <table class="table table-striped table-bordered table-hover" id="salesTable">
                                <thead>
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="select_all">
                                        </th>
                                        <th>Sale #</th>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        <th>Center</th>
                                        <th>Sold By</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Qty</th>
                                        <th>Taxable Amt</th>
                                        <th>GST Rate</th>
                                        <th>GST Amt</th>
                                        <th>Total Amt</th>
                                        <th>Payment</th>
                                        <th>Payment Mode</th>
                                        <th>Payment Method</th>
                                        <th>Invoice Id</th>
                                        <!-- <th>Remarks</th> -->
                                        <th>Status</th>
                                        <th>Approval</th>
                                        <th>Approved By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($sales) && is_array($sales)): ?>
                                        <?php foreach($sales as $sale): ?>
                                            <?php if(isset($sale->sale_number) && !empty($sale->sale_number)): ?>
                                                
                                            <tr>
                                                <td>
                                                    <?php if($sale->status == 'CONFIRMED' && $sale->accountant_approval_status != 'APPROVED'): ?>
                                                        <input type="checkbox" 
                                                            class="sale-checkbox" 
                                                            value="<?php echo $sale->id; ?>">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($sale->sale_number); ?></strong>
                                                </td>
                                                <td><?php echo isset($sale->patient_id) ? htmlspecialchars($sale->patient_id) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->patient_name) ? htmlspecialchars($sale->patient_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->center_name) ? htmlspecialchars($sale->center_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->salesperson_name) ? htmlspecialchars($sale->salesperson_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->sale_date) && !empty($sale->sale_date) ? date('M d, Y', strtotime($sale->sale_date)) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->total_items) && is_numeric($sale->total_items) ? number_format($sale->total_items) : '0'; ?></td>
                                                <td><?php echo isset($sale->total_quantity) && is_numeric($sale->total_quantity) ? number_format($sale->total_quantity) : '0'; ?></td>
                                                <td>₹<?php 
                                                    $taxable_amount = $sale->total_amount - $sale->tax_amount;
                                                    echo number_format($taxable_amount, 2); 
                                                ?></td>
                                                <td><?php echo isset($sale->gst_rates) && !empty($sale->gst_rates) ? htmlspecialchars($sale->gst_rates) . '%' : 'N/A'; ?></td>
                                                <td>₹<?php echo isset($sale->tax_amount) && is_numeric($sale->tax_amount) ? number_format($sale->tax_amount, 2) : '0.00'; ?></td>
                                                <td>₹<?php echo isset($sale->total_amount) && is_numeric($sale->total_amount) ? number_format($sale->total_amount, 2) : '0.00'; ?></td>
                                                
                                                <!-- *** MODIFIED: Added ID to this TD for easy JS update *** -->
                                                <td id="payment-cell-<?php echo $sale->id; ?>">
                                                    <?php if(isset($sale->payment_status) && !empty($sale->payment_status)): ?>
                                                    <span id="payment_status_badge_<?php echo $sale->id; ?>" 
                                                        class="badge 
                                                        <?php 
                                                            echo $sale->payment_status == 'PAID' ? 'badge-success' : 
                                                                ($sale->payment_status == 'PARTIAL' ? 'badge-warning' : 
                                                                ($sale->payment_status == 'CANCELLED' ? 'badge-secondary' : 
                                                                ($sale->payment_status == 'REJECTED' ? 'badge-danger' : 'badge-danger')));
                                                        ?>">
                                                        <?php echo htmlspecialchars($sale->payment_status); ?>
                                                    </span>
                                                    <?php 
                                                    // Show who approved/rejected
                                                    if($sale->payment_status == 'PAID' && isset($sale->payment_approved_by_name) && !empty($sale->payment_approved_by_name)): ?>
                                                        <br><small class="text-success"><i class="fa fa-check"></i> By: <?php echo htmlspecialchars($sale->payment_approved_by_name); ?></small>
                                                    <?php elseif($sale->payment_status == 'REJECTED' && isset($sale->payment_rejected_by_name) && !empty($sale->payment_rejected_by_name)): ?>
                                                        <br><small class="text-danger"><i class="fa fa-times"></i> By: <?php echo htmlspecialchars($sale->payment_rejected_by_name); ?></small>
                                                    <?php endif; ?>
                                                    <?php if((isset($sale->utr_transaction_id) && !empty($sale->utr_transaction_id)) || (isset($sale->payment_image) && !empty($sale->payment_image)) || $sale->payment_status == 'PAID' || $sale->payment_status == 'REJECTED'): ?>
                                                        <br><a href="#" class="view-payment-details" data-sale-id="<?php echo $sale->id; ?>" style="font-size: 11px; color: #007bff;">
                                                            <i class="fa fa-eye"></i> View Details
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td id="payment-method-cell-<?php echo $sale->id; ?>" style="width:140px !important">
                                                    <select class="form-control payment-method-select" data-sale-id="<?php echo $sale->id; ?>" style="width: 100%; padding: 2px 5px; font-size: 12px; border: 1px solid #ddd; border-radius: 3px;">
                                                        <option value="">Select Method</option>
                                                        <option value="CASH" <?php echo (isset($sale->payment_method) && $sale->payment_method == 'CASH') ? 'selected' : ''; ?>>Cash</option>
                                                        <option value="CARD" <?php echo (isset($sale->payment_method) && $sale->payment_method == 'CARD') ? 'selected' : ''; ?>>Card</option>
                                                        <option value="UPI" <?php echo (isset($sale->payment_method) && $sale->payment_method == 'UPI') ? 'selected' : ''; ?>>UPI</option>
                                                        <option value="CHEQUE" <?php echo (isset($sale->payment_method) && $sale->payment_method == 'CHEQUE') ? 'selected' : ''; ?>>Cheque</option>
                                                        <option value="INSURANCE" <?php echo (isset($sale->payment_method) && $sale->payment_method == 'INSURANCE') ? 'selected' : ''; ?>>Insurance</option>
                                                        <option value="CREDIT" <?php echo (isset($sale->payment_method) && $sale->payment_method == 'CREDIT') ? 'selected' : ''; ?>>Credit</option>
                                                    </select>
                                                </td>
                                                <td><?php echo isset($sale->payment_method) ? htmlspecialchars($sale->payment_method) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->series_number) ? htmlspecialchars($sale->series_number) : 'N/A'; ?></td>
                                                <!-- <td><?php echo isset($sale->remarks) ? htmlspecialchars($sale->remarks) : 'N/A'; ?></td> -->
                                                <td>
                                                    <?php if(isset($sale->status) && !empty($sale->status)): ?>
                                                    <span class="badge <?php 
                                                        echo $sale->status == 'CONFIRMED' ? 'badge-success' : 
                                                            ($sale->status == 'CANCELLED' ? 'badge-danger' : 'badge-warning'); 
                                                    ?>">
                                                        <?php echo htmlspecialchars($sale->status); ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <!-- NEW: Accountant Approval Column -->
                                                <td id="approval-cell-<?php echo $sale->id; ?>">
                                                    <?php 
                                                    $approval_status = isset($sale->accountant_approval_status) ? $sale->accountant_approval_status : 'PENDING';
                                                    $is_accountant = isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant']);
                                                    // Show approval column for CONFIRMED sales OR sales that have been reviewed (DISAPPROVED/CANCELLED by accountant)
                                                    $show_approval = ($sale->status == 'CONFIRMED') || 
                                                                     ($approval_status == 'DISAPPROVED') || 
                                                                     ($approval_status == 'CANCELLED') ||
                                                                     ($approval_status == 'APPROVED');
                                                    ?>
                                                    
                                                    <?php if($show_approval):  ?>
                                                        <?php if($approval_status == 'APPROVED'): ?>
                                                            <span class="badge badge-success"><i class="fa fa-check"></i>APPROVED</span>
                                                            <?php if($sale->tally_status == 'APPROVED_TALLY'): ?>
                                                            <span class="badge badge-success"><i class="fa fa-check"></i>  <?php echo htmlspecialchars($sale->tally_status); ?></span>
                                                            <?php endif; ?>
                                                            <?php if($sale->tally_status == 'PENDING_TALLY' || $sale->tally_status == null): ?>
                                                            <a href="<?php echo base_url('stocks_new/send_to_tally/' . $sale->id); ?>" 
                                                               class="btn btn-xs btn-info" style="margin-top: 5px;">
                                                                <i class="fa fa-calculator"></i> Send to Tally
                                                            </a>
                                                            <?php endif; ?>
                                                            <?php if(isset($sale->accountant_approved_by_name) && !empty($sale->accountant_approved_by_name)): ?>
                                                                <br><small class="text-success">By: <?php echo htmlspecialchars($sale->accountant_approved_by_name); ?></small>
                                                            <?php endif; ?>
                                                        <?php elseif($approval_status == 'DISAPPROVED'): ?>
                                                            <span class="badge badge-danger"><i class="fa fa-times"></i> DISAPPROVED</span>
                                                            <?php if(isset($sale->accountant_approved_by_name) && !empty($sale->accountant_approved_by_name)): ?>
                                                                <br><small class="text-danger">By: <?php echo htmlspecialchars($sale->accountant_approved_by_name); ?></small>
                                                            <?php endif; ?>
                                                            <?php if(isset($sale->stock_restored) && $sale->stock_restored == 1): ?>
                                                                <br><small class="text-info"><i class="fa fa-undo"></i> Stock Restored</small>
                                                            <?php endif; ?>
                                                        <?php elseif($approval_status == 'CANCELLED'): ?>
                                                            <span class="badge badge-secondary"><i class="fa fa-ban"></i> CANCELLED</span>
                                                            <?php if(isset($sale->accountant_approved_by_name) && !empty($sale->accountant_approved_by_name)): ?>
                                                                <br><small class="text-muted">By: <?php echo htmlspecialchars($sale->accountant_approved_by_name); ?></small>
                                                            <?php endif; ?>
                                                            <?php if(isset($sale->stock_restored) && $sale->stock_restored == 1): ?>
                                                                <br><small class="text-info"><i class="fa fa-undo"></i> Stock Restored</small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <!-- PENDING - Show View Invoice link for accountant -->
                                                            <span class="badge badge-warning"><i class="fa fa-clock-o"></i> PENDING</span>
                                                            <?php if($is_accountant): ?>
                                                                <br>
                                                                <a href="<?php echo base_url('stocks_new/edit_sale/' . $sale->id); ?>" 
                                                                   class="btn btn-xs btn-info" style="margin-top: 5px;">
                                                                    <i class="fa fa-eye"></i> View & Approve
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <!-- Approved By Column -->
                                                <td>
                                                    <?php if(isset($sale->accountant_approved_by_name) && !empty($sale->accountant_approved_by_name)): ?>
                                                        <?php echo htmlspecialchars($sale->accountant_approved_by_name); ?>
                                                        <?php if(isset($sale->accountant_approved_at) && !empty($sale->accountant_approved_at)): ?>
                                                            <br><small class="text-muted"><?php echo date('d/m/Y', strtotime($sale->accountant_approved_at)); ?></small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_sale/' . (isset($sale->id) ? $sale->id : '')); ?>">
                                                                <i class="fa fa-edit"></i> View/Edit
                                                            </a></li>
                                                            <?php if(isset($sale->status) && $sale->status == 'DRAFT'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/confirm_sale/' . (isset($sale->id) ? $sale->id : '')); ?>" onclick="return confirm('Are you sure you want to confirm this sale? This will reduce stock.')">
                                                                    <i class="fa fa-check"></i> Confirm Sale
                                                                </a></li>
                                                            <?php endif; ?>
                                                            <?php if(isset($sale->status) && $sale->status == 'CONFIRMED'): ?>
                                                            <li><a href="<?php echo base_url('stocks_new/print_sale/' . (isset($sale->id) ? $sale->id : '')); ?>" target="_blank">
                                                                <i class="fa fa-print"></i> Print Bill
                                                            </a></li>
                                                            <?php endif; ?>
                                                            
                                                            <!-- *** NEW: Change Payment Status Button *** -->
                                                            <li class="divider mt-5"></li>
                                                            <li>
                                                                <a href="#" class="change-payment-status" 
                                                                   data-sale-id="<?php echo isset($sale->id) ? $sale->id : ''; ?>"
                                                                   data-current-status="<?php echo isset($sale->payment_status) ? $sale->payment_status : 'UNPAID'; ?>">
                                                                    <?php if(isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant'])): ?>
                                                                    <i class="fa fa-check-square-o"></i> Verify Payment (Approve/Reject)
                                                                    <?php else: ?>
                                                                    <i class="fa fa-money"></i> Change Payment Status
                                                                    <?php endif; ?>
                                                                </a>
                                                            </li>
                                                            <?php if((isset($sale->utr_transaction_id) && !empty($sale->utr_transaction_id)) || (isset($sale->payment_image) && !empty($sale->payment_image))): ?>
                                                            <li>
                                                                <a href="#" class="view-payment-details" data-sale-id="<?php echo $sale->id; ?>">
                                                                    <i class="fa fa-eye"></i> View Payment Details
                                                                </a>
                                                            </li>
                                                            <?php endif; ?>

                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php if(empty($sales) || !is_array($sales) || count(array_filter($sales, function($s) { return isset($s->sale_number) && !empty($s->sale_number); })) == 0): ?>
                                        <tr>
                                            <td colspan="18" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No sales found. The database table 'sales' may not exist. <a href="<?php echo base_url('stocks_new/add_sale'); ?>">Create your first sale</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="text-center mt-3">
    <ul><?php echo $pagination_links; ?></ul>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sales Statistics (omitted for brevity... your existing code) -->
        
        <!-- FEFO Sales Information (omitted for brevity... your existing code) -->
        
    </div> <!-- This closes a container div from your original file -->
</div> <!-- This closes a container div from your original file -->


<!-- *** NEW: Payment Status Modal *** -->
<?php 
// Check if user is from account team
$is_accountant = isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant']);
?>
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal-close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="paymentModalLabel">
                    <?php echo $is_accountant ? 'Payment Verification' : 'Change Payment Status'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <form id="paymentStatusForm" enctype="multipart/form-data">
                    <!-- Hidden field to store the sale ID -->
                    <input type="hidden" id="modal_sale_id" name="sale_id" value="">
                    
                    <?php if($is_accountant): ?>
                    <!-- ACCOUNTANT VIEW: Only Approve/Reject/Cancel options -->
                    <div class="form-group">
                        <label for="modal_payment_status"><strong>Payment Action:</strong></label>
                        <select id="modal_payment_status" name="new_status" class="form-control" required>
                            <option value="">-- Select Action --</option>
                            <option value="PAID">✓ APPROVE (Confirm Payment)</option>
                            <option value="REJECTED">✗ REJECT (Restore Stock)</option>
                            <option value="CANCELLED">✗ CANCEL (Restore Stock)</option>
                        </select>
                        <small class="help-block text-info">
                            <i class="fa fa-info-circle"></i> As an accountant, you can approve, reject or cancel this payment.
                        </small>
                        <small class="help-block text-warning" id="stock_restore_warning" style="display: none;">
                            <i class="fa fa-exclamation-triangle"></i> This action will restore stock to inventory.
                        </small>
                    </div>
                    <?php else: ?>
                    <!-- OTHER USERS VIEW: All status options -->
                    <div class="form-group">
                        <label for="modal_payment_status">New Payment Status:</label>
                        <select id="modal_payment_status" name="new_status" class="form-control" required>
                            <option value="PENDING">PENDING</option>
                            <option value="PARTIAL">PARTIAL</option>
                            <option value="PAID">PAID</option>
                            <option value="REJECTED">REJECTED (Restore Stock)</option>
                            <option value="CANCELLED">CANCELLED (Restore Stock)</option>
                        </select>
                        <small class="help-block text-warning" id="stock_restore_warning" style="display: none;">
                            <i class="fa fa-exclamation-triangle"></i> Selecting REJECTED or CANCELLED will restore stock to inventory.
                        </small>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="modal_payment_remark">Remark: <span class="text-danger">*</span></label>
                        <textarea id="modal_payment_remark" name="remarks" class="form-control" rows="3" placeholder="<?php echo $is_accountant ? 'Enter reason for your decision (required)' : 'Enter remark (optional)'; ?>" <?php echo $is_accountant ? 'required' : ''; ?>></textarea>
                    </div>
                    
                    <?php if(!$is_accountant): ?>
                    <!-- Only show these fields for non-accountants -->
                    <div class="form-group">
                        <label for="modal_utr_transaction_id">UTR / Transaction ID:</label>
                        <input type="text" id="modal_utr_transaction_id" name="utr_transaction_id" class="form-control" placeholder="Enter UTR or Transaction ID (optional)">
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_payment_image">Payment Proof (Image/PDF):</label>
                        <input type="file" id="modal_payment_image" name="payment_image" class="form-control" accept="image/*,.pdf">
                        <small class="help-block">Upload payment receipt or screenshot (optional - Max 5MB)</small>
                        <div id="imagePreview" style="margin-top: 10px; display: none;">
                            <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default modal-close">Close</button>
                <button type="button" class="btn btn-primary" id="savePaymentStatus">
                    <?php echo $is_accountant ? 'Submit Decision' : 'Save Changes'; ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- *** END of Modal *** -->

<!-- Payment Details View Modal -->
<style>
#paymentDetailsModal .modal-header {
    position: relative !important;
    padding: 15px !important;
    padding-right: 50px !important;
    border-bottom: 1px solid #e5e5e5 !important;
    min-height: 50px !important;
}
#paymentDetailsModal .modal-header .close-btn {
    position: absolute !important;
    right: 10px !important;
    top: 10px !important;
    z-index: 9999 !important;
    opacity: 1 !important;
    color: #333 !important;
    font-size: 24px !important;
    font-weight: bold !important;
    line-height: 1 !important;
    padding: 8px 12px !important;
    background: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid #ddd !important;
    border-radius: 3px !important;
    cursor: pointer !important;
    display: inline-block !important;
    width: auto !important;
    height: auto !important;
    text-decoration: none !important;
    visibility: visible !important;
}
#paymentDetailsModal .modal-header .close-btn:hover {
    opacity: 1 !important;
    color: #000 !important;
    background: rgba(240, 240, 240, 0.95) !important;
    border-color: #999 !important;
}
#paymentDetailsModal .modal-header .close-btn i {
    font-size: 20px !important;
    line-height: 1 !important;
    display: inline-block !important;
    vertical-align: middle !important;
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: unset!important;
    left: -9999px;
    opacity: 1!important;;
}
.fixed-table-wrapper {
    max-height: 700px;
    overflow: auto;
}

.fixed-table-wrapper table {
    min-width: 1600px; /* adjust based on your columns */
}

.fixed-table-wrapper thead th {
    position: sticky;
    top: 0;
    background: #343a40;
    color: white;
    z-index: 20;
}
</style>
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="paymentDetailsModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="position: relative;">
                <h4 class="modal-title" id="paymentDetailsModalLabel">Payment Details</h4>
                <button type="button" class="close-btn modal-close" aria-label="Close" title="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Loading payment details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default modal-close">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- *** NEW: Accountant Sale Approval Modal *** -->
<div class="modal fade" id="saleApprovalModal" tabindex="-1" role="dialog" aria-labelledby="saleApprovalModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f7f7f7; border-bottom: 2px solid #3498db;">
                <button type="button" class="close modal-close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="saleApprovalModalLabel">
                    <i class="fa fa-gavel"></i> Sale Approval - Accountant Review
                </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong><i class="fa fa-info-circle"></i> Sale Number:</strong> <span id="approval_sale_number"></span>
                </div>
                
                <form id="saleApprovalForm">
                    <input type="hidden" id="approval_sale_id" name="sale_id" value="">
                    
                    <div class="form-group">
                        <label><strong>Select Your Decision:</strong></label>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-4">
                                <label class="btn btn-success btn-block" style="padding: 15px;">
                                    <input type="radio" name="approval_action" value="APPROVED" style="margin-right: 5px;" required>
                                    <i class="fa fa-check fa-2x"></i><br>
                                    <strong>APPROVE</strong>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="btn btn-danger btn-block" style="padding: 15px;">
                                    <input type="radio" name="approval_action" value="DISAPPROVED" style="margin-right: 5px;">
                                    <i class="fa fa-times fa-2x"></i><br>
                                    <strong>DISAPPROVE</strong>
                                    <small style="display:block; font-size:10px;">(Restore Stock)</small>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="btn btn-warning btn-block" style="padding: 15px;">
                                    <input type="radio" name="approval_action" value="CANCELLED" style="margin-right: 5px;">
                                    <i class="fa fa-ban fa-2x"></i><br>
                                    <strong>CANCEL</strong>
                                    <small style="display:block; font-size:10px;">(Restore Stock)</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="approval_remarks"><strong>Remarks / Reason:</strong> <span class="text-danger">*</span></label>
                        <textarea id="approval_remarks" name="remarks" class="form-control" rows="3" placeholder="Enter your reason for this decision (required)" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default modal-close">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitSaleApproval">
                    <i class="fa fa-check"></i> Submit Decision
                </button>
            </div>
        </div>
    </div>
</div>
<!-- *** END: Accountant Sale Approval Modal *** -->

<script>
$(document).ready(function() {
    // Check if table has valid data before initializing DataTables
    var table = $('#salesTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 19 && !$(this).find('td[colspan]').length;
    });
    
    console.log('Total rows:', rows.length);
    console.log('Valid rows:', validRows.length);
    
    if(validRows.length > 0) {
        try {
            $('#salesTable').DataTable({
                "pageLength": 25,
                "order": [[ 5, "desc" ]], // Sort by date descending
                "columnDefs": [
                    { "orderable": false, "targets": [17, 18] } // Approved By, Actions columns
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No sales data available",
                    "zeroRecords": "No matching records found"
                },
                "processing": true,
                "deferRender": true
            });
            console.log('DataTables initialized successfully');
        } catch(e) {
            console.error('DataTables initialization failed:', e);
        }
    } else {
        console.log('No valid data rows found, skipping DataTables initialization');
        // Add basic styling to make it look like a table
        table.addClass('table-striped table-bordered table-hover');
    }

    // --- *** NEW SCRIPT FOR PAYMENT STATUS *** ---

    // Get the base URL for the controller
    var updateUrl = "<?php echo base_url('stocks_new/update_payment_status'); ?>";

    // Image preview functionality (only for images, not PDFs)
    $('#modal_payment_image').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            // Check if file is an image
            if (file.type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImg').attr('src', e.target.result);
                    $('#imagePreview').show();
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                // For PDFs, show a message instead of preview
                $('#previewImg').attr('src', '');
                $('#imagePreview').html('<p class="text-info"><i class="fa fa-file-pdf-o"></i> PDF file selected: ' + file.name + '</p>').show();
            } else {
                $('#imagePreview').hide();
            }
        } else {
            $('#imagePreview').hide();
        }
    });

    // Function to open modal (works with both Bootstrap and Materialize)
    function openPaymentModal() {
        // Try Materialize first
        if (typeof M !== 'undefined' && M.Modal) {
            var modalElement = document.getElementById('paymentModal');
            var modalInstance = M.Modal.getInstance(modalElement);
            if (!modalInstance) {
                modalInstance = M.Modal.init(modalElement, {
                    dismissible: true,
                    opacity: 0.5,
                    inDuration: 300,
                    outDuration: 200
                });
            }
            modalInstance.open();
        } else {
            // Fallback to Bootstrap or manual show
            $('#paymentModal').addClass('in').css('display', 'block');
            $('body').addClass('modal-open');
            $('<div class="modal-backdrop fade in"></div>').appendTo('body');
        }
    }

    // Function to close modal (works with both Bootstrap and Materialize)
    function closePaymentModal() {
        // Try Materialize first
        if (typeof M !== 'undefined' && M.Modal) {
            var modalElement = document.getElementById('paymentModal');
            var modalInstance = M.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.close();
            }
        }
        // Fallback to Bootstrap or manual hide
        $('#paymentModal').removeClass('in').css('display', 'none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        
        // Clear form when modal is closed
        $('#paymentStatusForm')[0].reset();
        $('#imagePreview').hide();
        $('#previewImg').attr('src', '');
        $('#savePaymentStatus').html('Save Changes').prop('disabled', false);
    }

    // Function to open payment details modal
    function openPaymentDetailsModal() {
        // Try Materialize first
        if (typeof M !== 'undefined' && M.Modal) {
            var modalElement = document.getElementById('paymentDetailsModal');
            var modalInstance = M.Modal.getInstance(modalElement);
            if (!modalInstance) {
                modalInstance = M.Modal.init(modalElement, {
                    dismissible: true,
                    opacity: 0.5,
                    inDuration: 300,
                    outDuration: 200
                });
            }
            modalInstance.open();
        } else {
            // Fallback to Bootstrap or manual show
            $('#paymentDetailsModal').addClass('in').css('display', 'block');
            $('body').addClass('modal-open');
            $('<div class="modal-backdrop fade in"></div>').appendTo('body');
        }
    }

    // Function to close payment details modal
    function closePaymentDetailsModal() {
        // Try Materialize first
        if (typeof M !== 'undefined' && M.Modal) {
            var modalElement = document.getElementById('paymentDetailsModal');
            var modalInstance = M.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.close();
            }
        }
        // Fallback to Bootstrap or manual hide
        $('#paymentDetailsModal').removeClass('in').css('display', 'none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }

    // Handle close buttons for both modals
    $(document).on('click', '.modal-close, .modal-backdrop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Check which modal is open
        var $paymentModal = $('#paymentModal');
        var $paymentDetailsModal = $('#paymentDetailsModal');
        
        if ($paymentModal.hasClass('in') || $paymentModal.css('display') == 'block') {
            closePaymentModal();
        }
        if ($paymentDetailsModal.hasClass('in') || $paymentDetailsModal.css('display') == 'block') {
            closePaymentDetailsModal();
        }
    });
    
    // Specific handler for payment details modal close button
    $(document).on('click', '#paymentDetailsModal .close-btn, #paymentDetailsModal .close, #paymentDetailsModal .modal-close', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closePaymentDetailsModal();
    });

    // Handle escape key
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27) {
            if ($('#paymentModal').hasClass('in') || $('#paymentModal').css('display') == 'block') {
                closePaymentModal();
            }
            if ($('#paymentDetailsModal').hasClass('in') || $('#paymentDetailsModal').css('display') == 'block') {
                closePaymentDetailsModal();
            }
        }
    });

    // Show/hide stock restore warning based on selected status
    $('#modal_payment_status').on('change', function() {
        var selectedStatus = $(this).val();
        if (selectedStatus == 'CANCELLED' || selectedStatus == 'REJECTED') {
            $('#stock_restore_warning').show();
        } else {
            $('#stock_restore_warning').hide();
        }
    });

    // View Payment Details handler
    $(document).on('click', '.view-payment-details', function(e) {
        e.preventDefault();
        var saleId = $(this).data('sale-id');
        
        // Show loading
        $('#paymentDetailsContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Loading payment details...</p></div>');
        
        // Open modal
        openPaymentDetailsModal();
        
        // Fetch payment details
        $.ajax({
            url: "<?php echo base_url('stocks_new/get_payment_details'); ?>",
            type: 'GET',
            data: { sale_id: saleId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var html = '<div class="row">';
                    html += '<div class="col-md-12">';
                    html += '<h5><i class="fa fa-info-circle"></i> Payment Information</h5>';
                    html += '<hr>';
                    
                    // Payment Status
                    html += '<div class="form-group">';
                    html += '<label><strong>Payment Status:</strong></label>';
                    var statusClass = 'badge-danger';
                    if (response.data.payment_status == 'PAID') statusClass = 'badge-success';
                    else if (response.data.payment_status == 'PARTIAL') statusClass = 'badge-warning';
                    else if (response.data.payment_status == 'REJECTED') statusClass = 'badge-danger';
                    else if (response.data.payment_status == 'CANCELLED') statusClass = 'badge-secondary';
                    html += '<p><span class="badge ' + statusClass + '">' + response.data.payment_status + '</span></p>';
                    html += '</div>';
                    
                    // Approval Info (if PAID)
                    if (response.data.payment_status == 'PAID' && response.data.payment_approved_by_name) {
                        html += '<div class="form-group">';
                        html += '<label><strong><i class="fa fa-check-circle text-success"></i> Approved By:</strong></label>';
                        html += '<p class="form-control-static text-success"><strong>' + response.data.payment_approved_by_name + '</strong>';
                        if (response.data.payment_approved_at) {
                            html += ' <small class="text-muted">on ' + response.data.payment_approved_at + '</small>';
                        }
                        html += '</p>';
                        html += '</div>';
                    }
                    
                    // Rejection Info (if REJECTED)
                    if (response.data.payment_status == 'REJECTED' && response.data.payment_rejected_by_name) {
                        html += '<div class="form-group">';
                        html += '<label><strong><i class="fa fa-times-circle text-danger"></i> Rejected By:</strong></label>';
                        html += '<p class="form-control-static text-danger"><strong>' + response.data.payment_rejected_by_name + '</strong>';
                        if (response.data.payment_rejected_at) {
                            html += ' <small class="text-muted">on ' + response.data.payment_rejected_at + '</small>';
                        }
                        html += '</p>';
                        html += '</div>';
                    }
                    
                    // Stock Restoration Info
                    if (response.data.stock_restored == 1) {
                        html += '<div class="form-group">';
                        html += '<label><strong><i class="fa fa-undo text-info"></i> Stock Restored:</strong></label>';
                        html += '<p class="form-control-static text-info">Yes';
                        if (response.data.stock_restored_at) {
                            html += ' <small class="text-muted">on ' + response.data.stock_restored_at + '</small>';
                        }
                        html += '</p>';
                        html += '</div>';
                    }
                    
                    // UTR/Transaction ID
                    if (response.data.utr_transaction_id) {
                        html += '<div class="form-group">';
                        html += '<label><strong>UTR / Transaction ID:</strong></label>';
                        html += '<p class="form-control-static">' + response.data.utr_transaction_id + '</p>';
                        html += '</div>';
                    }
                    
                    // Payment Image
                    if (response.data.payment_image) {
                        html += '<div class="form-group">';
                        html += '<label><strong>Payment Proof:</strong></label><br>';
                        var imageUrl = "<?php echo base_url('assets/'); ?>" + response.data.payment_image;
                        if (response.data.payment_image.toLowerCase().endsWith('.pdf')) {
                            html += '<a href="' + imageUrl + '" target="_blank" class="btn btn-info"><i class="fa fa-file-pdf-o"></i> View PDF</a>';
                        } else {
                            html += '<a href="' + imageUrl + '" target="_blank"><img src="' + imageUrl + '" class="img-responsive img-thumbnail" style="max-width: 100%; max-height: 400px; cursor: pointer;" alt="Payment Proof"></a>';
                            html += '<br><small class="text-muted">Click image to view full size</small>';
                        }
                        html += '</div>';
                    }
                    
                    // Remarks
                    if (response.data.remarks) {
                        html += '<div class="form-group">';
                        html += '<label><strong>Remarks:</strong></label>';
                        html += '<p class="form-control-static">' + response.data.remarks + '</p>';
                        html += '</div>';
                    }
                    
                    // Updated date
                    if (response.data.updated_at) {
                        html += '<div class="form-group">';
                        html += '<label><strong>Last Updated:</strong></label>';
                        html += '<p class="form-control-static text-muted"><small>' + response.data.updated_at + '</small></p>';
                        html += '</div>';
                    }
                    
                    html += '</div></div>';
                    $('#paymentDetailsContent').html(html);
                } else {
                    $('#paymentDetailsContent').html('<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> ' + response.message + '</div>');
                }
            },
            error: function() {
                $('#paymentDetailsContent').html('<div class="alert alert-danger"><i class="fa fa-ban"></i> Error loading payment details. Please try again.</div>');
            }
        });
    });

    // 1. When user clicks "Change Payment Status" link
    // We use $(document).on(...) to support DataTables pagination
    $(document).on('click', '.change-payment-status', function(e) {
        e.preventDefault();
        
        // Get data from the link's data attributes
        var saleId = $(this).data('sale-id');
        var currentStatus = $(this).data('current-status');
        
        // Populate the modal
        $('#modal_sale_id').val(saleId);
        $('#modal_payment_status').val(currentStatus);
        $('#modal_payment_remark').val(''); // Clear remark field
        $('#modal_utr_transaction_id').val(''); // Clear UTR field
        $('#modal_payment_image').val(''); // Clear image field
        $('#imagePreview').hide(); // Hide preview
        
        // Open the modal
        openPaymentModal();
    });

    // 2. When user clicks the "Save" button in the modal
    $('#savePaymentStatus').on('click', function() {
        var $button = $(this);
        var $form = $('#paymentStatusForm');
        var isAccountant = <?php echo (isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant'])) ? 'true' : 'false'; ?>;
        
        // Validate required fields
        if (!$('#modal_payment_status').val()) {
            alert(isAccountant ? 'Please select an action (Approve/Reject/Cancel)' : 'Please select a payment status');
            return;
        }
        
        // Accountants must provide a remark
        if (isAccountant && !$('#modal_payment_remark').val().trim()) {
            alert('Please provide a reason for your decision');
            $('#modal_payment_remark').focus();
            return;
        }
        
        // Show loading state
        $button.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        // Create FormData object for file upload
        var formData = new FormData($form[0]);

        // 3. Perform the AJAX POST request to the controller with file upload
        $.ajax({
            url: updateUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Check if the response from the controller is 'success'
                if (response.success) {
                    
                    // Close the modal
                    closePaymentModal();
                    
                    // Show success message briefly, then refresh the page
                    $('#alert-container').html(
                        '<div class="alert alert-success alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                        response.message + '<br><small>Page will refresh in 1 second...</small>' +
                        '</div>'
                    );

                    // Refresh the page after 1 second
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else {
                    // Show error message from the controller
                     $('#alert-container').html(
                        '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                        response.message +
                        '</div>'
                     );
                }
            },
            error: function(xhr, status, error) {
                // Show a generic AJAX error
                var errorMsg = 'Could not connect to the server. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#alert-container').html(
                    '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                    errorMsg +
                    '</div>'
                 );
            },
            complete: function() {
                // Reset button text and re-enable it
                $button.html('Save Changes').prop('disabled', false);
            }
        });
    });

    // Payment Method Change Handler
    $(document).on('change', '.payment-method-select', function() {
        var $select = $(this);
        var saleId = $select.data('sale-id');
        var newPaymentMethod = $select.val();
        var $cell = $('#payment-method-cell-' + saleId);

        // Show loading state
        $select.prop('disabled', true);
        $cell.css('opacity', '0.6');

        // AJAX call to update payment method
        $.ajax({
            url: "<?php echo base_url('stocks_new/update_payment_method'); ?>",
            type: 'POST',
            data: {
                sale_id: saleId,
                payment_method: newPaymentMethod
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update the read-only Payment Method column instantly
                    var $row = $cell.closest('tr');
                    var $paymentMethodCell = $row.find('td').eq(14); // Payment Method column (0-indexed)
                    $paymentMethodCell.text(newPaymentMethod || 'N/A');

                    // Show success indication
                    $cell.css('background-color', '#d4edda');
                    setTimeout(function() {
                        $cell.css('background-color', '');
                    }, 1000);

                    // Show success message
                    $('#alert-container').html(
                        '<div class="alert alert-success alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                        'Payment method updated successfully.' +
                        '</div>'
                    );

                    // Auto-hide alert after 3 seconds
                    setTimeout(function() {
                        $('.alert').fadeOut();
                    }, 3000);
                } else {
                    // Show error message
                    $('#alert-container').html(
                        '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                        response.message +
                        '</div>'
                    );

                    // Reset the select to previous value if there was an error
                    // Note: We can't easily restore the previous value without storing it
                    // The server should handle validation and return error if needed
                }
            },
            error: function(xhr, status, error) {
                // Show error message
                $('#alert-container').html(
                    '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                    'Failed to update payment method. Please try again.' +
                    '</div>'
                );

                // Reset on error
                $select.val($select.data('original-value') || '');
            },
            complete: function() {
                // Restore normal state
                $select.prop('disabled', false);
                $cell.css('opacity', '1');
            }
        });
    });

    // Store original value when select gains focus
    $(document).on('focus', '.payment-method-select', function() {
        var $select = $(this);
        $select.data('original-value', $select.val());
    });

    // --- *** END OF NEW SCRIPT *** ---
    document.getElementById('modal_payment_status').addEventListener('change', function() {
        const badge = document.getElementById('payment_status_badge');
        const status = this.value;
        // Reset badge classes
        badge.className = 'badge';
        // Set text
        badge.textContent = status;
        // Apply color based on status
        switch (status) {
            case 'PAID':
                badge.classList.add('badge-success');
                break;
            case 'PARTIAL':
                badge.classList.add('badge-warning');
                break;
            case 'CANCELLED':
                badge.classList.add('badge-secondary');
                break;
            default: // PENDING
                badge.classList.add('badge-danger');
        }
    });

    // Export functionality
    window.exportSalesList = function(format) {
        // Get current filter values
        var centerId = $('select[name="center_id"]').val() || '';
        var patientId = $('input[name="patient_id"]').val() || '';
        var patientName = $('input[name="patient_name"]').val() || '';
        var status = $('select[name="status"]').val() || '';
        var approvalStatus = $('select[name="approval_status"]').val() || '';
        var dateFrom = $('input[name="date_from"]').val() || '';
        var dateTo = $('input[name="date_to"]').val() || '';
        
        // Build export URL with filters
        var url = '<?php echo base_url("stocks_new/export_sales_list"); ?>?format=' + format;
        if(centerId) url += '&center_id=' + centerId;
        if(patientId) url += '&patient_id=' + patientId;
        if(patientName) url += '&patient_name=' + patientName;
        if(status) url += '&status=' + status;
        if(approvalStatus) url += '&approval_status=' + approvalStatus;
        if(dateFrom) url += '&date_from=' + dateFrom;
        if(dateTo) url += '&date_to=' + dateTo;
        
        // Open in new window for download
        window.open(url, '_blank');
    };

    window.exportDetailedSales = function(format) {
        // Get current filter values
        var centerId = $('select[name="center_id"]').val() || '';
        var patientId = $('input[name="patient_id"]').val() || '';
        var patientName = $('input[name="patient_name"]').val() || '';
        var status = $('select[name="status"]').val() || '';
        var approvalStatus = $('select[name="approval_status"]').val() || '';
        var dateFrom = $('input[name="date_from"]').val() || '';
        var dateTo = $('input[name="date_to"]').val() || '';

        // Build export URL with filters
        var url = '<?php echo base_url("stocks_new/get_detailed_sales_for_export"); ?>?format=' + format;
        if(centerId) url += '&center_id=' + centerId;
        if(patientId) url += '&patient_id=' + patientId;
        if(patientName) url += '&patient_name=' + patientName;
        if(status) url += '&status=' + status;
        if(approvalStatus) url += '&approval_status=' + approvalStatus;
        if(dateFrom) url += '&date_from=' + dateFrom;
        if(dateTo) url += '&date_to=' + dateTo;

        // Open in new window for download
        window.open(url, '_blank');
    };

    // *** NEW: Sale Approval Modal Functions ***
    
    // Open Sale Approval Modal
    function openSaleApprovalModal() {
        if (typeof M !== 'undefined' && M.Modal) {
            var modalElement = document.getElementById('saleApprovalModal');
            var modalInstance = M.Modal.getInstance(modalElement);
            if (!modalInstance) {
                modalInstance = M.Modal.init(modalElement, {
                    dismissible: true,
                    opacity: 0.5,
                    inDuration: 300,
                    outDuration: 200
                });
            }
            modalInstance.open();
        } else {
            $('#saleApprovalModal').addClass('in').css('display', 'block');
            $('body').addClass('modal-open');
            $('<div class="modal-backdrop fade in"></div>').appendTo('body');
        }
    }

    // Close Sale Approval Modal
    function closeSaleApprovalModal() {
        if (typeof M !== 'undefined' && M.Modal) {
            var modalElement = document.getElementById('saleApprovalModal');
            var modalInstance = M.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.close();
            }
        }
        $('#saleApprovalModal').removeClass('in').css('display', 'none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        
        // Reset form
        $('#saleApprovalForm')[0].reset();
        $('input[name="approval_action"]').parent().removeClass('active');
        $('#submitSaleApproval').html('<i class="fa fa-check"></i> Submit Decision').prop('disabled', false);
    }

    // Handle close button for approval modal
    $(document).on('click', '#saleApprovalModal .modal-close', function(e) {
        e.preventDefault();
        closeSaleApprovalModal();
    });

    // Highlight selected radio button
    $(document).on('change', 'input[name="approval_action"]', function() {
        $('input[name="approval_action"]').parent().removeClass('active');
        $(this).parent().addClass('active');
    });

    // Open modal when Review button is clicked
    $(document).on('click', '.approve-sale-btn', function(e) {
        e.preventDefault();
        var saleId = $(this).data('sale-id');
        var saleNumber = $(this).data('sale-number');
        
        // Set values in modal
        $('#approval_sale_id').val(saleId);
        $('#approval_sale_number').text(saleNumber);
        
        // Open modal
        openSaleApprovalModal();
    });

    // Submit Sale Approval
    $('#submitSaleApproval').on('click', function() {
        var $button = $(this);
        var saleId = $('#approval_sale_id').val();
        var action = $('input[name="approval_action"]:checked').val();
        var remarks = $('#approval_remarks').val().trim();
        
        // Validation
        if (!action) {
            alert('Please select an action (Approve, Disapprove, or Cancel)');
            return;
        }
        
        if (!remarks) {
            alert('Please provide a reason for your decision');
            $('#approval_remarks').focus();
            return;
        }
        
        // Confirm action for disapprove/cancel (stock will be restored)
        if (action == 'DISAPPROVED' || action == 'CANCELLED') {
            if (!confirm('This will restore the stock to inventory. Are you sure you want to ' + action.toLowerCase() + ' this sale?')) {
                return;
            }
        }
        
        // Show loading
        $button.html('<i class="fa fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
        
        // AJAX call
        $.ajax({
            url: "<?php echo base_url('stocks_new/accountant_approve_sale'); ?>",
            type: 'POST',
            data: {
                sale_id: saleId,
                approval_action: action,
                remarks: remarks
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    closeSaleApprovalModal();
                    
                    $('#alert-container').html(
                        '<div class="alert alert-success alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                        response.message + '<br><small>Page will refresh in 1 second...</small>' +
                        '</div>'
                    );
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    $('#alert-container').html(
                        '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                        response.message +
                        '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Could not connect to the server. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#alert-container').html(
                    '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                    errorMsg +
                    '</div>'
                );
            },
            complete: function() {
                $button.html('<i class="fa fa-check"></i> Submit Decision').prop('disabled', false);
            }
        });
    });
    // *** END: Sale Approval Modal Functions ***

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // COUNT FUNCTION
    function updateSelectedCount() {
        let count = document.querySelectorAll('.sale-checkbox:checked').length;
        let counter = document.getElementById('selected_count');
        if(counter){
            counter.innerText = count;
        }
    }

    // SELECT ALL
    let selectAll = document.getElementById('select_all');
    if(selectAll){
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.sale-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    // INDIVIDUAL CHECKBOX CHANGE
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('sale-checkbox')) {
            updateSelectedCount();
        }
    });

    // BULK APPROVE BUTTON
    let bulkBtn = document.getElementById('bulk_approve_btn');
    if(bulkBtn){
        bulkBtn.addEventListener('click', function (e) {

            e.preventDefault(); // VERY IMPORTANT

            let selected = [];
            document.querySelectorAll('.sale-checkbox:checked').forEach(cb => {
                selected.push(cb.value);
            });

            if(selected.length === 0){
                alert('Please select at least one sale.');
                return;
            }

            if(!confirm('Are you sure you want to approve selected sales?')){
                return;
            }

            fetch("<?php echo base_url('stocks_new/bulk_approve_sales'); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ sale_ids: selected })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => {
                console.error(error);
                alert("Something went wrong.");
            });

        });
    }

});
</script>