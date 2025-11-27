<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPD Initial Assessment Sheet - India IVF Clinic</title>
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --background: #ffffff;
            --foreground: #1f2937;
            --muted: #6b7280;
            --muted-foreground: #9ca3af;
            --border: #d1d5db;
            --input: #f9fafb;
            --ring: #3b82f6;
            --radius: 0.75rem;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* ===== RESET & BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: var(--foreground);
            line-height: 1.5;
        }

        /* ===== LAYOUT ===== */
        .container {
            min-height: 100vh;
            width: 100%;
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .container {
                padding: 2rem;
            }
        }

        .main-content {
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* ===== CARD COMPONENT ===== */
        .card {
            background: var(--background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--foreground);
            margin-bottom: 0.25rem;
        }
        label {
            margin-bottom: 10px !important;
        }
        .card-subtitle {
            font-size: 0.875rem;
            color: var(--muted);
        }

        .card-content {
            padding: 1.5rem;
        }

        /* ===== FORM ELEMENTS ===== */
        .form-grid {
            display: grid;
            gap: 1rem;
        }

        .grid-cols-1 {
            grid-template-columns: 1fr;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        @media (max-width: 768px) {
            .grid-cols-2,
            .grid-cols-3,
            .grid-cols-4 {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .label {
            text-align:left;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--foreground);
        }

        .required::after {
            content: " *";
            color: var(--danger);
        }

        .input,
        .textarea,
        .select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            background: var(--input);
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .input:focus,
        .textarea:focus,
        .select:focus {
            outline: none;
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .textarea {
            resize: vertical;
            min-height: 5rem;
        }

        .select {
            display:flex;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* ===== CHECKBOX & RADIO ===== */
        .checkbox-group,
        .radio-group {
           /* display: flex;*/
            flex-direction: column;
            gap: 0.5rem;
        }

        .checkbox-item,
        .radio-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkbox-item:hover,
        .radio-item:hover {
            border-color: var(--ring);
        }

        .checkbox-input,
        .radio-input {
            width: 1rem;
            height: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.25rem;
            cursor: pointer;
        }

        .radio-input {
            border-radius: 50%;
        }

        .checkbox-input:checked,
        .radio-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* ===== BUTTONS ===== */
        .button-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .button-primary {
            background: var(--primary);
            color: white;
        }

        .button-primary:hover {
            background: var(--primary-dark);
        }

        .button-secondary {
            background: var(--secondary);
            color: white;
        }

        .button-outline {
            background: transparent;
            border-color: var(--border);
            color: var(--foreground);
        }

        .button-outline:hover {
            background: var(--input);
        }

        /* ===== SEPARATOR ===== */
        .separator {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 0;
        }

        /* ===== SECTIONS ===== */
        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--foreground);
            margin-bottom: 1rem;
        }

        .subsection-title {
            font-size: 1rem;
            font-weight: 500;
            color: var(--foreground);
            margin: 1rem 0 0.5rem 0;
            padding-left: 0.5rem;
            border-left: 3px solid var(--primary);
        }

        /* ===== UTILITY CLASSES ===== */
        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .text-muted {
            color: var(--muted);
        }

        .text-danger {
            color: var(--danger);
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .space-y-4 > * + * {
            margin-top: 1rem;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .justify-end {
            justify-content: flex-end;
        }

        .w-full {
            width: 100%;
        }

        .hidden {
            display: none;
        }

        @media (min-width: 768px) {
            .md\:flex-row {
                flex-direction: row;
            }
            
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .md\:grid-cols-3 {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .md\:grid-cols-4 {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .md\:col-span-2 {
                grid-column: span 2;
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #000;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeIn 0.35s ease-out;
        }

input:not([type]), input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea, .select{
    background-color: transparent;
    border: none;
    border: 1px solid #9e9e9e;
    border-radius: 0;
    outline: none;
    height: 3rem;
    width: 100%;
    font-size: 1rem;
    margin: 0 0 20px 0;
    padding: 0;
    box-shadow: none;
    box-sizing: content-box;
    transition: all 0.3s;
    font-size: 14px;
    padding: 4px;
}
label.radio-item {
    width: 50%;
    float: right;
    display: flex;
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static;
    left: -9999px;
    opacity: 1;
    margin: 0px;
}
textarea {
    height: 100px !important;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <!-- Header Card -->
            <div class="card animate-in">
                <div class="card-header">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="card-title">Camp Initial Assessment Sheet</h1>
                        </div>
                    </div>
                </div>
                
                <div class="card-content">
                    <form method="post" action="" enctype="multipart/form-data" id="opdForm"  class="form-grid space-y-4">
                        <input type="hidden" name="action" value="add_assessment_form" />
                        <input type="hidden" name="patient_id" id="patient_id" value="<?php $patient_id = $data['paitent_id']; echo $patient_id; ?>">
                        <input type="hidden" name="wife_phone" id="wife_phone" value="<?php echo $data['wife_phone']; ?>">
                        <input type="hidden" name="centre" id="centre" value="<?php echo $data['appoitment_for']; ?>">
                        <input type="hidden" name="camp" id="camp" class="input" value="<?php echo $data['camp_selection']; ?>">
                        <!-- Patient & Visit Information -->
                        <section class="form-grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="label required" for="name">Patient Name</label>
                                <input type="text" name="name" id="name" class="input" placeholder="Patient Name" required>
                                <div class="error-message text-xs text-danger hidden" id="date-error"></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="label required"  for="centre">Spouse Name</label>
                                <input type="text" name="spouse_name" id="spouse_name" class="input" placeholder="Spouse Name" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="label" for="city">City</label>
                                <input type="text" name="city" id="city" class="input" placeholder="City ">
                            </div>
                            
                            <div class="form-group">
                                <label class="label" for="age">Patient Age</label>
                                <input type="number" id="patient_age" name="patient_age" class="input" placeholder="Years" min="0" max="80">
                                <div class="error-message text-xs text-danger hidden" id="age-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="label" for="age">Spouse Age</label>
                                <input type="number" id="spouse_age" name="spouse_age" class="input" placeholder="Years" min="0" max="80">
                                <div class="error-message text-xs text-danger hidden" id="age-error"></div>
                            </div>

                            <div class="form-group">
                                <label class="label required" for="name">Keyword / Case Tag</label>
                                 <select id="keyword" name="keyword" class="select" required>
                                    <option value="">Select Keyword / Case Tag</option>
                                    <option value="Low AMH">Low AMH</option>
                                    <option value="Failed IVF">Failed IVF</option>
                                </select>
                                <div class="error-message text-xs text-danger hidden" id="centre-error"></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="label">Type of Infertility</label>
                                <div class="radio-group grid-cols-2">
                                    <label class="radio-item">
                                        <input type="radio" name="infertilityType" value="Primary" class="radio-input" checked>
                                        Primary
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="infertilityType" value="Secondary" class="radio-input">
                                        Secondary
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group md:col-span-2">
                                <label class="label" for="menstruationHistory">Menstruation History (Female)</label>
                                <input type="text" id="menstruationHistory" name="menstruationHistory" class="input" placeholder="Cycle length, flow, regularity">
                            </div>
                            
                            <div class="form-group">
                                <label class="label" for="lmp">LMP</label>
                                <input type="date" name="lmp" id="lmp" class="input">
                            </div>
                        </section>
                        
                        <div class="separator"></div>
                        
                        <!-- Medical Histories -->
                        <section class="form-grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="label" for="past_medical_history">Past Medical History</label>
                                <textarea id="past_medical_history" name="past_medical_history" class="textarea" placeholder="Diabetes, thyroid, TB, HTN, etc."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="label" for="pastSurgicalHistory">Past Surgical History</label>
                                <textarea id="past_surgical_history" id="past_surgical_history" class="textarea" placeholder="LSCS, laparoscopy, myomectomy, etc."></textarea>
                            </div>
                        </section>
                        
                        <div class="separator"></div>
                        
                        <!-- Investigations -->
                        <section class="form-grid space-y-4">
                            <h3 class="section-title">Investigations</h3>
                            
                          <div class="form-grid grid-cols-2 md:grid-cols-4 gap-2">
    <label class="checkbox-item">
        <input type="checkbox" name="investigation[]" value="HSG" class="checkbox-input">
        HSG
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="investigation[]" value="USG" class="checkbox-input">
        USG
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="investigation[]" value="S. AMH" class="checkbox-input">
        S. AMH
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="investigation[]" value="Semen Analysis" class="checkbox-input">
        Semen Analysis
    </label>
</div>
                            
                            <div class="form-group">
                                <label class="label" for="investigation_other">Any Other</label>
                                <input type="text" name="investigation_other" id="investigation_other" class="input" placeholder="e.g., TSH, PRL, AFC">
                            </div>
                        </section>
                        
                        <div class="separator"></div>
                        
                        <!-- Diagnosis & Management -->
                        <section class="form-grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Diagnosis -->
                            <div class="form-grid space-y-4" style="margin-top:-15px;">
                                <h3 class="section-title">Diagnosis / Factors</h3>
                                
                               <div class="checkbox-group">
    <label class="checkbox-item">
        <input type="checkbox" name="diagnosis[]" value="Low ovarian reserve" class="checkbox-input">
        Low ovarian reserve
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="diagnosis[]" value="Tubal Factor" class="checkbox-input">
        Tubal Factor
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="diagnosis[]" value="Male Factor" class="checkbox-input">
        Male Factor
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="diagnosis[]" value="Female Factor" class="checkbox-input">
        Female Factor
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="diagnosis[]" value="Unexplained infertility" class="checkbox-input">
        Unexplained infertility
    </label>
</div>
                                <div class="form-group">
                                    <label class="label" for="diagnosis-other">Other diagnosis (free text)</label>
                                    <input type="text" id="diagnosis_other" name="diagnosis_other" class="input" placeholder="Add other notes">
                                    <p class="text-xs text-muted">Press Enter to add tag.</p>
                                </div>
                            </div>
                            
                            <!-- Management -->
                            <div class="form-grid space-y-4">
                                <h3 class="section-title">Management Advised</h3>
                                
                                <div class="checkbox-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <label class="checkbox-item">
        <input type="checkbox" name="management[]" value="Natural" class="checkbox-input">
        Natural
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="management[]" value="IUI" class="checkbox-input">
        IUI
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="management[]" value="Medical" class="checkbox-input">
        Medical
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="management[]" value="ART" class="checkbox-input">
        ART
    </label>
    <label class="checkbox-item">
        <input type="checkbox" name="management[]" value="Surgical" class="checkbox-input">
        Surgical
    </label>
</div>
                                
                                <div class="form-group">
                                    <label class="label" for="reason">Reason for advised management</label>
                                    <textarea id="reason" name="reason" class="textarea" placeholder="Clinical reasoning / plan"></textarea>
                                </div>
                            </div>
                        </section>
                        
                        <div class="separator"></div>
                        
                        <!-- Advice & Follow-up -->
                        <section class="form-grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group md:col-span-2">
                                <label class="label" for="advice">Advice</label>
                                <textarea id="advice" name="advice" class="textarea" placeholder="Medications, lifestyle, next steps"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="label">Next Follow-up On</label>
                                <input type="date" id="next_follow_up" name="next_follow_up" class="input mb-2">
                                <input type="time" id="nextFollowUpTime" name="nextFollowUpTime" class="input">
                                <p class="text-xs text-muted">Time optional.</p>
                            </div>
                        </section>
                        
                        <div class="separator"></div>
                        
                        <!-- Sign-off -->
                        <section class="form-grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="label" for="doctorName">Doctor / Counsellor Name</label>
                                <input type="text" id="counsellor_name" name="counsellor_name" class="input" placeholder="Dr. …">
                            </div>
                            
                            <div class="form-group">
                                <label class="label">Signature</label>
                                <div class="input text-muted" style="border-style: dashed; display: flex; align-items: center; justify-content: center; min-height: 3rem;">
                                    Digital signature pad / upload here
                                </div>
                            </div>
                        </section>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <input type="submit" id="submitbutton" class="btn btn-large" value="Submit" />
                        </div>
                    </form>
                </div>
            </div>
            
         
        </div>
    </div>

    <script>
        // Form handling logic
        function handleSubmit(event) {
            event.preventDefault();
            
            // Basic validation
            const errors = {};
            const date = document.getElementById('date').value;
            const centre = document.getElementById('centre').value;
            const name = document.getElementById('name').value;
            const age = document.getElementById('age').value;
            
            if (!date) errors.date = 'Date required';
            if (!centre) errors.centre = 'Centre required';
            if (!name) errors.name = 'Patient name required';
            if (age && (Number(age) < 0 || Number(age) > 80)) errors.age = 'Enter valid age';
            
            // Show errors
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.textContent = errors[field];
                    errorElement.classList.remove('hidden');
                }
            });
            
            // Hide errors for valid fields
            ['date', 'centre', 'name', 'age'].forEach(field => {
                if (!errors[field]) {
                    const errorElement = document.getElementById(`${field}-error`);
                    if (errorElement) errorElement.classList.add('hidden');
                }
            });
            
            // If no errors, proceed
            if (Object.keys(errors).length === 0) {
                alert('Assessment saved successfully! (Demo)');
                updateSummary();
            }
        }
        
        function resetForm() {
            if (!confirm('Clear all fields?')) return;
            
            document.getElementById('opdForm').reset();
            
            // Clear custom checkboxes
            document.querySelectorAll('.checkbox-input, .radio-input').forEach(input => {
                if (input.type === 'checkbox') input.checked = false;
                if (input.type === 'radio' && input.value === 'Primary') input.checked = true;
            });
            
            // Clear error messages
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
            });
            
            updateSummary();
        }
        
        function updateSummary() {
            const name = document.getElementById('name').value || '—';
            const age = document.getElementById('age').value || '—';
            const infertilityType = document.querySelector('input[name="infertilityType"]:checked')?.value || '—';
            
            const diagnosis = Array.from(document.querySelectorAll('input[name="diagnosis"]:checked'))
                .map(cb => cb.value).join(', ') || '—';
                
            const management = Array.from(document.querySelectorAll('input[name="management"]:checked'))
                .map(cb => cb.value).join(', ') || '—';
            
            document.getElementById('summary-patient').textContent = `${name} (${age})`;
            document.getElementById('summary-infertility').textContent = infertilityType;
            document.getElementById('summary-diagnosis').textContent = diagnosis;
            document.getElementById('summary-management').textContent = management;
        }
        
        // Update summary on input changes
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('opdForm');
            form.addEventListener('input', updateSummary);
            updateSummary(); // Initial update
            
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;
        });
    </script>
</body>
</html>