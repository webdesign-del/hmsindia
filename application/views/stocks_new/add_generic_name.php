<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-plus"></i> Add Generic Name
                    <small>Add a new generic medicine name</small>
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Generic Name Information</h3>
                    </div>
                    <div class="panel-body">
                        <?php if(isset($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger">
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?php echo base_url('stocks_new/add_generic_name'); ?>" class="form-horizontal">
                            <input type="hidden" name="action" value="add_generic_name" />
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generic_name" class="col-sm-4 control-label">Generic Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="generic_name" name="generic_name" 
                                                   value="<?php echo set_value('generic_name'); ?>" 
                                                   placeholder="Enter generic name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="generic_code" class="col-sm-4 control-label">Generic Code *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="generic_code" name="generic_code" 
                                                   value="<?php echo set_value('generic_code'); ?>" 
                                                   placeholder="Enter generic code" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="col-sm-4 control-label">Description</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" id="description" name="description" rows="3" 
                                                      placeholder="Enter generic name description"><?php echo set_value('description'); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="therapeutic_class" class="col-sm-4 control-label">Therapeutic Class</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="therapeutic_class" name="therapeutic_class" 
                                                   value="<?php echo set_value('therapeutic_class'); ?>" 
                                                   placeholder="Enter therapeutic class">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="status" name="status">
                                                <option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
                                                <option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Add Generic Name
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/generic_names'); ?>" class="btn btn-default">
                                                <i class="fa fa-arrow-left"></i> Back to Generic Names
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
    </div>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var isValid = true;
        
        // Check required fields
        $('input[required], select[required]').each(function() {
            if($(this).val() === '') {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if(!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Remove error class on input
    $('input, select').on('input change', function() {
        $(this).removeClass('error');
    });
});
</script>

<style>
.error {
    border-color: #d9534f !important;
    box-shadow: 0 0 0 0.2rem rgba(217, 83, 79, 0.25) !important;
}
</style>
