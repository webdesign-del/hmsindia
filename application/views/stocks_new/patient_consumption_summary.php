<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Select2 CSS/JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<!-- jQuery is required by Select2, make sure it's loaded before this -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-pie-chart"></i> Patient Consumption Summary
            <small>View total items consumed by a patient, grouped by date and category</small>
        </h1>
    </div>
</div>

<!-- Search Panel -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Search Patient
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/patient_consumption_summary'); ?>" method="get" class="form-inline">
                    <div class="form-group" style="min-width: 300px;">
                        <label>Select Patient:</label>
                        <select name="patient_id" id="patient_select" class="form-control" style="width: 100%;">
                            <?php if (!empty($selected_patient)): ?>
                                <option value="<?php echo htmlspecialchars($selected_patient->patient_id); ?>" selected>
                                    <?php echo htmlspecialchars($selected_patient->patient_name . ' (' . $selected_patient->patient_id . ')'); ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- *** NEW DATE FILTERS *** -->
                    <div class="form-group">
                        <label>From:</label>
                        <input type="date" class="form-control" name="start_date" 
                               value="<?php echo htmlspecialchars($filters['start_date'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>To:</label>
                        <input type="date" class="form-control" name="end_date" 
                               value="<?php echo htmlspecialchars($filters['end_date'] ?? ''); ?>">
                    </div>
                    <!-- *** END NEW FILTERS *** -->
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="<?php echo base_url('stocks_new/patient_consumption_summary'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset
                    </a>

                    <!-- Link to the detailed report -->
                    <?php if (!empty($selected_patient)): ?>
                        <?php
                            // Build query string for the detailed report link
                            $detail_query = http_build_query(['patient_id' => $selected_patient->patient_id ?? '']);
                        ?>
                        <a href="<?php echo base_url('stocks_new/patient_consumption_report?' . $detail_query); ?>" class="btn btn-info">
                            <i class="fa fa-list"></i> View Detailed Report
                        </a>
                    <?php endif; ?>
                    
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
                <i class="fa fa-pie-chart"></i> Consumption Summary
                <?php if (!empty($selected_patient)): ?>
                    for <strong><?php echo htmlspecialchars($selected_patient->patient_name); ?></strong>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="consumptionSummaryTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Total Quantity Consumed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($consumption_summary)): ?>
                                <?php foreach($consumption_summary as $summary): ?>
                                    <tr>
                                        <!-- *** NEW: DATE CELL *** -->
                                        <td><?php echo date('d-m-Y', strtotime($summary->consumption_date)); ?></td>
                                        <td><strong><?php echo htmlspecialchars($summary->category); ?></strong></td>
                                        <td><strong class="text-danger"><?php echo abs($summary->total_consumed); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <!-- *** NEW: Colspan is 3 *** -->
                                    <td colspan="3" class="text-center text-muted">
                                        <?php if (!empty($selected_patient)): ?>
                                            <i class="fa fa-info-circle"></i> No consumption data found for this patient in the selected date range.
                                        <?php else: ?>
                                            <i class="fa fa-search"></i> Please search for a patient to see their consumption summary.
                                        <?php endif; ?>
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
        placeholder: 'Type Patient ID or Name to search...',
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

    // Initialize DataTable (optional, for sorting)
    <?php if(!empty($consumption_summary)): ?>
    $('#consumptionSummaryTable').DataTable({
        "paging": false,
        "searching": false, 
        "info": false, 
        "order": [[ 0, "desc" ]] // Sort by date descending
    });
    <?php endif; ?>
});
</script>