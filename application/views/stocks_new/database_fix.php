<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-database"></i> Database Structure Fix
            <small>Fix missing columns in stock_transfers table</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-exclamation-triangle"></i> Database Structure Issue
            </div>
            <div class="panel-body">
                <p><strong>Error:</strong> The <code>approved_date</code> column is missing from the <code>stock_transfers</code> table.</p>
                <p><strong>Solution:</strong> Run the SQL script below to add the missing columns.</p>
                
                <div class="alert alert-info">
                    <h4>SQL Script to Fix Database Structure:</h4>
                    <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
-- Add missing columns to stock_transfers table
ALTER TABLE `stock_transfers` 
ADD COLUMN `approved_date` TIMESTAMP NULL AFTER `approved_by`;

-- Add other useful columns
ALTER TABLE `stock_transfers` 
ADD COLUMN `from_department` VARCHAR(100) NULL AFTER `from_center_id`;

ALTER TABLE `stock_transfers` 
ADD COLUMN `to_department` VARCHAR(100) NULL AFTER `to_center_id`;

-- Add indexes for better performance
ALTER TABLE `stock_transfers` 
ADD INDEX `idx_status` (`status`),
ADD INDEX `idx_approved_by` (`approved_by`),
ADD INDEX `idx_approved_date` (`approved_date`);
                    </pre>
                </div>
                
                <button class="btn btn-primary" onclick="runDatabaseFix()">
                    <i class="fa fa-tools"></i> Run Database Fix
                </button>
                
                <div id="fix_results" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Current Table Structure
            </div>
            <div class="panel-body">
                <?php 
                try {
                    $columns = $this->db->list_fields('stock_transfers');
                    echo '<h5>Current columns in stock_transfers table:</h5>';
                    echo '<ul>';
                    foreach($columns as $column) {
                        echo '<li><code>' . $column . '</code></li>';
                    }
                    echo '</ul>';
                    
                    // Check if approved_date exists
                    if(in_array('approved_date', $columns)) {
                        echo '<div class="alert alert-success"><i class="fa fa-check"></i> approved_date column exists!</div>';
                    } else {
                        echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> approved_date column is missing!</div>';
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
                <i class="fa fa-list"></i> Manual Fix Instructions
            </div>
            <div class="panel-body">
                <h5>If the automatic fix doesn't work, run this SQL manually:</h5>
                <ol>
                    <li>Open phpMyAdmin or your MySQL client</li>
                    <li>Select your database</li>
                    <li>Go to SQL tab</li>
                    <li>Run this command:
                        <pre style="background: #f5f5f5; padding: 10px; margin: 10px 0;">
ALTER TABLE `stock_transfers` 
ADD COLUMN `approved_date` TIMESTAMP NULL AFTER `approved_by`;</pre>
                    </li>
                    <li>Click "Go" to execute</li>
                </ol>
                
                <p><strong>After running the SQL:</strong></p>
                <ul>
                    <li>Go back to <a href="<?php echo base_url('stocks_new/transfers'); ?>">Stock Transfers</a></li>
                    <li>Click "Approve All Pending" button</li>
                    <li>Transfers should now complete successfully</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function runDatabaseFix() {
    $('#fix_results').html('<i class="fa fa-spinner fa-spin"></i> Running database fix...');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/run_database_fix"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check"></i> Database fix completed successfully!<br>';
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
