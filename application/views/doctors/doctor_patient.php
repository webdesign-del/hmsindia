 <?php $all_method = &get_instance(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Page Header -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-calendar"></i> OPD Register
                    </h3>
                </div>
				<div class="panel-body px-6" style="margin-left: 10px; margin-right: 1px;">
					<form action="<?php echo base_url().'doctors/doctor_patient'; ?>" method="get" class="form-horizontal">
						<div class="row">
							<!-- Doctor Filter -->
							<div class="col-md-4">
								<div class="form-group">
									<label for="doctor" class="control-label">Filter by Doctor</label>
									<select class="form-control" id="doctor" name="doctor">
										<option value="">--Select Doctor--</option>
										<?php 
										$doctor_list = $all_method->center_doctors(); 
										foreach($doctor_list as $key => $vals){ ?>
											<option value="<?php echo $vals['ID']; ?>" 
												<?php echo (isset($_GET['doctor']) && $_GET['doctor'] == $vals['ID']) ? 'selected' : ''; ?>>
												<?php echo $vals['name']; ?>
											</option>
										<?php } ?>                    
									</select>
								</div>
							</div>

							<!-- Start Date -->
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date" class="control-label">Start Date</label>
									<input type="text" class="particular_date_filter form-control" 
										id="start_date" name="start_date" 
										value="<?php echo $start_date;?>" placeholder="YYYY-MM-DD" />
								</div>
							</div>

							<!-- End Date -->
							<div class="col-md-4">
								<div class="form-group">
									<label for="end_date" class="control-label">End Date</label>
									<input type="text" class="particular_date_filter form-control" 
										id="end_date" name="end_date" 
										value="<?php echo $end_date;?>" placeholder="YYYY-MM-DD" />
								</div>
							</div>
						</div>

						<div class="row">
							<!-- Patient ID -->
							<div class="col-md-4 gap-2">
								<div class="form-group">
									<label for="patient_id" class="control-label">Patient ID</label>
									<input type="text" id="patient_id" name="patient_id" 
										value="<?php echo $patient_id;?>" class="form-control" />
								</div>
							</div>  

							<!-- Action Buttons -->
							<div class="col-md-6 float-right" style="float: right;">
								<div class="form-group">
									<label class="control-label">&nbsp;</label>
									<div>
										<button name="search" type="submit" class="btn btn-primary">
											<i class="fa fa-search"></i> Search
										</button>
										<a href="<?php echo base_url().'doctors/doctor_patient'; ?>" class="btn btn-default">
											<i class="fa fa-refresh"></i> Reset
										</a>
										<button type="button" class="btn btn-success" onclick="printDiv2();">
											<i class="fa fa-print"></i> Print
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>

            </div>
            
            <!-- Results Table -->
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 0;">
                    <div id="msg_area" class="alert alert-danger" style="display: none;"></div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-condensed" id="doctor_appointments1">
                            <thead class="thead-inverse">
                                <tr>
                                    <th class="text-center" style="width: 50px;">S.No</th>
                                    <th class="text-center" style="width: 150px;">Actions</th>
                                    <th class="text-center" style="width: 100px;">Date</th>
                                    <th class="text-center" style="width: 80px;">CRM ID</th>
                                    <th class="text-center" style="width: 100px;">Phone</th>
                                    <th class="text-center" style="width: 100px;">Appt ID</th>
                                    <th class="text-center" style="width: 80px;">IIC ID</th>
                                    <th class="text-center" style="width: 150px;">Patient Name</th>
                                    <th class="text-center" style="width: 60px;">Age</th>
                                    <th class="text-center" style="width: 120px;">Doctor</th>
                                    <th class="text-center" style="width: 120px;">Purpose</th>
                                    <th class="text-center" style="width: 150px;">Male Investigation</th>
                                    <th class="text-center" style="width: 150px;">Female Investigation</th>
                                    <th class="text-center" style="width: 120px;">Procedure</th>
                                    <th class="text-center" style="width: 120px;">Male Medicine</th>
                                    <th class="text-center" style="width: 120px;">Female Medicine</th>
                                    <th class="text-center" style="width: 120px;">Next Follow Up</th>
                                    <th class="text-center" style="width: 120px;">Package Name</th>
                                    <th class="text-center" style="width: 80px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Counselor</th>
                                </tr>
                            </thead>
                            <tbody id="appointment_body">
                            <?php $count = 1; foreach($app_result as $ky => $vl){ ?>
                                <tr>
                                    <td class="text-center"><?php echo $count; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group-vertical btn-group-xs">
                                            <?php if($_SESSION['logged_counselor']){ ?>
                                                <?php if ($vl['procedure_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'billings/forma_invoice/'; ?><?php echo $vl['ID']; ?>" class="btn btn-primary btn-xs" target="_blank" title="Procedure">
                                                        <i class="fa fa-stethoscope"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if($vl['package_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'billings/procedure_package/'; ?><?php echo $vl['ID']; ?>" class="btn btn-info btn-xs" target="_blank" title="Package">
                                                        <i class="fa fa-cube"></i>
                                                    </a>
                                                <?php } ?>
                                            <?php } elseif ($_SESSION['logged_billing_manager']) { ?>
                                                <?php if ($vl['procedure_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'after-consultation-step-2?t=procedure_billing&i='; ?><?php echo $vl['ID']; ?>" class="btn btn-primary btn-xs" target="_blank" title="Procedure">
                                                        <i class="fa fa-stethoscope"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($vl['investation_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'after-consultation-step-2?t=investigation_billing&i='; ?><?php echo $vl['ID']; ?>" class="btn btn-warning btn-xs" target="_blank" title="Investigation">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($vl['medicine_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'stocks/add_billing_medicine?appointment_id='; ?><?php echo $vl['appointment_id']; ?>" class="btn btn-success btn-xs" target="_blank" title="Medicine">
                                                        <i class="fa fa-medkit"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($vl['medicine_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'stocks_new/add_sale?appointment_id='; ?><?php echo $vl['appointment_id']; ?>" class="btn btn-success btn-xs" target="_blank" title="Medicine">
                                                        <i class="fa fa-medkit"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($vl['package_suggestion'] == 1) { ?>
                                                    <a href="<?php echo base_url().'after-consultation-step-2?t=package_billing&i='; ?><?php echo $vl['ID']; ?>" class="btn btn-info btn-xs" target="_blank" title="Package">
                                                        <i class="fa fa-sitemap"></i>
                                                    </a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-info"><?php echo $vl['consultation_date']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php $sql_app = "SELECT * FROM `hms_appointments` WHERE paitent_id=".$vl['patient_id']."";
                                        $select_result_appo = run_select_query($sql_app);
                                        echo $select_result_appo['crm_id'];
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-phone"></i> <?php //echo $select_result_appo['wife_phone']; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge"><?php echo $vl['appointment_id']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary"><?php echo $vl['patient_id']; ?></span>
                                    </td>
                                    <td>
                                        <?php $sql = "SELECT * FROM `hms_patient_medical_info` WHERE patient_id=".$vl['patient_id']."";
                                        $select_result = run_select_query($sql);
                                        echo $select_result['female_name'];
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-default"><?php echo $select_result['female_age']; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo $all_method->doctor_name($vl['doctor_id']); ?></strong>
                                    </td>
                                    <td>
                                        <small><?php echo $vl['follow_up_purpose']; ?></small>
                                    </td>
                                    <td>
                                        <div class="investigation-list" style="max-height: 120px; overflow-y: auto; max-width: 150px;">
                                            <?php 
                                            $male_investigations = array();
                                            
                                            // Get investigations from hms_investigation table
                                            $serializedString = $vl['male_investigation_suggestion_list'];
                                            $unserializedArray = unserialize($serializedString);
                                            if (is_array($unserializedArray)) {
                                                foreach ($unserializedArray as $key => $value) {
                                                    $sql2 = "SELECT * FROM `hms_investigation` WHERE ID IN ($value)";
                                                    $select_result2 = run_select_query($sql2);
                                                    if (!empty($select_result2) && isset($select_result2['investigation'])) {
                                                        $male_investigations[] = $select_result2['investigation'];
                                                    }
                                                }
                                            }
                                            
                                            // Get investigations from hms_master_investigations table
                                            $serializedString = $vl['male_minvestigation_suggestion_list'];
                                            $unserializedArray = unserialize($serializedString);
                                            if (is_array($unserializedArray)) {
                                                foreach ($unserializedArray as $key => $value) {
                                                    $id = (int)$value;
                                                    $sql_male = "SELECT * FROM `hms_master_investigations` WHERE ID = $id";
                                                    $select_male = run_select_query($sql_male);
                                                    if (!empty($select_male) && isset($select_male['investigation_name'])) {
                                                        $male_investigations[] = $select_male['investigation_name'];
                                                    }
                                                }
                                            }
                                            
                                            // Display investigations with limit
                                            $display_limit = 8; // Show only first 8 investigations
                                            $total_count = count($male_investigations);
                                            $displayed_count = 0;
                                            
                                            foreach ($male_investigations as $investigation) {
                                                if ($displayed_count >= $display_limit) break;
                                                
                                                // Truncate long investigation names
                                                $display_name = strlen($investigation) > 25 ? substr($investigation, 0, 25) . '...' : $investigation;
                                                echo '<span class="label label-warning" title="' . htmlspecialchars($investigation) . '">' . htmlspecialchars($display_name) . '</span><br>';
                                                $displayed_count++;
                                            }
                                            
                                            // Show count if there are more investigations
                                            if ($total_count > $display_limit) {
                                                $remaining = $total_count - $display_limit;
                                                echo '<span class="label label-default">+' . $remaining . ' more</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="investigation-list" style="max-height: 120px; overflow-y: auto; max-width: 150px;">
                                            <?php 
                                            $female_investigations = array();
                                            
                                            // Get investigations from hms_investigation table
                                            $serializedStringfemale = $vl['female_investigation_suggestion_list'];
                                            $unserializedArrayfemale = unserialize($serializedStringfemale);
                                            if (is_array($unserializedArrayfemale)) {
                                                foreach ($unserializedArrayfemale as $key => $ids) {
                                                    $sql2 = "SELECT * FROM `hms_investigation` WHERE ID IN ($ids)";
                                                    $female_result = run_select_query($sql2);
                                                    if (!empty($female_result) && isset($female_result['investigation'])) {
                                                        $female_investigations[] = $female_result['investigation'];
                                                    }
                                                }
                                            }
                                            
                                            // Get investigations from hms_master_investigations table
                                            $serializedStringfemale = $vl['female_minvestigation_suggestion_list'];
                                            $unserializedArrayfemale = unserialize($serializedStringfemale);
                                            if (is_array($unserializedArrayfemale)) {
                                                foreach ($unserializedArrayfemale as $key => $id) {
                                                    $id = (int)$id;
                                                    $sql2 = "SELECT * FROM `hms_master_investigations` WHERE ID = $id";
                                                    $female_result = run_select_query($sql2);
                                                    if (!empty($female_result) && isset($female_result['investigation_name'])) {
                                                        $female_investigations[] = $female_result['investigation_name'];
                                                    }
                                                }
                                            }
                                            
                                            // Display investigations with limit
                                            $display_limit = 8; // Show only first 8 investigations
                                            $total_count = count($female_investigations);
                                            $displayed_count = 0;
                                            
                                            foreach ($female_investigations as $investigation) {
                                                if ($displayed_count >= $display_limit) break;
                                                
                                                // Truncate long investigation names
                                                $display_name = strlen($investigation) > 25 ? substr($investigation, 0, 25) . '...' : $investigation;
                                                echo '<span class="label label-danger" title="' . htmlspecialchars($investigation) . '">' . htmlspecialchars($display_name) . '</span><br>';
                                                $displayed_count++;
                                            }
                                            
                                            // Show count if there are more investigations
                                            if ($total_count > $display_limit) {
                                                $remaining = $total_count - $display_limit;
                                                echo '<span class="label label-default">+' . $remaining . ' more</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="procedure-list">
                                            <?php $serializedStringfemale = $vl['sub_procedure_suggestion_list'];
                                            $unserializedArrayfemale = unserialize($serializedStringfemale);
                                            foreach ($unserializedArrayfemale as $key => $ids) {
                                                $sql2 = "SELECT * FROM `hms_procedures` WHERE ID IN ($ids)";
                                                $female_result = run_select_query($sql2);
                                                echo '<span class="label label-info">' . $female_result['procedure_name'] . '</span> ';
                                            } ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="medicine-list" style="max-height: 120px; overflow-y: auto; max-width: 120px;">
                                            <?php 
                                            $male_medicines = array();
                                            $serializedMaleMedicine = $vl['male_medicine_suggestion_list'];
                                            $unserializedArrayMaleMedicine = unserialize($serializedMaleMedicine);
                                            if (isset($unserializedArrayMaleMedicine['male_medicine_suggestion_list'])) {
                                                $male_medicine_suggestion_list = $unserializedArrayMaleMedicine['male_medicine_suggestion_list'];
                                                foreach ($male_medicine_suggestion_list as $suggestion) {
                                                    $item_number = $suggestion['male_medicine_name'];
                                                    $sql_que = "SELECT * FROM `hms_stocks` WHERE item_number = '$item_number'";
                                                    $malemed_result = run_select_query($sql_que);
                                                    if ($malemed_result) {
                                                        $male_medicines[] = $malemed_result['item_name'];
                                                    }
                                                }
                                            }
                                            
                                            // Display medicines with limit
                                            $display_limit = 6; // Show only first 6 medicines
                                            $total_count = count($male_medicines);
                                            $displayed_count = 0;
                                            
                                            foreach ($male_medicines as $medicine) {
                                                if ($displayed_count >= $display_limit) break;
                                                
                                                // Truncate long medicine names
                                                $display_name = strlen($medicine) > 20 ? substr($medicine, 0, 20) . '...' : $medicine;
                                                echo '<span class="label label-success" title="' . htmlspecialchars($medicine) . '">' . htmlspecialchars($display_name) . '</span><br>';
                                                $displayed_count++;
                                            }
                                            
                                            // Show count if there are more medicines
                                            if ($total_count > $display_limit) {
                                                $remaining = $total_count - $display_limit;
                                                echo '<span class="label label-default">+' . $remaining . ' more</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="medicine-list" style="max-height: 120px; overflow-y: auto; max-width: 120px;">
                                            <?php 
                                            $female_medicines = array();
                                            $serializedfeMaleMedicine = $vl['female_medicine_suggestion_list'];
                                            $unserializedArrayfeMaleMedicine = unserialize($serializedfeMaleMedicine);
                                            if (isset($unserializedArrayfeMaleMedicine['female_medicine_suggestion_list'])) {
                                                $female_medicine_suggestion_list = $unserializedArrayfeMaleMedicine['female_medicine_suggestion_list'];
                                                foreach ($female_medicine_suggestion_list as $suggestion) {
                                                    $item_number = $suggestion['female_medicine_name'];
                                                    $sql_quefe = "SELECT * FROM `hms_stocks` WHERE item_number = '$item_number'";
                                                    $femalemed_result = run_select_query($sql_quefe);
                                                    if ($femalemed_result) {
                                                        $female_medicines[] = $femalemed_result['item_name'];
                                                    }
                                                }
                                            }
                                            
                                            // Display medicines with limit
                                            $display_limit = 6; // Show only first 6 medicines
                                            $total_count = count($female_medicines);
                                            $displayed_count = 0;
                                            
                                            foreach ($female_medicines as $medicine) {
                                                if ($displayed_count >= $display_limit) break;
                                                
                                                // Truncate long medicine names
                                                $display_name = strlen($medicine) > 20 ? substr($medicine, 0, 20) . '...' : $medicine;
                                                echo '<span class="label label-success" title="' . htmlspecialchars($medicine) . '">' . htmlspecialchars($display_name) . '</span><br>';
                                                $displayed_count++;
                                            }
                                            
                                            // Show count if there are more medicines
                                            if ($total_count > $display_limit) {
                                                $remaining = $total_count - $display_limit;
                                                echo '<span class="label label-default">+' . $remaining . ' more</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="follow-up-info">
                                            <strong><?php echo $vl['follow_up_date']; ?></strong><br>
                                            <small class="text-muted"><?php echo $vl['follow_slot']; ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $package_suggestion_list = unserialize($vl['package_suggestion_list']);

                                        if ($package_suggestion_list !== false && is_array($package_suggestion_list)) {
                                            foreach ($package_suggestion_list as $package_name => $group) {
                                                if (is_numeric($package_name)) {
                                                    $package_name = "Package " . ($package_name + 1);
                                                }

                                                $procedure_ids = explode(',', $group);
                                                $rowspan = count($procedure_ids);
                                                $package_total_price = 0;

                                                $first_procedure_id = trim($procedure_ids[0]);
                                                $sql4 = "SELECT * FROM hms_procedure_package WHERE procedure_id='$first_procedure_id'";
                                                $select_result4 = run_select_query($sql4);

                                                // Calculate total price for the entire package
                                                foreach ($procedure_ids as $procedure_id) {
                                                    $procedure_id = trim($procedure_id);

                                                    if (!empty($procedure_id)) {
                                                        $sql_quefe = "SELECT * FROM hms_procedures WHERE ID = '$procedure_id'";
                                                        $femalemed_result = run_select_query($sql_quefe);
                                                    }
                                                }
                                                ?>
                                                <span class="label label-primary"><?= htmlspecialchars($select_result4['package_name']); ?></span>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    
                                    <td class="text-center">
                                        <?php if ($vl['status'] == '1') { ?>
                                            <span class="label label-success">Approved</span>
                                        <?php } else { ?>
                                            <span class="label label-warning">Pending</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <strong><?php echo $vl['counsellor_signature']; ?></strong>
                                    </td>
                                </tr>
                            <?php $count++; } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="panel-footer">
                        <div class="text-center">
                            <?php echo $links; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Print Section -->
<div class="row" id="print_this_section2" style="display:none;">
    <div class="col-md-12">
        <h3 class="text-center">OPD Register - Print View</h3>
        <table class="table table-striped table-bordered table-hover" id="doctor_appointments1">
            <thead>
                <tr>
                    <th style="border:1px solid #ccc;">S. No.</th>
                    <th style="border:1px solid #ccc;">Date</th>
                    <th style="border:1px solid #ccc;">IIC ID</th>
                    <th style="border:1px solid #ccc;">Patient Name</th>
                    <th style="border:1px solid #ccc;">Age</th>
                    <th style="border:1px solid #ccc;">Husband Name</th>
                    <th style="border:1px solid #ccc;">Purpose</th>
                    <th style="border:1px solid #ccc;">Male Investigation</th>
                    <th style="border:1px solid #ccc;">Female Investigation</th>
                    <th style="border:1px solid #ccc;">Male Medicine</th>
                    <th style="border:1px solid #ccc;">Female Medicine</th>
                </tr>
            </thead>
            <tbody id="appointment_body">
                <?php $count = 1; foreach($app_result as $ky => $vl){ ?>
                <tr>
                    <td style="border:1px solid #ccc;"><?php echo $count; ?></td>
                    <td style="border:1px solid #ccc;"><?php echo $vl['consultation_date']; ?></td>
                    <td style="border:1px solid #ccc;"><?php echo $vl['patient_id']; ?></td>
                    <td style="border:1px solid #ccc;">
                        <?php $sql = "SELECT * FROM `hms_patient_medical_info` WHERE patient_id=".$vl['patient_id']."";
                        $select_result = run_select_query($sql);
                        echo $select_result['female_name'];
                        ?>
                    </td>
                    <td style="border:1px solid #ccc;"><?php echo $select_result['female_age']; ?></td>
                    <td style="border:1px solid #ccc;"><?php echo $select_result['male_name']; ?></td>
                    <td style="border:1px solid #ccc;"><?php echo $vl['follow_up_purpose']; ?></td>
                    <td style="border:1px solid #ccc;">
                        <?php $serializedString = $vl['male_investigation_suggestion_list'];
                        $unserializedArray = unserialize($serializedString);
                        foreach ($unserializedArray as $key => $value) {
                            $sql2 = "SELECT * FROM `hms_investigation` WHERE ID IN ($value)";
                            $select_result2 = run_select_query($sql2);
                            echo $select_result2['investigation'] . "<br>";
                        } ?>
                    </td>
                    <td style="border:1px solid #ccc;">
                        <?php $serializedStringfemale = $vl['female_investigation_suggestion_list'];
                        $unserializedArrayfemale = unserialize($serializedStringfemale);
                        foreach ($unserializedArrayfemale as $key => $ids) {
                            $sql2 = "SELECT * FROM `hms_investigation` WHERE ID IN ($ids)";
                            $female_result = run_select_query($sql2);
                            echo $female_result['investigation'] . "<br>";
                        } ?>
                    </td>
                    <td style="border:1px solid #ccc;">
                        <?php $serializedMaleMedicine = $vl['male_medicine_suggestion_list'];
                        $unserializedArrayMaleMedicine = unserialize($serializedMaleMedicine);
                        $male_medicine_suggestion_list = $unserializedArrayMaleMedicine['male_medicine_suggestion_list'];
                        foreach ($male_medicine_suggestion_list as $suggestion) {
                            $item_number = $suggestion['male_medicine_name'];
                            $sql_que = "SELECT * FROM `hms_stocks` WHERE item_number = '$item_number'";
                            $malemed_result = run_select_query($sql_que);
                            if ($malemed_result) {
                                echo $malemed_result['item_name'] . "<br>";
                            }
                        }
                        ?>
                    </td>
                    <td style="border:1px solid #ccc;">
                        <?php $serializedfeMaleMedicine = $vl['female_medicine_suggestion_list'];
                        $unserializedArrayfeMaleMedicine = unserialize($serializedfeMaleMedicine);
                        $female_medicine_suggestion_list = $unserializedArrayfeMaleMedicine['female_medicine_suggestion_list'];
                        foreach ($female_medicine_suggestion_list as $suggestion) {
                            $item_number = $suggestion['female_medicine_name'];
                            $sql_quefe = "SELECT * FROM `hms_stocks` WHERE item_number = '$item_number'";
                            $femalemed_result = run_select_query($sql_quefe);
                            if ($femalemed_result) {
                                echo $femalemed_result['item_name'] . "<br>";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php $count++; } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    // Date picker initialization
    $(".particular_date_filter").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        onSelect: function(dateStr) {
            $('#loader_div').hide();
            var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
            var data = {appointment_date: startDate, type: 'particular_date_filter'};
        }
    });
    
    // Initialize tooltips
    $('[title]').tooltip();
    
    // Table responsive handling
    $('.table-responsive').on('show.bs.dropdown', function (e) {
        var $table = $(this),
            $menu = $(e.target).find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true);
        
        if (menuOffsetHeight > tableOffsetHeight) {
            $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
        }
    }).on('hide.bs.dropdown', function () {
        $(this).css("padding-bottom", 0);
    });
});

function printDiv2() {
    $('.hide_print').hide();
    $('input[type="submit"]').css('visibility', 'hidden');
    $('p#last_updated').css('visibility', 'hidden');
    var divToPrint = document.getElementById('print_this_section2');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><head><title>OPD Register</title><style>body{font-family:Arial,sans-serif;font-size:12px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ccc;padding:5px;text-align:left;}th{background-color:#f5f5f5;font-weight:bold;}</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    setTimeout(function() { newWin.close(); }, 10);
    window.location.reload();
}
</script>

<style>
/* Custom Styles for OPD Register */
.panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.panel-heading .panel-title {
    font-weight: 600;
    font-size: 18px;
}

.panel-heading .fa {
    margin-right: 8px;
}

.form-group label {
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-top: none;
}

.table td {
    vertical-align: middle;
}

.investigation-list .label,
.medicine-list .label,
.procedure-list .label {
    display: inline-block;
    margin: 1px 0;
    font-size: 9px;
    padding: 2px 4px;
    line-height: 1.2;
    word-wrap: break-word;
    max-width: 100%;
}

.investigation-list,
.medicine-list {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 5px;
    background-color: #f8f9fa;
}

.investigation-list::-webkit-scrollbar,
.medicine-list::-webkit-scrollbar {
    width: 4px;
}

.investigation-list::-webkit-scrollbar-track,
.medicine-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.investigation-list::-webkit-scrollbar-thumb,
.medicine-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.investigation-list::-webkit-scrollbar-thumb:hover,
.medicine-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.follow-up-info {
    line-height: 1.2;
}

.panel-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.custom-pagination {
    padding: 8px;
}

.custom-pagination a {
    padding: 10px;
    text-decoration: none;
    color: #007bff;
}

.custom-pagination a:hover {
    color: #0056b3;
}

.form-control {
    height: 34px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Print Styles */
@media print {
    .panel-heading {
        background: #f8f9fa !important;
        color: #000 !important;
    }
    
    .btn {
        display: none !important;
    }
    
    .table-responsive {
        overflow: visible !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group-vertical {
        display: block;
    }
    
    .btn-group-vertical .btn {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .table-responsive {
        font-size: 12px;
    }
}
</style>
