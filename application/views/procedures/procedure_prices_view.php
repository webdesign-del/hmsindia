<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Manage Procedure Prices</h3>
            </div>
            <div class="box-body">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                <?php endif; ?>

                <form action="<?php echo base_url('procedures/save_procedure_price'); ?>" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Select Procedure</label>
                            <select name="procedure_id" id="proc_selector" class="form-control" required onchange="updateProcName()">
                                <option value="">-- Select Procedure --</option>
                                <?php if(!empty($master_procedures)): foreach($master_procedures as $m): ?>
                                    <option value="<?php echo $m->id; ?>" data-name="<?php echo $m->name; ?>" data-code="<?php echo $m->code; ?>">
                                        <?php echo $m->name; ?> (ID: <?php echo $m->id; ?>)
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <input type="hidden" name="procedure_name" id="hidden_proc_name">
                        <input type="hidden" name="code" id="hidden_proc_code">

                        <div class="col-md-3">
                            <label>Min Price (₹)</label>
                            <input type="number" step="0.01" name="min_price" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label>Actual Price (₹)</label>
                            <input type="number" step="0.01" name="actual_price" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Save Price</button>
                        </div>
                    </div>
                </form>

                <hr>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr style="background-color: #f4f4f4;">
                            <th>Proc ID</th>
                            <th>Procedure Name</th>
                            <th>Code</th>
                            <th>Min Price</th>
                            <th>Actual Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($existing_prices)): foreach($existing_prices as $p): ?>
                        <tr>
                            <td><?php echo $p->procedure_id; ?></td>
                            <td><?php echo $p->procedure_name; ?></td>
                            <td><?php echo $p->code; ?></td>
                            <td>₹<?php echo number_format($p->min_price, 2); ?></td>
                            <td>₹<?php echo number_format($p->actual_price, 2); ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center">No price records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
function updateProcName() {
    var select = document.getElementById('proc_selector');
    var selectedOption = select.options[select.selectedIndex];
    
    // Get data from the selected <option>
    var name = selectedOption.getAttribute('data-name');
    var code = selectedOption.getAttribute('data-code');
    
    // Set the values into the hidden input fields
    document.getElementById('hidden_proc_name').value = name;
    document.getElementById('hidden_proc_code').value = code;
}
</script>