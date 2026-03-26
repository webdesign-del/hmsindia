<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Procedure List (<?= $total_records ?>)</h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?= base_url('accounts/procedure_list') ?>" class="well well-sm">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="patient_id/Receipt/Patient/Procedure" value="<?= $this->input->get('search') ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="approved" <?= ($this->input->get('status') == 'approved')?'selected':'' ?>>Approved</option>
                        <option value="pending" <?= ($this->input->get('status') == 'pending')?'selected':'' ?>>Pending</option>
                        <option value="cancel" <?= ($this->input->get('status') == 'cancel')?'selected':'' ?>>Cancel</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="<?= $this->input->get('start_date') ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="<?= $this->input->get('end_date') ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    <a href="<?= base_url('accounts/procedure_list') ?>" class="btn btn-default">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient ID</th>
                    <th>Receipt #</th>
                    <th>Patient</th>
                    <th>Procedure</th>
                    <th>Code</th>
                    <th>Procedure Complete Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($procedures as $row): ?>
                <tr>
                    <td><?= date('d-M-Y', strtotime($row->on_date)) ?></td>
                    <td><strong><?= $row->patient_id ?></strong></td>
                    <td><strong><?= $row->receipt_number ?></strong></td>
                    <td><?= $row->wife_name ?></td>
                    <td><?= $row->procedure_name ?></td>
                    <td><?= $row->code ?></td>
                    <td>
                        <?php if (strpos($row->procedure_name, "Semen Analysis") !== false && !empty($row->sa_iic)): ?>
                            <div class="label-details" style="font-size: 11px; color: #555; margin-top: 4px;">
                                <span class="text-info"><i class="fa fa-microscope"></i> Semen ID:</span> <?= $row->sa_iic ?> | 
                                <span class="text-muted">Date:</span> <?= date('d-M-y', strtotime($row->sa_date)) ?>
                            </div>

                        <?php elseif (strpos($row->procedure_name, "ICSI") !== false && !empty($row->ov_iic)): ?>
                            <div class="label-details" style="font-size: 11px; color: #d9534f; margin-top: 4px;">
                                <span class="text-danger"><i class="fa fa-flask"></i> ICSI Cycle:</span> <?= $row->ov_iic ?> | 
                                <span class="text-muted">Proc. Date:</span> <?= date('d-M-y', strtotime($row->ov_date)) ?>
                            </div>
                        
                        <?php elseif ((strpos($row->procedure_name, "IVF (Single cycle ovulation induction till trigger)") !== false || 
                            strpos($row->procedure_name, "IVF (Single cycle ovulation induction till trigger)_First Cycle_Self-IVF") !== false) 
                            && !empty($row->op_iic)): 
                        ?>
                            <div class="label-details" style="font-size: 11px; color: #d9534f; margin-top: 4px;">
                                <span class="text-danger"><i class="fa fa-flask"></i> IVF:</span> <?= $row->op_iic ?> | 
                                <span class="text-muted">Proc. Date:</span> <?= date('d-M-y', strtotime($row->op_date)) ?>
                            </div>

                             <?php elseif ((strpos($row->procedure_name, "IVF Procedure Charge (without Ovulation Induction Injection)") !== false || 
                            strpos($row->procedure_name, "OFFER IVF Procedure Charge (without Ovulation Induction Injection)") !== false) 
                            && !empty($row->et_iic)): 
                        ?>
                            <div class="label-details" style="font-size: 11px; color: #d9534f; margin-top: 4px;">
                                <span class="text-danger"><i class="fa fa-flask"></i> IVF:</span> <?= $row->et_iic ?> | 
                                <span class="text-muted">Proc. Date:</span> <?= date('d-M-y', strtotime($row->et_date)) ?>
                            </div>

                        <?php endif; ?>
                    </td>
                    <td><span class="label label-success"><?= $row->status ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix">
        <?= $pagination ?>
    </div>
</div>