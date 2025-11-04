<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Edit Brand
            <small>Update brand information</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/dashboard">Stock Management</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/brands">Brands</a></li>
            <li class="active">Edit Brand</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Brand Information</h3>
                    </div>
                    
                    <?php echo form_open('stocks_new/edit_brand/' . (isset($brand->id) ? $brand->id : $brand->ID), array('class' => 'form-horizontal')); ?>
                    <div class="box-body">
                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Validation Error!</h4>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Brand Name *</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo set_value('name', isset($brand->brand_name) ? $brand->brand_name : (isset($brand->name) ? $brand->name : '')); ?>" required>
                                        <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">Status *</label>
                                    <div class="col-sm-9">
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="1" 
                                                       <?php echo set_radio('status', '1', ($brand->status == '1' || $brand->status == 'active')); ?>> Active
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="0" 
                                                       <?php echo set_radio('status', '0', ($brand->status == '0' || $brand->status == 'inactive')); ?>> Inactive
                                            </label>
                                        </div>
                                        <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Brand Number</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo isset($brand->brand_number) ? $brand->brand_number : 'N/A'; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Created Date</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo isset($brand->date) ? date('d-m-Y H:i:s', strtotime($brand->date)) : 'N/A'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="hidden" name="action" value="update_brand">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Brand
                        </button>
                        <a href="<?php echo base_url(); ?>stocks_new/brands" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Brands
                        </a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var name = $('#name').val().trim();
        var status = $('input[name="status"]:checked').val();
        
        if (!name) {
            e.preventDefault();
            alert('Please enter brand name');
            $('#name').focus();
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