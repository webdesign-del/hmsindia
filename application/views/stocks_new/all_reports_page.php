<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Include Select2 and Datepicker assets if not already in your header -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-download"></i> Export Reports
            <small>Download inventory data as CSV files</small>
        </h1>
    </div>
</div>

<div class="row">

    <!-- Sales Report Panel -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-shopping-cart"></i> Export Sales Report (Item-wise)
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/export_sales_report'); ?>" method="get" target="_blank" class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date From</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_from" class="form-control datepicker" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date To</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_to" class="form-control datepicker" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Center</label>
                        <div class="col-sm-8">
                            <select name="center_id" class="form-control select2" style="width: 100%;">
                                <option value="">All Centers</option>
                                <?php foreach($centers as $center): ?>
                                    <option value="<?php echo $center->ID; ?>"><?php echo htmlspecialchars($center->center_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Status</label>
                        <div class="col-sm-8">
                            <select name="status" class="form-control" style="width: 100%;">
                                <option value="CONFIRMED">Confirmed (Default)</option>
                                <option value="DRAFT">Draft</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Download Sales Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transfer Report Panel -->
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-truck"></i> Export Transfer Report (Item-wise)
            </div>
            <div class="panel-body">
                 <form action="<?php echo base_url('stocks_new/export_transfer_report'); ?>" method="get" target="_blank" class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date From</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_from" class="form-control datepicker" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date To</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_to" class="form-control datepicker" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">From Location</label>
                        <div class="col-sm-8">
                            <select name="from_center_id" class="form-control select2" style="width: 100%;">
                                <option value="">All Locations</option>
                                <option value="central">Central Warehouse</option>
                                <?php foreach($centers as $center): ?>
                                    <option value="<?php echo $center->ID; ?>"><?php echo htmlspecialchars($center->center_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-4 control-label">To Location</label>
                        <div class="col-sm-8">
                            <select name="to_center_id" class="form-control select2" style="width: 100%;">
                                <option value="">All Locations</option>
                                <?php foreach($centers as $center): ?>
                                    <option value="<?php echo $center->ID; ?>"><?php echo htmlspecialchars($center->center_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-info"><i class="fa fa-download"></i> Download Transfer Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <!-- Stock Movement Log Panel -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list-alt"></i> Export Stock Movement Log
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/export_stock_report'); ?>" method="get" target="_blank" class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date From</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_from" class="form-control datepicker" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date To</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_to" class="form-control datepicker" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Center</label>
                        <div class="col-sm-8">
                            <select name="center_id" class="form-control select2" style="width: 100%;">
                                <option value="">All Centers</option>
                                <?php foreach($centers as $center): ?>
                                    <option value="<?php echo $center->ID; ?>"><?php echo htmlspecialchars($center->center_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Medicine</label>
                        <div class="col-sm-8">
                            <select name="medicine_id" class="form-control select2" style="width: 100%;">
                                <option value="">All Medicines</option>
                                 <?php foreach($medicines as $medicine): ?>
                                    <option value="<?php echo $medicine->id; ?>"><?php echo htmlspecialchars($medicine->medicine_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-default"><i class="fa fa-download"></i> Download Movement Log</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add other report panels here (e.g., Current Stock, Expiry Report) -->
    <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-exclamation-triangle"></i> Other Reports
            </div>
            <div class="panel-body">
                <p>You can add forms here for:</p>
                <ul>
                    <li>Full Current Stock Report</li>
                    <li>Expiry Report</li>
                    <li>Disposal Report</li>
                    <li>Vendor Return Report</li>
                </ul>
                <p>Each will have its own filters and point to its own export function.</p>
            </div>
        </div>
    </div>

</div>


<script type="text/javascript">
$(document).ready(function() {
    // Initialize all Select2 dropdowns
    $('.select2').select2({
        allowClear: true,
        placeholder: "Select an option"
    });

    // Initialize all Datepickers
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd', // MySQL date format
        autoclose: true,
        todayHighlight: true
    });
});
</script>
