<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<form action="<?php echo base_url('procedures/save_procedure_price'); ?>" method="post">
    <div class="row">
        <div class="col-md-4">
            <label>Select Master Procedure (Searchable)</label>
            <select name="procedure_id" id="proc_id" class="form-control select2" required onchange="fillMetadata()">
                <option value="">-- Type to Search --</option>
                <?php foreach($master_procedures as $m): ?>
                    <option value="<?= $m->ID; ?>" 
                            data-name="<?= $m->procedure_name; ?>" 
                            data-code="<?= $m->code; ?>"
                            data-price="<?= $m->price; ?>"> <?= $m->procedure_name; ?> (<?= $m->code; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" name="procedure_name" id="hidden_name">
        <input type="hidden" name="code" id="hidden_code">

        <div class="col-md-3">
            <label>Min Price</label>
            <input type="number" name="min_price" id="min_price" class="form-control" required step="0.01">
        </div>
        <div class="col-md-3">
            <label>Actual Price</label>
            <input type="number" name="actual_price" id="actual_price" class="form-control" required step="0.01">
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success btn-block">Add Entry</button>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Searchable Dropdown
    $('.select2').select2({
        placeholder: "Type procedure name...",
        allowClear: true,
        width: '100%'
    });

    // Trigger metadata fill when Select2 changes
    $('#proc_id').on('select2:select', function (e) {
        fillMetadata();
    });
});

function fillMetadata() {
    var select = document.getElementById('proc_id');
    var selected = select.options[select.selectedIndex];
    
    // 1. Fill Hidden Fields
    document.getElementById('hidden_name').value = selected.getAttribute('data-name');
    document.getElementById('hidden_code').value = selected.getAttribute('data-code');
    
    // 2. Auto-load the Actual Price from the Master Table
    var masterPrice = selected.getAttribute('data-price');
    document.getElementById('actual_price').value = masterPrice;
    
    // Optional: Set Min Price to the same value as a starting point
    document.getElementById('min_price').value = masterPrice;
}
</script>