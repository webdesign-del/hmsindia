<style>
    /* Fixed Header Styling */
    .freeze-table { 
        max-height: 700px; 
        overflow-y: auto; 
        border: 1px solid #ddd;
    }
    .freeze-table table { margin-bottom: 0; }
    .freeze-table thead th { 
        position: sticky; 
        top: 0; 
        background-color: #3c8dbc !important; 
        color: white; 
        z-index: 100;
        box-shadow: inset 0 -1px 0 #ddd;
    }
    /* Label styling */
    .label-details {
        background: #f9f9f9;
        padding: 5px;
        border-radius: 4px;
        border-left: 3px solid #d9534f;
    }
    .well-sm { background: #ffffff; border: 1px solid #e3e3e3; }
</style>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Procedure List (<?= number_format($total_records) ?>)</h3>
    </div>
    
    <div class="box-body">
        <form method="GET" action="<?= base_url('accounts/procedure_list') ?>" class="well well-sm">
            <div class="row">
                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="ID / Receipt / Name / Procedure" value="<?= $this->input->get('search') ?>">
                </div>
                <div class="col-md-2">
                    <label>Center</label>
                    <select name="center_id" class="form-control">
                        <option value="">All Centers</option>
                        <?php foreach($centers as $c): ?>
                            <option value="<?= $c['center_number'] ?>" <?= ($this->input->get('center_id') == $c['center_number'])?'selected':'' ?>>
                                <?= $c['center_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>From Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $this->input->get('start_date') ?>">
                </div>
                <div class="col-md-2">
                    <label>To Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $this->input->get('end_date') ?>">
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    <a href="<?= base_url('accounts/export_procedure_list?').$_SERVER['QUERY_STRING'] ?>" class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i> Export
                    </a>
                    <a href="<?= base_url('accounts/procedure_list') ?>" class="btn btn-default">Reset</a>
                </div>
            </div>
        </form>

        <div class="freeze-table">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Code</th>
                        <th>Procedure Name</th>
                        <th width="20%">Clinical Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($procedures)): ?>
                        <?php foreach($procedures as $row): ?>
                        <tr>
                            <td><?= date('d-M-Y', strtotime($row->on_date)) ?></td>
                            <td><a href="<?= base_url('patient_details') ?>/<?= $row->patient_id ?>"><?= $row->patient_id ?></a></td>
                            <td><?= !empty($row->center_name) ? $row->center_name : 'N/A'; ?></td>
                            <td><?= strtoupper($row->wife_name) ?></td>
                            <td><code style="color: #333;"><?= $row->code ?></code></td>
                            <td><?= $row->procedure_name ?></td>
                            <td>
                                <?php if (strpos($row->procedure_name, "Semen Analysis") !== false && !empty($row->sa_iic)): ?>
                                    <div class="label-details" style="border-left-color: #5bc0de;">
                                        <span class="text-info"><strong>Semen ID:</strong></span> <?= $row->sa_iic ?><br>
                                        <small class="text-muted">Date: <?= date('d-M-y', strtotime($row->sa_date)) ?></small>
                                    </div>

                                <?php elseif (strpos($row->procedure_name, "ICSI") !== false && !empty($row->ov_iic)): ?>
                                    <div class="label-details">
                                        <span class="text-danger"><strong>ICSI Cycle:</strong></span> <?= $row->ov_iic ?><br>
                                        <small class="text-muted">Proc. Date: <?= date('d-M-y', strtotime($row->ov_date)) ?></small>
                                    </div>
                                
                                <?php elseif ((strpos($row->procedure_name, "ovulation induction till trigger") !== false) && !empty($row->op_iic)): ?>
                                    <div class="label-details">
                                        <span class="text-danger"><strong>IVF:</strong></span> <?= $row->op_iic ?><br>
                                        <small class="text-muted">Proc. Date: <?= date('d-M-y', strtotime($row->op_date)) ?></small>
                                    </div>

                                <?php elseif ((strpos($row->procedure_name, "Procedure Charge") !== false) && !empty($row->et_iic)): ?>
                                    <div class="label-details">
                                        <span class="text-danger"><strong>IVF/ET:</strong></span> <?= $row->et_iic ?><br>
                                        <small class="text-muted">Proc. Date: <?= date('d-M-y', strtotime($row->et_date)) ?></small>
                                    </div>

                                <?php elseif ((strpos($row->code, "IP39") !== false || strpos($row->code, "IP149") !== false) && !empty($row->ed_iic)): ?>
                                    <div class="label-details">
                                        <span class="text-danger"><strong>Embryo Glue:</strong></span> <?= $row->ed_iic ?><br>
                                        <small class="text-muted">Proc. Date: <?= date('d-M-y', strtotime($row->ed_date)) ?></small>
                                    </div>

                                <?php elseif ((strpos($row->code, "IP147") !== false) && !empty($row->edl_iic)): ?>
                                    <div class="label-details">
                                        <span class="text-danger"><strong>Laser AH:</strong></span> <?= $row->edl_iic ?><br>
                                        <small class="text-muted">Proc. Date: <?= date('d-M-y', strtotime($row->edl_date)) ?></small>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $label_class = 'label-default';
                                    if($row->status == 'approved') $label_class = 'label-success';
                                    if($row->status == 'pending') $label_class = 'label-warning';
                                    if($row->status == 'cancel') $label_class = 'label-danger';
                                ?>
                                <span class="label <?= $label_class ?>"><?= strtoupper($row->status) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No procedures found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="box-footer clearfix">
        <div class="pull-left" style="margin-top: 5px;">
            Showing records...
        </div>
        <?= $pagination ?>
    </div>
</div>