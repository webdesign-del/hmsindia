<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-tags"></i> Categories Management
                    <small>Manage medicine categories and classifications</small>
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
                        <a href="<?php echo base_url('stocks_new/add_category'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Category
                        </a>
                        <a href="<?php echo base_url('stocks_new/generic_names'); ?>" class="btn btn-info">
                            <i class="fa fa-list"></i> Generic Names
                        </a>
                        <a href="<?php echo base_url('stocks_new/medicines'); ?>" class="btn btn-success">
                            <i class="fa fa-pills"></i> Medicines
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Medicine Categories
                        <span class="badge pull-right"><?php echo count($categories); ?> categories</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="categoriesTable">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Parent Category</th>
                                        <th>Medicines Count</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($categories)): ?>
                                        <?php foreach($categories as $category): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo $category->category_name; ?></strong>
                                                </td>
                                                <td><?php echo $category->description; ?></td>
                                                <td><?php echo $category->parent_category ?: 'Root Category'; ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo $category->medicines_count; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo $category->status == 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo $category->status; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($category->created_at)); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_category/' . $category->id); ?>">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/medicines?category_id=' . $category->id); ?>">
                                                                <i class="fa fa-pills"></i> View Medicines
                                                            </a></li>
                                                            <?php if($category->status == 'ACTIVE'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/deactivate_category/' . $category->id); ?>" onclick="return confirm('Are you sure you want to deactivate this category?')">
                                                                    <i class="fa fa-ban"></i> Deactivate
                                                                </a></li>
                                                            <?php else: ?>
                                                                <li><a href="<?php echo base_url('stocks_new/activate_category/' . $category->id); ?>" onclick="return confirm('Are you sure you want to activate this category?')">
                                                                    <i class="fa fa-check"></i> Activate
                                                                </a></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No categories found. <a href="<?php echo base_url('stocks_new/add_category'); ?>">Create your first category</a>
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
        
        <!-- Category Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-tags fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($categories); ?></div>
                                <div>Total Categories</div>
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
                                <div class="huge"><?php echo count(array_filter($categories, function($c) { return $c->status == 'ACTIVE'; })); ?></div>
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
                                <div class="huge"><?php echo array_sum(array_column($categories, 'medicines_count')); ?></div>
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
                                <i class="fa fa-sitemap fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count(array_filter($categories, function($c) { return $c->parent_category; })); ?></div>
                                <div>Sub Categories</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Category Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Category Management
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Category Features:</h4>
                                <ul>
                                    <li><strong>Hierarchical Structure:</strong> Support for parent-child categories</li>
                                    <li><strong>Medicine Classification:</strong> Organize medicines by type and use</li>
                                    <li><strong>Status Management:</strong> Activate/deactivate categories</li>
                                    <li><strong>Medicine Count:</strong> Track medicines in each category</li>
                                    <li><strong>Search & Filter:</strong> Easy medicine discovery by category</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Common Categories:</h4>
                                <ul>
                                    <li>Antibiotics</li>
                                    <li>Pain Relief</li>
                                    <li>Cardiovascular</li>
                                    <li>Diabetes</li>
                                    <li>Respiratory</li>
                                    <li>Gastrointestinal</li>
                                    <li>Vitamins & Supplements</li>
                                    <li>Emergency Medicines</li>
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
    $('#categoriesTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ]], // Sort by category name
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ]
    });
});
</script>

