<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-list-alt"></i> Generic Names Management
                    <small>Manage generic medicine names and classifications</small>
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
                        <a href="<?php echo base_url('stocks_new/add_generic_name'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Generic Name
                        </a>
                        <a href="<?php echo base_url('stocks_new/categories'); ?>" class="btn btn-info">
                            <i class="fa fa-tags"></i> Categories
                        </a>
                        <a href="<?php echo base_url('stocks_new/medicines'); ?>" class="btn btn-success">
                            <i class="fa fa-pills"></i> Medicines
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
                        <form action="<?php echo base_url('stocks_new/generic_names'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Search:</label>
                                <input type="text" name="search" class="form-control" placeholder="Search generic names" value="<?php echo $this->input->get('search'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Category:</label>
                                <select name="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo $this->input->get('category_id') == $category->id ? 'selected' : ''; ?>>
                                            <?php echo $category->category_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="ACTIVE" <?php echo $this->input->get('status') == 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                    <option value="INACTIVE" <?php echo $this->input->get('status') == 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/generic_names'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Generic Names Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Generic Names List
                        <span class="badge pull-right"><?php echo isset($generic_names) ? count($generic_names) : 0; ?> names</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="genericNamesTable">
                                <thead>
                                    <tr>
                                        <th>Generic Name</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Medicines Count</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($generic_names) && !empty($generic_names) && is_array($generic_names)): ?>
                                        <?php foreach($generic_names as $generic): ?>
                                            <?php if(isset($generic) && is_object($generic)): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo isset($generic->generic_name) ? htmlspecialchars($generic->generic_name) : 'N/A'; ?></strong>
                                                </td>
                                                <td><?php echo isset($generic->category_name) ? htmlspecialchars($generic->category_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($generic->description) ? htmlspecialchars($generic->description) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo isset($generic->medicines_count) ? intval($generic->medicines_count) : 0; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo (isset($generic->status) && $generic->status == 'ACTIVE') ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo isset($generic->status) ? htmlspecialchars($generic->status) : 'N/A'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($generic->created_at) ? date('M d, Y', strtotime($generic->created_at)) : 'N/A'; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_generic_name/' . (isset($generic->id) ? intval($generic->id) : 0)); ?>">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/medicines?generic_id=' . (isset($generic->id) ? intval($generic->id) : 0)); ?>">
                                                                <i class="fa fa-pills"></i> View Medicines
                                                            </a></li>
                                                            <?php if(isset($generic->status) && $generic->status == 'ACTIVE'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/deactivate_generic_name/' . (isset($generic->id) ? intval($generic->id) : 0)); ?>" onclick="return confirm('Are you sure you want to deactivate this generic name?')">
                                                                    <i class="fa fa-ban"></i> Deactivate
                                                                </a></li>
                                                            <?php else: ?>
                                                                <li><a href="<?php echo base_url('stocks_new/activate_generic_name/' . (isset($generic->id) ? intval($generic->id) : 0)); ?>" onclick="return confirm('Are you sure you want to activate this generic name?')">
                                                                    <i class="fa fa-check"></i> Activate
                                                                </a></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No generic names found. <a href="<?php echo base_url('stocks_new/add_generic_name'); ?>">Create your first generic name</a>
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
        
        <!-- Generic Names Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-list-alt fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($generic_names) ? count($generic_names) : 0; ?></div>
                                <div>Total Generic Names</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-check-circle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($generic_names) ? count(array_filter($generic_names, function($g) { return isset($g->status) && $g->status == 'ACTIVE'; })) : 0; ?></div>
                                <div>Active</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-pills fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($generic_names) ? array_sum(array_map(function($g) { return isset($g->medicines_count) ? $g->medicines_count : 0; }, $generic_names)) : 0; ?></div>
                                <div>Total Medicines</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-tags fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($generic_names) ? count(array_unique(array_map(function($g) { return isset($g->category_name) ? $g->category_name : 'N/A'; }, $generic_names))) : 0; ?></div>
                                <div>Categories</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Generic Names Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Generic Names Management
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Generic Names Features:</h4>
                                <ul>
                                    <li><strong>Standardized Names:</strong> Use WHO/INN generic names</li>
                                    <li><strong>Category Classification:</strong> Organize by therapeutic class</li>
                                    <li><strong>Medicine Association:</strong> Link to branded medicines</li>
                                    <li><strong>Search & Discovery:</strong> Easy medicine identification</li>
                                    <li><strong>Status Management:</strong> Activate/deactivate as needed</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Common Generic Names:</h4>
                                <ul>
                                    <li>Paracetamol (Pain Relief)</li>
                                    <li>Amoxicillin (Antibiotic)</li>
                                    <li>Metformin (Diabetes)</li>
                                    <li>Aspirin (Cardiovascular)</li>
                                    <li>Omeprazole (Gastrointestinal)</li>
                                    <li>Salbutamol (Respiratory)</li>
                                    <li>Ibuprofen (Anti-inflammatory)</li>
                                    <li>Ciprofloxacin (Antibiotic)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Check if table exists and has proper structure
    var $table = $('#genericNamesTable');
    var $tbody = $table.find('tbody');
    var $rows = $tbody.find('tr');
    
    // Only initialize DataTables if we have valid data rows (not empty state row)
    if ($rows.length > 0) {
        var hasDataRows = false;
        $rows.each(function() {
            var $row = $(this);
            var $cells = $row.find('td');
            // Check if this is a data row (not the "no data" row)
            if ($cells.length === 7 && !$row.find('td[colspan]').length) {
                hasDataRows = true;
                return false; // break out of loop
            }
        });
        
        if (hasDataRows) {
            try {
                $table.DataTable({
                    "pageLength": 25,
                    "order": [[ 0, "asc" ]], // Sort by generic name
                    "columnDefs": [
                        { "orderable": false, "targets": 6 } // Disable sorting on Actions column
                    ],
                    "language": {
                        "emptyTable": "No generic names available",
                        "zeroRecords": "No matching generic names found"
                    },
                    "responsive": true,
                    "autoWidth": false,
                    "destroy": true // Allow re-initialization
                });
            } catch (e) {
                console.error('DataTables initialization error:', e);
            }
        }
    }
});
</script>

