<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    $ID = $_GET['ID'];
    
    $session_user = $this->session->userdata('logged_billing_manager');

    if (!$session_user) {
        echo json_encode(['status' => 'unauthorized', 'message' => 'User session not found.']);
        exit;
    }

    // Update appointment DB record
    $update_data = [
        'wife_name'           => $this->input->post('wife_name', true),
        'husband_name'        => $this->input->post('husband_name', true),
        'wife_phone'          => $this->input->post('wife_phone', true),
        'appoitment_for'      => $this->input->post('appoitment_for', true),
        'appoitmented_doctor' => $this->input->post('appoitmented_doctor', true),
        'appoitmented_date'   => $this->input->post('appoitmented_date', true),
        'appoitmented_slot'   => $this->input->post('appoitmented_slot', true),
        'status'              => $this->input->post('status', true)
    ];

    $appointment_id = $this->input->post('appointment_id', true) ?? $_GET['ID'];
    $ID = $appointment_id;

    $this->db->where('ID', $ID);
    $query2 = $this->db->update('hms_appointments', $update_data);
    $num = (int) $query2;

    // Logging
    file_put_contents('app_data.txt', "\n" . date("d-m-Y H:i:s") . "-------------" . json_encode($update_data) . "\n", FILE_APPEND);

    if ($num > 0) {
        $query = $this->db->query("SELECT * FROM hms_appointments WHERE ID='".$appointment_id."' LIMIT 1");
        $appointment = $query->row();

        if ($appointment) {
            $url = "https://flertility.in/appointment/hms-appointment-status/?accessKey=AKIA3OFKVR3DZWGD7HSGKTER001";

            $data = [
                'patient_id'        => $appointment->paitent_id,
                'appointment_id'    => $appointment_id,
                'appointment_status'=> 'rescheduled',
                'patient_mobile'    => $appointment->wife_phone,
                'appoitment_for'    => $appointment->appoitment_for,
                'hms_username'      => $session_user['username'],
                'hms_employee_id'   => $session_user['employee_number'],
                'hms_employee_name' => $session_user['name'],
            ];
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        // Check if patient exists
        $check_patient = get_patient_by_number($update_data['wife_phone']);
        if (!empty($check_patient)) {
            $check_patient_register = get_patient_detail($check_patient);

            if (!empty($check_patient_register) && $check_patient_register['whats_registers'] == 0) {
                $centre_details = get_centre_details($update_data['appoitment_for']);
                whatsappregister($update_data['wife_phone'], json_encode([
                    "name"   => $update_data['wife_name'],
                    "center" => $centre_details['center_name']
                ]));

                $this->db->update('hms_patients', ['whats_registers' => 1]);
            }
        } else {
            whatsappregister($update_data['wife_phone'], json_encode([
                "name" => $update_data['wife_name']
            ]));
        }

        // WhatsApp appointment confirmation
        $centre_details = get_centre_details($update_data['appoitment_for']);
        whatsappappointment(
            $update_data['wife_phone'],
            $update_data['wife_name'],
            $centre_details['center_name'],
            date("d-m-Y", strtotime($update_data['appoitmented_date'])),
            $update_data['appoitmented_slot'],
            $centre_details['center_location'] ?? ""
        );

        $_SESSION['message'] = "Appointment has been Rescheduled successfully!";
    } else {
        $_SESSION['message'] = "Appointment Status Update failed!";
    } 

    header("Location: https://indiaivf.website/accounts/patient_update?ID=" . urlencode($ID));
    exit;
}

$ID = $_GET['ID'];
$sql1 = "SELECT * FROM hms_appointments WHERE ID='$ID'";
$query = $this->db->query($sql1);
$select_result1 = $query->result(); 

