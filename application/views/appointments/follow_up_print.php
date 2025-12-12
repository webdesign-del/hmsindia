<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow-up Consultation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff;
            color: #333;
        }
        
        .print-header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .print-header h1 {
            margin: 0;
            color: #007bff;
            font-size: 28px;
        }
        
        .print-header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 18px;
            font-weight: normal;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #f8f9fa;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 16px;
            border-left: 5px solid #007bff;
            margin-bottom: 15px;
            color: #333;
        }
        
        .field-row {
            display: flex;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .field-label {
            font-weight: bold;
            width: 200px;
            flex-shrink: 0;
            color: #555;
        }
        
        .field-value {
            flex: 1;
            color: #333;
        }
        
        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        
        .medicine-table th {
            background-color: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
        }
        
        .medicine-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .medicine-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .list-items {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .list-items li {
            margin-bottom: 5px;
            color: #333;
        }
        
        .follow-up-info {
            background-color: #e7f3ff;
            border: 1px solid #007bff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .no-data {
            color: #999;
            font-style: italic;
        }
        
        @media print {
            body { margin: 0; }
            .section { page-break-inside: avoid; }
            .medicine-table { page-break-inside: auto; }
        }
        
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="print-header">
        <h1>Follow-up Consultation Report</h1>
        <h2>Hospital Management System</h2>
    </div>
    
    <!-- Patient Information -->
    <div class="section">
        <div class="section-title">Patient Information</div>
        <div class="field-row">
            <div class="field-label">Patient Name:</div>
            <div class="field-value"><?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : 'N/A'; ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Phone Number:</div>
            <div class="field-value"><?php echo isset($patient_data['wife_phone']) ? $patient_data['wife_phone'] : 'N/A'; ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Email:</div>
            <div class="field-value"><?php echo isset($patient_data['wife_email']) ? $patient_data['wife_email'] : 'N/A'; ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Doctor:</div>
            <div class="field-value"><?php echo isset($appointments['doctor_name']) ? $appointments['doctor_name'] : 'N/A'; ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Appointment Date:</div>
            <div class="field-value"><?php echo isset($appointments['appoitmented_date']) ? date("d-m-Y", strtotime($appointments['appoitmented_date'])) : 'N/A'; ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Center:</div>
            <div class="field-value"><?php echo isset($appointments['center_name']) ? $appointments['center_name'] : 'N/A'; ?></div>
        </div>
    </div>
    
    <!-- Clinical Findings -->
    <div class="section">
        <div class="section-title">Clinical Findings</div>
        <div class="field-row">
            <div class="field-label">Female Findings:</div>
            <div class="field-value"><?php echo isset($patient_doctor_consultation['female_findings']) ? $patient_doctor_consultation['female_findings'] : 'N/A'; ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Male Findings:</div>
            <div class="field-value"><?php echo isset($patient_doctor_consultation['male_findings']) ? $patient_doctor_consultation['male_findings'] : 'N/A'; ?></div>
        </div>
    </div>
    
    <!-- Investigations -->
    <?php if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == 1): ?>
    <div class="section">
        <div class="section-title">Investigations Recommended</div>
        
        <?php if(!empty($patient_doctor_consultation['female_minvestigation_suggestion_list'])): ?>
        <div class="field-row">
            <div class="field-label">Female Investigations:</div>
            <div class="field-value">
                <div class="list-items">
                    <?php 
                    $female_investigations = unserialize($patient_doctor_consultation['female_minvestigation_suggestion_list']);
                    if(is_array($female_investigations)) {
                        foreach($female_investigations as $investigation) {
                            echo '<li>' . htmlspecialchars($investigation) . '</li>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($patient_doctor_consultation['male_minvestigation_suggestion_list'])): ?>
        <div class="field-row">
            <div class="field-label">Male Investigations:</div>
            <div class="field-value">
                <div class="list-items">
                    <?php 
                    $male_investigations = unserialize($patient_doctor_consultation['male_minvestigation_suggestion_list']);
                    if(is_array($male_investigations)) {
                        foreach($male_investigations as $investigation) {
                            echo '<li>' . htmlspecialchars($investigation) . '</li>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- OPD Medicines -->
    <?php if(isset($patient_doctor_consultation['medicine_suggestion']) && $patient_doctor_consultation['medicine_suggestion'] == 1): ?>
    <div class="section">
        <div class="section-title">OPD Medicines</div>
        
        <?php if(!empty($patient_doctor_consultation['female_medicine_suggestion_list'])): ?>
        <div class="field-row">
            <div class="field-label">Female Medicines:</div>
            <div class="field-value">
                <?php 
                $female_medicines = unserialize($patient_doctor_consultation['female_medicine_suggestion_list']);
                if(is_array($female_medicines) && !empty($female_medicines)) {
                    echo '<table class="medicine-table">';
                    echo '<thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Route</th><th>Remarks</th></tr></thead>';
                    echo '<tbody>';
                    foreach($female_medicines as $medicine) {
                        if(is_array($medicine)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_name'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_dosage'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_frequency'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_days'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_route'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_remarks'] ?? 'N/A') . '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<span class="no-data">No medicines prescribed</span>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($patient_doctor_consultation['male_medicine_suggestion_list'])): ?>
        <div class="field-row">
            <div class="field-label">Male Medicines:</div>
            <div class="field-value">
                <?php 
                $male_medicines = unserialize($patient_doctor_consultation['male_medicine_suggestion_list']);
                if(is_array($male_medicines) && !empty($male_medicines)) {
                    echo '<table class="medicine-table">';
                    echo '<thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Route</th><th>Remarks</th></tr></thead>';
                    echo '<tbody>';
                    foreach($male_medicines as $medicine) {
                        if(is_array($medicine)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_name'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_dosage'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_frequency'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_days'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_route'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_remarks'] ?? 'N/A') . '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<span class="no-data">No medicines prescribed</span>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- IPD Medicines -->
    <!-- <?php if(isset($patient_doctor_consultation['medicine_suggestion_ipd']) && $patient_doctor_consultation['medicine_suggestion_ipd'] == 1): ?>
    <div class="section">
        <div class="section-title">IPD Medicines</div>
        
        <?php if(!empty($patient_doctor_consultation['female_medicine_suggestion_list_ipd'])): ?>
        <div class="field-row">
            <div class="field-label">Female Medicines:</div>
            <div class="field-value">
                <?php 
                $female_medicines_ipd = unserialize($patient_doctor_consultation['female_medicine_suggestion_list_ipd']);
                if(is_array($female_medicines_ipd) && !empty($female_medicines_ipd)) {
                    echo '<table class="medicine-table">';
                    echo '<thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Route</th><th>Remarks</th></tr></thead>';
                    echo '<tbody>';
                    foreach($female_medicines_ipd as $medicine) {
                        if(is_array($medicine)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_name'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_dosage'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_frequency'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_days'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_route'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['female_medicine_remarks'] ?? 'N/A') . '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<span class="no-data">No medicines prescribed</span>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($patient_doctor_consultation['male_medicine_suggestion_list_ipd'])): ?>
        <div class="field-row">
            <div class="field-label">Male Medicines:</div>
            <div class="field-value">
                <?php 
                $male_medicines_ipd = unserialize($patient_doctor_consultation['male_medicine_suggestion_list_ipd']);
                if(is_array($male_medicines_ipd) && !empty($male_medicines_ipd)) {
                    echo '<table class="medicine-table">';
                    echo '<thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Route</th><th>Remarks</th></tr></thead>';
                    echo '<tbody>';
                    foreach($male_medicines_ipd as $medicine) {
                        if(is_array($medicine)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_name'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_dosage'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_frequency'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_days'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_route'] ?? 'N/A') . '</td>';
                            echo '<td>' . htmlspecialchars($medicine['male_medicine_remarks'] ?? 'N/A') . '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<span class="no-data">No medicines prescribed</span>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?> -->
    
    <!-- Procedures -->
    <?php if(isset($patient_doctor_consultation['procedure_suggestion']) && $patient_doctor_consultation['procedure_suggestion'] == 1): ?>
    <div class="section">
        <div class="section-title">Procedures Recommended</div>
        <div class="field-row">
            <div class="field-label">Procedures:</div>
            <div class="field-value">
                <?php 
                if(!empty($patient_doctor_consultation['sub_procedure_suggestion_list'])) {
                    $procedures = unserialize($patient_doctor_consultation['sub_procedure_suggestion_list']);
                    if(is_array($procedures)) {
                        echo '<div class="list-items">';
                        foreach($procedures as $procedure) {
                            echo '<li>' . htmlspecialchars($procedure) . '</li>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<span class="no-data">No procedures recommended</span>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Packages -->
    <?php if(isset($patient_doctor_consultation['package_suggestion']) && $patient_doctor_consultation['package_suggestion'] == 1): ?>
    <div class="section">
        <div class="section-title">Packages Recommended</div>
        <div class="field-row">
            <div class="field-label">Packages:</div>
            <div class="field-value">
                <?php 
                if(!empty($patient_doctor_consultation['package_suggestion_list'])) {
                    $packages = unserialize($patient_doctor_consultation['package_suggestion_list']);
                    if(is_array($packages)) {
                        echo '<div class="list-items">';
                        foreach($packages as $package) {
                            echo '<li>' . htmlspecialchars($package) . '</li>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<span class="no-data">No packages recommended</span>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Follow-up Appointment -->
    <?php if(isset($patient_doctor_consultation['follow_up']) && $patient_doctor_consultation['follow_up'] == 1): ?>
    <div class="section">
        <div class="section-title">Follow-up Appointment</div>
        <div class="follow-up-info">
            <div class="field-row">
                <div class="field-label">Follow-up Date:</div>
                <div class="field-value"><?php echo isset($patient_doctor_consultation['follow_up_date']) ? date("d-m-Y", strtotime($patient_doctor_consultation['follow_up_date'])) : 'N/A'; ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">Follow-up Time:</div>
                <div class="field-value"><?php echo isset($patient_doctor_consultation['follow_slot']) ? $patient_doctor_consultation['follow_slot'] : 'N/A'; ?></div>
            </div>
            <div class="field-row">
                <div class="field-label">Purpose:</div>
                <div class="field-value"><?php echo isset($patient_doctor_consultation['follow_up_purpose']) ? $patient_doctor_consultation['follow_up_purpose'] : 'N/A'; ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        <p><strong>Generated on:</strong> <?php echo date('d-m-Y H:i:s'); ?></p>
        <p>This is a computer-generated consultation report.</p>
        <p>For any queries, please contact the hospital administration.</p>
    </div>
    
    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
