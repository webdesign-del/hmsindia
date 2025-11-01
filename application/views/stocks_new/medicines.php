<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-capsules"></i> Medicine Management
                    <small>Manage all medicines in the system</small>
                </h1>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus"></i> Quick Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/add_medicine'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Medicine
                        </a>
                        <a href="<?php echo base_url('stocks_new/add_batch'); ?>" class="btn btn-success">
                            <i class="fa fa-archive"></i> Add New Batch
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-building-o"></i> View Stock Levels
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
                        <form action="<?php echo base_url('stocks_new/medicines'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Medicine Name:</label>
                                <input type="text" name="medicine_name" class="form-control" placeholder="Enter medicine name" value="<?php echo $this->input->get('medicine_name'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Generic Name:</label>
                                <input type="text" name="generic_name" class="form-control" placeholder="Enter generic name" value="<?php echo $this->input->get('generic_name'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Brand:</label>
                                <select name="brand_id" class="form-control">
                                    <option value="">All Brands</option>
                                    <?php foreach($brands as $brand): ?>
                                        <option value="<?php echo isset($brand->ID) ? $brand->ID : $brand->id; ?>" <?php echo $this->input->get('brand_id') == (isset($brand->ID) ? $brand->ID : $brand->id) ? 'selected' : ''; ?>>
                                            <?php echo isset($brand->brand_name) ? $brand->brand_name : (isset($brand->name) ? $brand->name : 'N/A'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Category:</label>
                                <input type="text" name="category" class="form-control" placeholder="Enter category" value="<?php echo $this->input->get('category'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/medicines'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Medicines Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Medicines List
                        <span class="badge pull-right"><?php echo count($medicines); ?> medicines</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="medicinesTable">
                                <thead>
                                    <tr>
                                        <th>Medicine Code</th>
                                        <th>Medicine Name</th>
                                        <th>Generic Name</th>
                                        <th>Brand</th>
                                        <th>Strength</th>
                                        <th>Unit</th>
                                        <th>Category</th>
                                        <th>Min Stock</th>
                                        <th>Max Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($medicines)): ?>
                                        <?php foreach($medicines as $medicine): ?>
                                            <tr>
                                                <td><?php echo $medicine->medicine_code; ?></td>
                                                <td><?php echo $medicine->medicine_name; ?></td>
                                                <td><?php echo $medicine->generic_name; ?></td>
                                                <td><?php echo $medicine->brand_name; ?></td>
                                                <td><?php echo $medicine->strength; ?></td>
                                                <td><?php echo $medicine->unit; ?></td>
                                                <td><?php echo $medicine->category; ?></td>
                                                <td><?php echo $medicine->min_stock_level; ?></td>
                                                <td><?php echo $medicine->max_stock_level; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $medicine->status == 'active' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo ucfirst($medicine->status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('stocks_new/edit_medicine/' . $medicine->id); ?>" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?php echo base_url('stocks_new/add_batch?medicine_id=' . $medicine->id); ?>" class="btn btn-info btn-sm">
                                                        <i class="fa fa-plus"></i> Add Batch
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No medicines found. <a href="<?php echo base_url('stocks_new/add_medicine'); ?>">Add your first medicine</a>
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
    </div>

<script>
<script>
$(document).ready(function() {
    
    // --- ADD THIS PHP CHECK ---
    // Only initialize DataTable if the $medicines array is not empty
    <?php if(!empty($medicines)): ?>
    
    $('#medicinesTable').DataTable({
        "pageLength": 25,
        "order": [[ 1, "asc" ]], // Sort by second column (Medicine Name)
        "columnDefs": [
            // Assuming 11 columns total, and the last one (index 10) is "Actions"
            { "orderable": false, "targets": 10 } 
        ],
        "language": {
            "emptyTable": "No medicines found",
            "zeroRecords": "No matching medicines found"
        },
        "responsive": true,
        "autoWidth": false
    });

    // --- AND ADD THIS ENDIF ---
    <?php endif; ?>
    
});
</script>
</script>

