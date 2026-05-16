<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-building"></i> Center Stocks
            <small>Center-wise inventory management</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li class="active">Center Stocks</li>
        </ol>
    </div>
</div>




    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --bg-color: #f8f9fa;
            --border-color: #dee2e6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        h2 {
            color: var(--secondary-color);
            margin-top: 30px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 5px;
        }
        h3 {
            color: #555;
            margin-top: 20px;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="date"], input[type="time"], select, textarea {
            padding: 0px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid var(--border-color);
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: var(--primary-color);
            color: #fff;
        }
        .checkbox-group {
            margin: 10px 0;
        }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 30px;
        }
        .btn-submit:hover {
            background-color: #2980b9;
        }
        .pro-tip {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>

<div class="container">
    <h1>✅ IVF Lab Daily Monitoring Format (Andrology + Embryology Labs) </h1>

    <div class="meta-grid">
        <div class="form-group">
            <label>Lab Name[cite: 7]:</label>
            <input type="text" placeholder="Enter Lab Name">
        </div>
        <div class="form-group">
            <label>Date[cite: 7]:</label>
            <input type="date">
        </div>
        <div class="form-group">
            <label>Shift[cite: 7]:</label>
            <select>
                <option>Morning</option>
                <option>Afternoon</option>
                <option>Evening</option>
            </select>
        </div>
        <div class="form-group">
            <label>Reported By[cite: 7]:</label>
            <input type="text" placeholder="Your Name">
        </div>
    </div>

    <h2>A. Surface Cleaning [cite: 8]</h2>
    
    <table>
        <tr>
            <th>Area [cite: 9]</th>
            <th>Done ( ✔ / ✘ ) [cite: 9]</th>
            <th>Time [cite: 9]</th>
            <th>Remarks [cite: 9]</th>
        </tr>
        <tr><td>Work benches cleaned (IVF lab) [cite: 9]</td><td><input type="checkbox"></td><td><input type="time"></td><td><input type="text"></td></tr>
        <tr><td>Andrology workstations cleaned [cite: 9]</td><td><input type="checkbox"></td><td><input type="time"></td><td><input type="text"></td></tr>
        <tr><td>Microscopes external cleaning [cite: 9]</td><td><input type="checkbox"></td><td><input type="time"></td><td><input type="text"></td></tr>
        <tr><td>Laminar hood surface cleaned [cite: 9]</td><td><input type="checkbox"></td><td><input type="time"></td><td><input type="text"></td></tr>
        <tr><td>Incubator outer surface cleaned [cite: 9]</td><td><input type="checkbox"></td><td><input type="time"></td><td><input type="text"></td></tr>
    </table>

    <h3>B. Disinfection [cite: 10]</h3>
    <table>
        <tr>
            <th>Item [cite: 11]</th>
            <th>Agent Used [cite: 11]</th>
            <th>Done ( ✔ / ✘ ) [cite: 11]</th>
            <th>Remarks [cite: 11]</th>
        </tr>
        <tr><td>Floor mopping [cite: 11]</td><td><input type="text" placeholder="e.g. IPA / H2O2"> [cite: 11]</td><td><input type="checkbox"></td><td><input type="text"></td></tr>
        <tr><td>Laminar airflow UV (if applicable) [cite: 11]</td><td><input type="text"></td><td><input type="checkbox"></td><td><input type="text"></td></tr>
        <tr><td>Door handles & switches [cite: 11]</td><td><input type="text"></td><td><input type="checkbox"></td><td><input type="text"></td></tr>
    </table>

    <h3>C. Weekly/Rotational Tasks (mark if done today) [cite: 12]</h3>
    <div class="checkbox-group"><label><input type="checkbox"> Incubator internal cleaning [cite: 13]</label></div>
    <div class="checkbox-group"><label><input type="checkbox"> HEPA filter check [cite: 14]</label></div>
    <div class="checkbox-group"><label><input type="checkbox"> Deep cleaning (walls/storage) [cite: 15]</label></div>

    <h2>🌡️ SECTION 2: Lab Environmental Parameters [cite: 16]</h2>
    
    <h3>A. IVF Lab Conditions [cite: 17]</h3>
    <table>
        <tr>
            <th>Parameter [cite: 18]</th><th>Morning [cite: 18]</th><th>Afternoon [cite: 18]</th><th>Evening [cite: 18]</th><th>Acceptable Range [cite: 18]</th><th>Remarks [cite: 18]</th>
        </tr>
        <tr><td>Temperature (°C) [cite: 18]</td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td>22–25°C [cite: 18]</td><td><input type="text"></td></tr>
        <tr><td>Humidity (%) [cite: 18]</td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td>40–60% [cite: 18]</td><td><input type="text"></td></tr>
        <tr><td>CO₂ (%) [cite: 18]</td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td>5–6% [cite: 18]</td><td><input type="text"></td></tr>
        <tr><td>VOC Level (if monitored) [cite: 18]</td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td>Low/Acceptable [cite: 18]</td><td><input type="text"></td></tr>
        <tr><td>Air Pressure [cite: 18]</td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td>Positive [cite: 18]</td><td><input type="text"></td></tr>
    </table>

    <h3>B. Andrology Lab Conditions [cite: 19]</h3>
    <table>
        <tr>
            <th>Parameter [cite: 20]</th><th>Reading [cite: 20]</th><th>Acceptable Range [cite: 20]</th><th>Remarks [cite: 20]</th>
        </tr>
        <tr><td>Temperature [cite: 20]</td><td><input type="text"></td><td>22–25°C [cite: 20]</td><td><input type="text"></td></tr>
        <tr><td>Humidity [cite: 20]</td><td><input type="text"></td><td>40–60% [cite: 20]</td><td><input type="text"></td></tr>
    </table>

    <h2>⚙️ SECTION 3: Equipment Status [cite: 21]</h2>
    
    <h3>A. Incubators [cite: 22]</h3>
    <table>
        <tr><th>Equipment ID [cite: 23]</th><th>Temp (°C) [cite: 23]</th><th>CO₂ (%) [cite: 23]</th><th>Alarm Status [cite: 23]</th><th>Water Level [cite: 23]</th><th>Remarks [cite: 23]</th></tr>
        <tr><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td></tr>
    </table>

    <h3>B. Laminar Air Flow / Workstations [cite: 24]</h3>
    <table>
        <tr><th>Unit ID [cite: 25]</th><th>UV Working [cite: 25]</th><th>Airflow OK [cite: 25]</th><th>Last Cleaning [cite: 25]</th><th>Remarks [cite: 25]</th></tr>
        <tr><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td></tr>
    </table>

    <h3>C. Microscopes [cite: 26]</h3>
    <table>
        <tr><th>ID [cite: 27]</th><th>Working Condition [cite: 27]</th><th>Cleaned [cite: 27]</th><th>Remarks [cite: 27]</th></tr>
        <tr><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td></tr>
    </table>

    <h3>D. Cryo Storage [cite: 28]</h3>
    <table>
        <tr><th>Tank ID [cite: 29]</th><th>LN₂ Level [cite: 29]</th><th>Refilled (Y/N) [cite: 29]</th><th>Alarm [cite: 29]</th><th>Remarks [cite: 29]</th></tr>
        <tr><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td><td><input type="text"></td></tr>
    </table>

    <h2>🧪 SECTION 4: Consumables & Media Check [cite: 30]</h2>
    <table>
        <tr><th>Item [cite: 31]</th><th>Status (OK/Low/Expired) [cite: 31]</th><th>Expiry Checked [cite: 31]</th><th>Remarks [cite: 31]</th></tr>
        <tr><td>Culture media [cite: 31]</td><td><input type="text"></td><td><input type="checkbox"></td><td><input type="text"></td></tr>
        <tr><td>Pipettes [cite: 31]</td><td><input type="text"></td><td><input type="checkbox"></td><td><input type="text"></td></tr>
        <tr><td>Dishes [cite: 31]</td><td><input type="text"></td><td><input type="checkbox"></td><td><input type="text"></td></tr>
        <tr><td>Gloves [cite: 31]</td><td><input type="text"></td><td><input type="checkbox"></td><td><input type="text"></td></tr>
    </table>

    <h2>🚨 SECTION 5-a: Deviations / Incidents [cite: 32]</h2>
    <div class="form-group">
        <label>Any parameter out of range? → Yes / No [cite: 33]</label>
        <select><option>No</option><option>Yes</option></select>
    </div>
    <div class="form-group">
        <label>If yes, details: [cite: 34]</label>
        <textarea rows="3"></textarea>
    </div>
    <div class="form-group">
        <label>Corrective Action Taken: [cite: 35]</label>
        <textarea rows="3"></textarea>
    </div>

    <h2>👨‍⚕️ SECTION 5-b: Authorization [cite: 36]</h2>
    <div class="meta-grid">
        <div class="form-group"><label>Technician Name & Signature: [cite: 37]</label><input type="text"></div>
        <div class="form-group"><label>Embryologist Review: [cite: 38]</label><input type="text"></div>
        <div class="form-group"><label>Supervisor Remarks: [cite: 39]</label><input type="text"></div>
    </div>

    <div class="pro-tip">
        <label><strong>🔥 Pro Tip (For Remote Monitoring):</strong> Upload photos for Lab cleaning proof, Incubator readings, or LN₂ levels. [cite: 54, 55, 56, 57, 58]</label><br><br>
        <input type="file" accept="image/*" multiple>
    </div>

    <button class="btn-submit">Submit Daily Report</button>
</div>



<!-- Center Stocks Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Center Stocks List
                <span class="badge pull-right"><?php echo count($center_stocks); ?> items</span>
            </div>
            <div class="panel-body">
                <?php if(!empty($center_stocks)): ?>
                    <div class="table-responsive">
                        <table id="centerStocksTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Center</th>
                                    <th>Medicine</th>
                                    <th>Batch Number</th>
                                    <th>Brand</th>
                                    <th>Vendor</th>
                                    <th>Department</th>
                                    <th>Expiry Date</th>
                                    <th>Expiry Days</th>
                                    <th>Pack Size</th>
                                    <th>Quantity</th>
                                    <th>Vendor Price With gst</th>
                                    <th>Mrp</th>
                                    <th>Status</th>
                                    <?php 
                                         $is_accountant = isset($_SESSION['logged_central_stock_manager']) && !empty($_SESSION['logged_central_stock_manager']);
                                    ?>
                                    <?php if($is_accountant): ?>
                                      <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($center_stocks as $stock): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $stock->center_name; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->medicine_name; ?></strong><br>
                                            <small class="text-muted"><?php echo $stock->medicine_code; ?></small>
                                        </td>
                                        <td><?php echo $stock->batch_number; ?></td>
                                        <td><?php echo $stock->brand_name; ?></td>
                                        <td><?php echo $stock->vendor_name; ?></td>
                                        <td><?php echo $stock->department; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($stock->expiry_date)); ?></td>
                                        <td>
                                            <?php if($stock->expiry_days < 0): ?>
                                                <span class="label label-danger">Expired (<?php echo abs($stock->expiry_days); ?> days)</span>
                                            <?php elseif($stock->expiry_days <= 30): ?>
                                                <span class="label label-warning">Expiring Soon (<?php echo $stock->expiry_days; ?> days)</span>
                                            <?php else: ?>
                                                <span class="label label-success"><?php echo $stock->expiry_days; ?> days</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo isset($stock->pack_size) && $stock->pack_size !== null ? $stock->pack_size : '1'; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->quantity; ?></strong>
                                        </td>
                                        <td>₹<?php echo number_format($stock->purchase_price, 2); ?></td>
                                        <td>₹<?php echo number_format($stock->selling_price, 2); ?></td>
                                        <td>
                                            <?php if($stock->status == 'ACTIVE'): ?>
                                                <span class="label label-success">Active</span>
                                            <?php elseif($stock->status == 'INACTIVE'): ?>
                                                <span class="label label-default">Inactive</span>
                                            <?php elseif($stock->status == 'QUARANTINE'): ?>
                                                <span class="label label-warning">Quarantine</span>
                                            <?php else: ?>
                                                <span class="label label-info"><?php echo $stock->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <?php 
                                         $is_accountant = isset($_SESSION['logged_central_stock_manager']) && !empty($_SESSION['logged_central_stock_manager']);
                                        ?>
                                        <?php if($is_accountant): ?>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-success" onclick="updateCenterStockStatus(<?php echo $stock->id; ?>, 'ACTIVE')">
                                                    <i class="fa fa-check"></i> Activate
                                                </button>
                                                <button type="button" class="btn btn-xs btn-warning" onclick="updateCenterStockStatus(<?php echo $stock->id; ?>, 'INACTIVE')">
                                                    <i class="fa fa-pause"></i> Deactivate
                                                </button>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No center stocks found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if(!empty($center_stocks)): ?>
    $('#centerStocksTable').DataTable({
        "pageLength": 25,
        "order": [[ 6, "asc" ]], // Sort by Expiry Date column (0-based index 6)
        "columnDefs": [
            { "orderable": false, "targets": 12 } // Actions column (0-based index 12)
        ],
        "language": {
            "emptyTable": "No center stocks found",
            "zeroRecords": "No matching center stocks found"
        },
        "responsive": true,
        "autoWidth": false
    });
    <?php endif; ?>
});
function updateCenterStockStatus(stockId, status) {
    if(confirm('Are you sure you want to update the stock status?')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/update_center_stock_status"); ?>',
            type: 'POST',
            data: {
                stock_id: stockId,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Stock status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating stock status.');
            }
        });
    }
}
function deleteCenterStock(stockId) {
    if(confirm('Are you sure you want to delete this center stock? This action cannot be undone.')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/delete_center_stock"); ?>',
            type: 'POST',
            data: {
                stock_id: stockId
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Center stock deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting center stock.');
            }
        });
    }
}
function exportCenterStockReport() {
    var filters = {
        center_id: $('#centerFilter').val(),
        medicine_id: $('#medicineFilter').val(),
        batch_number: $('#batchFilter').val(),
        status: $('#statusFilter').val(),
        department: $('#departmentFilter').val(),
    };
    var queryString = $.param(filters);
    window.open('<?php echo base_url("stocks_new/center_stocks_export"); ?>?' + queryString, '_blank');
}
</script>
