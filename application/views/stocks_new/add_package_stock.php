<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-plus-circle"></i> Add Package Stock
            <small>Assemble packages from center stocks</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-cubes"></i> Package Stock Assembly
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

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    <strong>Note:</strong> Package assembly deducts medicines from <strong>central_stocks</strong> (warehouse inventory) using FIFO and creates packages at Central Warehouse. Use "Transfer Package Stock" to move packages to selling centers where they can be sold to customers.
                </div>

                <form action="<?php echo base_url('stocks_new/add_package_stock'); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="action" value="add_package_stock">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Select Package *</label>
                                <div class="col-sm-8">
                                    <select name="package_id" id="package_id_select" class="form-control" required>
                                        <option value="">Select Package</option>
                                        <?php foreach($packages as $package): ?>
                                            <?php if($package->status == 'active'): ?>
                                                <option value="<?php echo $package->id; ?>"
                                                        data-name="<?php echo htmlspecialchars($package->package_name); ?>"
                                                        data-code="<?php echo htmlspecialchars($package->package_code); ?>"
                                                        data-price="<?php echo $package->selling_price; ?>">
                                                    <?php echo htmlspecialchars($package->package_name . ' (' . $package->package_code . ')'); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Center</label>
                                <div class="col-sm-8">
                                    <select name="center_id" class="form-control" required>
                                        <option value="">Select Center</option>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo $center->ID; ?>" <?php echo ($center->ID == 1) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($center->center_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Packages are always assembled at Central Warehouse</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">To Department</label>
                                <div class="col-sm-8">
                                        <select name="department" id="department" class="form-control">
                                            <option value="">Select Department</option>
                                            <option value="CASH MEDICINE NOIDA">CASH MEDICINE NOIDA</option>
                                            <option value="CASH MEDICINE GGN">CASH MEDICINE GGN</option>
                                            <option value="CASH MEDICINE BASANT LOK">CASH MEDICINE BASANT LOK</option>
                                            <option value="CASH MEDICINE SRINAGAR">CASH MEDICINE SRINAGAR</option>
                                            <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE GHAZIABAD</option>
                                            <option value="CASH MEDICINE ROHINI">CASH MEDICINE ROHINI</option>
                                            <option value="HORMONAL ROHINI">HORMONAL ROHINI</option>
                                            <option value="Hormonal Ghaziabad">Hormonal Ghaziabad</option>
                                            <option value="HORMONAL SRINAGAR">HORMONAL SRINAGAR</option>
                                            <option value="Hormonal Basant Lok">Hormonal Basant Lok</option>
                                            <option value="Hormonal Gurgaon">Hormonal Gurgaon</option>
                                            <option value="Hormonal Noida">Hormonal Noida</option>
                                            <option value="Embryologist Noida">Embryologist Noida</option>
                                            <option value="OT Noida">OT Noida</option>
                                            <option value="OT Basant Lok">OT Basant Lok</option>
                                            <option value="Embryology Basant Lok">Embryology Basant Lok</option>
                                            <option value="Embryology Srinagar">Embryology Srinagar</option>
                                            <option value="OT Srinagar">OT Srinagar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Quantity (Boxes) *</label>
                                <div class="col-sm-8">
                                    <input type="number" name="quantity" class="form-control" placeholder="Number of boxes to assemble" min="1" required>
                                    <small class="form-text text-muted">Enter how many boxes/packages you want to create</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Package Details</label>
                                <div class="col-sm-8">
                                    <div id="package_details" class="well" style="display: none;">
                                        <p><strong>Package:</strong> <span id="package_name"></span></p>
                                        <p><strong>Code:</strong> <span id="package_code"></span></p>
                                        <p><strong>Price per Box:</strong> ₹<span id="package_price"></span></p>
                                        <hr>
                                        <p><strong>Contents:</strong></p>
                                        <div id="package_contents">
                                            <!-- Package contents will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-plus-circle"></i> Assemble Package Stock
                                    </button>
                                    <a href="<?php echo base_url('stocks_new/packages'); ?>" class="btn btn-default">
                                        <i class="fa fa-times"></i> Cancel
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

<script>
$(document).ready(function() {
    // Handle package selection
    $('#package_id_select').on('change', function() {
        var selectedOption = $(this).find('option:selected');

        if (selectedOption.val()) {
            $('#package_name').text(selectedOption.data('name'));
            $('#package_code').text(selectedOption.data('code'));
            $('#package_price').text(selectedOption.data('price'));
            $('#package_details').show();

            // Load package contents via AJAX
            loadPackageContents(selectedOption.val());
        } else {
            $('#package_details').hide();
            $('#package_contents').html('');
        }
    });

    function loadPackageContents(packageId) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/get_package_items/"); ?>' + packageId,
            type: 'GET',
            success: function(response) {
                $('#package_contents').html(response);
            },
            error: function() {
                $('#package_contents').html('<p class="text-danger">Error loading package contents</p>');
            }
        });
    }
});
</script>

