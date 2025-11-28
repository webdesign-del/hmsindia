<div class="col-md-12">
    <div class="card" style="margin-bottom:20px;">
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="col-sm-12 col-xs-12" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #155724;"><?php echo $this->session->flashdata('success'); ?></h4>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="col-sm-12 col-xs-12" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #721c24;"><?php echo $this->session->flashdata('error'); ?></h4>
            </div>
        <?php endif; ?>
        
        <div class="col-md-12"><h3>Goods Receipt Notes (GRN)</h3></div>
        
        <div class="col-md-12 mt-3">
            <a href="<?php echo base_url('accounts/purchase-orders-list'); ?>" class="btn btn-info pull-right mr-2">Back to Purchase Orders</a>
        </div>
        <hr>
        <div class="clearfix"></div>
        
        <!-- Filters -->
        <form action="<?php echo base_url('accounts/grn-list'); ?>" method="get" style="margin:10px">
            <div class="row">
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>GRN Number</label>
                    <input type="text" name="grn_number" class="form-control" value="<?php echo $filters['grn_number'] ?? ''; ?>" placeholder="Enter GRN Number">
                </div>
                
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>PO Number</label>
                    <input type="text" name="po_number" class="form-control" value="<?php echo $filters['po_number'] ?? ''; ?>" placeholder="Enter PO Number">
                </div>
                
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control" value="<?php echo $filters['vendor_name'] ?? ''; ?>" placeholder="Enter Vendor Name">
                </div>
                
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="">-- All Status --</option>
                        <option value="1" <?php echo (isset($filters['status']) && $filters['status']=='1') ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo (isset($filters['status']) && $filters['status']=='0') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="<?php echo $filters['start_date'] ?? ''; ?>">
                </div>
                
                <div class="col-sm-3 col-xs-12 mt-3">
                    <label>End Date</label>
                    <input type="date" class="form-control" name="end_date" value="<?php echo $filters['end_date'] ?? ''; ?>">
                </div>
                
                <div class="col-sm-12 mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    <a href="<?php echo base_url('accounts/grn-list'); ?>" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </div>
        </form>
        
        <div class="clearfix"></div>
        <div class="card-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>GRN Number</th>
                            <th>PO Number</th>
                            <th>GRN Date</th>
                            <th>Vendor</th>
                            <th>Centre</th>
                            <th>Department</th>
                            <th>Received By</th>
                            <th>Total Items</th>
                            <th>Total Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        if(!empty($grn_result)) {
                            foreach($grn_result as $grn) { 
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><strong><?php echo $grn['grn_number']; ?></strong></td>
                            <td><?php echo $grn['po_number']; ?></td>
                            <td><?php echo date('d M Y', strtotime($grn['grn_date'])); ?></td>
                            <td><?php echo $grn['vendor_name']; ?></td>
                            <td><?php 
                                $all_method = &get_instance();
                                echo $all_method->get_center_name_by_number($grn['centre']); 
                            ?></td>
                            <td><?php echo $grn['department']; ?></td>
                            <td><?php echo $grn['received_by']; ?></td>
                            <td><?php echo $grn['total_items']; ?></td>
                            <td><?php echo $grn['total_quantity']; ?></td>
                            <td>
                                <?php if ($grn['status'] == '1'): ?>
                                    <span class="label label-success">Active</span>
                                <?php else: ?>
                                    <span class="label label-default">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url('accounts/view_grn/'.$grn['id']); ?>" class="btn btn-info btn-sm" title="View GRN">
                                    <i class="glyphicon glyphicon-eye-open"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php 
                                $count++;
                            }
                        } else { 
                        ?>
                        <tr>
                            <td colspan="12" class="text-center">No GRN Found</td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="12">
                                <p class="custom-pagination"><?php echo $links; ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-pagination {
        padding: 8px;
    }
    .custom-pagination a {
        padding: 10px;
        text-decoration: none;
    }
    .form-control {
        height: 30px !important;
        border: 1px solid #9e9e9e !important;
    }
</style>

