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
            margin-bottom: 10px;
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
            background: #fff;
            display: block;
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
        [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
            position: absolute;
            left: unset;
            opacity: 1;
        }
    </style>

<div class="container">
    <h1>✅ IVF Lab Daily Monitoring Format (Andrology + Embryology Labs)</h1>

    <form action="<?php echo base_url('stocks_new/save_ivf_daily_report'); ?>" method="post" enctype="multipart/form-data">

    <div class="meta-grid">
        <div class="form-group">
            <label>Lab Name:</label>
            <input type="text" name="lab_name" required placeholder="Enter Lab Name">
        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="report_date" required value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
            <label>Shift:</label>
            <select name="shift">
                <option value="Morning">Morning</option>
                <option value="Afternoon">Afternoon</option>
                <option value="Evening">Evening</option>
            </select>
        </div>
        <div class="form-group">
            <label>Reported By:</label>
            <input type="text" name="reported_by" required placeholder="Your Name">
        </div>
    </div>

    <h2>A. Surface Cleaning</h2>
    <table>
        <tr>
            <th>Area</th>
            <th>Done ( ✔ / ✘ )</th>
            <th>Time</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>box incubator- forma 160i</td>
            <td><input type="checkbox" name="sc[box_incubator][done]" value="1"></td>
            <td><input type="time" name="sc[box_incubator][time]"></td>
            <td><input type="text" name="sc[box_incubator][remarks]"></td>
        </tr>
        <tr>
            <td>planner -BT37- tri gas</td>
            <td><input type="checkbox" name="sc[planner][done]" value="1"></td>
            <td><input type="time" name="sc[planner][time]"></td>
            <td><input type="text" name="sc[planner][remarks]"></td>
        </tr>
        <tr>
            <td>spovum LAF with display -embryology</td>
            <td><input type="checkbox" name="sc[spovum_laf][done]" value="1"></td>
            <td><input type="time" name="sc[spovum_laf][time]"></td>
            <td><input type="text" name="sc[spovum_laf][remarks]"></td>
        </tr>
        <tr>
            <td>narishigae micromanipulator with olympus (ICSI machine)</td>
            <td><input type="checkbox" name="sc[icsi_machine][done]" value="1"></td>
            <td><input type="time" name="sc[icsi_machine][time]"></td>
            <td><input type="text" name="sc[icsi_machine][remarks]"></td>
        </tr>
        <tr>
            <td>ICSI machine- display monitor with recording</td>
            <td><input type="checkbox" name="sc[icsi_display][done]" value="1"></td>
            <td><input type="time" name="sc[icsi_display][time]"></td>
            <td><input type="text" name="sc[icsi_display][remarks]"></td>
        </tr>
        <tr>
            <td>positive pressure tower</td>
            <td><input type="checkbox" name="sc[pressure_tower][done]" value="1"></td>
            <td><input type="time" name="sc[pressure_tower][time]"></td>
            <td><input type="text" name="sc[pressure_tower][remarks]"></td>
        </tr>
        <tr>
            <td>digital aspirtion pump- ASPIRE-HP-100D</td>
            <td><input type="checkbox" name="sc[aspiration_pump][done]" value="1"></td>
            <td><input type="time" name="sc[aspiration_pump][time]"></td>
            <td><input type="text" name="sc[aspiration_pump][remarks]"></td>
        </tr>
    </table>

    <h3>B. Disinfection</h3>
    <table>
        <tr>
            <th>Item</th>
            <th>Agent Used</th>
            <th>Done ( ✔ / ✘ )</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>Floor mopping</td>
            <td><input type="text" name="disinfection[floor][agent]" placeholder="e.g. IPA / H2O2"></td>
            <td><input type="checkbox" name="disinfection[floor][done]" value="1"></td>
            <td><input type="text" name="disinfection[floor][remarks]"></td>
        </tr>
        <tr>
            <td>Laminar airflow UV (if applicable)</td>
            <td><input type="text" name="disinfection[laf_uv][agent]"></td>
            <td><input type="checkbox" name="disinfection[laf_uv][done]" value="1"></td>
            <td><input type="text" name="disinfection[laf_uv][remarks]"></td>
        </tr>
        <tr>
            <td>Door handles & switches</td>
            <td><input type="text" name="disinfection[handles][agent]"></td>
            <td><input type="checkbox" name="disinfection[handles][done]" value="1"></td>
            <td><input type="text" name="disinfection[handles][remarks]"></td>
        </tr>
    </table>

    <h3>C. Weekly/Rotational Tasks (mark if done today)</h3>
    <div class="checkbox-group"><label><input type="checkbox" name="weekly[incubator_cleaning]" value="1"> &nbsp;&nbsp;&nbsp;&nbsp; Incubator internal cleaning</label></div>
    <div class="checkbox-group"><label><input type="checkbox" name="weekly[hepa_check]" value="1"> &nbsp;&nbsp;&nbsp;&nbsp; HEPA filter check</label></div>
    <div class="checkbox-group"><label><input type="checkbox" name="weekly[deep_cleaning]" value="1"> &nbsp;&nbsp;&nbsp;&nbsp; Deep cleaning (walls/storage)</label></div>

    <h2>🌡️ SECTION 2: Lab Environmental Parameters</h2>
    
    <h3>A. IVF Lab Conditions</h3>
    <table>
        <tr>
            <th>Parameter</th><th>Morning</th><th>Afternoon</th><th>Evening</th><th>Acceptable Range</th><th>Remarks</th>
        </tr>
        <tr>
            <td>Temperature (°C)</td>
            <td><input type="text" name="env[ivf_temp][morn]"></td><td><input type="text" name="env[ivf_temp][aft]"></td><td><input type="text" name="env[ivf_temp][eve]"></td>
            <td>22–25°C</td>
            <td><input type="text" name="env[ivf_temp][remarks]"></td>
        </tr>
        <tr>
            <td>Humidity (%)</td>
            <td><input type="text" name="env[ivf_humidity][morn]"></td><td><input type="text" name="env[ivf_humidity][aft]"></td><td><input type="text" name="env[ivf_humidity][eve]"></td>
            <td>40–60%</td>
            <td><input type="text" name="env[ivf_humidity][remarks]"></td>
        </tr>
        <tr>
            <td>CO₂ (%)</td>
            <td><input type="text" name="env[ivf_co2][morn]"></td><td><input type="text" name="env[ivf_co2][aft]"></td><td><input type="text" name="env[ivf_co2][eve]"></td>
            <td>5–6%</td>
            <td><input type="text" name="env[ivf_co2][remarks]"></td>
        </tr>
        <tr>
            <td>VOC Level (if monitored)</td>
            <td><input type="text" name="env[ivf_voc][morn]"></td><td><input type="text" name="env[ivf_voc][aft]"></td><td><input type="text" name="env[ivf_voc][eve]"></td>
            <td>Low/Acceptable</td>
            <td><input type="text" name="env[ivf_voc][remarks]"></td>
        </tr>
        <tr>
            <td>Air Pressure</td>
            <td><input type="text" name="env[ivf_pressure][morn]"></td><td><input type="text" name="env[ivf_pressure][aft]"></td><td><input type="text" name="env[ivf_pressure][eve]"></td>
            <td>Positive</td>
            <td><input type="text" name="env[ivf_pressure][remarks]"></td>
        </tr>
    </table>

    <h3>B. Andrology Lab Conditions</h3>
    <table>
        <tr>
            <th>Parameter</th><th>Reading</th><th>Acceptable Range</th><th>Remarks</th>
        </tr>
        <tr>
            <td>Temperature</td>
            <td><input type="text" name="env[andro_temp][val]"></td>
            <td>22–25°C</td>
            <td><input type="text" name="env[andro_temp][remarks]"></td>
        </tr>
        <tr>
            <td>Humidity</td>
            <td><input type="text" name="env[andro_humidity][val]"></td>
            <td>40–60%</td>
            <td><input type="text" name="env[andro_humidity][remarks]"></td>
        </tr>
    </table>

    <h2>⚙️ SECTION 3: Equipment Status</h2>
    
    <h3>A. Incubators</h3>
    <table>
        <tr><th>Equipment ID</th><th>Temp (°C)</th><th>CO₂ (%)</th><th>Alarm Status</th><th>Water Level</th><th>Remarks</th></tr>
        <tr>
            <td><input type="text" name="eq[incubator][id]"></td>
            <td><input type="text" name="eq[incubator][temp]"></td>
            <td><input type="text" name="eq[incubator][co2]"></td>
            <td><input type="text" name="eq[incubator][alarm]"></td>
            <td><input type="text" name="eq[incubator][water]"></td>
            <td><input type="text" name="eq[incubator][remarks]"></td>
        </tr>
    </table>

    <h3>B. Laminar Air Flow / Workstations</h3>
    <table>
        <tr><th>Unit ID</th><th>UV Working</th><th>Airflow OK</th><th>Last Cleaning</th><th>Remarks</th></tr>
        <tr>
            <td><input type="text" name="eq[laf][id]"></td>
            <td><input type="text" name="eq[laf][uv]"></td>
            <td><input type="text" name="eq[laf][airflow]"></td>
            <td><input type="text" name="eq[laf][last_clean]"></td>
            <td><input type="text" name="eq[laf][remarks]"></td>
        </tr>
    </table>

    <h3>C. Microscopes</h3>
    <table>
        <tr><th>ID</th><th>Working Condition</th><th>Cleaned</th><th>Remarks</th></tr>
        <tr>
            <td><input type="text" name="eq[microscope][id]"></td>
            <td><input type="text" name="eq[microscope][condition]"></td>
            <td><input type="text" name="eq[microscope][cleaned]"></td>
            <td><input type="text" name="eq[microscope][remarks]"></td>
        </tr>
    </table>

    <h3>D. Cryo Storage</h3>
    <table>
        <tr><th>Tank ID</th><th>LN₂ Level</th><th>Refilled (Y/N)</th><th>Alarm</th><th>Remarks</th></tr>
        <tr>
            <td><input type="text" name="eq[cryo][id]"></td>
            <td><input type="text" name="eq[cryo][ln2]"></td>
            <td><input type="text" name="eq[cryo][refilled]"></td>
            <td><input type="text" name="eq[cryo][alarm]"></td>
            <td><input type="text" name="eq[cryo][remarks]"></td>
        </tr>
    </table>

    <h2>🧪 SECTION 4: Consumables & Media Check</h2>
    <table>
        <tr><th>Item</th><th>Status (OK/Low/Expired)</th><th>Expiry Checked</th><th>Remarks</th></tr>
        <tr>
            <td>Culture media</td>
            <td><input type="text" name="consumables[media][status]"></td>
            <td><input type="checkbox" name="consumables[media][expiry]" value="1"></td>
            <td><input type="text" name="consumables[media][remarks]"></td>
        </tr>
        <tr>
            <td>Pipettes</td>
            <td><input type="text" name="consumables[pipettes][status]"></td>
            <td><input type="checkbox" name="consumables[pipettes][expiry]" value="1"></td>
            <td><input type="text" name="consumables[pipettes][remarks]"></td>
        </tr>
        <tr>
            <td>Dishes</td>
            <td><input type="text" name="consumables[dishes][status]"></td>
            <td><input type="checkbox" name="consumables[dishes][expiry]" value="1"></td>
            <td><input type="text" name="consumables[dishes][remarks]"></td>
        </tr>
        <tr>
            <td>Gloves</td>
            <td><input type="text" name="consumables[gloves][status]"></td>
            <td><input type="checkbox" name="consumables[gloves][expiry]" value="1"></td>
            <td><input type="text" name="consumables[gloves][remarks]"></td>
        </tr>
    </table>

    <h2>📦 SECTION 4-b: Active Center Stocks Status</h2>
    <?php if(!empty($center_stocks)): ?>
        <div class="table-responsive">
            <table id="centerStocksTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr style="background-color: var(--primary-color); color: #fff;">
                        <th>Center</th>
                        <th>Medicine</th>
                        <th>Batch Number</th>
                        <th>Department</th>
                        <th>Expiry Date</th>
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
                            <td><?php echo $stock->department; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($stock->expiry_date)); ?></td>
                            <td>
                                <strong><?php echo isset($stock->pack_size) && $stock->pack_size !== null ? $stock->pack_size : '1'; ?></strong>
                            </td>
                            <td>
                                <strong style="color: #27ae60;"><?php echo $stock->quantity; ?></strong>
                            </td>
                            <td>₹<?php echo number_format($stock->purchase_price, 2); ?></td>
                            <td>₹<?php echo number_format($stock->selling_price, 2); ?></td>
                            <td>
                                <?php if($stock->status == 'ACTIVE'): ?>
                                    <span class="label label-success" style="background-color: #2ecc71; padding: 2px 6px; color: #fff; border-radius: 3px;">Active</span>
                                <?php elseif($stock->status == 'INACTIVE'): ?>
                                    <span class="label label-default" style="background-color: #95a5a6; padding: 2px 6px; color: #fff; border-radius: 3px;">Inactive</span>
                                <?php elseif($stock->status == 'QUARANTINE'): ?>
                                    <span class="label label-warning" style="background-color: #f1c40f; padding: 2px 6px; color: #fff; border-radius: 3px;">Quarantine</span>
                                <?php else: ?>
                                    <span class="label label-info" style="background-color: #3498db; padding: 2px 6px; color: #fff; border-radius: 3px;"><?php echo $stock->status; ?></span>
                                <?php endif; ?>
                            </td>
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
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <i class="fa fa-info-circle"></i> No center stocks found matching your criteria.
        </div>
    <?php endif; ?>
    <h2>🚨 SECTION 5-a: Deviations / Incidents</h2>
    <div class="form-group">
        <label>Any parameter out of range? → Yes / No</label>
        <select name="deviation_out_of_range">
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
    </div>
    <div class="form-group">
        <label>If yes, details:</label>
        <textarea rows="3" name="deviation_details"></textarea>
    </div>
    <div class="form-group">
        <label>Corrective Action Taken:</label>
        <textarea rows="3" name="deviation_action"></textarea>
    </div>

    <h2>👨‍⚕️ SECTION 5-b: Authorization</h2>
    <div class="meta-grid">
        <div class="form-group"><label>Technician Name & Signature:</label><input type="text" name="auth_technician"></div>
        <div class="form-group"><label>Embryologist Review:</label><input type="text" name="auth_embryologist"></div>
        <div class="form-group"><label>Supervisor Remarks:</label><input type="text" name="auth_supervisor"></div>
    </div>

    <input type="submit" name="submit_report" value="Submit Daily Report" class="btn-submit">
    </form> 
</div>

