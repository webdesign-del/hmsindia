<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper mt-5">
    <section class="content-header">
        <h1>
            Medicine Brands Management
            <small>Manage medicine brands and manufacturers</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>stocks_new/dashboard">Stock Management</a></li>
            <li class="active">Brands</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header clearfix">
                        <h3 class="box-title pull-left">Medicine Brands List</h3>
                        <a href="<?php echo base_url(); ?>stocks_new/add_brand" class="btn btn-primary btn-sm pull-right">
                            <i class="fa fa-plus"></i> Add New Brand
                        </a>
                    </div>
                    <div class="box-body">
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table id="brandsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Brand Number</th>
                                        <th>Brand Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($brands)): ?>
                                        <?php foreach($brands as $brand): ?>
                                            <tr>
                                                <td><?php echo isset($brand->ID) ? $brand->ID : (isset($brand->id) ? $brand->id : 'N/A'); ?></td>
                                                <td><?php echo isset($brand->brand_number) ? $brand->brand_number : 'N/A'; ?></td>
                                                <td><?php echo isset($brand->name) ? $brand->name : 'N/A'; ?></td>
                                                <td>
                                                    <?php 
                                                    $status = isset($brand->status) ? $brand->status : 'inactive';
                                                    if($status == 'active' || $status == '1'): ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="label label-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url(); ?>stocks_new/edit_brand/<?php echo isset($brand->ID) ? $brand->ID : $brand->id; ?>" 
                                                           class="btn btn-info btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <?php 
                                                        $status = isset($brand->status) ? $brand->status : 'inactive';
                                                        if($status == 'active' || $status == '1'): ?>
                                                            <a href="<?php echo base_url(); ?>stocks_new/delete_brand/<?php echo isset($brand->ID) ? $brand->ID : $brand->id; ?>" 
                                                               class="btn btn-danger btn-sm" title="Deactivate"
                                                               onclick="return confirm('Are you sure you want to deactivate this brand?')">
                                                                <i class="fa fa-ban"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No brands found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#brandsTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 25,
        "order": [[2, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ]
    });
});
</script>
