<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-plus"></i> Add New Medicine
                    <small>Add a new medicine to the system</small>
                </h1>
            </div>
        </div>
        
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url('stocks_new/medicines'); ?>">Medicines</a></li>
                    <li class="active">Add Medicine</li>
                </ol>
            </div>
        </div>
        
        <!-- Excel Import Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-file-excel-o"></i> Import Medicines from Excel
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <strong><i class="fa fa-info-circle"></i> Instructions:</strong>
                            <ul style="margin-bottom: 0;">
                                <li>Upload an Excel file (.xlsx, .xls) with medicine data</li>
                                <li>First row should contain column headers</li>
                                <li>Required columns: Medicine Code, Medicine Name, Generic Name, Brand Name, Strength, Unit, Category, Min Stock Level, Max Stock Level</li>
                                <li>Optional columns: Pack Size, HSN Code, GST Rate, Reorder Level, Is Narcotic, Is Controlled Substance, Is Psychotropic</li>
                                <li>Download <a href="<?php echo base_url('stocks_new/download_medicine_template'); ?>" target="_blank"><strong>Excel Template</strong></a> for reference</li>
                            </ul>
                        </div>
                        <form action="<?php echo base_url('stocks_new/import_medicines_excel'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Select Excel File *</label>
                                <div class="col-sm-6">
                                    <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                                    <small class="help-block">Supported formats: .xlsx, .xls</small>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-upload"></i> Import Medicines
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Medicine Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-capsules"></i> Medicine Information
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
                        
                        <form action="<?php echo base_url('stocks_new/add_medicine'); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="add_medicine">
                            
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Medicine Code *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="medicine_code" class="form-control" placeholder="Enter unique medicine code" value="<?php echo set_value('medicine_code'); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Medicine Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="medicine_name" class="form-control" placeholder="Enter medicine name" value="<?php echo set_value('medicine_name'); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Generic Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="generic_name" class="form-control" placeholder="Enter generic name" value="<?php echo set_value('generic_name'); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Brand *</label>
                                        <div class="col-sm-8">
                                            <select name="brand_id" class="form-control" required>
                                                <option value="">Select Brand</option>
                                                <?php foreach($brands as $brand): ?>
                                                    <option value="<?php echo isset($brand->ID) ? $brand->ID : $brand->id; ?>" <?php echo set_select('brand_id', isset($brand->ID) ? $brand->ID : $brand->id); ?>>
                                                        <?php echo isset($brand->brand_name) ? $brand->brand_name : (isset($brand->name) ? $brand->name : 'N/A'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Strength *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="strength" class="form-control" placeholder="e.g., 500mg, 10ml" value="<?php echo set_value('strength'); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Unit *</label>
                                        <div class="col-sm-8">
                                            <select name="unit" class="form-control" required>
                                                <option value="">Select Unit</option>
                                                <option value="PCS" <?php echo set_select('unit', 'PCS'); ?>>PCS</option>
                                                <option value="TABLET" <?php echo set_select('unit', 'TABLET'); ?>>TABLET</option>
                                                <option value="CAPSULE" <?php echo set_select('unit', 'CAPSULE'); ?>>CAPSULE</option>
                                                <option value="VIAL" <?php echo set_select('unit', 'VIAL'); ?>>VIAL</option>
                                                <option value="AMPOULE" <?php echo set_select('unit', 'AMPOULE'); ?>>AMPOULE</option>
                                                <option value="BOTTLE" <?php echo set_select('unit', 'BOTTLE'); ?>>BOTTLE</option>
                                                <option value="TUBE" <?php echo set_select('unit', 'TUBE'); ?>>TUBE</option>
                                                <option value="SACHET" <?php echo set_select('unit', 'SACHET'); ?>>SACHET</option>
                                                <option value="PATCH" <?php echo set_select('unit', 'PATCH'); ?>>PATCH</option>
                                                <option value="INJECTION" <?php echo set_select('unit', 'INJECTION'); ?>>INJECTION</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Category *</label>
                                        <div class="col-sm-8">
                                            <select name="category" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <option value="Package injections" <?php echo set_select('category', 'Package injections'); ?>>Package injections</option>
                                                <option value="OT DCI" <?php echo set_select('category', 'OT DCI'); ?>>OT DCI</option>
                                                <option value="EMBRYOLOGIST DCI" <?php echo set_select('category', 'EMBRYOLOGIST DCI'); ?>>EMBRYOLOGIST DCI</option>
                                                <option value="Cash Medicines" <?php echo set_select('category', 'Cash Medicines'); ?>>Cash Medicines</option>
                                                <!-- <option value="PPI" <?php echo set_select('category', 'PPI'); ?>>PPI</option>
                                                <option value="Steroid" <?php echo set_select('category', 'Steroid'); ?>>Steroid</option>
                                                <option value="Vitamin" <?php echo set_select('category', 'Vitamin'); ?>>Vitamin</option>
                                                <option value="Supplements" <?php echo set_select('category', 'Supplements'); ?>>Supplements</option>
                                                <option value="Other" <?php echo set_select('category', 'Other'); ?>>Other</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Pack Size</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pack_size" class="form-control" placeholder="" value="<?php echo set_value('pack_size'); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">HSN Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="hsn_code" class="form-control" placeholder="Enter HSN code" value="<?php echo set_value('hsn_code'); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">GST Rate (%)</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="gst_rate" class="form-control" placeholder="Enter GST rate" value="<?php echo set_value('gst_rate', '12'); ?>" step="0.01" min="0" max="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Min Stock Level *</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="min_stock_level" class="form-control" placeholder="Minimum stock level" value="<?php echo set_value('min_stock_level'); ?>" required min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Max Stock Level *</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="max_stock_level" class="form-control" placeholder="Maximum stock level" value="<?php echo set_value('max_stock_level'); ?>" required min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Reorder Level</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="reorder_level" class="form-control" placeholder="Reorder level" value="<?php echo set_value('reorder_level'); ?>" min="0">
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
                                                    <input type="checkbox" name="is_narcotic" value="1" <?php echo set_checkbox('is_narcotic', '1'); ?> style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                                    Narcotic Drug
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="is_controlled_substance" value="1" <?php echo set_checkbox('is_controlled_substance', '1'); ?> style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                                    Controlled Substance
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="is_psychotropic" value="1" <?php echo set_checkbox('is_psychotropic', '1'); ?> style="left: 0px !important;opacity: 1 !important;position: unset !important;">
                                                    Psychotropic Substance
                                                </label>
                                            </div>
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
                                                <i class="fa fa-save"></i> Add Medicine
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/medicines'); ?>" class="btn btn-default">
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
    </div>      
