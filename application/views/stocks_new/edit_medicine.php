<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-edit"></i> Edit Medicine
                    <small>Update medicine information</small>
                </h1>
            </div>
        </div>
        
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url('stocks_new/medicines'); ?>">Medicines</a></li>
                    <li class="active">Edit Medicine</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Medicine Information</h3>
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

                        <form method="post" action="<?php echo base_url('stocks_new/edit_medicine/' . $medicine->id); ?>" class="form-horizontal">
                            <input type="hidden" name="action" value="update_medicine" />
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medicine_code" class="col-sm-4 control-label">Medicine Code *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="medicine_code" name="medicine_code" 
                                                   value="<?php echo set_value('medicine_code', $medicine->medicine_code); ?>" 
                                                   placeholder="Enter unique medicine code" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="medicine_name" class="col-sm-4 control-label">Medicine Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="medicine_name" name="medicine_name" 
                                                   value="<?php echo set_value('medicine_name', $medicine->medicine_name); ?>" 
                                                   placeholder="Enter medicine name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="brand_id" class="col-sm-4 control-label">Brand *</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="brand_id" name="brand_id" required>
                                                <option value="">Select Brand</option>
                                                <?php foreach($brands as $brand): ?>
                                                    <option value="<?php echo isset($brand->ID) ? $brand->ID : $brand->id; ?>" 
                                                            <?php echo set_select('brand_id', isset($brand->ID) ? $brand->ID : $brand->id, ($medicine->brand_id == (isset($brand->ID) ? $brand->ID : $brand->id))); ?>>
                                                        <?php echo isset($brand->brand_name) ? $brand->brand_name : (isset($brand->name) ? $brand->name : 'N/A'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="generic_name" class="col-sm-4 control-label">Generic Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="generic_name" name="generic_name" 
                                                   value="<?php echo set_value('generic_name', $medicine->generic_name); ?>" 
                                                   placeholder="Enter generic name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="strength" class="col-sm-4 control-label">Strength *</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="strength" name="strength" 
                                                   value="<?php echo set_value('strength', $medicine->strength); ?>" 
                                                   placeholder="Enter strength (e.g., 500mg)" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="unit" class="col-sm-4 control-label">Unit *</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="unit" name="unit" required>
                                                <option value="">Select Unit</option>
                                                <option value="PCS" <?php echo set_select('unit', 'PCS', ($medicine->unit == 'PCS')); ?>>PCS</option>
                                                <option value="TABLET" <?php echo set_select('unit', 'TABLET', ($medicine->unit == 'TABLET')); ?>>TABLET</option>
                                                <option value="CAPSULE" <?php echo set_select('unit', 'CAPSULE', ($medicine->unit == 'CAPSULE')); ?>>CAPSULE</option>
                                                <option value="VIAL" <?php echo set_select('unit', 'VIAL', ($medicine->unit == 'VIAL')); ?>>VIAL</option>
                                                <option value="AMPOULE" <?php echo set_select('unit', 'AMPOULE', ($medicine->unit == 'AMPOULE')); ?>>AMPOULE</option>
                                                <option value="BOTTLE" <?php echo set_select('unit', 'BOTTLE', ($medicine->unit == 'BOTTLE')); ?>>BOTTLE</option>
                                                <option value="TUBE" <?php echo set_select('unit', 'TUBE', ($medicine->unit == 'TUBE')); ?>>TUBE</option>
                                                <option value="SACHET" <?php echo set_select('unit', 'SACHET', ($medicine->unit == 'SACHET')); ?>>SACHET</option>
                                                <option value="PATCH" <?php echo set_select('unit', 'PATCH', ($medicine->unit == 'PATCH')); ?>>PATCH</option>
                                                <option value="INJECTION" <?php echo set_select('unit', 'INJECTION', ($medicine->unit == 'INJECTION')); ?>>INJECTION</option>
                                                <option value="ML" <?php echo set_select('unit', 'ML', ($medicine->unit == 'ML')); ?>>ML</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category" class="col-sm-4 control-label">Category *</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="Package injections" <?php echo set_select('category', 'Package injections', ($medicine->category == 'Package injections')); ?>>Package injections</option>
                                                <option value="OT DCI" <?php echo set_select('category', 'OT DCI', ($medicine->category == 'OT DCI')); ?>>OT DCI</option>
                                                <option value="EMBRYOLOGIST DCI" <?php echo set_select('category', 'EMBRYOLOGIST DCI', ($medicine->category == 'EMBRYOLOGIST DCI')); ?>>EMBRYOLOGIST DCI</option>
                                                <option value="Cash Medicines" <?php echo set_select('category', 'Cash Medicines', ($medicine->category == 'Cash Medicines')); ?>>Cash Medicines</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pack_size" class="col-sm-4 control-label">Pack Size</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="pack_size" name="pack_size" 
                                                   value="<?php echo set_value('pack_size', $medicine->pack_size); ?>" 
                                                   placeholder="e.g., 10x10, 1x30">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="hsn_code" class="col-sm-4 control-label">HSN Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="hsn_code" name="hsn_code" 
                                                   value="<?php echo set_value('hsn_code', $medicine->hsn_code); ?>" 
                                                   placeholder="Enter HSN code">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="gst_rate" class="col-sm-4 control-label">GST Rate (%)</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="gst_rate" name="gst_rate" 
                                                   value="<?php echo set_value('gst_rate', $medicine->gst_rate); ?>" 
                                                   placeholder="Enter GST rate" step="0.01" min="0" max="100">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="min_stock_level" class="col-sm-4 control-label">Min Stock Level *</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" 
                                                   value="<?php echo set_value('min_stock_level', $medicine->min_stock_level); ?>" 
                                                   placeholder="Enter minimum stock level" min="0" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="max_stock_level" class="col-sm-4 control-label">Max Stock Level *</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="max_stock_level" name="max_stock_level" 
                                                   value="<?php echo set_value('max_stock_level', $medicine->max_stock_level); ?>" 
                                                   placeholder="Enter maximum stock level" min="0" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="reorder_level" class="col-sm-4 control-label">Reorder Level</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="reorder_level" name="reorder_level" 
                                                   value="<?php echo set_value('reorder_level', $medicine->reorder_level); ?>" 
                                                   placeholder="Reorder level" min="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="status" name="status">
                                                <option value="active" <?php echo set_select('status', 'active', ($medicine->status == 'active')); ?>>Active</option>
                                                <option value="inactive" <?php echo set_select('status', 'inactive', ($medicine->status == 'inactive')); ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Special Properties -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Special Properties</h4>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="is_narcotic" value="1" <?php echo set_checkbox('is_narcotic', '1', ($medicine->is_narcotic == 1)); ?> style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                                    Narcotic Drug
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="is_controlled_substance" value="1" <?php echo set_checkbox('is_controlled_substance', '1', ($medicine->is_controlled_substance == 1)); ?> style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                                    Controlled Substance
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="is_psychotropic" value="1" <?php echo set_checkbox('is_psychotropic', '1', ($medicine->is_psychotropic == 1)); ?> style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                                    Psychotropic Substance
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Update Medicine
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/medicines'); ?>" class="btn btn-default">
                                                <i class="fa fa-arrow-left"></i> Back to Medicines
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

<script>
$(document).ready(function() {
    // Form validation
    $('#edit_medicine_form').on('submit', function(e) {
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
