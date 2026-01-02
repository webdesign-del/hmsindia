<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-cubes"></i> Medicine Packages
            <small>Manage medicine boxes/packages</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Package List
                <div class="pull-right">
                    <a href="<?php echo base_url('stocks_new/create_package'); ?>" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Create Package
                    </a>
                    <a href="<?php echo base_url('stocks_new/add_package_stock'); ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-plus-circle"></i> Add Package Stock
                    </a>
                    <!-- <a href="<?php echo base_url('stocks_new/transfer_package_stock'); ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-exchange"></i> Transfer Package
                    </a> -->
                    <button type="button" class="btn btn-primary btn-sm" onclick="viewPackageStockReport()">
                        <i class="fa fa-bar-chart"></i> Stock Report
                    </button>
                </div>
            </div>
            <div class="panel-body">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Package Code</th>
                                <th>Package Name</th>
                                <th>Description</th>
                                <th>Items Count</th>
                                <th>Selling Price</th>
                                <th>MRP</th>
                                <th>GST Rate</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($packages)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No packages found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($packages as $package): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($package->package_code); ?></td>
                                        <td><?php echo htmlspecialchars($package->package_name); ?></td>
                                        <td><?php echo htmlspecialchars($package->description ?? 'N/A'); ?></td>
                                        <td><?php echo $package->total_items; ?> items</td>
                                        <td>₹<?php echo number_format($package->selling_price, 2); ?></td>
                                        <td>₹<?php echo number_format($package->mrp, 2); ?></td>
                                        <td><?php echo $package->gst_rate; ?>%</td>
                                        <td>
                                            <span class="badge <?php echo $package->status == 'active' ? 'badge-success' : 'badge-danger'; ?>">
                                                <?php echo ucfirst($package->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    Actions <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="<?php echo base_url('stocks_new/edit_package/' . $package->id); ?>">
                                                            <i class="fa fa-edit"></i> Edit Package
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" onclick="viewPackageItems(<?php echo $package->id; ?>)">
                                                            <i class="fa fa-eye"></i> View Items
                                                        </a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="<?php echo base_url('stocks_new/delete_package/' . $package->id); ?>"
                                                           onclick="return confirm('Are you sure you want to delete this package?')">
                                                            <i class="fa fa-trash"></i> Delete Package
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Package Items Modal (Simple Fallback) -->
<div id="packageItemsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 10000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 5px; max-width: 95%; max-height: 90%; overflow-y: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.2); min-width: 600px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <h4 style="margin: 0; color: #333;">Package Items</h4>
            <button id="closePackageModal" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #666;">&times;</button>
        </div>
        <div id="packageItemsContent" style="min-height: 100px;">
            <!-- Package items will be loaded here -->
        </div>
        <div style="text-align: right; margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee;">
            <button id="closePackageModalBtn" class="btn btn-default">Close</button>
        </div>
    </div>
</div>

<script>
// Simple modal functionality without external dependencies
$(document).ready(function() {
    console.log('Package modal JavaScript loaded successfully');

    // Test jQuery availability
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded!');
        return;
    }

    console.log('jQuery available, setting up modal handlers');

    // Close modal functionality
    function closeModal() {
        console.log('Closing modal');
        $('#packageItemsModal').hide();
    }

    // Close modal when clicking close buttons
    $('#closePackageModal, #closePackageModalBtn').on('click', function(e) {
        console.log('Close button clicked');
        e.preventDefault();
        closeModal();
    });

    // Close modal when clicking outside the modal content
    $('#packageItemsModal').on('click', function(e) {
        if (e.target === this) {
            console.log('Clicked outside modal');
            closeModal();
        }
    });

    // Close modal on Escape key
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27 && $('#packageItemsModal').is(':visible')) {
            console.log('Escape key pressed');
            closeModal();
        }
    });

    console.log('Modal handlers set up successfully');
});

function viewPackageStockReport() {
    console.log('Opening package stock report modal');

    try {
        // Clear previous content and show loading
        $('#packageItemsContent').html('<div class="text-center" style="padding: 20px;"><i class="fa fa-spinner fa-spin"></i> Loading package stock report...</div>');

        // Show modal with updated title
        $('#packageItemsModal h4').text('Package Stock Report');
        $('#packageItemsModal').show();
        console.log('Stock report modal displayed successfully');

        // Make AJAX call to load stock report
        $.ajax({
            url: '<?php echo base_url("stocks_new/get_package_stock_report"); ?>',
            type: 'GET',
            dataType: 'html',
            timeout: 10000,
            cache: false,
            success: function(response) {
                console.log('AJAX success, response length:', response.length);
                if (response.trim() === '') {
                    $('#packageItemsContent').html('<div class="alert alert-warning" style="margin: 10px;">No stock report data available.</div>');
                } else {
                    $('#packageItemsContent').html(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                $('#packageItemsContent').html('<div class="alert alert-danger" style="margin: 10px;"><strong>Error:</strong> Could not load stock report. Please try again.</div>');
            }
        });

    } catch(e) {
        console.error('Error in viewPackageStockReport:', e);
        alert('Error opening stock report. Please try again.');
    }
}

function viewPackageItems(packageId) {
    console.log('Opening package items modal for ID:', packageId);

    try {
        // Clear previous content and show loading
        $('#packageItemsContent').html('<div class="text-center" style="padding: 20px;"><i class="fa fa-spinner fa-spin"></i> Loading package items...</div>');

        // Show modal
        $('#packageItemsModal').show();
        console.log('Modal displayed successfully');

        // Make AJAX call to load content
        $.ajax({
            url: '<?php echo base_url("stocks_new/get_package_items/"); ?>' + packageId,
            type: 'GET',
            dataType: 'html',
            timeout: 10000,
            cache: false,
            success: function(response) {
                console.log('AJAX success, response length:', response.length);
                if (response.trim() === '') {
                    $('#packageItemsContent').html('<div class="alert alert-warning" style="margin: 10px;">No content received from server.</div>');
                } else {
                    $('#packageItemsContent').html(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                $('#packageItemsContent').html('<div class="alert alert-danger" style="margin: 10px;"><strong>Error:</strong> Could not load package items. Please try again.</div>');
            }
        });

    } catch(e) {
        console.error('Error in viewPackageItems:', e);
        alert('Error opening package details. Please try again.');
    }
}

</script>
