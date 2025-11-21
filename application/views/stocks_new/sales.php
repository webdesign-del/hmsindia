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
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="salesTable">
                                <thead>
                                    <tr>
                                        <th>Sale #</th>
                                        <th>Patient</th>
                                        <th>Center</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($sales) && is_array($sales)): ?>
                                        <?php foreach($sales as $sale): ?>
                                            <?php if(isset($sale->sale_number) && !empty($sale->sale_number)): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($sale->sale_number); ?></strong>
                                                </td>
                                                <td><?php echo isset($sale->patient_name) ? htmlspecialchars($sale->patient_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->center_name) ? htmlspecialchars($sale->center_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->sale_date) && !empty($sale->sale_date) ? date('M d, Y', strtotime($sale->sale_date)) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->total_items) && is_numeric($sale->total_items) ? number_format($sale->total_items) : '0'; ?></td>
                                                <td><?php echo isset($sale->total_quantity) && is_numeric($sale->total_quantity) ? number_format($sale->total_quantity) : '0'; ?></td>
                                                <td>₹<?php echo isset($sale->total_amount) && is_numeric($sale->total_amount) ? number_format($sale->total_amount, 2) : '0.00'; ?></td>
                                                
                                                <!-- *** MODIFIED: Added ID to this TD for easy JS update *** -->
                                                <td id="payment-cell-<?php echo $sale->id; ?>">
                                                    <?php if(isset($sale->payment_status) && !empty($sale->payment_status)): ?>
                                                    <!-- <span class="badge <?php 
                                                        echo $sale->payment_status == 'PAID' ? 'badge-success' : 
                                                            ($sale->payment_status == 'PARTIAL' ? 'badge-warning' : 'badge-danger'); 
                                                    ?>">
                                                        <?php echo htmlspecialchars($sale->payment_status); ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?> -->
                                                    <span id="payment_status_badge" 
                                                        class="badge 
                                                        <?php 
                                                            echo $sale->payment_status == 'PAID' ? 'badge-success' : 
                                                                ($sale->payment_status == 'PARTIAL' ? 'badge-warning' : 
                                                                ($sale->payment_status == 'CANCELLED' ? 'badge-secondary' : 'badge-danger'));
                                                        ?>">
                                                        <?php echo htmlspecialchars($sale->payment_status); ?>
                                                    </span>

                                                </td>
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
                                                            <li><a href="<?php echo base_url('stocks_new/print_sale/' . (isset($sale->id) ? $sale->id : '')); ?>" target="_blank">
                                                                <i class="fa fa-print"></i> Print Bill
                                                            </a></li>
                                                            
                                                            <!-- *** NEW: Change Payment Status Button *** -->
                                                            <li class="divider mt-5"></li>
                                                            <li>
                                                                <a href="#" class="change-payment-status" 
                                                                   data-sale-id="<?php echo isset($sale->id) ? $sale->id : ''; ?>"
                                                                   data-current-status="<?php echo isset($sale->payment_status) ? $sale->payment_status : 'UNPAID'; ?>">
                                                                    <i class="fa fa-money"></i> Change Payment Status
                                                                </a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php if(empty($sales) || !is_array($sales) || count(array_filter($sales, function($s) { return isset($s->sale_number) && !empty($s->sale_number); })) == 0): ?>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No sales found. The database table 'sales' may not exist. <a href="<?php echo base_url('stocks_new/add_sale'); ?>">Create your first sale</a>
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
        
        <!-- Sales Statistics (omitted for brevity... your existing code) -->
        
        <!-- FEFO Sales Information (omitted for brevity... your existing code) -->
        
    </div> <!-- This closes a container div from your original file -->
</div> <!-- This closes a container div from your original file -->


