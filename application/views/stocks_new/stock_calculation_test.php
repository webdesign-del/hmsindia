<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-calculator"></i> Stock Calculation Test
            <small>Verify total stock calculations</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Stock Calculation Logic Test
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Test the stock calculation logic
                    echo '<h5>Testing Stock Calculation Logic:</h5>';
                    
                    // Get sample data using the corrected method
                    $stock_levels = $this->Stock_model_new->get_current_stock_levels();
                    
                    if(!empty($stock_levels)) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-bordered">';
                        echo '<thead>';
                        echo '<tr><th>Medicine</th><th>Batch</th><th>Central Qty</th><th>Center Qty</th><th>Total Qty</th><th>Manual Calc</th><th>Match?</th></tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        $total_matches = 0;
                        $total_items = 0;
                        
                        foreach(array_slice($stock_levels, 0, 10) as $stock) {
                            $central_qty = isset($stock->central_quantity) && is_numeric($stock->central_quantity) ? $stock->central_quantity : 0;
                            $center_qty = isset($stock->center_quantity) && is_numeric($stock->center_quantity) ? $stock->center_quantity : 0;
                            $total_qty = isset($stock->total_quantity) && is_numeric($stock->total_quantity) ? $stock->total_quantity : 0;
                            $manual_calc = $central_qty + $center_qty;
                            $matches = ($total_qty == $manual_calc) ? 'Yes' : 'No';
                            
                            if($matches == 'Yes') $total_matches++;
                            $total_items++;
                            
                            echo '<tr>';
                            echo '<td>' . (isset($stock->medicine_name) ? $stock->medicine_name : 'N/A') . '</td>';
                            echo '<td>' . (isset($stock->batch_number) ? $stock->batch_number : 'N/A') . '</td>';
                            echo '<td>' . number_format($central_qty) . '</td>';
                            echo '<td>' . number_format($center_qty) . '</td>';
                            echo '<td>' . number_format($total_qty) . '</td>';
                            echo '<td>' . number_format($manual_calc) . '</td>';
                            echo '<td><span class="badge ' . ($matches == 'Yes' ? 'badge-success' : 'badge-danger') . '">' . $matches . '</span></td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody></table></div>';
                        
                        echo '<div class="alert ' . ($total_matches == $total_items ? 'alert-success' : 'alert-warning') . '">';
                        echo '<strong>Results:</strong> ' . $total_matches . ' out of ' . $total_items . ' calculations match (' . round(($total_matches/$total_items)*100, 2) . '%)';
                        echo '</div>';
                        
                    } else {
                        echo '<div class="alert alert-warning">No stock data found!</div>';
                    }
                    
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
                <i class="fa fa-exclamation-triangle"></i> Raw Data Check
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Check raw data from tables
                    echo '<h5>Raw Data from Tables:</h5>';
                    
                    // Check central stocks
                    $central_stocks = $this->db->select('cs.batch_id, cs.quantity, mb.batch_number, m.medicine_name')
                        ->from('central_stocks cs')
                        ->join('medicine_batches mb', 'cs.batch_id = mb.id')
                        ->join('medicines m', 'mb.medicine_id = m.id')
                        ->limit(5)
                        ->get()
                        ->result();
                    
                    echo '<h6>Central Stocks Sample:</h6>';
                    if(!empty($central_stocks)) {
                        echo '<table class="table table-sm"><thead><tr><th>Medicine</th><th>Batch</th><th>Quantity</th></tr></thead><tbody>';
                        foreach($central_stocks as $cs) {
                            echo '<tr><td>' . $cs->medicine_name . '</td><td>' . $cs->batch_number . '</td><td>' . $cs->quantity . '</td></tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-info">No central stocks found</div>';
                    }
                    
                    // Check center stocks
                    $center_stocks = $this->db->select('ccs.batch_id, ccs.quantity, ccs.center_id, mb.batch_number, m.medicine_name, c.center_name')
                        ->from('center_stocks ccs')
                        ->join('medicine_batches mb', 'ccs.batch_id = mb.id')
                        ->join('medicines m', 'mb.medicine_id = m.id')
                        ->join('hms_centers c', 'ccs.center_id = c.ID', 'left')
                        ->limit(5)
                        ->get()
                        ->result();
                    
                    echo '<h6>Center Stocks Sample:</h6>';
                    if(!empty($center_stocks)) {
                        echo '<table class="table table-sm"><thead><tr><th>Medicine</th><th>Batch</th><th>Center</th><th>Quantity</th></tr></thead><tbody>';
                        foreach($center_stocks as $ccs) {
                            echo '<tr><td>' . $ccs->medicine_name . '</td><td>' . $ccs->batch_number . '</td><td>' . ($ccs->center_name ?: 'Center ' . $ccs->center_id) . '</td><td>' . $ccs->quantity . '</td></tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-info">No center stocks found</div>';
                    }
                    
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
        <div class="panel panel-success">
            <div class="panel-heading">
                <i class="fa fa-tools"></i> Fix Stock Calculations
            </div>
            <div class="panel-body">
                <p>If the calculations are incorrect, click the button below to fix the stock levels view and calculations.</p>
                <button class="btn btn-primary" onclick="fixStockCalculations()">
                    <i class="fa fa-wrench"></i> Fix Stock Calculations
                </button>
                <div id="fix_results" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function fixStockCalculations() {
    $('#fix_results').html('<i class="fa fa-spinner fa-spin"></i> Fixing stock calculations...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/fix_stock_levels"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Stock calculations fix completed!<br>';
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
