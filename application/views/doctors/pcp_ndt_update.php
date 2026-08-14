<?php $all_method =& get_instance(); ?>
<?php
if (isset($_POST['submit'])) {
    $ID = $this->input->get('ID', true);

    // Securely collect and sanitize form inputs
    $update_data = [
        'wife_name'                    => $this->input->post('wife_name', true),
        'wife_age'                     => $this->input->post('wife_age', true),
        'husband_name'                 => $this->input->post('husband_name', true),
        'wife_address'                 => $this->input->post('wife_address', true),
        'wife_phone'                   => $this->input->post('wife_phone', true),
        'husband_age'                  => $this->input->post('husband_age', true),
        'female_pregnancy_other_p'     => $this->input->post('female_pregnancy_other_p', true),
        'female_pregnancy_other_l'     => $this->input->post('female_pregnancy_other_l', true),
        'female_pregnancy_other_a'     => $this->input->post('female_pregnancy_other_a', true),
        'details_management_advised'   => $this->input->post('details_management_advised', true),
        'IVF_Consultant'               => $this->input->post('IVF_Consultant', true),
        'procedure_done'               => $this->input->post('procedure_done', true),
        'outcome_of_tretment'          => $this->input->post('outcome_of_tretment', true),
        'further_referredfor_dellvery' => $this->input->post('further_referredfor_dellvery', true),
        'outcome_of_pregnancy'         => $this->input->post('outcome_of_pregnancy', true),
        'male'                         => $this->input->post('male', true),
        'female'                       => $this->input->post('female', true),
        'malformation_in_newborn'      => $this->input->post('malformation_in_newborn', true),
        'female_issues'                => $this->input->post('female_issues', true),
        'date_of_discharge'            => $this->input->post('date_of_discharge', true),
        'embryologist'                 => $this->input->post('embryologist', true),
        'center'                       => $this->input->post('center', true),
        
        // NEW FIELDS ADDED HERE
        'adhar_no'                     => $this->input->post('adhar_no', true),
        'genetic_lab_serial_no'        => $this->input->post('genetic_lab_serial_no', true),
        'date_of_sample'               => $this->input->post('date_of_sample', true),
        'test_result_id_no'            => $this->input->post('test_result_id_no', true),
        'remark'                       => $this->input->post('remark', true)
    ];

    // Secure Query Builder Update
    $this->db->where('ID', $ID);
    $query2 = $this->db->update('pcp_ndt', $update_data);

    if ($query2) {
        $_SESSION['MSG'] = "Record has been successfully updated!";
    } else {
        $_SESSION['MSG'] = "Failed to update record.";
    }
}

$ID = $this->input->get('ID', true);
$query = $this->db->get_where('pcp_ndt', ['ID' => $ID]);
$select_result1 = $query->result(); 