<!-- *** NEW: Payment Status Modal *** -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" style="box-shadow:none !important;background-color:transparent !important;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="paymentModalLabel">Change Payment Status</h4>
            </div>
            <div class="modal-body">
                <!-- Hidden field to store the sale ID -->
                <input type="hidden" id="modal_sale_id" value="">
                
                <div class="form-group">
                    <label for="modal_payment_status">New Payment Status:</label>
                    <!-- <select id="modal_payment_status" class="form-control">
                        <option value="PENDING">PENDING</option>
                        <option value="PARTIAL">PARTIAL</option>
                        <option value="PAID">PAID</option>
                        <option value="CANCELLED">CANCELLED</option>
                    </select> -->
                    <select id="modal_payment_status" class="form-control">
                        <option value="PENDING" <?= $sale->payment_status == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                        <option value="PARTIAL" <?= $sale->payment_status == 'PARTIAL' ? 'selected' : '' ?>>PARTIAL</option>
                        <option value="PAID" <?= $sale->payment_status == 'PAID' ? 'selected' : '' ?>>PAID</option>
                        <option value="CANCELLED" <?= $sale->payment_status == 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="modal_payment_remark">Remark:</label>
                    <textarea id="modal_payment_remark" class="form-control" rows="3" placeholder="Enter remark for payment status change (optional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePaymentStatus">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<!-- *** END of Modal *** -->


<script>
$(document).ready(function() {
    // Check if table has valid data before initializing DataTables
    var table = $('#salesTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 10 && !$(this).find('td[colspan]').length;
    });
    
    console.log('Total rows:', rows.length);
    console.log('Valid rows:', validRows.length);
    
    if(validRows.length > 0) {
        try {
            $('#salesTable').DataTable({
                "pageLength": 25,
                "order": [[ 3, "desc" ]], // Sort by date descending
                "columnDefs": [
                    { "orderable": false, "targets": 9 }
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

    // *** MODIFICATION: Initialize the modal using Materialize CSS ***
    // This is necessary because materialize.js is loaded
    try {
        $('#paymentModal').modal();
    } catch(e) {
        console.error("Could not initialize modal. Is materialize.js loaded correctly?", e);
    }


    // Get the base URL for the controller
    var updateUrl = "<?php echo base_url('stocks_new/update_payment_status'); ?>";

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
        
        // *** MODIFICATION: Use Materialize 'open' command ***
        $('#paymentModal').modal('open');
    });

    // 2. When user clicks the "Save" button in the modal
    $('#savePaymentStatus').on('click', function() {
        var saleId = $('#modal_sale_id').val();
        var newStatus = $('#modal_payment_status').val();
        var remark = $('#modal_payment_remark').val();

        var $button = $(this);
        // Show loading state
        $button.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        // 3. Perform the AJAX POST request to the controller
        $.post(updateUrl, { sale_id: saleId, new_status: newStatus, remarks: remark })
            .done(function(response) {
                // Check if the response from the controller is 'success'
                if (response.success) {
                    
                    // *** MODIFICATION: Use Materialize 'close' command ***
                    $('#paymentModal').modal('close');
                    
                    // Show success message in the alert container
                    $('#alert-container').html(
                        '<div class="alert alert-success alert-dismissible" style="margin-top: 15px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                        response.message +
                        '</div>'
                    );

                    // Update the payment badge in the table
                    var badgeClass = 'badge-danger'; // Default for UNPAID
                    if (newStatus === 'PAID') {
                        badgeClass = 'badge-success';
                    } else if (newStatus === 'PARTIAL') {
                        badgeClass = 'badge-warning';
                    }
                    
                    var newBadge = '<span class="badge ' + badgeClass + '">' + newStatus + '</span>';
                    
                    // Update the cell content
                    $('#payment-cell-' + saleId).html(newBadge);

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
            })
            .fail(function() {
                // Show a generic AJAX error
                $('#alert-container').html(
                    '<div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Error!</h4>' +
                    'Could not connect to the server. Please try again.' +
                    '</div>'
                 );
            })
            .always(function() {
                // Reset button text and re-enable it
                $button.html('Save Changes').prop('disabled', false);
            });
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
        var patientName = $('input[name="patient_name"]').val() || '';
        var status = $('select[name="status"]').val() || '';
        var dateFrom = $('input[name="date_from"]').val() || '';
        var dateTo = $('input[name="date_to"]').val() || '';
        
        // Build export URL with filters
        var url = '<?php echo base_url("stocks_new/export_sales_list"); ?>?format=' + format;
        if(centerId) url += '&center_id=' + centerId;
        if(patientName) url += '&patient_name=' + patientName;
        if(status) url += '&status=' + status;
        if(dateFrom) url += '&date_from=' + dateFrom;
        if(dateTo) url += '&date_to=' + dateTo;
        
        // Open in new window for download
        window.open(url, '_blank');
    };

});
</script>

