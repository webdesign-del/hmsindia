<form action="<?php echo base_url('procedures/save_procedure_price'); ?>" method="post">
    <div class="row">
        <div class="col-md-4">
            <label>Select Master Procedure</label>
            <select name="procedure_id" id="proc_id" class="form-control" required onchange="fillMetadata()">
                <option value="">-- Choose Procedure --</option>
                <?php foreach($master_procedures as $m): ?>
                    <option value="<?= $m->ID; ?>" data-name="<?= $m->procedure_name; ?>" data-code="<?= $m->code; ?>">
                        <?= $m->procedure_name; ?> (<?= $m->code; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" name="procedure_name" id="hidden_name">
        <input type="hidden" name="code" id="hidden_code">

        <div class="col-md-3">
            <label>Min Price</label>
            <input type="number" name="min_price" class="form-control" required step="0.01">
        </div>
        <div class="col-md-3">
            <label>Actual Price</label>
            <input type="number" name="actual_price" class="form-control" required step="0.01">
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success btn-block">Add Entry</button>
        </div>
    </div>
</form>

<script>
function fillMetadata() {
    var select = document.getElementById('proc_id');
    var selected = select.options[select.selectedIndex];
    
    // Pull the data- attributes we set in the foreach loop
    document.getElementById('hidden_name').value = selected.getAttribute('data-name');
    document.getElementById('hidden_code').value = selected.getAttribute('data-code');
}
</script>