<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-bug"></i> Transfer Debug Page
            <small>Debug transfer functionality issues</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-database"></i> Central Stocks Data
            </div>
            <div class="panel-body">
                <?php 
                $central_stocks_count = $this->db->count_all('central_stocks');
                $central_stocks_active = $this->db->where('status', 'ACTIVE')->where('quantity >', 0)->count_all_results('central_stocks');
                ?>
                <p><strong>Total Central Stocks Records:</strong> <?php echo $central_stocks_count; ?></p>
                <p><strong>Active Central Stocks:</strong> <?php echo $central_stocks_active; ?></p>
                
                <?php if($central_stocks_active > 0): ?>
                    <div class="alert alert-success">
                        <i class="fa fa-check"></i> Central stocks data is available for transfers.
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> No active central stocks found. This might be why transfers are not working.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-exchange"></i> Transfer Test
            </div>
            <div class="panel-body">
                <button class="btn btn-primary" onclick="testTransferFunction()">
                    <i class="fa fa-play"></i> Test Transfer Function
                </button>
                <div id="transfer_test_result" style="margin-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Sample Central Stocks Data
            </div>
            <div class="panel-body">
                <?php 
                $sample_stocks = $this->db->select('cs.*, mb.batch_number, mb.expiry_date, m.medicine_name')
                    ->from('central_stocks cs')
                    ->join('medicine_batches mb', 'cs.batch_id = mb.id')
                    ->join('medicines m', 'mb.medicine_id = m.id')
                    ->where('cs.status', 'ACTIVE')
                    ->where('cs.quantity >', 0)
                    ->limit(5)
                    ->get()
                    ->result();
                ?>
                
                <?php if(!empty($sample_stocks)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sample_stocks as $stock): ?>
                                    <tr>
                                        <td><?php echo $stock->medicine_name; ?></td>
                                        <td><?php echo $stock->batch_number; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($stock->expiry_date)); ?></td>
                                        <td><?php echo $stock->quantity; ?></td>
                                        <td><?php echo $stock->status; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> No sample central stocks data found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-cogs"></i> Available Stocks for Transfer Test
            </div>
            <div class="panel-body">
                <button class="btn btn-success" onclick="testAvailableStocks()">
                    <i class="fa fa-search"></i> Test Available Stocks Query
                </button>
                <div id="available_stocks_result" style="margin-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function testTransferFunction() {
    $('#transfer_test_result').html('<i class="fa fa-spinner fa-spin"></i> Testing...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_available_stocks_for_transfer"); ?>',
        type: 'GET',
        data: {
            transfer_type: 'CENTRAL_TO_CENTER'
        },
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Transfer function working! Found ' + response.length + ' available stocks.';
            html += '</div>';
            
            if(response.length > 0) {
                html += '<div class="table-responsive" style="margin-top: 10px;">';
                html += '<table class="table table-striped table-sm">';
                html += '<thead><tr><th>Medicine</th><th>Batch</th><th>Quantity</th><th>Status</th></tr></thead>';
                html += '<tbody>';
                
                for(var i = 0; i < Math.min(response.length, 5); i++) {
                    var stock = response[i];
                    html += '<tr>';
                    html += '<td>' + stock.medicine_name + '</td>';
                    html += '<td>' + stock.batch_number + '</td>';
                    html += '<td>' + stock.quantity_remaining + '</td>';
                    html += '<td>' + stock.expiry_status + '</td>';
                    html += '</tr>';
                }
                
                html += '</tbody></table></div>';
            }
            
            $('#transfer_test_result').html(html);
        },
        error: function(xhr, status, error) {
            $('#transfer_test_result').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: ' + error + '</div>');
        }
    });
}

function testAvailableStocks() {
    $('#available_stocks_result').html('<i class="fa fa-spinner fa-spin"></i> Testing available stocks query...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_available_stocks_for_transfer"); ?>',
        type: 'GET',
        data: {
            transfer_type: 'CENTRAL_TO_CENTER'
        },
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-info">';
            html += '<strong>Query Result:</strong> ' + response.length + ' stocks found<br>';
            html += '<strong>Response:</strong> <pre>' + JSON.stringify(response, null, 2) + '</pre>';
            html += '</div>';
            $('#available_stocks_result').html(html);
        },
        error: function(xhr, status, error) {
            $('#available_stocks_result').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
        }
    });
}
</script>
