<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-pencil"></i> Edit Vendor Return
            <small>Update details for <?php echo htmlspecialchars($return_details->return_number); ?></small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li><a href="<?php echo base_url('stocks_new/vendor_returns'); ?>">Vendor Returns</a></li>
            <li class="active">Edit Return</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-undo"></i> Vendor Return Information (Status: PENDING)
            </div>
            <div class="panel-body">
                <?php if(validation_errors()): ?>
                    <div class="alert alert-danger"> <?php echo validation_errors(); ?> </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"> <?php echo $this->session->flashdata('error'); ?> </div>
                <?php endif; ?>

                <form action="<?php echo base_url('stocks_new/process_edit_vendor_return/' . $return_details->id); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="action" value="update_vendor_return">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Vendor</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($return_details->vendor_name ?? 'N/A'); ?>" disabled>
                                    <input type="hidden" name="vendor_id" value="<?php echo $return_details->vendor_id; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Return Date *</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="return_date" value="<?php echo set_value('return_date', $return_details->return_date); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Center (From)</label>
                                <div class="col-sm-8">
                                     <input type="text" class="form-control" value="<?php echo htmlspecialchars($return_details->center_name ?? 'N/A'); ?>" disabled>
                                     <input type="hidden" name="center_id" value="<?php echo $return_details->center_id; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="status">
                                        <option value="PENDING" <?php echo ($return_details->status == 'PENDING') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="APPROVED" <?php echo ($return_details->status == 'APPROVED') ? 'selected' : ''; ?>>Approve</option>
                                        <option value="COMPLETED" <?php echo ($return_details->status == 'COMPLETED') ? 'selected' : ''; ?>>Completed</option>
                                        <option value="REJECTED" <?php echo ($return_details->status == 'REJECTED') ? 'selected' : ''; ?>>Reject</option>
                                    </select>
                                    <small class="text-muted">Note: Changing from PENDING will lock this record.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Return Reason</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="return_reason" value="<?php echo set_value('return_reason', $return_details->return_reason); ?>" placeholder="e.g., Expired, Damaged, Recall">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Returned Items (Reference Only - Cannot be changed)</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Qty Returned</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($return_items)): ?>
                                            <?php foreach($return_items as $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                                                    <td><?php echo isset($item->expiry_date) ? date('M d, Y', strtotime($item->expiry_date)) : 'N/A'; ?></td>
                                                    <td><?php echo number_format(abs($item->quantity_change)); ?></td>
                                                    <td>₹<?php echo number_format($item->unit_price ?? 0, 2); ?></td>
                                                    <td>₹<?php echo number_format(abs($item->total_value ?? 0), 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No item details found in stock log.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="info">
                                            <th colspan="5" style="text-align:right;">Total Items:</th>
                                            <th><?php echo number_format($return_details->total_items ?? 0); ?></th>
                                        </tr>
                                        <tr class="info">
                                            <th colspan="5" style="text-align:right;">Total Quantity:</th>
                                            <th><?php echo number_format($return_details->total_quantity ?? 0); ?></th>
                                        </tr>
                                        <tr class="info">
                                            <th colspan="5" style="text-align:right;">Total Value:</th>
                                            <th>₹<?php echo number_format($return_details->total_value ?? 0, 2); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>

                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                                <label class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="remarks" rows="3" placeholder="Additional remarks or notes"><?php echo set_value('remarks', $return_details->remarks); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                    <a href="<?php echo base_url('stocks_new/view_vendor_return/' . $return_details->id); ?>" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> </div> </div> </div> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for status dropdown
    $('select[name="status"]').select2();
});
</script>