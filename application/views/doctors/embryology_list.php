<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">

        <!-- FILTER PANEL -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-filter"></i> Filters
            </div>
            <div class="panel-body">

                <form method="get" class="form-inline">

                    <div class="form-group">
                        <label>IIC ID</label>
                        <input type="text" name="iic_id" class="form-control"
                               value="<?= $this->input->get('iic_id') ?>">
                    </div>

                    <div class="form-group">
                        <label>Center</label>
                        <input type="text" name="center" class="form-control"
                               value="<?= $this->input->get('center') ?>">
                    </div>

                    <div class="form-group">
                        <label>Doctor ID</label>
                        <input type="text" name="doctor_id" class="form-control"
                               value="<?= $this->input->get('doctor_id') ?>">
                    </div>

                    <div class="form-group">
                        <label>From</label>
                        <input type="date" name="from_date" class="form-control"
                               value="<?= $this->input->get('from_date') ?>">
                    </div>

                    <div class="form-group">
                        <label>To</label>
                        <input type="date" name="to_date" class="form-control"
                               value="<?= $this->input->get('to_date') ?>">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-search"></i> Filter
                    </button>

                    <a href="<?= base_url('doctors/embryology_list') ?>" 
                       class="btn btn-default">
                        Reset
                    </a>

                </form>

            </div>
        </div>


        <!-- LIST PANEL -->
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Embryology Discharge List
            </div>
            <div class="panel-body table-responsive">

                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="info">
                            <th>ID</th>
                            <th>IIC ID</th>
                            <th>Embryo Transfer</th>
                            <th>FET</th>
                            <th>Blastocyst</th>
                            <th>Laser Assisted</th>
                            <th>Embryo Glue</th>
                            <th>Admission Date</th>
                            <th>Procedure Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td></td>
                                    <td><?= $row->iic_id ?></td>
                                    <td><?= $row->Embryo_Transfer ?></td>
                                    <td><?= $row->FET ?></td>
                                    <td><?= $row->Blastocyst ?></td>
                                    <td><?= $row->Laser_Assisted ?></td>
                                    <td><?= $row->Embryo_Glue ?></td>
                                    <td><?= date('d-m-Y', strtotime($row->date_of_addmission)) ?></td>
                                    <td>
                                        <?= !empty($row->date_of_procedure) && $row->date_of_procedure != '0000-00-00'
                                            ? date('d-m-Y', strtotime($row->date_of_procedure))
                                            : 'N/A'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-danger">
                                    No records found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>

            <div class="panel-footer text-center">
                <?= $pagination ?>
            </div>
        </div>

    </div>
</div>

<style>
.panel-footer a {
    border: 1px solid saddlebrown;
    padding: 5px 10px;
}
strong{
    border-width: 1px;
    border-style: solid;
    border-color: saddlebrown;
    border-image: initial;
    padding: 5px 10px;
}
</style>