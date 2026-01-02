<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transfer Package Stock</h3>
                    <small>Move packages from Central Warehouse to selling centers</small>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        <strong>Note:</strong> Transfer packages from Central Warehouse (where they are assembled) to specific departments within selling centers. Central Warehouse is always the source location.
                    </div>

                    <form method="post" action="<?php echo base_url(); ?>stocks_new/transfer_package_stock">
                        <input type="hidden" name="action" value="transfer_package_stock">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="package_id">Package *</label>
                                    <select name="package_id" id="package_id" class="form-control" required>
                                        <option value="">Select Package</option>
                                        <?php foreach ($packages as $package): ?>
                                            <option value="<?php echo $package->id; ?>">
                                                <?php echo $package->package_name; ?> (<?php echo $package->package_code; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity to Transfer *</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_center_id">From Center</label>
                                    <input type="text" class="form-control" value="Central Warehouse" readonly>
                                    <input type="hidden" name="from_center_id" value="1">
                                    <small class="form-text text-muted">Packages are always transferred from Central Warehouse</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_center_id">To Center *</label>
                                    <select name="to_center_id" id="to_center_id" class="form-control" required>
                                        <option value="">Select Selling Center</option>
                                        <?php foreach ($centers as $center): ?>
                                            <?php if ($center->ID != 1): // Exclude Central Warehouse from "To" options ?>
                                            <option value="<?php echo $center->ID; ?>">
                                                <?php echo $center->center_name; ?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Select the center where packages will be sold</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="to_department">To Department</label>
                                    <select name="to_department" id="to_department" class="form-control">
                                        <option value="">Select Department (Optional)</option>
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
                                    <small class="form-text text-muted">Optional: Specify department within the selling center</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Transfer Package</button>
                            <a href="<?php echo base_url(); ?>stocks_new/packages" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // From center is always Central Warehouse (ID: 1), so validate to center is different
    $('#to_center_id').change(function() {
        var toCenter = $(this).val();

        if (toCenter && toCenter === '1') { // Central Warehouse ID
            alert('Cannot transfer to Central Warehouse. Select a different selling center.');
            $(this).val('');
        }
    });
});
</script>
