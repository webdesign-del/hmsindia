<style type="text/css">
    /* Custom Styling for Better UI */
    .panel-piluku {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.07);
        overflow: hidden;
        background: #fff;
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #3e73b9 0%, #20417e 100%);
        color: white;
        padding: 20px 25px;
        border: none;
    }

    .form-label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        color: #555;
    }

    .rounded-3 {
        border-radius: 8px !important;
        border: 1px solid #e1e5eb;
        transition: all 0.3s;
    }

    .rounded-3:focus {
        border-color: #3e73b9;
        box-shadow: 0 0 0 3px rgba(62, 115, 185, 0.1);
    }

    .border-danger.rounded-3 {
        border: 1px solid #d45276 !important;
        background-color: #fffafa;
    }

    .btn-save {
        background: #3e73b9;
        border: none;
        transition: 0.3s;
    }

    .btn-save:hover {
        background: #20417e;
        transform: translateY(-2px);
    }

    .section-divider {
        border-bottom: 1px solid #f0f0f0;
        margin: 20px 0;
        padding-bottom: 5px;
        color: #999;
        font-size: 11px;
        font-weight: bold;
    }
</style>

<form class="col-sm-12 col-xs-12" method="post">
    <div class="row">
        <div class="col-sm-12 col-sm-offset col-xs-12 panel panel-piluku">
            
            <div class="card-header card-header-custom">
                <h4 class="mb-0">
                    <i class="fa fa-edit mr-2"></i> <?= isset($procedure) ? 'Edit' : 'Add' ?> Procedure Pricing
                </h4>
                <p class="mb-0 small" style="opacity: 0.8;">Manage billing limits and standard rates</p>
            </div>

            <div class="panel-body profile-edit">
                <div class="row">
                    
                    <div class="col-md-12"><div class="section-divider">PROCEDURE INFORMATION</div></div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Procedure ID</label>
                        <input type="text" name="procedure_id" 
                            class="form-control rounded-3" 
                            placeholder="Enter ID"
                            value="<?= $procedure['procedure_id'] ?? ''; ?>" required readonly>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Procedure Code</label>
                        <input type="text" name="code" 
                            class="form-control rounded-3" 
                            placeholder="e.g. IVF-001"
                            value="<?= $procedure['code'] ?? ''; ?>" readonly>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Full Procedure Name</label>
                        <input type="text" name="procedure_name" 
                            class="form-control rounded-3" 
                            placeholder="Enter Name"
                            value="<?= $procedure['procedure_name'] ?? ''; ?>" required readonly>
                    </div>

                    <div class="col-md-12"><div class="section-divider">PRICING CONFIGURATION</div></div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-danger">
                            Minimum Price (Floor)
                        </label>
                        <div class="input-group">
                            <span class="input-group-addon" style="background: #fdf2f2; border-color: #d45276; color: #d45276;">₹</span>
                            <input type="number" step="0.01" name="min_price" 
                                class="form-control rounded-3 border-danger" 
                                placeholder="0.00"
                                value="<?= $procedure['min_price'] ?? ''; ?>">
                        </div>
                        <small class="text-danger" style="font-size: 11px;">⚠️ System will alert if billed below this.</small>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold text-success">Actual Price (Standard)</label>
                        <div class="input-group">
                            <span class="input-group-addon">₹</span>
                            <input type="number" step="0.01" name="actual_price" 
                                class="form-control rounded-3" 
                                placeholder="0.00"
                                value="<?= $procedure['actual_price'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Billing Visibility Status</label>
                        <select name="status" class="form-control rounded-3">
                            <option value="1" <?= (isset($procedure) && $procedure['status']==1) ? 'selected' : ''; ?>>✅ Active (Visible in Billing)</option>
                            <option value="0" <?= (isset($procedure) && $procedure['status']==0) ? 'selected' : ''; ?>>❌ Inactive (Hidden)</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-4 mb-4">
                    <div class="col-md-12 d-flex justify-content-between">
                        <hr>
                        <a href="<?= base_url('procedures'); ?>" class="btn btn-default px-4 rounded-pill">
                            <i class="fa fa-times mr-1"></i> Cancel
                        </a>

                        <button type="submit" class="btn btn-save btn-success px-5 rounded-pill shadow">
                            <i class="fa fa-check-circle mr-1"></i> Save Configuration
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>