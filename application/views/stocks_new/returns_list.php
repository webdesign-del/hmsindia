<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
/* Fix for AdminLTE/Bootstrap checkbox visibility */
input[type="checkbox"].return_check, #check_all_returns {
    position: static !important;
    opacity: 1 !important;
    cursor: pointer;
}
.well-sm { margin-top: 10px; }
.pagination { margin: 0; }
</style>    

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-list"></i> Medicine Returns List
            <small>View all medicine returns</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bolt"></i> Quick Actions & Filters
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo base_url('stocks_new/medicine_returns'); ?>" class="btn btn-primary">
                            <i class="fa fa-undo"></i> New Return
                        </a>
                        <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-info">
                            <i class="fa fa-shopping-cart"></i> Sales
                        </a>
                       <?php 
                        $user_role = $this->session->userdata('logged_accountant')['role'] ?? ''; 
                        
                        if(empty($user_role)) {
                            $user_role = $_SESSION['logged_user']['role'] ?? ''; 
                        }

                        if (strtolower($user_role) == 'accountant'): 
                        ?>
                            <button type="button" id="btn_bulk_tally" class="btn btn-warning">
                                <i class="fa fa-exchange"></i> Push Selected to Tally
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <?php if(!empty($returns)): ?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-download"></i> Export <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="<?php echo base_url('stocks_new/export_returns_list?format=excel'); ?>" target="_blank"><i class="fa fa-file-excel-o"></i> Export CSV</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/export_returns_list?format=pdf'); ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Print PDF</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>

                <form method="GET" action="<?= base_url('stocks_new/returns') ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control" placeholder="Return/Receipt/Patient" value="<?= $this->input->get('search') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="center_id" class="form-control">
                                <option value="">All Centers</option>
                                <?php foreach($centers as $c): ?>
                                    <option value="<?= $c->id ?>" <?= ($this->input->get('center_id') == $c->id)?'selected':'' ?>><?= $c->center_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="PENDING" <?= ($this->input->get('status') == 'PENDING')?'selected':'' ?>>Pending</option>
                                <option value="APPROVED" <?= ($this->input->get('status') == 'APPROVED')?'selected':'' ?>>Approved</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" value="<?= $this->input->get('start_date') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" value="<?= $this->input->get('end_date') ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Returns History (Total: <?= $total_records ?? 0 ?>)
            </div>
            <div class="panel-body">
                <?php if(!empty($returns)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="returnsTable">
                            <thead>
                                <tr>
                                    <th width="30 text-center"><input type="checkbox" id="check_all_returns"></th>
                                    <th>Return #</th>   
                                    <th>Patient</th>
                                    <th>Receipt #</th>
                                    <th>Center</th>
                                    <th>Return Date</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($returns as $return): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if(strtoupper($return->status) === 'APPROVED' && ($return->tally_status ?? 0) != 1): ?>
                                                <input type="checkbox" class="return_check" value="<?php echo $return->id; ?>">
                                            <?php elseif(($return->tally_status ?? 0) == 1): ?>
                                                <i class="fa fa-check-circle text-success" title="Sent to Tally"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo $return->return_number; ?></strong></td>
                                        <td><?php echo $return->patient_name; ?><br><small class="text-muted"><?php echo $return->patient_id; ?></small></td>
                                        <td><?php echo $return->receipt_number; ?></td>
                                        <td><?php echo $return->center_name ?? 'N/A'; ?></td>
                                        <td><?php echo date('d-M-Y', strtotime($return->return_date)); ?></td>
                                        <td><span class="label label-info"><?php echo $return->return_reason; ?></span></td>
                                        <td>₹<?php echo number_format($return->final_return_amount ?? 0, 2); ?></td>
                                        <td>
                                            <?php
                                            $status = strtoupper($return->status ?? 'PENDING');
                                            $lbl = ($status == 'APPROVED') ? 'label-success' : (($status == 'PENDING') ? 'label-warning' : 'label-danger');
                                            ?>
                                            <span class="label <?php echo $lbl; ?>"><?php echo $status; ?></span>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('stocks_new/view_return/' . $return->id); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                                            
                                           <?php if (strtolower($user_role) == 'accountant' && strtoupper($return->status) == 'PENDING'): ?>
                                                <a href="<?php echo base_url('stocks_new/approve_return/' . $return->id); ?>" class="btn btn-success btn-xs" onclick="return confirm('Approve this return?')"><i class="fa fa-check"></i></a>
                                                <a href="<?php echo base_url('stocks_new/disapprove_return/' . $return->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Disapprove this return?')"><i class="fa fa-times"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center p-20">No records found.</div>
                <?php endif; ?>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">Showing <?= count($returns) ?> of <?= $total_records ?? 0 ?></div>
                <div class="pull-right">
                    <?= $pagination ?? '' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 1. Select All Checkbox
    $('#check_all_returns').on('click', function() {
        $('.return_check').prop('checked', this.checked);
    });

    // 2. Bulk Tally Push
    $('#btn_bulk_tally').on('click', function() {
        var selectedIds = [];
        $('.return_check:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('Please select at least one approved record.');
            return;
        }

        if(!confirm('Push ' + selectedIds.length + ' records to Tally?')) return;

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: '<?php echo base_url("stocks_new/bulk_returns_tally"); ?>',
            type: 'POST',
            data: {
                return_ids: selectedIds,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Error: ' + res.message);
                    btn.prop('disabled', false).html('<i class="fa fa-exchange"></i> Push Selected to Tally');
                }
            },
            error: function() {
                alert('Connection Error');
                btn.prop('disabled', false).html('<i class="fa fa-exchange"></i> Push Selected to Tally');
            }
        });
    });
});
</script>