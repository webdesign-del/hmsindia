<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Select2 CSS/JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<!-- jQuery is required by Select2, make sure it's loaded before this -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-pie-chart"></i> Patient Medicine Package
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
                <form action="<?php echo base_url('stocks_new/medicine_package'); ?>" method="get" class="form-inline">
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
                        <!-- <a href="<?php echo base_url('stocks_new/patient_consumption_report?' . $detail_query); ?>" class="btn btn-info">
                            <i class="fa fa-list"></i> View Detailed Report
                        </a> -->
                    <?php endif; ?>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Medicine Package Consumption Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-cubes"></i> Medicine Package Consumption Details
                <?php if (!empty($selected_patient)): ?>
                    for <strong><?php echo htmlspecialchars($selected_patient->patient_name); ?></strong>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="packageConsumptionTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Package Name</th>
                                <th>Package Code</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Value</th>
                                <!-- <th>Sale Number</th> -->
                                <!-- <th>Movement Type</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($consumption_medicine_package)): ?>
                                <?php foreach($consumption_medicine_package as $consumption): ?>
                                    <tr>
                                        <td><?php echo date('d-m-Y H:i', strtotime($consumption->consumption_datetime)); ?></td>
                                        <td><strong><?php echo htmlspecialchars($consumption->package_name); ?></strong></td>
                                        <td><?php echo htmlspecialchars($consumption->package_code); ?></td>
                                        <td class="text-center"><span class="badge"><?php echo abs($consumption->quantity_consumed); ?></span></td>
                                        <td class="text-right">₹<?php echo number_format($consumption->unit_price, 2); ?></td>
                                        <td class="text-right"><strong class="text-danger">₹<?php echo number_format($consumption->total_value, 2); ?></strong></td>
                                        <!-- <td><?php echo htmlspecialchars($consumption->sale_number ?? 'N/A'); ?></td> -->
                                        <!-- <td><span class="label label-info"><?php echo htmlspecialchars($consumption->movement_type); ?></span></td> -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <?php if (!empty($selected_patient)): ?>
                                            <i class="fa fa-info-circle"></i> No medicine package consumption data found for this patient in the selected date range.
                                        <?php else: ?>
                                            <i class="fa fa-search"></i> Please search for a patient to see their medicine package consumption details.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if(!empty($consumption_medicine_package)): ?>
                        <tfoot>
                            <tr class="info">
                                <td colspan="5" class="text-right"><strong>Total Value:</strong></td>
                                <td class="text-right"><strong>₹<?php
                                    $total_value = 0;
                                    foreach($consumption_medicine_package as $consumption) {
                                        $total_value += $consumption->total_value;
                                    }
                                    echo number_format($total_value, 2);
                                ?></strong></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
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
    <?php if(!empty($consumption_medicine_package)): ?>
    $('#packageConsumptionTable').DataTable({
        "paging": false,
        "searching": false,
        "info": false,
        "order": [[ 0, "desc" ]] // Sort by date descending
    });
    <?php endif; ?>
});
</script>