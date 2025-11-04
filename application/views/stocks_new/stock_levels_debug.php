<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-bug"></i> Stock Levels Debug
            <small>Check stock levels data accuracy</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Database Structure Check
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Check if view exists
                    $view_check = $this->db->query("SHOW TABLES LIKE 'v_current_stock_levels'")->result();
                    if(empty($view_check)) {
                        echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> View v_current_stock_levels does not exist!</div>';
                    } else {
                        echo '<div class="alert alert-success"><i class="fa fa-check"></i> View v_current_stock_levels exists</div>';
                    }
                    
                    // Check table structures
                    $tables_to_check = ['medicines', 'medicine_batches', 'central_stocks', 'center_stocks', 'hms_centers', 'medicine_brands'];
                    echo '<h5>Table Structure Check:</h5><ul>';
                    foreach($tables_to_check as $table) {
                        try {
                            $columns = $this->db->list_fields($table);
                            echo '<li><strong>' . $table . ':</strong> ' . count($columns) . ' columns ✓</li>';
                        } catch (Exception $e) {
                            echo '<li><strong>' . $table . ':</strong> <span class="text-danger">Error - ' . $e->getMessage() . '</span></li>';
                        }
                    }
                    echo '</ul>';
                    
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-database"></i> Raw Data Check
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Check medicines table
                    $medicines_count = $this->db->count_all('medicines');
                    echo '<p><strong>Medicines:</strong> ' . $medicines_count . ' records</p>';
                    
                    // Check medicine_batches table
                    $batches_count = $this->db->count_all('medicine_batches');
                    echo '<p><strong>Medicine Batches:</strong> ' . $batches_count . ' records</p>';
                    
                    // Check central_stocks table
                    $central_stocks_count = $this->db->count_all('central_stocks');
                    echo '<p><strong>Central Stocks:</strong> ' . $central_stocks_count . ' records</p>';
                    
                    // Check center_stocks table
                    $center_stocks_count = $this->db->count_all('center_stocks');
                    echo '<p><strong>Center Stocks:</strong> ' . $center_stocks_count . ' records</p>';
                    
                    // Check centers table
                    $centers_count = $this->db->count_all('hms_centers');
                    echo '<p><strong>Centers:</strong> ' . $centers_count . ' records</p>';
                    
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error checking raw data: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Sample Data from Each Table
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Sample medicines
                    echo '<h5>Sample Medicines:</h5>';
                    $medicines = $this->db->select('id, medicine_name, medicine_code')->from('medicines')->limit(5)->get()->result();
                    if(!empty($medicines)) {
                        echo '<table class="table table-sm"><thead><tr><th>ID</th><th>Name</th><th>Code</th></tr></thead><tbody>';
                        foreach($medicines as $med) {
                            echo '<tr><td>' . $med->id . '</td><td>' . $med->medicine_name . '</td><td>' . $med->medicine_code . '</td></tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-warning">No medicines found!</div>';
                    }
                    
                    // Sample batches
                    echo '<h5>Sample Medicine Batches:</h5>';
                    $batches = $this->db->select('id, batch_number, expiry_date, quantity_remaining')->from('medicine_batches')->limit(5)->get()->result();
                    if(!empty($batches)) {
                        echo '<table class="table table-sm"><thead><tr><th>ID</th><th>Batch</th><th>Expiry</th><th>Qty</th></tr></thead><tbody>';
                        foreach($batches as $batch) {
                            echo '<tr><td>' . $batch->id . '</td><td>' . $batch->batch_number . '</td><td>' . $batch->expiry_date . '</td><td>' . $batch->quantity_remaining . '</td></tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-warning">No batches found!</div>';
                    }
                    
                    // Sample central stocks
                    echo '<h5>Sample Central Stocks:</h5>';
                    $central_stocks = $this->db->select('batch_id, quantity')->from('central_stocks')->limit(5)->get()->result();
                    if(!empty($central_stocks)) {
                        echo '<table class="table table-sm"><thead><tr><th>Batch ID</th><th>Quantity</th></tr></thead><tbody>';
                        foreach($central_stocks as $stock) {
                            echo '<tr><td>' . $stock->batch_id . '</td><td>' . $stock->quantity . '</td></tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-warning">No central stocks found!</div>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error getting sample data: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <i class="fa fa-tools"></i> Fix Stock Levels Data
            </div>
            <div class="panel-body">
                <p>If the stock levels are showing incorrect data, click the button below to fix the view and data structure.</p>
                <button class="btn btn-primary" onclick="fixStockLevels()">
                    <i class="fa fa-wrench"></i> Fix Stock Levels
                </button>
                <div id="fix_results" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function fixStockLevels() {
    $('#fix_results').html('<i class="fa fa-spinner fa-spin"></i> Fixing stock levels...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/fix_stock_levels"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Stock levels fix completed!<br>';
            html += '<strong>Results:</strong> ' + JSON.stringify(response, null, 2);
            html += '</div>';
            $('#fix_results').html(html);
            
            // Reload page after 2 seconds
            setTimeout(function() {
                location.reload();
            }, 2000);
        },
        error: function(xhr, status, error) {
            $('#fix_results').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
        }
    });
}
</script>
