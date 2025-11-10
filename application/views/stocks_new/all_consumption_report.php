<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Select2 CSS/JS -->
<link href="https.cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<!-- jQuery is required by Select2, make sure it's loaded before this -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-bar-chart"></i> All Consumption Report
            <small>Summary of all items consumed by patients</small>
        </h1>
    </div>
</div>

<!-- Search Panel -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Search & Filter
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/all_consumption_report'); ?>" method="get" class="form-inline">
                    
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" class="form-control" name="start_date" 
                               value="<?php echo htmlspecialchars($filters['start_date'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" class="form-control" name="end_date" 
                               value="<?php echo htmlspecialchars($filters['end_date'] ?? ''); ?>">
                    </div>

                    <div class="form-group" style="min-width: 300px;">
                        <label>IIC ID (Patient):</label>
                        <select name="patient_id" id="patient_select" class="form-control" style="width: 100%;">
                            <?php if (!empty($selected_patient)): ?>
                                <option value="<?php echo htmlspecialchars($selected_patient->patient_id); ?>" selected>
                                    <?php echo htmlspecialchars($selected_patient->patient_name . ' (' . $selected_patient->patient_id . ')'); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="<?php echo base_url('stocks_new/all_consumption_report'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Consumption Summary Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Consumption Report Data
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="consumptionReportTable">
                        <thead>
                            <tr>
                                <th>IIC ID</th>
                                <th>Patient Name</th>
                                <th>Date</th>
                                <th>OT DCI</th>
                                <th>Package Injections</th>
                                <th>Embryologist DCI</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($report_data)): ?>
                                <?php foreach($report_data as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                        <td><?php echo htmlspecialchars($row->patient_name); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row->consumption_date)); ?></td>
                                        <td><?php echo abs($row->ot_total); ?></td>
                                        <td><?php echo abs($row->injections_total); ?></td>
                                        <td><?php echo abs($row->embryologist_total); ?></td>
                                        <td><strong><?php echo abs($row->grand_total); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fa fa-info-circle"></i> No consumption data found for these filters.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    
    // Initialize the Patient Select2 Search Box
    $('#patient_select').select2({
        placeholder: 'Type Patient ID or Name (Optional)',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: "<?php echo base_url('stocks_new/search_patients_json'); ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term // search term
                };
            },
            processResults: function (data) {
                // Map the results to the format Select2 expects
                var formattedData = $.map(data, function (obj) {
                    obj.id = obj.patient_id; // Use patient_id as the ID
                    obj.text = obj.patient_name + ' (' + obj.patient_id + ')';
                    return obj;
                });
                return {
                    results: formattedData
                };
            },
            cache: true
        }
    });

    // Initialize DataTable
    <?php if(!empty($report_data)): ?>
    $('#consumptionReportTable').DataTable({
        "pageLength": 25,
        "order": [[ 2, "desc" ]], // Sort by date descending
        "responsive": true,
        "searching": false, 
        "info": true, 
        "paging": true
    });
    <?php endif; ?>
});
</script>