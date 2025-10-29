<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <?php 
        $selected_center = isset($_SESSION['logged_billing_manager']['center']) 
            ? $_SESSION['logged_billing_manager']['center'] 
            : '';
        ?>
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-plus"></i> Create New Sale
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
                        <p><strong>Centers Count:</strong> <?php echo is_array($centers) ? count($centers) : 'Not an array'; ?></p>
                        <?php if(!empty($centers) && is_array($centers)): ?>
                            <p><strong>First Center:</strong></p>
                            <pre><?php print_r($centers[0]); ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> -->
        <?php endif; ?>
        
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url('stocks_new/sales'); ?>">Sales</a></li>
                    <li class="active">Create Sale</li>
                </ol>
            </div>
        </div>
        
        <!-- Sale Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-shopping-cart"></i> Sale Information
                    </div>
                    <div class="panel-body">
                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger">
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?php echo base_url('stocks_new/add_sale'); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="add_sale">
                            
                            <!-- Sale Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Center *</label>
                                        <div class="col-sm-8">
                                            <select name="center_id" class="form-control" required onchange="loadCenterStock()">
                                            <option value="">Select Center</option>
                                            <?php if (!empty($centers) && is_array($centers)): ?>
                                                <?php foreach ($centers as $center): ?>
                                                    <?php 
                                                        $isSelected = ($center->center_number == $selected_center) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo isset($center->ID) ? $center->ID : ''; ?>" <?php echo $isSelected; ?>>
                                                        <?php echo isset($center->center_name) ? htmlspecialchars($center->center_name) : 'N/A'; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No centers available</option>
                                            <?php endif; ?>
                                        </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Patient Name *</label>
                                      <div class="col-sm-8">
                                        <input type="text" 
                                            name="patient_name" 
                                            class="form-control" 
                                            placeholder="Enter patient name" 
                                            value="<?php echo !empty($patient_name) ? $patient_name : set_value('patient_name'); ?>" 
                                            required>
                                     </div>

                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Patient ID</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="patient_id" class="form-control" placeholder="Enter patient ID" 
                                            value="<?php echo !empty($patient_id) ? $patient_id : set_value('patient_id'); ?>"> 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Doctor Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="doctor_name" class="form-control" placeholder="Enter doctor name"
                                             value="<?php echo !empty($doctor_name) ? $doctor_name : set_value('doctor_name'); ?>"> 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Sale Date *</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="sale_date" class="form-control" value="<?php echo set_value('sale_date', date('Y-m-d')); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Payment Method</label>
                                        <div class="col-sm-8">
                                            <select name="payment_method" class="form-control">
                                                <option value="CASH" <?php echo set_select('payment_method', 'CASH'); ?>>Cash</option>
                                                <option value="CARD" <?php echo set_select('payment_method', 'CARD'); ?>>Card</option>
                                                <option value="UPI" <?php echo set_select('payment_method', 'UPI'); ?>>UPI</option>
                                                <option value="CHEQUE" <?php echo set_select('payment_method', 'CHEQUE'); ?>>Cheque</option>
                                                <option value="INSURANCE" <?php echo set_select('payment_method', 'INSURANCE'); ?>>Insurance</option>
                                                <option value="CREDIT" <?php echo set_select('payment_method', 'CREDIT'); ?>>Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Payment Status</label>
                                        <div class="col-sm-8">
                                            <select name="payment_status" class="form-control">
                                                <option value="PENDING" <?php echo set_select('payment_status', 'PENDING'); ?>>Pending</option>
                                                <option value="PAID" <?php echo set_select('payment_status', 'PAID'); ?>>Paid</option>
                                                <option value="PARTIAL" <?php echo set_select('payment_status', 'PARTIAL'); ?>>Partial</option>
                                                <option value="CANCELLED" <?php echo set_select('payment_status', 'CANCELLED'); ?>>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter any remarks"><?php echo set_value('remarks'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Create Sale
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-default">
                                                <i class="fa fa-arrow-left"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> FEFO Sales Process
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>How FEFO Works in Sales:</h4>
                                <ol>
                                    <li><strong>Select Center:</strong> Choose the center/pharmacy</li>
                                    <li><strong>Add Items:</strong> Select medicines from available stock</li>
                                    <li><strong>FEFO Selection:</strong> System automatically suggests batches with earliest expiry</li>
                                    <li><strong>Confirm Sale:</strong> Stock is reduced using FEFO principle</li>
                                    <li><strong>Audit Trail:</strong> Complete tracking of which batches were sold</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h4>Benefits:</h4>
                                <ul>
                                    <li>Automatic batch selection by expiry date</li>
                                    <li>Reduces medicine wastage</li>
                                    <li>Ensures patient safety</li>
                                    <li>Complies with regulatory requirements</li>
                                    <li>Complete audit trail for all sales</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Important Notes -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-exclamation-triangle"></i> Important Notes
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>Stock Availability:</strong> Only medicines with available stock in the selected center will be shown.</li>
                            <li><strong>FEFO Priority:</strong> Batches with earliest expiry dates will be suggested first.</li>
                            <li><strong>Confirmation Required:</strong> Sales need to be confirmed before stock is actually reduced.</li>
                            <li><strong>Audit Trail:</strong> All sales are logged with batch-level tracking.</li>
                            <li><strong>Patient Safety:</strong> System prevents sale of expired medicines.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadCenterStock() {
    var centerId = $('select[name="center_id"]').val();
    if (centerId) {
        // You can implement AJAX call here to load center-specific stock information
        console.log('Loading stock for center: ' + centerId);
    }
}

$(document).ready(function() {
    // Initialize form
    loadCenterStock();
});
</script>

