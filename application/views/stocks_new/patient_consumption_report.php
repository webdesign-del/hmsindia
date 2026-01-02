<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Select2 CSS/JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<!-- jQuery is required by Select2, make sure it's loaded before this -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-user-circle"></i> Patient Consumption Report
            <small>View all items consumed by a specific patient</small>
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
                <?php if (!empty($selected_patient)): ?>
                <!-- Filter Tabs -->
                <ul class="nav nav-tabs" id="consumptionTabs" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">All Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="medicines-tab" data-toggle="tab" href="#medicines" role="tab">Medicines</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="packages-tab" data-toggle="tab" href="#packages" role="tab">Packages</a>
                    </li>
                </ul>
                <div class="tab-content" id="consumptionTabContent">
                    <!-- All Items Tab -->
                    <div class="tab-pane fade in active" id="all" role="tabpanel">
                <form action="<?php echo base_url('stocks_new/patient_consumption_report'); ?>" method="get" class="form-inline">
                    <div class="form-group" style="min-width: 400px;">
                        <label>Select Patient:</label>
                        <!-- This is the Select2 search box -->
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
                    <a href="<?php echo base_url('stocks_new/patient_consumption_report'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                       <!-- *** NEW EXPORT BUTTON *** -->
                    <?php
                        // Build query string for the export button
                        $export_query = http_build_query(['patient_id' => $selected_patient->patient_id ?? '']);
                    ?>
                    <a href="<?php echo base_url('stocks_new/patient_consumption_export?' . $export_query); ?>" class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i> Export to CSV
                    </a>
                    <!-- *** END NEW BUTTON *** -->
                </form>

                <!-- Filter Tabs -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#all-items" data-toggle="tab">All Items</a></li>
                            <li><a href="#medicines-only" data-toggle="tab">Medicines Only</a></li>
                            <li><a href="#packages-only" data-toggle="tab">Packages Only</a></li>
                        </ul>
                        <div class="tab-content" style="margin-top: 20px;">
                            <!-- All Items Tab -->
                            <div class="tab-pane active" id="all-items">
                                Shows all medicines and packages consumed by the patient.
                            </div>
                            <!-- Medicines Only Tab -->
                            <div class="tab-pane" id="medicines-only">
                                Shows only individual medicine consumption.
                            </div>
                            <!-- Packages Only Tab -->
                            <div class="tab-pane" id="packages-only">
                                Shows only package consumption.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Consumption Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Consumption History
                <?php if (!empty($selected_patient)): ?>
                    for <strong><?php echo htmlspecialchars($selected_patient->patient_name); ?></strong>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="consumptionTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sale # / Ref</th>
                                <th>Type</th>
                                <th>Item</th>
                                <th>Batch #</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Consumed At</th>
                                <th>Billed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($consumption_data)): ?>
                                <?php foreach($consumption_data as $item): ?>
                                    <tr class="consumption-row" data-type="<?php echo $item->item_type; ?>">
                                        <td><?php echo date('d-m-Y H:i', strtotime($item->received_date)); ?></td>
                                        <td><strong><?php echo htmlspecialchars($item->sale_number); ?></strong></td>
                                        <td>
                                            <?php if($item->item_type == 'package'): ?>
                                                <span class="badge badge-info">Package</span>
                                            <?php else: ?>
                                                <span class="badge badge-primary">Medicine</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($item->item_name); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($item->item_code); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($item->batch_number); ?></td>
                                        <td><strong class="text-danger"><?php echo abs($item->quantity_change); ?></strong></td>
                                        <td>₹<?php echo number_format($item->unit_price, 2); ?></td>
                                        <td>₹<?php echo number_format($item->total_value, 2); ?></td>
                                        <td><?php echo htmlspecialchars($item->center_name); ?></td>
                                        <td><?php echo htmlspecialchars($item->user_name); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataRow">
                                    <td colspan="10" class="text-center text-muted">
                                        <?php if (!empty($selected_patient)): ?>
                                            <i class="fa fa-info-circle"></i> No consumption data found for this patient.
                                        <?php else: ?>
                                            <i class="fa fa-search"></i> Please search for a patient to see their consumption history.
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

    // Initialize DataTable *only* if there is data
    <?php if(!empty($consumption_data)): ?>
    $('#consumptionTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "desc" ]], // Sort by date descending
        "responsive": true,
        "searching": false, // Use the main search box, not the table one
        "info": false,
        "paging": false
    });
    <?php endif; ?>

    // Tab filtering functionality
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('href');
        var filterType = '';

        if (target === '#medicines-only') {
            filterType = 'medicine';
        } else if (target === '#packages-only') {
            filterType = 'package';
        }

        // Show/hide rows based on filter
        $('.consumption-row').each(function() {
            if (filterType === '') {
                $(this).show();
            } else {
                if ($(this).data('type') === filterType) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });

        // Handle no data row
        var visibleRows = $('.consumption-row:visible').length;
        if (visibleRows === 0 && $('#noDataRow').length === 0) {
            $('#consumptionTableBody').append('<tr id="noDataRow"><td colspan="10" class="text-center text-muted"><i class="fa fa-info-circle"></i> No items found for this category.</td></tr>');
        } else if (visibleRows > 0) {
            $('#noDataRow').remove();
        }
    });
});
</script>