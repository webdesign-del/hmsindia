<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus-circle text-primary"></i> Add New Brand
            <small>Add a new medicine brand to the system</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/dashboard"><i class="fa fa-cubes"></i> Stock Management</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/brands"><i class="fa fa-tags"></i> Brands</a></li>
            <li class="active"><i class="fa fa-plus"></i> Add Brand</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-tag"></i> Brand Information
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <?php echo form_open('stocks_new/add_brand', array('class' => 'form-horizontal', 'id' => 'brandForm')); ?>
                    <div class="box-body">
                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Validation Error!</h4>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">
                                        <i class="fa fa-tag text-primary"></i> Brand Name <span class="text-red">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-tag"></i>
                                            </span>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo set_value('name'); ?>" 
                                                   placeholder="Enter brand name" required>
                                        </div>
                                        <?php echo form_error('name', '<div class="text-danger"><i class="fa fa-exclamation-circle"></i> ', '</div>'); ?>
                                        <small class="help-block">
                                            <i class="fa fa-info-circle"></i> Enter the complete brand name as it appears on the medicine packaging
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">
                                        <i class="fa fa-toggle-on text-primary"></i> Status <span class="text-red">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="radio-group">
                                            <label class="radio-inline radio-custom">
                                                <input type="radio" name="status" value="1" <?php echo set_radio('status', '1', TRUE); ?>>
                                                <span class="radio-label">
                                                    <i class="fa fa-check-circle text-green"></i> Active
                                                </span>
                                            </label>
                                            <label class="radio-inline radio-custom">
                                                <input type="radio" name="status" value="0" <?php echo set_radio('status', '0'); ?>>
                                                <span class="radio-label">
                                                    <i class="fa fa-times-circle text-red"></i> Inactive
                                                </span>
                                            </label>
                                        </div>
                                        <?php echo form_error('status', '<div class="text-danger"><i class="fa fa-exclamation-circle"></i> ', '</div>'); ?>
                                        <small class="help-block">
                                            <i class="fa fa-info-circle"></i> Active brands will be available for selection in medicine forms
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="hidden" name="action" value="add_brand">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save"></i> Add Brand
                                </button>
                                <a href="<?php echo base_url(); ?>stocks_new/brands" class="btn btn-default btn-lg">
                                    <i class="fa fa-arrow-left"></i> Back to Brands
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.radio-group {
    margin-top: 5px;
}

.radio-custom {
    margin-right: 20px;
    padding: 8px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 5px;
    background: #f9f9f9;
    transition: all 0.3s ease;
    cursor: pointer;
}

.radio-custom:hover {
    border-color: #3c8dbc;
    background: #f0f8ff;
}

.radio-custom input[type="radio"] {
    display: none;
}

.radio-custom input[type="radio"]:checked + .radio-label {
    color: #3c8dbc;
    font-weight: bold;
}

.radio-custom input[type="radio"]:checked {
    background: #3c8dbc;
}

.radio-custom:has(input[type="radio"]:checked) {
    border-color: #3c8dbc;
    background: #e3f2fd;
}

.radio-label {
    margin-left: 5px;
    font-weight: 500;
}

.input-group-addon {
    background-color: #f4f4f4;
    border-color: #d2d6de;
}

.box-solid .box-header {
    background: linear-gradient(135deg, #3c8dbc 0%, #2c6aa0 100%);
    color: white;
}

.box-solid .box-header .box-title {
    color: white;
}

.help-block {
    margin-top: 5px;
    font-style: italic;
}

.btn-lg {
    padding: 10px 20px;
    font-size: 16px;
    margin: 0 5px;
}

.text-red {
    color: #dd4b39 !important;
}

.text-green {
    color: #00a65a !important;
}

.text-primary {
    color: #3c8dbc !important;
}

#brandForm .form-group {
    margin-bottom: 25px;
}

#brandForm .control-label {
    font-weight: 600;
    padding-top: 8px;
}
</style>

<script>
$(document).ready(function() {
    // Enhanced form validation with better UX
    $('#brandForm').on('submit', function(e) {
        var isValid = true;
        var name = $('#name').val().trim();
        var status = $('input[name="status"]:checked').val();
        
        // Clear previous error states
        $('.form-group').removeClass('has-error');
        $('.help-block').removeClass('text-danger');
        
        // Validate brand name
        if (!name) {
            e.preventDefault();
            $('#name').closest('.form-group').addClass('has-error');
            $('#name').focus();
            showNotification('Please enter brand name', 'error');
            isValid = false;
        } else if (name.length < 2) {
            e.preventDefault();
            $('#name').closest('.form-group').addClass('has-error');
            $('#name').focus();
            showNotification('Brand name must be at least 2 characters long', 'error');
            isValid = false;
        }
        
        // Validate status
        if (!status) {
            e.preventDefault();
            $('input[name="status"]').closest('.form-group').addClass('has-error');
            showNotification('Please select status', 'error');
            isValid = false;
        }
        
        if (isValid) {
            // Show loading state
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Adding Brand...');
            submitBtn.prop('disabled', true);
        }
    });
    
    // Real-time validation
    $('#name').on('input', function() {
        var name = $(this).val().trim();
        if (name.length > 0) {
            $(this).closest('.form-group').removeClass('has-error');
        }
    });
    
    $('input[name="status"]').on('change', function() {
        $(this).closest('.form-group').removeClass('has-error');
    });
    
    // Auto-focus on name field
    $('#name').focus();
    
    // Function to show notifications
    function showNotification(message, type) {
        var alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
        var icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
        
        var notification = $('<div class="alert ' + alertClass + ' alert-dismissible" style="margin-top: 10px;">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            '<h4><i class="icon fa ' + icon + '"></i> ' + (type === 'error' ? 'Error!' : 'Success!') + '</h4>' +
            message +
            '</div>');
        
        $('.box-body').prepend(notification);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            notification.fadeOut();
        }, 5000);
    }
});
</script>