foreach ($select_result1 as $res_val) {      

    // Fetch active doctors matching strictly the assigned center_id (ignoring allowed_centers)
    $current_center_id = $res_val->appoitment_for;
    $current_doctor_id = $res_val->appoitmented_doctor;

    $initial_doctors = [];
    if (!empty($current_center_id)) {
        $prefix = $this->config->item('db_prefix');
        $escaped_center = $this->db->escape_str($current_center_id);
        
        $sql_doc = "SELECT DISTINCT d.ID, d.name 
                    FROM {$prefix}doctors d
                    INNER JOIN {$prefix}centers c 
                        ON (d.center_id = c.ID OR d.center_id = c.center_number)
                    WHERE d.status = '1' 
                      AND c.status = '1'
                      AND (c.center_number = '$escaped_center' OR c.ID = '$escaped_center' OR d.center_id = '$escaped_center')
                    ORDER BY d.name ASC";
        $initial_doctors = $this->db->query($sql_doc)->result();
    }
?>
<div class="page-wrapper">
  <form class="col-sm-12 col-xs-12" action="" enctype='multipart/form-data' method="post">
    <div class="row">
        <div class="col-sm-12 col-xs-12 panel panel-piluku">
          <div class="panel-heading">
            <h3 class="heading">Reschedule Appointments</h3>
          </div>
          
          <?php if(isset($_SESSION['message'])): ?>
            <p style="color: green; font-weight: bold;"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
          <?php endif; ?>

          <div class="panel-body profile-edit">
          
            <!-- ROW 1: Patient Details -->
            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label>Wife Name</label>
                <input placeholder="Wife Name" id="wife_name" value="<?php echo html_escape($res_val->wife_name); ?>" name="wife_name" type="text" class="form-control">
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label>Husband Name</label>
                <input placeholder="Husband Name" name="husband_name" id="husband_name" value="<?php echo html_escape($res_val->husband_name); ?>" type="text" class="form-control">
              </div>
            </div>

            <!-- ROW 2: Center & Doctor Selection -->
            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label>Appointment For (Center)</label>
                <select name="appoitment_for" id="appoitment_for" class="form-control" style="display: block !important;">
                    <option value="">--Select Center--</option>
                    <?php 
                    $sql2 = "SELECT * FROM hms_centers WHERE status='1'";
                    $query = $this->db->query($sql2);
                    $select_result2 = $query->result(); 

                    foreach ($select_result2 as $res_val2) { 
                        $selected = ($res_val2->center_number == $res_val->appoitment_for) ? 'selected="selected"' : '';
                    ?>  
                        <option value="<?php echo $res_val2->center_number; ?>" <?php echo $selected; ?>><?php echo $res_val2->center_name; ?></option>
                    <?php } ?>  
                </select>
              </div>

              <div class="form-group col-sm-6 col-xs-12 appoitmented_doctor">
                <label>Doctor Name</label>
                <select name="appoitmented_doctor" id="appoitmented_doctor" class="form-control" style="display: block !important;">
                    <option value="">--Select Doctor--</option>
                    <?php 
                    if (!empty($initial_doctors)) {
                        foreach ($initial_doctors as $doc) {
                            $doc_selected = ($doc->ID == $current_doctor_id) ? 'selected="selected"' : '';
                            echo '<option value="' . $doc->ID . '" ' . $doc_selected . '>' . html_escape($doc->name) . '</option>';
                        }
                    }
                    ?>
                </select>
              </div>
            </div>

            <!-- ROW 3: Date, Slot & Status -->
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12 appoitmented_date">
                <label>Appointment Date</label>
                <input placeholder="Appointment Date" id="appoitmented_date" value="<?php echo html_escape($res_val->appoitmented_date); ?>" name="appoitmented_date" type="text" class="particular_date_filter form-control">
               </div>
               
               <div class="form-group col-sm-6 col-xs-12 appoitmented_slot">
                <label>Appointment Slot</label>
                <select name="appoitmented_slot" class="empty-field form-control" id="appoitmented_slot" required style="display: block !important;">
                    <option value="<?php echo html_escape($res_val->appoitmented_slot); ?>"><?php echo html_escape($res_val->appoitmented_slot) ?: '--Select Slot--'; ?></option>
                </select>
               </div>

               <div class="form-group col-sm-6 col-xs-12">
                <input type="hidden" name="wife_phone" value="<?php echo html_escape($res_val->wife_phone); ?>" class="empty-field" id="wife_phone" required>
               </div>

               <div class="form-group col-sm-6 col-xs-12">
                <label>Status</label>
                <select name="status" id="status" class="form-control" style="display: block !important;">
                    <option value="">----Select Status---</option>
                    <option value="in_clinic" <?php echo ($res_val->status == 'in_clinic') ? 'selected="selected"' : ''; ?>>In Clinic</option>
                    <option value="cancelled" <?php echo ($res_val->status == 'cancelled') ? 'selected="selected"' : ''; ?>>Cancelled</option>
                    <option value="rescheduled" <?php echo ($res_val->status == 'rescheduled') ? 'selected="selected"' : ''; ?>>Rescheduled</option>
                </select>
               </div>
            </div>
             
            <input type="submit" name="submit" value="SUBMIT" class="btn btn-primary" style="margin-top: 15px;"> 
          </div>  
        </div>
    </div>  
  </form>
</div>
<?php } ?>

