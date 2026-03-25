<?php $all_method =&get_instance(); ?>
<div class="card">
    <div class="row card-content">
    <div class="box-header with-border">
        <h3 class="box-title">Wallet Usage History</h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?= base_url('accounts/all_patient_wallet_summary') ?>" class="row">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search Name or ID" value="<?= $this->input->get('search') ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control" value="<?= $this->input->get('start_date') ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control" value="<?= $this->input->get('end_date') ?>">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="Procedure" <?= ($this->input->get('type') == 'Procedure') ? 'selected' : '' ?>>Procedure</option>
                    <option value="Consultation" <?= ($this->input->get('type') == 'Consultation') ? 'selected' : '' ?>>Consultation</option>
                    <option value="Investigation" <?= ($this->input->get('type') == 'Investigation') ? 'selected' : '' ?>>Investigation</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Filter</button>
                <a href="<?= base_url('accounts/all_patient_wallet_summary/true?').$_SERVER['QUERY_STRING'] ?>" class="btn btn-success"><i class="fa fa-download"></i> Export</a>
            </div>
        </form>

        <hr>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient Name (ID)</th>
                    <th>Receipt #</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Center</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($wallet_list as $row): ?>
                <tr>
                    <td><?= $row['on_date'] ?></td>
                    <td><?= $row['patient_name'] ?> (<?= $row['patient_id'] ?>)</td>
                    <td><?= $row['receipt_number'] ?></td>
                    <td><span class="label label-info"><?= $row['type'] ?></span></td>
                    <td><b><?= number_format($row['payment_done'], 2) ?></b></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['center_name'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center">
            <?= $pagination ?>
        </div>
    </div>
</div>
</div>