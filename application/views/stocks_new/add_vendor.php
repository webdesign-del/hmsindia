<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Add New Vendor
            <small>Add a new vendor</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/dashboard">Stock Management</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/vendors">Vendors</a></li>
            <li class="active">Add Vendor</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vendor Information</h3>
                    </div>
                    
                    <?php echo form_open('stocks_new/add_vendor', array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>
                    <div class="box-body">
                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Validation Error!</h4>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Basic Information Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-info-circle"></i> Basic Information</h4>
                                    </div>
                                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="col-sm-4 control-label">Vendor Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo set_value('name'); ?>" required>
                                        <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name" class="col-sm-4 control-label">Company Name *</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="company_name" name="company_name" 
                                               value="<?php echo set_value('company_name'); ?>" required>
                                        <?php echo form_error('company_name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="companies_type" class="col-sm-4 control-label">Company Type *</label>
                                    <div class="col-sm-8">
                                        <select name="companies_type" id="companies_type" class="form-control" required>
                                            <option value="">Select Company Type</option>
                                            <option value="Private limited company" <?php echo set_select('companies_type', 'Private limited company'); ?>>Private limited company</option>
                                            <option value="Partnership" <?php echo set_select('companies_type', 'Partnership'); ?>>Partnership</option>
                                            <option value="Limited Liability Company" <?php echo set_select('companies_type', 'Limited Liability Company'); ?>>Limited Liability Company</option>
                                            <option value="Holding company" <?php echo set_select('companies_type', 'Holding company'); ?>>Holding company</option>
                                        </select>
                                        <?php echo form_error('companies_type', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address" class="col-sm-4 control-label">Company Address *</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="company_address" name="company_address" rows="3" required><?php echo set_value('company_address'); ?></textarea>
                                        <?php echo form_error('company_address', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-phone"></i> Contact Information</h4>
                                    </div>
                                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number" class="col-sm-3 control-label">Phone Number *</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                               value="<?php echo set_value('phone_number'); ?>" required>
                                        <?php echo form_error('phone_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo set_value('email'); ?>">
                                        <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person_name" class="col-sm-3 control-label">Contact Person *</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" 
                                               value="<?php echo set_value('contact_person_name'); ?>" required>
                                        <?php echo form_error('contact_person_name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person_designation" class="col-sm-3 control-label">Designation *</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="contact_person_designation" name="contact_person_designation" 
                                               value="<?php echo set_value('contact_person_designation'); ?>" required>
                                        <?php echo form_error('contact_person_designation', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Banking Information Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-bank"></i> Banking Information</h4>
                                    </div>
                                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name" class="col-sm-3 control-label">Bank Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                               value="<?php echo set_value('bank_name'); ?>">
                                        <?php echo form_error('bank_name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch_name" class="col-sm-3 control-label">Branch Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="branch_name" name="branch_name" 
                                               value="<?php echo set_value('branch_name'); ?>">
                                        <?php echo form_error('branch_name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="beneficiary_name" class="col-sm-3 control-label">Beneficiary Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="beneficiary_name" name="beneficiary_name" 
                                               value="<?php echo set_value('beneficiary_name'); ?>">
                                        <?php echo form_error('beneficiary_name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_no" class="col-sm-3 control-label">Account Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="account_no" name="account_no" 
                                               value="<?php echo set_value('account_no'); ?>">
                                        <?php echo form_error('account_no', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifsc_code" class="col-sm-3 control-label">IFSC Code</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" 
                                               value="<?php echo set_value('ifsc_code'); ?>">
                                        <?php echo form_error('ifsc_code', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_type" class="col-sm-3 control-label">Account Type</label>
                                    <div class="col-sm-9">
                                        <select name="account_type" id="account_type" class="form-control">
                                            <option value="">Select Account Type</option>
                                            <option value="Savings Account" <?php echo set_select('account_type', 'Savings Account'); ?>>Savings Account</option>
                                            <option value="Current Account" <?php echo set_select('account_type', 'Current Account'); ?>>Current Account</option>
                                            <option value="Salary Account" <?php echo set_select('account_type', 'Salary Account'); ?>>Salary Account</option>
                                        </select>
                                        <?php echo form_error('account_type', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Legal Documents Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-file-text"></i> Legal Documents & Numbers</h4>
                                    </div>
                                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gst_number" class="col-sm-3 control-label">GST Number *</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="gst_number" name="gst_number" 
                                               value="<?php echo set_value('gst_number'); ?>" required>
                                        <?php echo form_error('gst_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gst_file" class="col-sm-3 control-label">GST Certificate</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="gst_file" name="gst_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload GST certificate (PDF, JPG, PNG)</small>
                                        <?php echo form_error('gst_file', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="drug_license_number" class="col-sm-3 control-label">Drug License Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="drug_license_number" name="drug_license_number" 
                                               value="<?php echo set_value('drug_license_number'); ?>">
                                        <?php echo form_error('drug_license_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="drug_license_file" class="col-sm-3 control-label">Drug License</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="drug_license_file" name="drug_license_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload drug license (PDF, JPG, PNG)</small>
                                        <?php echo form_error('drug_license_file', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pan_number" class="col-sm-3 control-label">PAN Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="pan_number" name="pan_number" 
                                               value="<?php echo set_value('pan_number'); ?>">
                                        <?php echo form_error('pan_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pan_file" class="col-sm-3 control-label">PAN Card</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="pan_file" name="pan_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload PAN card (PDF, JPG, PNG)</small>
                                        <?php echo form_error('pan_file', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fssai_number" class="col-sm-3 control-label">FSSAI Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fssai_number" name="fssai_number" 
                                               value="<?php echo set_value('fssai_number'); ?>">
                                        <?php echo form_error('fssai_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fssai_file" class="col-sm-3 control-label">FSSAI Certificate</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="fssai_file" name="fssai_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload FSSAI certificate (PDF, JPG, PNG)</small>
                                        <?php echo form_error('fssai_file', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="msme_number" class="col-sm-3 control-label">MSME Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="msme_number" name="msme_number" 
                                               value="<?php echo set_value('msme_number'); ?>">
                                        <?php echo form_error('msme_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="msme_file" class="col-sm-3 control-label">MSME Certificate</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="msme_file" name="msme_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload MSME certificate (PDF, JPG, PNG)</small>
                                        <?php echo form_error('msme_file', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cancel_check" class="col-sm-3 control-label">Cancelled Cheque</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="cancel_check" name="cancel_check" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload cancelled cheque (PDF, JPG, PNG)</small>
                                        <?php echo form_error('cancel_check', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mou_file" class="col-sm-3 control-label">MOU Document</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="mou_file" name="mou_file" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload MOU document (PDF, JPG, PNG)</small>
                                        <?php echo form_error('mou_file', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-toggle-on"></i> Status</h4>
                                    </div>
                                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">Status *</label>
                                    <div class="col-sm-9">
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="1" <?php echo set_radio('status', '1', TRUE); ?>> Active
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="0" <?php echo set_radio('status', '0'); ?>> Inactive
                                            </label>
                                        </div>
                                        <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="hidden" name="action" value="add_vendor">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Add Vendor
                        </button>
                        <a href="<?php echo base_url(); ?>stocks_new/vendors" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Vendors
                        </a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.panel {
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    border: none;
}

.panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 20px;
}

.panel-info .panel-heading {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.panel-success .panel-heading {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.panel-warning .panel-heading {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.panel-default .panel-heading {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.panel-title {
    font-weight: 600;
    font-size: 16px;
}

.panel-body {
    padding: 25px;
    background-color: #fafafa;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    border-radius: 4px;
    border: 1px solid #ddd;
    padding: 10px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.control-label {
    font-weight: 600;
    color: #555;
    padding-top: 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 25px;
    font-weight: 600;
    border-radius: 4px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn-default {
    background: #f8f9fa;
    border: 1px solid #ddd;
    color: #555;
    padding: 10px 25px;
    font-weight: 600;
    border-radius: 4px;
}

.btn-default:hover {
    background: #e9ecef;
    border-color: #adb5bd;
    transform: translateY(-1px);
}

.box-footer {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    padding: 20px;
    text-align: right;
}

.text-muted {
    color: #6c757d !important;
    font-size: 12px;
}

.file-upload-info {
    background: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 4px;
    padding: 8px 12px;
    margin-top: 5px;
    font-size: 12px;
    color: #1976d2;
}
</style>

<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var name = $('#name').val().trim();
        var company_name = $('#company_name').val().trim();
        var companies_type = $('#companies_type').val();
        var company_address = $('#company_address').val().trim();
        var phone_number = $('#phone_number').val().trim();
        var contact_person_name = $('#contact_person_name').val().trim();
        var contact_person_designation = $('#contact_person_designation').val().trim();
        var gst_number = $('#gst_number').val().trim();
        var status = $('input[name="status"]:checked').val();
        
        if (!name) {
            e.preventDefault();
            alert('Please enter vendor name');
            $('#name').focus();
            return false;
        }
        
        if (!company_name) {
            e.preventDefault();
            alert('Please enter company name');
            $('#company_name').focus();
            return false;
        }
        
        if (!companies_type) {
            e.preventDefault();
            alert('Please select company type');
            $('#companies_type').focus();
            return false;
        }
        
        if (!company_address) {
            e.preventDefault();
            alert('Please enter company address');
            $('#company_address').focus();
            return false;
        }
        
        if (!phone_number) {
            e.preventDefault();
            alert('Please enter phone number');
            $('#phone_number').focus();
            return false;
        }
        
        if (!contact_person_name) {
            e.preventDefault();
            alert('Please enter contact person name');
            $('#contact_person_name').focus();
            return false;
        }
        
        if (!contact_person_designation) {
            e.preventDefault();
            alert('Please enter contact person designation');
            $('#contact_person_designation').focus();
            return false;
        }
        
        if (!gst_number) {
            e.preventDefault();
            alert('Please enter GST number');
            $('#gst_number').focus();
            return false;
        }
        
        if (!status) {
            e.preventDefault();
            alert('Please select status');
            return false;
        }
    });
});
</script>