<?php
// 1. FETCH EXISTING DATA FROM DATABASE
$sql = "SELECT * From hms_doctor_consultation where ID='" . $data["ID"] . "'";
$select_result = run_select_query($sql);

$sql3 = "SELECT * From hms_patients where patient_id='" . $data["patient_id"] . "'";
$select_result3 = run_select_query($sql3);

// 2. FETCH ALL PROCEDURES WITH MIN PRICES
$procedure_list = [];
$proc_sql = "SELECT p.*, mp.min_price 
                FROM hms_procedures p 
                LEFT JOIN hms_procedure_min_prices mp ON p.ID = mp.procedure_id 
                WHERE p.parent_id='0' AND p.status='1' 
                ORDER BY p.procedure_name ASC";

$query = $this->db->query($proc_sql);
if ($query) {
    $procedure_list = $query->result_array();
}

// Create Javascript Dropdown Template Options Engine
$js_options = '<option value="">Select Procedure</option>';
foreach ($procedure_list as $proc) {
    $safe_name = addslashes($proc["procedure_name"]);
    $code = addslashes($proc["code"]);
    $min_p = $proc["min_price"] ? $proc["min_price"] : 0;
    
    // 🎯 FIX: data-name attribute injected into template dynamic options
    $js_options .= '<option value="' . $proc["ID"] . '" data-name="' . $safe_name . '" data-code="' . $code . '" data-price="' . $proc["price"] . '" data-min="' . $min_p . '">' . $safe_name . ", " . $code . '</option>';
}
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="card">
   <div class="card-content">
      <div class="row">
         <div class="ga-pro">
            
            <form action="" method="post" id="procedureForm">
               <input type="hidden" name="action" value="addprocedure">
               <input type="hidden" value="<?php echo $data["patient_id"]; ?>" id="patient_id" name="patient_id" class="form-control">
               <input type="hidden" value="<?php echo $data["appointment_id"]; ?>" id="appointment_id" name="appointment_id" class="form-control">
               <input type="hidden" value="<?php echo date("Y-m-d h:i:s"); ?>" id="date" name="date" class="form-control">
               <input type="hidden" value="<?php echo date("Y-m-d"); ?>" id="add_on" name="add_on" class="form-control">
               <input type="hidden" value="<?php echo $data["ID"]; ?>" id="ID" name="ID" class="form-control">
               <input type="hidden" value="1" id="status" name="status" class="form-control">
               <input type="hidden" value="<?php echo $data["center_number"]; ?>" name="center_number" id="center_number" class="form-control">
               <input type="hidden" value="<?php echo $_SESSION["logged_counselor"]["employee_number"]; ?>" name="employee_number" id="employee_number" class="form-control">
               <input type="hidden" value="<?php echo date("F"); ?>" name="month" id="month" class="form-control">
               
               <!-- Patient Summary Meta Grid Header Block -->
               <table style="width:100%;margin-bottom:20px;">
                  <tbody>
                      <tr>
                        <td colspan="2" rowspan="3"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
                        <td colspan="1" style="font-size:14px;">Patient ID : </td>
                        <td colspan="3"><input type="text" class="form-control" value="<?php echo $data["patient_id"]; ?>" style="width:80%;border-top:0px;border-left:0px;border-right:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="1" style="font-size:14px;">Name of Wife : </td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3["wife_name"]; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly></td>
                        <td colspan="1" style="font-size:14px;">Age :</td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3["wife_age"]; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly></td>
                     </tr>
                     <tr>
                        <td colspan="1" style="font-size:14px;">Name of Husband:</td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3["husband_name"]; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly></td>
                        <td colspan="1" style="font-size:14px;">Age :</td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3["husband_age"]; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly></td>
                     </tr>
                  </tbody>
               </table>

               <!-- Core Package Generation Matrix Table Container -->
               <table style="width:100%; border:1px solid #000; margin-top:20px;" id="procedure_table">
                    <thead>
                        <tr>
                            <th colspan="1" style="width:25%; border:1px solid #000; padding:5px;">Name</th>
                            <th colspan="1" style="width:10%; border:1px solid #000; padding:5px;">Code</th>
                            <th colspan="1" style="width:10%; border:1px solid #000; padding:5px;">Amount</th>
                            <?php if ($select_result["status"] == "0") { ?>
                            <th colspan="1" style="width:15%; border:1px solid #000; padding:5px;">Min Price</th>
                            <?php } ?>
                            <th colspan="1" style="width:10%; border:1px solid #000; padding:5px;">Discount (Amt)</th>
                            <?php if ($select_result["status"] == "0") { ?>
                            <th colspan="1" style="width:10%; border:1px solid #000; padding:5px;">Discount %</th>
                            <?php } ?>
                            <th colspan="1" style="width:15%; border:1px solid #000; padding:5px;">After Discount</th>
                            <?php if ($select_result["status"] == "0") { ?>
                            <th colspan="1" style="width:5%; border:1px solid #000; padding:5px;">Action</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="procedure_body">
                        <?php
                        $sub_procedure_counter = 0;
                        if ($select_result["status"] == "0") {
                            $suggestions = unserialize($data["sub_procedure_suggestion_list"]);
                            if (is_array($suggestions)) {
                                foreach ($suggestions as $item) {
                                    $item_sql = "SELECT p.*, mp.min_price FROM hms_procedures p LEFT JOIN hms_procedure_min_prices mp ON p.ID = mp.procedure_id WHERE p.ID = '$item'";
                                    $res = $this->db->query($item_sql)->row_array();
                                    if ($res) {
                                        $sub_procedure_counter++; ?>
                                        <tr id="row_<?= $sub_procedure_counter ?>">
                                            <td style="border:1px solid #000; padding:5px;">
                                                <select name="procedure_ID_<?= $sub_procedure_counter ?>" id="procedure_ID_<?= $sub_procedure_counter ?>" class="form-control select2-dropdown" onchange="getProcedureDetails(<?= $sub_procedure_counter ?>)" disabled>
                                                    <option value="">Select Procedure</option>
                                                    <?php foreach ($procedure_list as $proc) {
                                                        $selected = ($proc["ID"] == $res["ID"]) ? "selected" : "";
                                                        $proc_name_safe = htmlspecialchars($proc["procedure_name"], ENT_QUOTES);
                                                        echo "<option value='{$proc["ID"]}' data-name='{$proc_name_safe}' data-code='{$proc["code"]}' data-price='{$proc["price"]}' data-min='" . ($proc["min_price"] ?? 0) . "' $selected>{$proc["procedure_name"]}</option>";
                                                    } ?>
                                                </select>
                                                <input type="hidden" name="procedure_ID_<?= $sub_procedure_counter ?>" value="<?= isset($selected_procedure_id) ? $selected_procedure_id : (isset($res['ID']) ? $res['ID'] : ''); ?>">
                                                <input type="hidden" id="procedure_name_<?= $sub_procedure_counter ?>" name="procedure_name_<?= $sub_procedure_counter ?>" value="<?= (is_array($res) && isset($res['procedure_name'])) ? htmlspecialchars($res['procedure_name'], ENT_QUOTES) : (is_string($res) ? htmlspecialchars($res, ENT_QUOTES) : ''); ?>">
                                            </td>
                                            <td style="border:1px solid #000;"><input type="text" class="form-control" id="code_<?= $sub_procedure_counter ?>" name="code_<?= $sub_procedure_counter ?>" value="<?= $res["code"] ?>" readonly></td>
                                            <td style="border:1px solid #000;"><input type="number" class="form-control" id="price_<?= $sub_procedure_counter ?>" name="price_<?= $sub_procedure_counter ?>" value="<?= $res["price"] ?>" readonly></td>
                                            <td style="border:1px solid #000;"><input type="number" class="form-control" style="background:#f9f9f9;" id="min_price_display_<?= $sub_procedure_counter ?>" name="min_price_display_<?= $sub_procedure_counter ?>" value="<?= $res['min_price'] ?? 0 ?>" readonly></td>
                                            <td style="border:1px solid #000;"><input type="number" class="form-control" id="discount_<?= $sub_procedure_counter ?>" name="discount_<?= $sub_procedure_counter ?>" value="0" oninput="calculateRow(<?= $sub_procedure_counter ?>)"></td>
                                            <td style="border:1px solid #000;"><input type="text" class="form-control" id="after_discount_<?= $sub_procedure_counter ?>" name="after_discount_<?= $sub_procedure_counter ?>" readonly></td>
                                            <td style="border:1px solid #000;"><input type="text" class="form-control" id="discount_percent_<?= $sub_procedure_counter ?>" readonly style="background:#f0f0f0; text-align:center;" value="0%"></td>
                                            <td style="border:1px solid #000; text-align:center;"><button type="button" onclick="removeRow(<?= $sub_procedure_counter ?>)" style="color:red; border:none; background:none; font-weight:bold;">X</button></td>
                                        </tr>
                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                        <?php
                        if ($select_result['status'] == '1' && !empty($select_result['procedure'])) { 
                            $procedureData = unserialize($select_result['procedure']);
                            $finalRows = $procedureData['consumables'] ?? $procedureData;

                            if (!empty($finalRows)) {
                                foreach ($finalRows as $consumable) {
                                    $price = (float)($consumable['price'] ?? 0);
                                    $discount = (float)($consumable['discount'] ?? 0);
                                    $afterDiscount = $price - $discount;
                                    $discPercent = ($price > 0) ? round(($discount / $price) * 100, 2) : 0;
                                    ?>
                                    <tr>
                                        <td colspan="1" width="40%" style="border:1px solid;padding:5px;"><?php echo $consumable['procedure_name']; ?></td>
                                        <td colspan="1" width="15%" style="border:1px solid;padding:5px;"><?php echo $consumable['code']; ?></td>
                                        <td colspan="1" width="15%" style="border:1px solid;padding:5px;"><?php echo $price; ?></td>
                                        <td colspan="1" width="15%" style="border:1px solid;padding:5px;"><?php echo $discount; ?></td>
                                        <?php if ($select_result["status"] == "0") { ?>
                                        <td style="border:1px solid #000; text-align:center; background:#f9f9f9;"><?php echo $discPercent; ?>%</td>
                                        <?php } ?>
                                        <td colspan="1" width="15%" style="border:1px solid;padding:5px; font-weight:bold;"><?php echo $afterDiscount; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
               </table>

               <div style="margin-top: 10px; text-align: right;">
                    <input type="hidden" id="last_counter_value" value="<?= $sub_procedure_counter ?>">
               </div>
               
               <!-- 🚀 FULL CONTENT TOTAL SECTION: Terms and Conditions specifications blocks -->
               <table width="100%" style="margin-top:20px;" class="">
                  <tbody>
                     <tr>
                        <td colspan="6"><strong style="margin-left:20px;">Terms &amp; Conditions (The above-mentioned package)</strong></td>
                     </tr>
                     <tr>
                        <td colspan="6"><strong>Includes:</strong></td>
                     </tr>
                     <tr>
                        <td colspan="6">
                           <p style="margin-left:20px;font-size:14px; text-align: left;">* Doctor consultation charges (During IVF Cycle only) (up to 5 consultations only). Single Self Egg &amp; Sperm IVF Cycle up to Embryo Transfer. Monitoring Ultrasound {From Stimulation to embryo transfer (Single Cycle)}, Ovulation Induction Injections only for making egg pre ovum pick up. Admission charges (Short stay room rent for OPU and ET). Anesthetist charges for ovum pick up only. IVF consumables charges for ovum pick up and ET without complication. Embryologist and surgeon charges till single embryo transfer.</p>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="6"><strong>Excludes:</strong></td>
                     </tr>
                     <tr>
                        <td colspan="6">
                           <p style="margin-left:20px;font-size:14px; text-align: left;">Any other medicine except ovulation induction injection. Discharge medicines for ovum pick up and embryo transfer. General anesthesia for embryo transfer, anesthesia fees, consumables, OT Charges etc. Pre and Post IVF Consultations. Investigations not included. Any Complication in OT during Ovum pick up &amp; Embryo transfer (Pre &amp; Post). Meals &amp; Lodging for patients. Surrogacy Charges. Egg donor charges according to egg donor. Sperm donor charges per donor.</p>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="6"><strong>Payment Structure:</strong></td>
                     </tr>
                     <tr>
                        <td colspan="6">
                           <p style="margin-left:20px;font-size:14px; text-align: left;">10% of the total package amount is payable as a booking amount at the time of registration.<br/>
                           40% of the amount must be paid before initiation of ovarian stimulation.<br/>
                           The remaining 50% must be paid before the trigger injection.</p>
                           <p style="margin-left:20px;font-size:14px; text-align: left;">Any add-on procedures or services (such as ICSI, blastocyst culture, freezing, thawing, etc.) will be informed to the patient in advance and charged separately as applicable.<br/>
                           The booking amount is non-refundable under any circumstances.<br/>
                           The booking is valid for a period of 60 days or 02 months from the date of payment. After this period, re-booking charges may apply.</p>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="6"><strong>Embryology-Related Terms &amp; Conditions</strong></td>
                     </tr>
                     <tr>
                        <td colspan="6">
                           <p style="margin-left:20px;font-size:14px; text-align: left;">Outcomes related to oocyte quality, fertilization, embryo development, freezing, thawing, and transfer cannot be guaranteed, as they depend on biological factors beyond human control. Charges for embryology services are applicable once the service is initiated, irrespective of the outcome.</p>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="6">
                           <p style="margin-left:20px;font-size:14px; text-align: left;">IVF success rates are statistical and depend on medical variables including age, ovarian reserve, sperm quality, uterine receptivity, and other biological factors; accordingly, the Clinic gives no assurance, representation, or Guarantee of IVF success, pregnancy, implantation, live birth, or the genetic normalcy of the child. The Clinic shall not be held liable for treatment failure, miscarriage, congenital anomalies, or medical complications arising from inherent biological limitations.</p>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="6" style="text-align: left;"><strong>Note:</strong> Booking amount not refundable and 25% of package cost should be deposited within 10 days of Registration, failing to which the package will automatically stand cancelled without prior notification.</td>
                     </tr>
                     <tr>
                        <td style="text-align: left;"><strong>►We do not do preconception sex selection and we don’t allow sex determination</strong></td>
                     </tr>
                  </tbody>
               </table>

               <!-- Package Calculation Total Inputs Blocks Matrix -->
               <table width="100%" style="margin-top:20px;">
                  <tbody>
                     <tr><td colspan="6" style="text-align: left;"><strong>Payment Details: </strong></td></tr>
                     <tr>
                        <td colspan="2" style="font-size:12px; text-align: left;">Total Package</td>
                        <td colspan="2" style="text-align: left;">Rs: <input type="text" id="total_after_discount" name="total_after_discount" value="<?php echo $select_result['total_after_discount']; ?>" style="border:0px;" readonly></td>
                        <td colspan="2" style="text-align: left;">Date: <input type="date" name="package_date" value="<?php echo $select_result['package_date']; ?>" style="border:0px;" required></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:12px; text-align: left;">Booking Amount (10 %)</td>
                        <td colspan="2" style="text-align: left;">Rs: <input type="text" name="booking_amount" value="<?php echo $select_result['booking_amount']; ?>" style="border:0px;" required></td>
                        <td colspan="2" style="text-align: left;">Date: <input type="date" name="booking_date" value="<?php echo $select_result['booking_date']; ?>" required style="border:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:12px; text-align: left;">Deposit on start of treatment</td>
                        <td colspan="2" style="text-align: left;">Rs: <input type="text" style="border:0px;"></td>
                        <td colspan="2" style="text-align: left;">Date: <input type="date" style="border:0px;"></td>
                     </tr>
                  </tbody>
               </table>

               <!-- Signatures collection layer validation components -->
               <table style="width:100%; margin-top:20px;" border="1" id="male_medicine_table">
                  <tbody>
                     <tr>
                        <td colspan="2" style="font-size:14px;width:40%; text-align: left;">Husband Name: <input type="text" readonly value="<?php echo $select_result3["husband_name"]; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                        <td colspan="2" style="font-size:14px; text-align: left;">Wife Name: <input type="text" readonly value="<?php echo $select_result3["wife_name"]; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                        <td colspan="2" style="font-size:14px; text-align: left;">Counsellor Name: <input type="text" readonly name="counsellor_signature" id="counsellor_signature" value="<?php echo $_SESSION["logged_counselor"]["name"]; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:14px;width:40%; text-align: left;">Husband Signature: <input type="text" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                        <td colspan="2" style="font-size:14px; text-align: left;">Wife Signature: <input type="text" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                        <td colspan="2" style="font-size:14px; text-align: left;">Counsellor Signature: <input type="text" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:14px;width:40%; text-align: left;">Coordinator Signature : <input type="text" id="coordinator_signature" name="coordinator_signature" value="<?php echo $select_result["coordinator_signature"]; ?>" style="width:100px;border-top:0px;border-left:0px;border-right:0px;"></td>
                        <td colspan="2" style="text-align: left;">Date: <input type="date" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                        <td colspan="2" style="text-align: left;">Time: <input type="time" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="6" style="font-size:14px;text-align:center;"><strong>Medical Management | Fertility enhancing surgeries | Follicular monitoring | IUI | IVF-ICSI | Egg Donation | Surrogacy | Embryo Freezing | Male Infertility | TESA/PESA | Laparo-hystero Surgeries |</strong></td>
                     </tr>
                  </tbody>
               </table>

               <div style="margin-top:20px;">
                  <?php if ($select_result["status"] == "0") { ?>
                     <input type='submit' id='btnsubmit' value='Submit Data' class="btn btn-success pull-right" style="margin-left:10px;">
                  <?php } ?>
                  <?php if ($select_result["status"] == "1") { ?>
                     <input type='button' id='btnprint' value='Print Preview' class="btn btn-primary pull-right" onclick='printDiv2();'>
                  <?php } ?>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<!-- Printable Section Wrapper Block -->
<div class="row" id="print_this_section2" style="display:none;">
    <div class="ga-pro">
        <table style="width:100%;">
         <tr>
            <td colspan="2" rowspan="3"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
            <td colspan="1" style="font-size:14px;">Patient ID : </td>
            <td colspan="3"><input type="text" class="form-control" value="<?php echo $data['patient_id']; ?>" style="width:80%;border-top:0px;border-left:0px;border-right:0px;" readonly></td>
         </tr>     
         <tr>
            <td colspan="1">Name of Wife: <span><?php echo $select_result3['wife_name']; ?></span></td>
            <td colspan="1">Age: <span><?php echo $select_result3['wife_age']; ?></span></td>
         </tr>
         <tr>
            <td colspan="1">Name of Husband: <span><?php echo $select_result3['husband_name']; ?></span></td>
            <td colspan="1">Age: <span><?php echo $select_result3['husband_age']; ?></span></td>
         </tr>
        </table>

        <table width="100%" style="margin-top:5px; border-collapse:collapse;" border="1" class="vb45rt">
            <thead>
                <tr>
                   <th colspan="2" width="40%" style="padding:5px;">Name</th>
                   <th colspan="1" width="15%" style="padding:5px;">Code</th>
                   <th colspan="1" width="15%" style="padding:5px;">Amount</th>
                   <th colspan="1" width="15%" style="padding:5px;">Discount</th>
                   <th colspan="1" width="15%" style="padding:5px;">After Discount</th>
                </tr>
            </thead>
            <tbody id="print_procedure_body"></tbody>
        </table>
    </div>
</div>

<!-- ============================================================================== -->
<!-- 🎯 JAVASCRIPT AUTOMATION ENGINE & RACING-CONDITION BYPASS WRAPPER -->
<!-- ============================================================================== -->
<script data-cfasync="false" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script data-cfasync="false" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script data-cfasync="false">
    $(document).ready(function() {
        $('.select2-dropdown').select2({ width: '100%' });

        // Auto-extract dynamic loop data layers
        $("select[id^='procedure_ID_']").each(function() {
            var selectEl = $(this);
            var numericId = selectEl.attr('id').replace('procedure_ID_', '');
            
            var selectedOption = selectEl.find('option:selected');
            if (selectedOption.length > 0 && selectedOption.val() !== "") {
                var procedureText = selectedOption.attr('data-name') || selectedOption.text();
                $('#procedure_name_' + numericId).val(procedureText);
                calculateRow(numericId); 
            }
        });

        calculateTotal();

        // --- FORM SUBMIT SECURITY CHAIN ---
        $('#procedureForm').on('submit', function(e) {
            $("select[id^='procedure_ID_']").each(function() {
                var selectEl = $(this);
                var numericId = selectEl.attr('id').replace('procedure_ID_', '');
                var selectedOption = selectEl.find('option:selected');
                if (selectedOption.length > 0 && selectedOption.val() !== "") {
                    var procedureText = selectedOption.attr('data-name') || selectedOption.text();
                    $('#procedure_name_' + numericId).val(procedureText);
                }
            });

            // 🚨 UNBLOCK ENGINE FOR FORM POST COMPILATION
            $('select[id^="procedure_ID_"]').prop('disabled', false);

            var totalAfterDiscount = parseFloat($('#total_after_discount').val()) || 0;
            var totalMinPrice = parseFloat($('#total_min_price_val').val()) || 0;

            if (totalAfterDiscount < totalMinPrice) {
                var diff = (totalMinPrice - totalAfterDiscount).toFixed(2);
                var msg = "TOTAL PACKAGE WARNING:\n" +
                          "Required Min Total: " + totalMinPrice.toFixed(2) + "\n" +
                      "This package Total: " + totalAfterDiscount.toFixed(2) + "\n" +
                          "Difference: " + diff + "\n\n" +
                          "This package is priced below the minimum required rate. Send approval request email?";
                
                if (!confirm(msg)) {
                    e.preventDefault();
                    $('select[id^="procedure_ID_"]').prop('disabled', true); 
                    return false;
                }
                
                $(this).append('<input type="hidden" name="requires_approval" value="1">');
                $(this).append('<input type="hidden" name="send_approval_mail" value="1">');
                $('#status').val('2'); 
            } else {
                $('#status').val('1'); 
            }
            
            $('#btnsubmit').attr('disabled', true).val('Processing...');
        });
    });

    function getProcedureDetails(id) {
        var select = document.getElementById('procedure_ID_' + id);
        if(!select) return;
        var opt = select.options[select.selectedIndex];
        if(!opt) return;

        document.getElementById('code_' + id).value = opt.getAttribute('data-code') || '';
        document.getElementById('price_' + id).value = opt.getAttribute('data-price') || 0;
        document.getElementById('min_price_display_' + id).value = opt.getAttribute('data-min') || 0;
        
        var pureName = opt.getAttribute('data-name') || opt.text.split(',')[0];
        document.getElementById('procedure_name_' + id).value = pureName;

        calculateRow(id);
    }

    function calculateRow(id) {
        var price = parseFloat(document.getElementById('price_' + id).value) || 0;
        var discount = parseFloat(document.getElementById('discount_' + id).value) || 0;
        var final = price - discount;
        document.getElementById('after_discount_' + id).value = final.toFixed(2);

        var percent = 0;
        if (price > 0 && discount > 0) { percent = (discount / price) * 100; }
        
        var percentField = document.getElementById('discount_percent_' + id);
        if(percentField) { percentField.value = percent.toFixed(2) + "%"; }

        var minPrice = parseFloat(document.getElementById('min_price_display_' + id).value) || 0;
        var row = document.getElementById('row_' + id);
        if (row) {
            if (final < minPrice && minPrice > 0) { row.style.backgroundColor = "#fff0f0"; } 
            else { row.style.backgroundColor = ""; }
        }
        calculateTotal();
    }

    function calculateTotal() {
        var grandTotalAfterDiscount = 0;
        var grandTotalMinPrice = 0;

        $("input[id^='after_discount_']").each(function() { grandTotalAfterDiscount += parseFloat($(this).val()) || 0; });
        $("input[id^='min_price_display_']").each(function() { grandTotalMinPrice += parseFloat($(this).val()) || 0; });

        $('#total_after_discount').val(grandTotalAfterDiscount.toFixed(2));
        
        if(!$('#total_min_price_val').length){
            $('#procedureForm').append('<input type="hidden" id="total_min_price_val" name="total_min_price_val">');
        }
        $('#total_min_price_val').val(grandTotalMinPrice.toFixed(2));
    }

    function removeRow(id) {
        $('#row_' + id).remove();
        calculateTotal();
    }

    function printDiv2() {
        var printBody = document.getElementById('print_procedure_body');
        printBody.innerHTML = ""; 

        $('#procedure_body tr').each(function() {
            var $row = $(this);
            var name = ""; var code = ""; var price = ""; var disc = ""; var after = "";

            if ($row.find('select').length > 0 || $row.find('input').length > 0) {
                var $select = $row.find('select');
                if($select.length > 0) {
                    name = $select.find('option:selected').attr('data-name') || $select.find('option:selected').text();
                    if(name === "Select Procedure") name = "";
                } else {
                    name = $row.find('input[name^="procedure_name"]').val();
                }
                code = $row.find('input[name^="code"]').val();
                price = $row.find('input[name^="price"]').val();
                disc = $row.find('input[name^="discount"]').val();
                after = $row.find('input[name^="after_discount"]').val();
            } else {
                name = $row.find('td').eq(0).text().trim();
                code = $row.find('td').eq(1).text().trim();
                price = $row.find('td').eq(2).text().trim();
                disc = $row.find('td').eq(3).text().trim();
                after = $row.find('td').eq(4).text().trim();
            }

            if(name && name !== "") { 
                var printRow = `<tr><td colspan="2" style="padding:5px;">${name}</td><td style="padding:5px;">${code}</td><td style="padding:5px;">${price}</td><td style="padding:5px;">${disc}</td><td style="padding:5px;">${after}</td></tr>`;
                printBody.insertAdjacentHTML('beforeend', printRow);
            }
        });

        var divToPrint = document.getElementById('print_this_section2');
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head><style>body{font-family:sans-serif;} table { border-collapse: collapse; width: 100%; } td, th { border: 1px solid black; padding: 5px; }</style></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();}, 10);
    }
</script>