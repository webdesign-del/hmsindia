<style>
/* ===== A4 PAGE DESIGN ===== */
.a4-container {
    width: 210mm;
    min-height: 297mm;
    background: #fff;
    padding: 20mm;
    margin: auto;
    font-family: Arial, sans-serif;
    border: 1px solid #ddd;
}

/* ===== HEADINGS ===== */
.header-section h2 {
    font-weight: bold;
    margin-bottom: 5px;
}

.section-title {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    border-bottom: 2px solid #333;
    padding-bottom: 4px;
}

/* ===== GENERAL STYLES ===== */
.form-label {
    font-weight: bold;
}

textarea {
    resize: none;
}

.checkbox-group .form-check {
    margin-right: 20px;
}

/* ===== PRINT STYLES ===== */
@media print {
    .btn-print, .btn {
        display: none !important;
    }
    body {
        background: white;
    }
    .a4-container {
        border: none;
        margin: 0;
        padding: 0;
    }
}
</style>


<div class="container a4-container">

    <div class="header-section text-center mb-4">
        <h2>INDIA IVF CLINIC</h2>
        <h4>OPD INITIAL ASSESSMENT SHEET</h4>
    </div>

    <form id="opdForm">

        <!-- ================= 1. CENTER & DATE ================= -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Centre:</label>
                <input type="text" class="form-control" name="centre">
            </div>

            <div class="col-md-6">
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>

        <hr>

        <!-- ================= 2. PATIENT DETAILS ================= -->
        <div class="section-title">Patient Details</div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Age</label>
                <input type="number" class="form-control" name="age">
            </div>

            <div class="col-md-4">
                <label class="form-label">Type of Infertility</label>
                <select name="type_of_infertility" class="form-select">
                    <option value="">Select</option>
                    <option value="Primary">Primary</option>
                    <option value="Secondary">Secondary</option>
                </select>
            </div>
        </div>

        <!-- ================= 3. CLINICAL HISTORY ================= -->
        <div class="section-title">Clinical History</div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Menstruation History / LMP</label>
                <input type="text" class="form-control" name="menstruation_history">
            </div>

            <div class="col-md-6">
                <label class="form-label">Past Medical History</label>
                <textarea class="form-control" name="past_medical_history"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Past Surgical History</label>
                <textarea class="form-control" name="past_surgical_history"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fertility Treatments Taken (IVF/IUI)</label>
                <textarea class="form-control" name="past_treatment"></textarea>
            </div>
        </div>

        <!-- ================= 4. INVESTIGATIONS ================= -->
        <div class="section-title">Investigations</div>

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">S. AMH</label>
                <input type="text" class="form-control" name="amh">
            </div>

            <div class="col-md-3">
                <label class="form-label">USG</label>
                <input type="text" class="form-control" name="usg">
            </div>

            <div class="col-md-3">
                <label class="form-label">HSG</label>
                <input type="text" class="form-control" name="hsg">
            </div>

            <div class="col-md-3">
                <label class="form-label">Semen Analysis</label>
                <input type="text" class="form-control" name="semen_analysis">
            </div>

            <div class="col-md-12">
                <label class="form-label">Any Other</label>
                <textarea class="form-control" name="investigations"></textarea>
            </div>
        </div>

        <!-- ================= 5. DIAGNOSIS ================= -->
        <div class="section-title">Diagnosis</div>

        <div class="row mb-3 checkbox-group">
            <div class="form-check form-check-inline">
                <input type="checkbox" name="low_ovarian_reserve" value="1" class="form-check-input">
                <label class="form-check-label">Low Ovarian Reserve</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="checkbox" name="tubal_factor" value="1" class="form-check-input">
                <label class="form-check-label">Tubal Factor</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="checkbox" name="male_factor" value="1" class="form-check-input">
                <label class="form-check-label">Male Factor</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="checkbox" name="female_factor" value="1" class="form-check-input">
                <label class="form-check-label">Female Factor</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="checkbox" name="unexplained_infertility" value="1" class="form-check-input">
                <label class="form-check-label">Unexplained Infertility</label>
            </div>
        </div>

        <!-- ================= 6. MANAGEMENT ================= -->
        <div class="section-title">Management & Advice</div>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Management Advised</label>
                <select class="form-select" name="management_advised">
                    <option selected>Select...</option>
                    <option value="Natural">Natural</option>
                    <option value="Medical">Medical</option>
                    <option value="Surgical">Surgical</option>
                    <option value="IUI">IUI</option>
                    <option value="ART">ART (IVF/ICSI)</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Reason for Advised Management</label>
                <textarea class="form-control" rows="2" name="reason_for_management"></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Advice / Remarks</label>
                <textarea class="form-control" rows="3" name="advice"></textarea>
            </div>
        </div>

        <!-- ================= 7. FOLLOW UP ================= -->
        <div class="row mt-4">
            <div class="col-md-4">
                <label class="form-label">Next Follow Up Date:</label>
                <input type="date" class="form-control" name="next_follow_up">

                <label class="form-label mt-2">At:</label>
                <input type="time" class="form-control" name="followup_time">
            </div>

            <div class="col-md-4">
                <label class="form-label">Keyword:</label>
                <input type="text" class="form-control" name="keyword">
            </div>

            <div class="col-md-4 text-center">
                <div style="border-bottom: 1px solid #000; height: 45px;"></div>
                <label class="form-label">Doctor / Counsellor Signature</label>
            </div>
        </div>

        <div class="text-center mt-5 btn-print">
            <button type="button" class="btn btn-primary btn-lg" onclick="window.print()">Print Sheet</button>
        </div>

    </form>

</div>
