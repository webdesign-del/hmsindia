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
               
               <!-- 🎯 IS DIV KE ANDAR KA SAB KUCH ENGINE PRINT KAREGA -->
               <div id="full_package_print_area">
                   <!-- Patient Summary Meta Grid Header Block -->
                   <table style="width:100%;margin-bottom:20px;">
                      <tbody>
                          <tr>
                            <td colspan="2" rowspan="3" style="border:none;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
                            <td colspan="1" style="font-size:14px; font-weight: bold; border:none;">Patient ID : </td>
                            <td colspan="3" style="border:none;"><input type="text" class="form-control plain-print-input" value="<?php echo $data["patient_id"]; ?>" style="width:80%;border-top:0px;border-left:0px;border-right:0px;"></td>
                         </tr>
                         <tr>
                            <td colspan="1" style="font-size:14px; font-weight: bold; border:none;">Name of Wife : </td>
                            <td colspan="1" style="border:none;"><b><?php echo $select_result3["wife_name"]; ?></b></td>
                            <td colspan="1" style="font-size:14px; font-weight: bold; border:none;">Age :</td>
                            <td colspan="1" style="border:none;"><b><?php echo $select_result3["wife_age"]; ?></b></td>
                         </tr>
                         <tr>
                            <td colspan="1" style="font-size:14px; font-weight: bold; border:none;">Name of Husband:</td>
                            <td colspan="1" style="border:none;"><b><?php echo $select_result3["husband_name"]; ?></b></td>
                            <td colspan="1" style="font-size:14px; font-weight: bold; border:none;">Age :</td>
                            <td colspan="1" style="border:none;"><b><?php echo $select_result3["husband_age"]; ?></b></td>
                         </tr>
                      </tbody>
                   </table>

                   <!-- Core Package Generation Matrix Table Container -->
                   <table style="width:100%; border:1px solid #000; border-collapse: collapse; margin-top:20px;" id="procedure_table">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th colspan="1" style="width:35%; border:1px solid #000; padding:5px; text-align: left;">Name</th>
                                <th colspan="1" style="width:15%; border:1px solid #000; padding:5px; text-align: left;">Code</th>
                                <th colspan="1" style="width:12%; border:1px solid #000; padding:5px; text-align: left;">Amount</th>
                                <?php if ($select_result["status"] == "0") { ?>
                                <th class="hide-on-print" colspan="1" style="width:13%; border:1px solid #000; padding:5px; text-align: left;">Min Price</th>
                                <?php } ?>
                                <th colspan="1" style="width:12%; border:1px solid #000; padding:5px; text-align: left;">Discount (Amt)</th>
                                <?php if ($select_result["status"] == "0") { ?>
                                <th class="hide-on-print" colspan="1" style="width:10%; border:1px solid #000; padding:5px; text-align: left;">Discount %</th>
                                <?php } ?>
                                <th colspan="1" style="width:15%; border:1px solid #000; padding:5px; text-align: left;">After Discount</th>
                                <?php if ($select_result["status"] == "0") { ?>
                                <th class="hide-on-print" colspan="1" style="width:5%; border:1px solid #000; padding:5px; text-align: left;">Action</th>
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
                                                    <select name="procedure_ID_<?= $sub_procedure_counter ?>" id="procedure_ID_<?= $sub_procedure_counter ?>" class="form-control select2-dropdown dynamic-proc-selector" onchange="getProcedureDetails(<?= $sub_procedure_counter ?>)" disabled>
                                                        <option value="">Select Procedure</option>
                                                        <?php foreach ($procedure_list as $proc) {
                                                            $selected = ($proc["ID"] == $res["ID"]) ? "selected" : "";
                                                            $proc_name_safe = htmlspecialchars($proc["procedure_name"], ENT_QUOTES);
                                                            echo "<option value='{$proc["ID"]}' data-name='{$proc_name_safe}' data-code='{$proc["code"]}' data-price='{$proc["price"]}' data-min='" . ($proc["min_price"] ?? 0) . "' $selected>{$proc["procedure_name"]}</option>";
                                                        } ?>
                                                    </select>
                                                    <input type="hidden" name="procedure_ID_<?= $sub_procedure_counter ?>" value="<?= $res['ID'] ?? '' ?>">
                                                    <input type="hidden" id="procedure_name_<?= $sub_procedure_counter ?>" name="procedure_name_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($res['procedure_name'] ?? '', ENT_QUOTES) ?>">
                                                </td>
                                                <td style="border:1px solid #000; padding:5px;"><input type="text" class="form-control plain-print-input" id="code_<?= $sub_procedure_counter ?>" name="code_<?= $sub_procedure_counter ?>" value="<?= $res["code"] ?>" readonly></td>
                                                <td style="border:1px solid #000; padding:5px;"><input type="number" class="form-control plain-print-input" id="price_<?= $sub_procedure_counter ?>" name="price_<?= $sub_procedure_counter ?>" value="<?= $res["price"] ?>" readonly></td>
                                                <td class="hide-on-print" style="border:1px solid #000; padding:5px;"><input type="number" class="form-control plain-print-input" style="background:#f9f9f9;" id="min_price_display_<?= $sub_procedure_counter ?>" name="min_price_display_<?= $sub_procedure_counter ?>" value="<?= $res['min_price'] ?? 0 ?>" readonly></td>
                                                <td style="border:1px solid #000; padding:5px;"><input type="number" class="form-control plain-print-input" id="discount_<?= $sub_procedure_counter ?>" name="discount_<?= $sub_procedure_counter ?>" value="0" oninput="calculateRow(<?= $sub_procedure_counter ?>)"></td>
                                                <td style="border:1px solid #000; padding:5px;"><input type="text" class="form-control plain-print-input" id="after_discount_<?= $sub_procedure_counter ?>" name="after_discount_<?= $sub_procedure_counter ?>" readonly></td>
                                                <td class="hide-on-print" style="border:1px solid #000; padding:5px;"><input type="text" class="form-control plain-print-input" id="discount_percent_<?= $sub_procedure_counter ?>" readonly style="background:#f0f0f0; text-align:center;" value="0%"></td>
                                                <td class="hide-on-print" style="border:1px solid #000; padding:5px; text-align:center;"><button type="button" onclick="removeRow(<?= $sub_procedure_counter ?>)" style="color:red; border:none; background:none; font-weight:bold;">X</button></td>
                                            </tr>
                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            
                            <!-- 🎯 STATUS 1 KA SAVED DATA BLOCK (YE BHI AB PRINT ME HUNDRED PERCENT AAYEGA) -->
                            <?php
                            if ($select_result['status'] == '1' && !empty($select_result['procedure'])) { 
                                $procedureData = unserialize($select_result['procedure']);
                                $finalRows = $procedureData['consumables'] ?? $procedureData;

                                if (!empty($finalRows)) {
                                    foreach ($finalRows as $consumable) {
                                        $price = (float)($consumable['price'] ?? 0);
                                        $discount = (float)($consumable['discount'] ?? 0);
                                        $afterDiscount = $price - $discount;
                                        ?>
                                        <tr class="saved-data-row">
                                            <td style="border:1px solid #000; padding:5px; text-align: left; font-weight: bold;"><?php echo $consumable['procedure_name']; ?></td>
                                            <td style="border:1px solid #000; padding:5px; text-align: left;"><?php echo $consumable['code']; ?></td>
                                            <td style="border:1px solid #000; padding:5px; text-align: left;"><?php echo $price; ?></td>
                                            <td style="border:1px solid #000; padding:5px; text-align: left;"><?php echo $discount; ?></td>
                                            <td style="border:1px solid #000; padding:5px; text-align: left; font-weight: bold;"><?php echo $afterDiscount; ?></td>
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
                   
                   <!-- Terms and Conditions Block -->
                   <table width="100%" style="margin-top:20px; border-collapse: collapse;">
                      <tbody>
                         <tr>
                            <td colspan="6" style="border:none; text-align:left;"><strong style="font-size:15px;">Terms &amp; Conditions (The above-mentioned package)</strong></td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none; text-align:left; padding-top:5px;"><strong>Includes:</strong></td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none;">
                               <p style="font-size:13px; text-align: left; margin:0 0 10px 10px;">* Doctor consultation charges (During IVF Cycle only) (up to 5 consultations only). Single Self Egg &amp; Sperm IVF Cycle up to Embryo Transfer. Monitoring Ultrasound {From Stimulation to embryo transfer (Single Cycle)}, Ovulation Induction Injections only for making egg pre ovum pick up. Admission charges (Short stay room rent for OPU and ET). Anesthetist charges for ovum pick up only. IVF consumables charges for ovum pick up and ET without complication. Embryologist and surgeon charges till single embryo transfer.</p>
                            </td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none; text-align:left;"><strong>Excludes:</strong></td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none;">
                               <p style="font-size:13px; text-align: left; margin:0 0 10px 10px;">Any other medicine except ovulation induction injection. Discharge medicines for ovum pick up and embryo transfer. General anesthesia for embryo transfer, anesthesia fees, consumables, OT Charges etc. Pre and Post IVF Consultations. Investigations not included. Any Complication in OT during Ovum pick up &amp; Embryo transfer (Pre &amp; Post). Meals &amp; Lodging for patients. Surrogacy Charges. Egg donor charges according to egg donor. Sperm donor charges per donor.</p>
                            </td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none; text-align:left;"><strong>Payment Structure:</strong></td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none;">
                               <p style="font-size:13px; text-align: left; margin:0 0 10px 10px;">10% of the total package amount is payable as a booking amount at the time of registration.<br/>
                               40% of the amount must be paid before initiation of ovarian stimulation.<br/>
                               The remaining 50% must be paid before the trigger injection.</p>
                               <p style="font-size:13px; text-align: left; margin:0 0 10px 10px;">Any add-on procedures or services (such as ICSI, blastocyst culture, freezing, thawing, etc.) will be informed to the patient in advance and charged separately as applicable.<br/>
                               The booking amount is non-refundable under any circumstances.<br/>
                               The booking is valid for a period of 60 days or 02 months from the date of payment. After this period, re-booking charges may apply.</p>
                            </td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none; text-align:left;"><strong>Embryology-Related Terms &amp; Conditions</strong></td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none;">
                               <p style="font-size:13px; text-align: left; margin:0 0 10px 10px;">Outcomes related to oocyte quality, fertilization, embryo development, freezing, thawing, and transfer cannot be guaranteed, as they depend on biological factors beyond human control. Charges for embryology services are applicable once the service is initiated, irrespective of the outcome.</p>
                            </td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none;">
                               <p style="font-size:13px; text-align: left; margin:0 0 10px 10px;">IVF success rates are statistical and depend on medical variables including age, ovarian reserve, sperm quality, uterine receptivity, and other biological factors; accordingly, the Clinic gives no assurance, representation, or Guarantee of IVF success, pregnancy, implantation, live birth, or the genetic normalcy of the child. The Clinic shall not be held liable for treatment failure, miscarriage, congenital anomalies, or medical complications arising from inherent biological limitations.</p>
                            </td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none; text-align: left; font-size:13px; padding-bottom:10px;"><b>Note:</b> Booking amount not refundable and 25% of package cost should be deposited within 10 days of Registration, failing to which the package will automatically stand cancelled without prior notification.</td>
                         </tr>
                         <tr>
                            <td colspan="6" style="border:none; text-align: left; font-size:13px; font-weight:bold; color:red;">►We do not do preconception sex selection and we don’t allow sex determination</td>
                         </tr>
                      </tbody>
                   </table>

                   <!-- Package Calculation Total Inputs -->
                   <table width="100%" style="margin-top:20px; border-collapse: collapse;" border="1">
                      <tbody>
                         <tr style="background:#f2f2f2;"><td colspan="6" style="text-align: left; padding:5px;"><strong>Payment Details: </strong></td></tr>
                         <tr>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px;">Total Package</td>
                            <td colspan="2" style="text-align: left; padding:5px;">Rs: <input type="text" class="plain-print-input" id="total_after_discount" name="total_after_discount" value="<?php echo $select_result['total_after_discount']; ?>" style="border:0px; font-weight:bold;" readonly></td>
                            <td colspan="2" style="text-align: left; padding:5px;">Date: <input type="date" class="plain-print-input" name="package_date" value="<?php echo $select_result['package_date']; ?>" style="border:0px;" required></td>
                         </tr>
                         <tr>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px;">Booking Amount (10 %)</td>
                            <td colspan="2" style="text-align: left; padding:5px;">Rs: <input type="text" class="plain-print-input" name="booking_amount" value="<?php echo $select_result['booking_amount']; ?>" style="border:0px;" required></td>
                            <td colspan="2" style="text-align: left; padding:5px;">Date: <input type="date" class="plain-print-input" name="booking_date" value="<?php echo $select_result['booking_date']; ?>" required style="border:0px;"></td>
                         </tr>
                         <tr>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px;">Deposit on start of treatment</td>
                            <td colspan="2" style="text-align: left; padding:5px;">Rs: <input type="text" class="plain-print-input" style="border:0px;"></td>
                            <td colspan="2" style="text-align: left; padding:5px;">Date: <input type="date" class="plain-print-input" style="border:0px;"></td>
                         </tr>
                      </tbody>
                   </table>

                   <!-- Signatures Layers -->
                   <table style="width:100%; margin-top:20px; border-collapse: collapse;" border="1" id="male_medicine_table">
                      <tbody>
                         <tr>
                            <td colspan="2" style="font-size:13px; width:33%; text-align: left; padding:5px;">Husband Name: <input type="text" class="plain-print-input" readonly value="<?php echo $select_result3["husband_name"]; ?>" style="width:70%; border:none; border-bottom:1px solid #000;"></td>
                            <td colspan="2" style="font-size:13px; width:33%; text-align: left; padding:5px;">Wife Name: <input type="text" class="plain-print-input" readonly value="<?php echo $select_result3["wife_name"]; ?>" style="width:70%; border:none; border-bottom:1px solid #000;"></td>
                            <td colspan="2" style="font-size:13px; width:33%; text-align: left; padding:5px;">Counsellor Name: <input type="text" class="plain-print-input" readonly name="counsellor_signature" id="counsellor_signature" value="<?php echo $_SESSION["logged_counselor"]["name"]; ?>" style="width:65%; border:none; border-bottom:1px solid #000;"></td>
                         </tr>
                         <tr>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px; height:35px;">Husband Signature: <input type="text" class="plain-print-input" style="width:65%; border:none;"></td>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px; height:35px;">Wife Signature: <input type="text" class="plain-print-input" style="width:65%; border:none;"></td>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px; height:35px;">Counsellor Signature: <input type="text" class="plain-print-input" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:65%; border:none;"></td>
                         </tr>
                         <tr>
                            <td colspan="2" style="font-size:13px; text-align: left; padding:5px;">Coordinator Signature : <input type="text" class="plain-print-input" id="coordinator_signature" name="coordinator_signature" value="<?php echo $select_result["coordinator_signature"]; ?>" style="width:50%; border:none; border-bottom:1px solid #000;"></td>
                            <td colspan="2" style="text-align: left; padding:5px; font-size:13px;">Date: <input type="date" class="plain-print-input" style="border:none; width:80%;"></td>
                            <td colspan="2" style="text-align: left; padding:5px; font-size:13px;">Time: <input type="time" class="plain-print-input" style="border:none; width:80%;"></td>
                         </tr>
                         <tr style="background:#f9f9f9;">
                            <td colspan="6" style="font-size:11px; text-align:center; padding:5px; font-weight:bold;">Medical Management | Fertility enhancing surgeries | Follicular monitoring | IUI | IVF-ICSI | Egg Donation | Surrogacy | Embryo Freezing | Male Infertility | TESA/PESA | Laparo-hystero Surgeries |</td>
                         </tr>
                      </tbody>
                   </table>
               </div>

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

<style>
.plain-print-input { background: transparent; }
@media print { .hide-on-print { display: none !important; } }
</style>

<script data-cfasync="false" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script data-cfasync="false" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script data-cfasync="false">
    $(document).ready(function() {
        $('.select2-dropdown').select2({ width: '100%' });

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

            $('select[id^="procedure_ID_"]').prop('disabled', false);
            var totalAfterDiscount = parseFloat($('#total_after_discount').val()) || 0;
            var totalMinPrice = parseFloat($('#total_min_price_val').val()) || 0;

            if (totalAfterDiscount < totalMinPrice) {
                var diff = (totalMinPrice - totalAfterDiscount).toFixed(2);
                var msg = "TOTAL PACKAGE WARNING:\nRequired Min Total: " + totalMinPrice.toFixed(2) + "\nThis package Total: " + totalAfterDiscount.toFixed(2) + "\nDifference: " + diff + "\n\nThis package is priced below the minimum required rate. Send approval request email?";
                if (!confirm(msg)) {
                    e.preventDefault();
                    $('select[id^="procedure_ID_"]').prop('disabled', true); 
                    return false;
                }
                $(this).append('<input type="hidden" name="requires_approval" value="1"><input type="hidden" name="send_approval_mail" value="1">');
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
        document.getElementById('procedure_name_' + id).value = opt.getAttribute('data-name') || opt.text.split(',')[0];
        calculateRow(id);
    }

    function calculateRow(id) {
        var price = parseFloat(document.getElementById('price_' + id).value) || 0;
        var discount = parseFloat(document.getElementById('discount_' + id).value) || 0;
        var final = price - discount;
        document.getElementById('after_discount_' + id).value = final.toFixed(2);

        var percent = 0;
        if (price > 0 && discount > 0) { percent = (discount / price) * 100; }
        if(document.getElementById('discount_percent_' + id)) { document.getElementById('discount_percent_' + id).value = percent.toFixed(2) + "%"; }

        var minPrice = parseFloat(document.getElementById('min_price_display_' + id).value) || 0;
        if (document.getElementById('row_' + id)) {
            if (final < minPrice && minPrice > 0) { document.getElementById('row_' + id).style.backgroundColor = "#fff0f0"; } 
            else { document.getElementById('row_' + id).style.backgroundColor = ""; }
        }
        calculateTotal();
    }

    function calculateTotal() {
        var grandTotalAfterDiscount = 0; var grandTotalMinPrice = 0;
        $("input[id^='after_discount_']").each(function() { grandTotalAfterDiscount += parseFloat($(this).val()) || 0; });
        $("input[id^='min_price_display_']").each(function() { grandTotalMinPrice += parseFloat($(this).val()) || 0; });
        
        // Handling Status 1 plain text table row total extraction
        if($('.saved-data-row').length > 0) {
            $('.saved-data-row').each(function() {
                var amt = parseFloat($(this).find('td').eq(4).text().trim()) || 0;
                grandTotalAfterDiscount += amt;
            });
        }

        $('#total_after_discount').val(grandTotalAfterDiscount.toFixed(2));
        if(!$('#total_min_price_val').length){ $('#procedureForm').append('<input type="hidden" id="total_min_price_val" name="total_min_price_val">'); }
        $('#total_min_price_val').val(grandTotalMinPrice.toFixed(2));
    }

    function removeRow(id) { $('#row_' + id).remove(); calculateTotal(); }

    // 🚀 UPDATED & FIXED: DEEP PRINTING ENGINE
    function printDiv2() {
        // Clone poora master wrapper element
        var printContent = document.getElementById('full_package_print_area').cloneNode(true);
        
        // 1. Convert Select drop-downs to pure print spans text
        $(printContent).find('select.dynamic-proc-selector').each(function() {
            var originalId = $(this).attr('id');
            var selectedText = $('#' + originalId + ' option:selected').attr('data-name') || $('#' + originalId + ' option:selected').text();
            if(selectedText === "Select Procedure") selectedText = "";
            $(this).replaceWith('<span style="font-weight:bold; font-size:14px;">' + selectedText + '</span>');
        });

        // 2. Convert active raw form input nodes to plain printed text string nodes
        $(printContent).find('input').each(function() {
            var val = $(this).val() || '';
            var type = $(this).attr('type');
            if(type !== 'hidden') {
                $(this).replaceWith('<span style="font-weight:bold; font-size:13px;">' + val + '</span>');
            }
        });

        // 3. Clear system specific functional nodes from print canvas layout
        $(printContent).find('.hide-on-print').remove();

        // 4. Open independent sandboxed tab thread for flawless rendering execution
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write(`
            <html>
            <head>
                <title>Package Summary Receipt</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 25px; color: #000; line-height:1.5; }
                    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 13px; }
                    thead tr { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    p { margin: 0 0 10px 0; }
                    span { display: inline-block; word-break: break-word; }
                </style>
            </head>
            <body onload="window.print(); setTimeout(function(){ window.close(); }, 200);">
                ${printContent.innerHTML}
            </body>
            </html>
        `);
        newWin.document.close();
    }
</script>