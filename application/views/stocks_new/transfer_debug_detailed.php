<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-bug"></i> Transfer Debug - Detailed Analysis
            <small>Comprehensive debugging for transfer issues</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-database"></i> Database Connection Test
            </div>
            <div class="panel-body">
                <?php 
                try {
                    $central_stocks_count = $this->db->count_all('central_stocks');
                    $medicine_batches_count = $this->db->count_all('medicine_batches');
                    $medicines_count = $this->db->count_all('medicines');
                    
                    echo '<div class="alert alert-success">';
                    echo '<i class="fa fa-check"></i> Database connection successful!<br>';
                    echo '<strong>Central Stocks:</strong> ' . $central_stocks_count . ' records<br>';
                    echo '<strong>Medicine Batches:</strong> ' . $medicine_batches_count . ' records<br>';
                    echo '<strong>Medicines:</strong> ' . $medicines_count . ' records';
                    echo '</div>';
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="fa fa-times"></i> Database error: ' . $e->getMessage();
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Central Stocks Query Test
            </div>
            <div class="panel-body">
                <?php 
                try {
                    $query = $this->db->select('cs.*, mb.batch_number, mb.expiry_date, m.medicine_name')
                        ->from('central_stocks cs')
                        ->join('medicine_batches mb', 'cs.batch_id = mb.id')
                        ->join('medicines m', 'mb.medicine_id = m.id')
                        ->where('cs.status', 'ACTIVE')
                        ->where('cs.quantity >', 0)
                        ->limit(3)
                        ->get();
                    
                    $results = $query->result();
                    
                    if(!empty($results)) {
                        echo '<div class="alert alert-success">';
                        echo '<i class="fa fa-check"></i> Central stocks query successful! Found ' . count($results) . ' active stocks.';
                        echo '</div>';
                        
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-sm">';
                        echo '<thead><tr><th>Medicine</th><th>Batch</th><th>Quantity</th><th>Status</th></tr></thead>';
                        echo '<tbody>';
                        
                        foreach($results as $stock) {
                            echo '<tr>';
                            echo '<td>' . $stock->medicine_name . '</td>';
                            echo '<td>' . $stock->batch_number . '</td>';
                            echo '<td>' . $stock->quantity . '</td>';
                            echo '<td>' . $stock->status . '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody></table></div>';
                    } else {
                        echo '<div class="alert alert-warning">';
                        echo '<i class="fa fa-exclamation-triangle"></i> No active central stocks found.';
                        echo '</div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="fa fa-times"></i> Query error: ' . $e->getMessage();
                    echo '</div>';
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
                <i class="fa fa-cogs"></i> Transfer Function Test
            </div>
            <div class="panel-body">
                <button class="btn btn-primary" onclick="testTransferFunction()">
                    <i class="fa fa-play"></i> Test CENTRAL_TO_CENTER Transfer Function
                </button>
                <button class="btn btn-success" onclick="testModelMethod()">
                    <i class="fa fa-database"></i> Test Model Method Directly
                </button>
                <div id="test_results" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Recent Transfers
            </div>
            <div class="panel-body">
                <?php 
                try {
                    $recent_transfers = $this->db->select('st.*, fc.center_name as from_center, tc.center_name as to_center')
                        ->from('stock_transfers st')
                        ->join('hms_centers fc', 'st.from_center_id = fc.ID', 'left')
                        ->join('hms_centers tc', 'st.to_center_id = tc.ID', 'left')
                        ->order_by('st.id', 'DESC')
                        ->limit(5)
                        ->get()
                        ->result();
                    
                    if(!empty($recent_transfers)) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped">';
                        echo '<thead>';
                        echo '<tr><th>ID</th><th>Transfer #</th><th>Type</th><th>From</th><th>To</th><th>Status</th><th>Date</th><th>Actions</th></tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        foreach($recent_transfers as $transfer) {
                            echo '<tr>';
                            echo '<td>' . $transfer->id . '</td>';
                            echo '<td>' . $transfer->transfer_number . '</td>';
                            echo '<td>' . str_replace('_', ' ', $transfer->transfer_type) . '</td>';
                            echo '<td>' . ($transfer->from_center ?: 'Central') . '</td>';
                            echo '<td>' . $transfer->to_center . '</td>';
                            echo '<td><span class="badge badge-' . ($transfer->status == 'DRAFT' ? 'warning' : 'success') . '">' . $transfer->status . '</span></td>';
                            echo '<td>' . date('M d, Y', strtotime($transfer->transfer_date)) . '</td>';
                            echo '<td>';
                            echo '<a href="' . base_url('stocks_new/edit_transfer/' . $transfer->id) . '" class="btn btn-xs btn-primary">Edit</a> ';
                            echo '<a href="' . base_url('stocks_new/transfer_details/' . $transfer->id) . '" class="btn btn-xs btn-info">View</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody></table></div>';
                    } else {
                        echo '<div class="alert alert-info">No transfers found.</div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error loading transfers: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function testTransferFunction() {
    $('#test_results').html('<i class="fa fa-spinner fa-spin"></i> Testing transfer function...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_available_stocks_for_transfer"); ?>',
        type: 'GET',
        data: {
            transfer_type: 'CENTRAL_TO_CENTER'
        },
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Transfer function working! Found ' + response.length + ' available stocks.<br>';
            html += '<strong>Response:</strong> <pre>' + JSON.stringify(response, null, 2) + '</pre>';
            html += '</div>';
            $('#test_results').html(html);
        },
        error: function(xhr, status, error) {
            $('#test_results').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
        }
    });
}

function testModelMethod() {
    $('#test_results').html('<i class="fa fa-spinner fa-spin"></i> Testing model method directly...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/test_model_method"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-info">';
            html += '<i class="fa fa-info"></i> Model method test completed.<br>';
            html += '<strong>Result:</strong> <pre>' + JSON.stringify(response, null, 2) + '</pre>';
            html += '</div>';
            $('#test_results').html(html);
        },
        error: function(xhr, status, error) {
            $('#test_results').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
        }
    });
}
</script>