foreach ($select_result1 as $res_val) {                         
?>
<div class="ga-pro">
  <div class="card">
    <div class="card-header">
      <h3>PCPNDT Record Details</h3>
      <?php if (isset($_SESSION['MSG'])): ?>
        <div class="alert alert-info">
          <?php echo $_SESSION['MSG']; unset($_SESSION['MSG']); ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="card-body">
      <form action="" method="post">
        
        <!-- SECTION 1: BASIC INFORMATION -->
        <div class="form-section-title">Patient Identification</div>
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label>IIC ID</label>
              <input type="text" class="form-control" name="patient_id" value="<?php echo html_escape($res_val->patient_id); ?>" readonly>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label>Aadhar No.</label>
              <input type="text" class="form-control" name="adhar_no" value="<?php echo html_escape($res_val->adhar_no ?? ''); ?>" placeholder="Enter Aadhar No.">
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label>Wife Name</label>
              <input type="text" class="form-control" name="wife_name" value="<?php echo html_escape($res_val->wife_name); ?>">
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label>Age</label>
              <input type="text" class="form-control" name="wife_age" value="<?php echo html_escape($res_val->wife_age); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="form-group">
              <label>Husband Name</label>
              <input type="text" class="form-control" name="husband_age" value="<?php echo html_escape($res_val->husband_age); ?>">
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label>Age</label>
              <input type="text" class="form-control" name="wife_age" value="<?php echo html_escape($res_val->wife_age); ?>">
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="form-group">
              <label>Wife Phone</label>
              <input type="text" class="form-control" name="wife_phone" value="<?php echo html_escape($res_val->wife_phone); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Address</label>
              <textarea class="form-control" name="wife_address" rows="2"><?php echo html_escape($res_val->wife_address); ?></textarea>
            </div>
          </div>
        </div>

        <!-- SECTION 2: CLINICAL & PARITY DETAILS -->
        <div class="form-section-title">Clinical & Parity Information</div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Parity of Woman (P / L / A)</label>
              <div class="parity-group">
                <input type="text" class="form-control" name="female_pregnancy_other_p" value="<?php echo html_escape($res_val->female_pregnancy_other_p); ?>" placeholder="P">
                <input type="text" class="form-control" name="female_pregnancy_other_l" value="<?php echo html_escape($res_val->female_pregnancy_other_l); ?>" placeholder="L">
                <input type="text" class="form-control" name="female_pregnancy_other_a" value="<?php echo html_escape($res_val->female_pregnancy_other_a); ?>" placeholder="A">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Reason For IVF / ART</label>
              <input type="text" class="form-control" name="details_management_advised" value="<?php echo html_escape($res_val->details_management_advised); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Detail of Referring Dr.</label>
              <input type="text" class="form-control" name="IVF_Consultant" value="<?php echo html_escape($res_val->IVF_Consultant); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Procedure Done</label>
              <textarea class="form-control" name="procedure_done" rows="2"><?php echo html_escape($res_val->procedure_done); ?></textarea>
            </div>
          </div>
        </div>

        <!-- SECTION 3: GENETIC & LAB DETAILS -->
        <div class="form-section-title">Genetic & Lab Details</div>
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="form-group">
              <label>Genetic Lab Serial No.</label>
              <input type="text" class="form-control" name="genetic_lab_serial_no" value="<?php echo html_escape($res_val->genetic_lab_serial_no ?? ''); ?>" placeholder="Lab Serial No.">
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="form-group">
              <label>Date of Sample</label>
              <input type="date" class="form-control" name="date_of_sample" value="<?php echo html_escape($res_val->date_of_sample ?? ''); ?>">
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="form-group">
              <label>Test Result ID No.</label>
              <input type="text" class="form-control" name="test_result_id_no" value="<?php echo html_escape($res_val->test_result_id_no ?? ''); ?>" placeholder="Result ID No.">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Remark</label>
              <textarea class="form-control" name="remark" rows="2" placeholder="Remarks..."><?php echo html_escape($res_val->remark ?? ''); ?></textarea>
            </div>
          </div>
        </div>

        <!-- SECTION 4: OUTCOMES & DISCHARGE -->
        <div class="form-section-title">Outcomes & Management</div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Outcome of The Treatment</label>
              <textarea class="form-control" name="outcome_of_tretment" rows="2"><?php echo html_escape($res_val->outcome_of_tretment); ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Dr. Referred for Delivery / Pregnancy Management</label>
              <textarea class="form-control" name="further_referredfor_dellvery" rows="2"><?php echo html_escape($res_val->further_referredfor_dellvery); ?></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Outcome Of the Pregnancy</label>
              <textarea class="form-control" name="outcome_of_pregnancy" rows="2"><?php echo html_escape($res_val->outcome_of_pregnancy); ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Malformation in Newborn Details</label>
              <textarea class="form-control" name="malformation_in_newborn" rows="2"><?php echo html_escape($res_val->malformation_in_newborn); ?></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Male Child Details</label>
              <textarea class="form-control" name="male" rows="2"><?php echo html_escape($res_val->male); ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Female Child Details</label>
              <textarea class="form-control" name="female" rows="2"><?php echo html_escape($res_val->female); ?></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Female Issues</label>
              <textarea class="form-control" name="female_issues" rows="2"><?php echo html_escape($res_val->female_issues); ?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Date Of Discharge</label>
              <input type="date" class="form-control" name="date_of_discharge" value="<?php echo html_escape($res_val->date_of_discharge); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Center</label>
              <select class="form-control" id="center" name="center">
                <option value=''>--Select Center--</option>
                <?php 
                $all_centers = $all_method->get_all_centers();
                foreach ($all_centers as $key => $val) {
                    $selected = ($res_val->center == $val['center_number']) ? 'selected' : '';
                    echo '<option value="' . html_escape($val['center_number']) . '" ' . $selected . '>' . html_escape($val['center_name']) . '</option>';
                } 
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Embryologist</label>
              <input type="text" class="form-control" name="embryologist" value="<?php echo html_escape($res_val->embryologist); ?>">
            </div>
          </div>
        </div>

        <div class="row style-actions" style="margin-top: 20px;">
          <div class="col-md-12 text-right">
            <button type="submit" name="submit" class="btn btn-primary btn-lg">Update PCPNDT Record</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
<?php } ?>

<!-- MODERN RESPONSIVE STYLES -->
<style>
.ga-pro {
    margin: 20px auto;
    font-family: Arial, sans-serif;
}
.ga-pro .card {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.ga-pro .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 15px 20px;
}
.ga-pro .card-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}
.ga-pro .card-body {
    padding: 20px;
}
.form-section-title {
    font-size: 15px;
    font-weight: bold;
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 5px;
    margin: 20px 0 15px 0;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #444;
    margin-bottom: 5px;
    display: block;
}
.form-control {
    width: 100%;
    height: 38px;
    padding: 6px 12px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
textarea.form-control {
    height: auto !important;
}
.parity-group {
    display: flex;
    gap: 10px;
}
.parity-group input {
    text-align: center;
}
.alert-info {
    margin-top: 10px;
    padding: 10px;
    background-color: #d9edf7;
    border-color: #bce8f1;
    color: #31708f;
    border-radius: 4px;
}
</style>