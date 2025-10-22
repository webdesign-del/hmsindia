<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-tools"></i> Transfer Data Fix
            <small>Fix missing transfer values and totals</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Transfer Data Analysis
            </div>
            <div class="panel-body">
                <?php 
                try {
                    // Get all transfers
                    $transfers = $this->db->select('st.*, fc.center_name as from_center, tc.center_name as to_center')
                        ->from('stock_transfers st')
                        ->join('hms_centers fc', 'st.from_center_id = fc.ID', 'left')
                        ->join('hms_centers tc', 'st.to_center_id = tc.ID', 'left')
                        ->order_by('st.id', 'DESC')
                        ->get()
                        ->result();
                    
                    echo '<p><strong>Total Transfers Found:</strong> ' . count($transfers) . '</p>';
                    
                    // Check which transfers have items
                    $transfers_with_items = 0;
                    $transfers_without_items = 0;
                    $total_items_count = 0;
                    $total_value_sum = 0;
                    
                    foreach($transfers as $transfer) {
                        $items = $this->db->select('COUNT(*) as count, SUM(quantity_transferred) as total_qty, SUM(total_price) as total_val')
                            ->from('stock_transfer_items')
                            ->where('transfer_id', $transfer->id)
                            ->get()
                            ->row();
                        
                        if($items && $items->count > 0) {
                            $transfers_with_items++;
                            $total_items_count += $items->count;
                            $total_value_sum += $items->total_val ?: 0;
                        } else {
                            $transfers_without_items++;
                        }
                    }
                    
                    echo '<p><strong>Transfers with Items:</strong> ' . $transfers_with_items . '</p>';
                    echo '<p><strong>Transfers without Items:</strong> ' . $transfers_without_items . '</p>';
                    echo '<p><strong>Total Items Count:</strong> ' . $total_items_count . '</p>';
                    echo '<p><strong>Total Value Sum:</strong> ₹' . number_format($total_value_sum, 2) . '</p>';
                    
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
                <i class="fa fa-wrench"></i> Fix Transfer Totals
            </div>
            <div class="panel-body">
                <p>This will update all transfer records with correct totals calculated from their items.</p>
                <button class="btn btn-warning" onclick="fixTransferTotals()">
                    <i class="fa fa-tools"></i> Fix All Transfer Totals
                </button>
                <div id="fix_results" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Transfer Items Analysis
            </div>
            <div class="panel-body">
                <?php 
                try {
                    $transfer_items = $this->db->select('sti.*, st.transfer_number, m.medicine_name, mb2.batch_number')
                        ->from('stock_transfer_items sti')
                        ->join('stock_transfers st', 'sti.transfer_id = st.id')
                        ->join('medicine_batches mb2', 'sti.batch_id = mb2.id')
                        ->join('medicines m', 'mb2.medicine_id = m.id')
                        ->order_by('sti.id', 'DESC')
                        ->limit(10)
                        ->get()
                        ->result();
                    
                    if(!empty($transfer_items)) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped">';
                        echo '<thead>';
                        echo '<tr><th>Transfer #</th><th>Medicine</th><th>Batch</th><th>Quantity</th><th>Unit Price</th><th>Total Price</th></tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        foreach($transfer_items as $item) {
                            echo '<tr>';
                            echo '<td>' . $item->transfer_number . '</td>';
                            echo '<td>' . $item->medicine_name . '</td>';
                            echo '<td>' . $item->batch_number . '</td>';
                            echo '<td>' . $item->quantity_transferred . '</td>';
                            echo '<td>₹' . number_format($item->unit_price, 2) . '</td>';
                            echo '<td>₹' . number_format($item->total_price, 2) . '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody></table></div>';
                    } else {
                        echo '<div class="alert alert-info">No transfer items found.</div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error loading transfer items: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function fixTransferTotals() {
    $('#fix_results').html('<i class="fa fa-spinner fa-spin"></i> Fixing transfer totals...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/fix_transfer_totals"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Transfer totals fixed successfully!<br>';
            html += '<strong>Updated:</strong> ' + response.updated + ' transfers<br>';
            html += '<strong>Total Items:</strong> ' + response.total_items + '<br>';
            html += '<strong>Total Value:</strong> ₹' + parseFloat(response.total_value).toFixed(2);
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