<!-- JAVASCRIPT LOGIC -->
<script type="text/javascript">
$(document).ready(function() {

    var saved_doctor = $('#appoitmented_doctor').val();
    var saved_date   = $('#appoitmented_date').val();
    var saved_slot   = "<?php echo $res_val->appoitmented_slot ?? ''; ?>";

    // 1. Function to Fetch Doctor Slots
    function fetch_doctor_slots(bookingDate, doctorId, slotToSelect) {
        if (doctorId != '' && doctorId != null && bookingDate != '' && bookingDate != null) {
            $.ajax({
                url: '<?php echo base_url("Accounts/doctor_slots"); ?>',
                type: 'POST',
                data: { selected: bookingDate, appoitmented_doctor: doctorId },
                success: function(response) {
                    var cleanHtml = response;
                    try { cleanHtml = JSON.parse(response); } catch(e) {}
                    
                    $('#appoitmented_slot').html(cleanHtml);
                    
                    if (slotToSelect != '' && slotToSelect != null) {
                        $('#appoitmented_slot').val(slotToSelect);
                    }
                    $('#appoitmented_slot').trigger('change.select2');
                },
                error: function(xhr, status, error) {
                    console.log("Slots AJAX Error: ", xhr.responseText);
                    $('#appoitmented_slot').html('<option value="">--No slot available--</option>');
                }
            });
        } else {
            $('#appoitmented_slot').html('<option value="">--Select Slot--</option>');
        }
    }

    // 2. Auto Fetch Slots on Page Load
    if (saved_doctor != '' && saved_date != '') {
        fetch_doctor_slots(saved_date, saved_doctor, saved_slot);
    }

    // 3. Datepicker Select Event
    $("#appoitmented_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onSelect: function(dateStr) {
            var selectedDoctor = $('#appoitmented_doctor').val();
            if (selectedDoctor != '' && selectedDoctor != null) {
                fetch_doctor_slots(dateStr, selectedDoctor, null);
            } else {
                alert("Please select a doctor first.");
                $(this).val('');
            }
        }
    });

    // 4. Doctor Dropdown Change Event
    $('#appoitmented_doctor').on("change", function() {
        var docId = $(this).val();
        var dateStr = $('#appoitmented_date').val();
        if (docId != '' && dateStr != '') {
            fetch_doctor_slots(dateStr, docId, null);
        } else {
            $('#appoitmented_slot').html('<option value="">--Select Slot--</option>');
        }
    });

    // 5. Center Dropdown Change Event
    $('#appoitment_for').on("change", function() {
        var centre_id = $(this).val();
        $('#appoitmented_date').val('');
        $('#appoitmented_slot').html('<option value="">--Select Slot--</option>');

        if (centre_id != '' && centre_id != null) {
            $.ajax({
                url: '<?php echo base_url("Accounts/search_doctor"); ?>',
                type: 'POST',
                data: { centre_id: centre_id },
                success: function(response) {
                    var cleanHtml = response;
                    try { cleanHtml = JSON.parse(response); } catch(e) {}
                    $('#appoitmented_doctor').html(cleanHtml).trigger('change.select2');
                }
            });
        } else {
            $('#appoitmented_doctor').html('<option value="">--Select Doctor--</option>');
        }
    });

});
</script>