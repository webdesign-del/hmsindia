<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-bug"></i> Center to Center Transfer Debug
            <small>Debug center to center transfer functionality</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Center to Center Transfer Test
            </div>
            <div class="panel-body">
                <form id="testForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Source Center:</label>
                                <select name="from_center_id" class="form-control" id="fromCenter">
                                    <option value="">Select Source Center</option>
                                    <?php foreach($centers as $center): ?>
                                        <option value="<?php echo $center->ID; ?>"><?php echo $center->center_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Destination Center:</label>
                                <select name="to_center_id" class="form-control" id="toCenter">
                                    <option value="">Select Destination Center</option>
                                    <?php foreach($centers as $center): ?>
                                        <option value="<?php echo $center->ID; ?>"><?php echo $center->center_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="testCenterToCenter()">
                        <i class="fa fa-search"></i> Test Center to Center Transfer
                    </button>
                </form>
                
                <div id="testResults" style="margin-top: 20px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-database"></i> Center Stocks Data Check
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Check center stocks data
                    $center_stocks = $this->db->select('ccs.*, mb.batch_number, m.medicine_name, c.center_name')
                        ->from('center_stocks ccs')
                        ->join('medicine_batches mb', 'ccs.batch_id = mb.id')
                        ->join('medicines m', 'mb.medicine_id = m.id')
                        ->join('hms_centers c', 'ccs.center_id = c.ID', 'left')
                        ->where('ccs.quantity >', 0)
                        ->limit(10)
                        ->get()
                        ->result();
                    
                    if(!empty($center_stocks)) {
                        echo '<h5>Center Stocks Sample Data:</h5>';
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-sm">';
                        echo '<thead><tr><th>Center</th><th>Medicine</th><th>Batch</th><th>Quantity</th><th>Status</th></tr></thead>';
                        echo '<tbody>';
                        foreach($center_stocks as $cs) {
                            echo '<tr>';
                            echo '<td>' . ($cs->center_name ?: 'Center ' . $cs->center_id) . '</td>';
                            echo '<td>' . $cs->medicine_name . '</td>';
                            echo '<td>' . $cs->batch_number . '</td>';
                            echo '<td>' . $cs->quantity . '</td>';
                            echo '<td>' . ($cs->status ?: 'N/A') . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table></div>';
                    } else {
                        echo '<div class="alert alert-warning">No center stocks found!</div>';
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Available Centers
            </div>
            <div class="panel-body">
                <?php 
                try {
                    $centers = $this->db->select('ID, center_name, center_type')
                        ->from('hms_centers')
                        ->where('status', 'active')
                        ->get()
                        ->result();
                    
                    if(!empty($centers)) {
                        echo '<h5>Available Centers:</h5>';
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-sm">';
                        echo '<thead><tr><th>ID</th><th>Center Name</th><th>Type</th></tr></thead>';
                        echo '<tbody>';
                        foreach($centers as $center) {
                            echo '<tr>';
                            echo '<td>' . $center->ID . '</td>';
                            echo '<td>' . $center->center_name . '</td>';
                            echo '<td>' . ($center->center_type ?: 'N/A') . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table></div>';
                    } else {
                        echo '<div class="alert alert-warning">No centers found!</div>';
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
        <div class="panel panel-success">
            <div class="panel-heading">
                <i class="fa fa-tools"></i> Fix Center to Center Transfers
            </div>
            <div class="panel-body">
                <p>If center to center transfers are not working, click the button below to fix the issues.</p>
                <button class="btn btn-primary" onclick="fixCenterToCenter()">
                    <i class="fa fa-wrench"></i> Fix Center to Center Transfers
                </button>
                <div id="fix_results" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function testCenterToCenter() {
    var fromCenter = document.getElementById('fromCenter').value;
    var toCenter = document.getElementById('toCenter').value;
    
    if(!fromCenter || !toCenter) {
        alert('Please select both source and destination centers');
        return;
    }
    
    $('#testResults').html('<i class="fa fa-spinner fa-spin"></i> Testing center to center transfer...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/test_center_to_center"); ?>',
        type: 'POST',
        data: {
            from_center_id: fromCenter,
            to_center_id: toCenter
        },
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Center to Center Transfer Test Results:<br>';
            html += '<strong>Available Stocks:</strong> ' + response.available_stocks + '<br>';
            html += '<strong>Source Center:</strong> ' + response.source_center + '<br>';
            html += '<strong>Destination Center:</strong> ' + response.destination_center + '<br>';
            html += '<strong>Test Status:</strong> ' + (response.success ? 'PASSED' : 'FAILED') + '<br>';
            if(response.error) {
                html += '<strong>Error:</strong> ' + response.error + '<br>';
            }
            html += '</div>';
            
            if(response.stocks && response.stocks.length > 0) {
                html += '<h5>Available Stocks for Transfer:</h5>';
                html += '<div class="table-responsive">';
                html += '<table class="table table-striped table-sm">';
                html += '<thead><tr><th>Medicine</th><th>Batch</th><th>Expiry</th><th>Quantity</th><th>Price</th></tr></thead>';
                html += '<tbody>';
                response.stocks.forEach(function(stock) {
                    html += '<tr>';
                    html += '<td>' + stock.medicine_name + '</td>';
                    html += '<td>' + stock.batch_number + '</td>';
                    html += '<td>' + stock.expiry_date + '</td>';
                    html += '<td>' + stock.quantity_remaining + '</td>';
                    html += '<td>₹' + stock.selling_price + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table></div>';
            }
            
            $('#testResults').html(html);
        },
        error: function(xhr, status, error) {
            $('#testResults').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
        }
    });
}

function fixCenterToCenter() {
    $('#fix_results').html('<i class="fa fa-spinner fa-spin"></i> Fixing center to center transfers...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/fix_center_to_center"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Center to Center Transfer fix completed!<br>';
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
