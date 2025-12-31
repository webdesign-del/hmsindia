<?php 
   // 1. FETCH EXISTING DATA
   $sql = "SELECT * From hms_doctor_consultation where ID='".$data['ID']."'";
   $select_result = run_select_query($sql);
   
   $sql3 = "SELECT * From hms_patients where  patient_id='".$data['patient_id']."'";
   $select_result3 = run_select_query($sql3);

   // 2. FETCH ALL PROCEDURES (CodeIgniter Way)
   // We use $this->db instead of $conn
   $procedure_list = [];
   $proc_sql = "SELECT * FROM hms_procedures where parent_id='0' and status='1' ORDER BY procedure_name ASC";
   $query = $this->db->query($proc_sql);
   
   // Check if query was successful
   if ($query) {
       $procedure_list = $query->result_array();
   }
   
   // Create a Javascript Options String for the "Add New" button
   $js_options = '<option value="">Select Procedure</option>';
   foreach($procedure_list as $proc) {
       // Escape special characters to prevent JS errors
       $safe_name = addslashes($proc['procedure_name']);
       $js_options .= '<option value="'.$proc['ID'].'" data-code="'.$proc['code'].'" data-price="'.$proc['price'].'">'.$safe_name.'</option>';
   }
?>

<div class="card">
   <div class="card-content">
      <div class="row">
         <div class="ga-pro">
            <h2 style="text-align:center;"></h2>
            
            <form action="" method="post" id="procedureForm">
               <input type="hidden"  name="action" value="addprocedure">
               <input type="hidden" value="<?php echo $data['patient_id']; ?>" id="patient_id" name="patient_id" class="form-control">
               <input type="hidden" value="<?php echo $data['appointment_id']; ?>" id="appointment_id" name="appointment_id" class="form-control">
               <input type="hidden" value="<?php echo date("Y-m-d h:i:s"); ?>" id="date" name="date" class="form-control">
               <input type="hidden" value="<?php echo date("Y-m-d"); ?>" id="add_on" name="add_on" class="form-control">
               <input type="hidden" value="<?php echo $data['ID']; ?>" id="ID" name="ID" class="form-control">
               <input type="hidden" value="1" id="status" name="status" class="form-control">
               <input type="hidden" value="<?php echo $data['center_number']; ?>" name="center_number" id="center_number" class="form-control">
               <input type="hidden" value="<?php echo $_SESSION['logged_counselor']['employee_number']; ?>" name="employee_number" id="employee_number" class="form-control">
               <input type="hidden" value="<?php echo date("F"); ?>" name="month" id="month" class="form-control">
               
               <table style="width:100%;margin-bottom:20px;">
                  <tbody>
                      <tr>
                        <td colspan="2" rowspan="3"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
                        <td colspan="1" style="font-size:14px;">Patient ID : </td>
                        <td colspan="3"><input type="text" class="form-control" value="<?php echo $data['patient_id']; ?>" style="width:80%;border-top:0px;border-left:0px;border-right:0px;"></td>
                     </tr>
                        <tr>
                        <td colspan="1" style="font-size:14px;">Name of Wife : </td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3['wife_name']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
                        <td colspan="1" style="font-size:14px;">Age :</td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3['wife_age']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
                     </tr>
                     <tr>
                        <td colspan="1" style="font-size:14px;">Name of Husband:</td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3['husband_name']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
                        <td colspan="1" style="font-size:14px;">Age :</td>
                        <td colspan="1"><input type="text" class="form-control" value="<?php echo $select_result3['husband_age']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
                     </tr>
                  </tbody>
               </table>

               <table style="width:100%; border:1px solid #000; margin-top:20px;" id="procedure_table">
                    <thead>
                        <tr>
                            <th colspan="2" style="width:30%; border:1px solid #000;">Name</th>
                            <th colspan="1" style="width:20%; border:1px solid #000;">Code</th>
                            <th colspan="1" style="width:20%; border:1px solid #000;">Amount</th>
                            <th colspan="1" style="width:15%; border:1px solid #000;">Discount</th>
                            <th colspan="1" style="width:15%; border:1px solid #000;">After Discount</th>
                            <th colspan="1" style="width:5%; border:1px solid #000;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="procedure_body">
                        <?php
                        $sub_procedure_counter = 0;
                        if ($select_result['status'] == '0') {
                            $sub_procedure_suggestion_list = unserialize($data['sub_procedure_suggestion_list']);
                            if ($sub_procedure_suggestion_list !== false && is_array($sub_procedure_suggestion_list)) {
                                foreach ($sub_procedure_suggestion_list as $item) {
                                    $sql_quefe = "SELECT * FROM `hms_procedures` WHERE ID = '$item'";
                                    $femalemed_result = run_select_query($sql_quefe);
                                    if ($femalemed_result) {
                                        $sub_procedure_counter++;
                                        $procedureData = unserialize($select_result['procedure']);
                                        $saved_discount = isset($procedureData['consumables'][$sub_procedure_counter-1]['discount']) ? $procedureData['consumables'][$sub_procedure_counter-1]['discount'] : 0;
                                        $saved_after = isset($procedureData['consumables'][$sub_procedure_counter-1]['after_discount']) ? $procedureData['consumables'][$sub_procedure_counter-1]['after_discount'] : $femalemed_result['price'];
                                        ?>
                                        <tr id="row_<?= $sub_procedure_counter ?>">
                                            <td colspan="2" style="border:1px solid #000;">
                                                <select name="procedure_ID_<?= $sub_procedure_counter ?>" id="procedure_ID_<?= $sub_procedure_counter ?>" class="form-control" onchange="getProcedureDetails(<?= $sub_procedure_counter ?>)">
                                                    <option value="">Select Procedure</option>
                                                    <?php foreach($procedure_list as $proc) { 
                                                        $selected = ($proc['ID'] == $femalemed_result['ID']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $proc['ID'] ?>" data-code="<?= $proc['code'] ?>" data-price="<?= $proc['price'] ?>" <?= $selected ?>>
                                                            <?= $proc['procedure_name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" id="procedure_name_<?= $sub_procedure_counter ?>" name="procedure_name_<?= $sub_procedure_counter ?>" value="<?= $femalemed_result['procedure_name']; ?>">
                                            </td>
                                            <td style="border:1px solid #000;">
                                                <input type="text" style="width:100%" id="code_<?= $sub_procedure_counter ?>" name="code_<?= $sub_procedure_counter ?>" value="<?= $femalemed_result['code']; ?>" readonly>
                                            </td>
                                            <td style="border:1px solid #000;">
                                                <input type="number" style="width:100%" id="price_<?= $sub_procedure_counter ?>" name="price_<?= $sub_procedure_counter ?>" value="<?= $femalemed_result['price']; ?>" readonly oninput="calculateRow(<?= $sub_procedure_counter ?>)">
                                            </td>
                                            <td style="border:1px solid #000;">
                                                <input type="number" style="width:100%" id="discount_<?= $sub_procedure_counter ?>" name="discount_<?= $sub_procedure_counter ?>" value="<?= $saved_discount ?>" oninput="calculateRow(<?= $sub_procedure_counter ?>)">
                                            </td>
                                            <td style="border:1px solid #000;">
                                                <input type="text" style="width:100%" id="after_discount_<?= $sub_procedure_counter ?>" name="after_discount_<?= $sub_procedure_counter ?>" value="<?= $saved_after ?>" readonly>
                                            </td>
                                            <td style="border:1px solid #000; text-align:center;">
                                                <button type="button" onclick="removeRow(<?= $sub_procedure_counter ?>)" style="color:red; font-weight:bold; cursor:pointer; border:none; background:none;">X</button>
                                            </td>
                                        </tr>
                        <?php       } 
                                } 
                            } 
                        } elseif ($select_result['status'] == '1') { 
                             // VIEW MODE LOGIC (UNCHANGED)
                            $procedureData = unserialize($select_result['procedure']);
                            if (!empty($procedureData['consumables'])) {
                                foreach ($procedureData['consumables'] as $consumable) {
                                    echo "<tr>";
                                    echo "<td colspan='2' style='border:1px solid;padding:5px;'>".$consumable['procedure_name']."</td>";
                                    echo "<td style='border:1px solid;padding:5px;'>".$consumable['code']."</td>";
                                    echo "<td style='border:1px solid;padding:5px;'>".$consumable['price']."</td>";
                                    echo "<td style='border:1px solid;padding:5px;'>".($consumable['discount'] ?? 0)."</td>";
                                    echo "<td style='border:1px solid;padding:5px;'>".((float)$consumable['price'] - (float)$consumable['discount'])."</td>";
                                    echo "<td style='border:1px solid;padding:5px;'></td>";
                                    echo "</tr>";
                                }
                            }
                        }
                        ?>
                    </tbody>
               </table>

               <?php if ($select_result['status'] == '0') { ?>
                    <div style="margin-top: 10px; text-align: right;">
                        <button type="button" onclick="addNewRow()" style="padding: 5px 15px; background-color: #007bff; color: white; border: none; cursor: pointer;">+ Add Procedure</button>
                        <input type="hidden" id="last_counter_value" value="<?= $sub_procedure_counter ?>">
                    </div>
               <?php } ?>

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



<p style="margin-left:20px;font-size:14px;">* Doctor consultation charges (During IVF Cycle only) (up to 5 consultations only). Single Self Egg &amp;Sperm IVF Cycle up to



EmbryoTransfer. Monitoring Ultrasound {From Stimulation to embryo transfer (Single Cycle)}, Ovulation Induction Injections only for



making egg pre ovum pick up. Admission charges (Short stay room rent for OPU andET). Anesthetist charges for ovum pick uponly.



IVF consumables charges for ovum pick up and ET without complication. Embryologist and surgeon charges till single embryo transfer.



</p>



</td>



</tr>



<tr>



<td colspan="6"><strong>Excludes:</strong></td>



</tr>



<tr>



<td colspan="6">



<p style="margin-left:20px;font-size:14px;">Any other medicine except ovulation inductioninjection. Discharge medicines for ovum pick up and embryotransfer. General anesthesia



for embryo transfer, anesthesia fees, consumables, OTChargesetc. Pre and Post IVFConsultations. Investigations notincluded. Any Complication in OT during Ovum pick up & Embryo transfer (Pre & Post). Meals & Lodging forpatients. SurrogacyCharges. Egg donor charges according to eggdonor. Sperm donor charges perdonor.



</p>



</td>



</tr>



<tr>



<td colspan="6"><strong>Note:</strong> Booking amount not refundable and 25% of package cost should be deposited within 10 days of Registration, failing to



which the package will automatically stand cancelled without prior notification.



</td>



</tr>



<tr>



<td><strong>►We do not do preconception sex selection and we don’t allow sex determination</strong></td>



</tr>



</tbody>



</table>




               <table width="100%" class="">
                  <tbody>
                     <tr><td colspan="6"><strong>Payment Details: </strong></td></tr>
                     <tr>
                        <td colspan="2" style="font-size:12px;">Total Package</td>
                        <td colspan="2">Rs: <?php echo $select_result['total_after_discount']; ?><input type="text" id="total_after_discount" name="total_after_discount" value="<?php echo $select_result['total_after_discount']; ?>" style="border:0px;" readonly></td>
                        <td colspan="2">Date: <input type="date" name="package_date" value="<?php echo $select_result['package_date']; ?>" style="border:0px;" required></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:12px;">Booking Amount (10 %)</td>
                        <td colspan="2">Rs: <input type="text" name="booking_amount" value="<?php echo $select_result['booking_amount']; ?>" style="border:0px;" required></td>
                        <td colspan="2">Date: <input type="date" name="booking_date" value="<?php echo $select_result['booking_date']; ?>" required style="border:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:12px;">Deposit on start of treatment</td>
                        <td colspan="2">Rs: <input type="text" style="border:0px;"></td>
                        <td colspan="2">Date: <input type="date" style="border:0px;"></td>
                     </tr>
                  </tbody>
               </table>
               <table style="width:100%;" id="male_medicine_table" border="1">

<tbody id="male_medicine_suggestion_table" style="border:1px solid #000; padding:10px; width:100%;">

<tr>

<td colspan="2" style="font-size:14px;width:40%">Husband Name: <input type="text" readonly="" id="" value="<?php echo $select_result3['husband_name']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Wife Name: <input type="text" id="" readonly="" value="<?php echo $select_result3['wife_name']; ?>"style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Counsellor Name: <input type="text" readonly="" name="counsellor_signature" id="counsellor_signature" value="<?php echo $_SESSION['logged_counselor']['name']?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

</tr>

<tr>

<td colspan="2" style="font-size:14px;width:40%">Husband Signature: <input type="text" id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Wife Signature: <input type="text" id="name29" value=""style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Counsellor Signature: <input type="text" name="" id="" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

</tr>

<tr>

<td colspan="2" style="font-size:14px;width:40%">Coordinator Signature : <input type="text" id="coordinator_signature" name="coordinator_signature" value="<?php echo $select_result['coordinator_signature']; ?>" style="width:100px;border-top:0px;border-left:0px;border-right:0px;"></td>

<td colspan="2">Date: <input type="date" id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

<td colspan="2">Time: <input type="time" id="name30" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

</tr>

</tbody>

</table>

<table id="male_medicine_table">

<tbody id="male_medicine_suggestion_table" style="padding:10px; width:100%;">

<tr>

<td colspan="6" style="font-size:14px;text-align:center;"><strong>Medical Management | Fertility enhancing surgeries | Follicular monitoring | IUI | IVF-ICSI | Egg Donation |

Surrogacy | Embryo Freezing | Male Infertility | TESA/PESA | Laparo-hystero Surgeries |</strong>

</td>

</tr>

</tbody>

</table>
               <div style="margin-top:20px;">
                  <?php if($select_result['status']=='0'){ ?>
                  <input type='submit' id='btnsubmit' value='Submit Data' class="btn btn-success pull-right" style="margin-left:10px;" onclick="if(this.form.checkValidity()){ this.style.pointerEvents='none'; this.value='Submitting...'; }" >
                  <?php } ?>
                       <input type='button' id='btnprint' value='Print Preview' class="btn btn-primary pull-right" onclick='printDiv2();'>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<div class="row" id="print_this_section2" style="display:none;">
    <div class="ga-pro">
        <table style="width:100%;" class="fg45yu">
         <tr>
            <td colspan="2" rowspan="3"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
            <td colspan="1" style="font-size:14px;">Patient ID : </td>
            <td colspan="3"><input type="text" class="form-control" value="<?php echo $data['patient_id']; ?>" style="width:80%;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
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
            <tbody id="print_procedure_body">
                </tbody>
        </table>

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



<p style="margin-left:20px;font-size:14px;">* Doctor consultation charges (During IVF Cycle only) (up to 5 consultations only). Single Self Egg &amp;Sperm IVF Cycle up to



EmbryoTransfer. Monitoring Ultrasound {From Stimulation to embryo transfer (Single Cycle)}, Ovulation Induction Injections only for



making egg pre ovum pick up. Admission charges (Short stay room rent for OPU andET). Anesthetist charges for ovum pick uponly.



IVF consumables charges for ovum pick up and ET without complication. Embryologist and surgeon charges till single embryo transfer.



</p>



</td>



</tr>



<tr>



<td colspan="6"><strong>Excludes:</strong></td>



</tr>



<tr>



<td colspan="6">



<p style="margin-left:20px;font-size:14px;">Any other medicine except ovulation inductioninjection. Discharge medicines for ovum pick up and embryotransfer. General anesthesia



for embryo transfer, anesthesia fees, consumables, OTChargesetc. Pre and Post IVFConsultations. Investigations notincluded. Any Complication in OT during Ovum pick up & Embryo transfer (Pre & Post). Meals & Lodging forpatients. SurrogacyCharges. Egg donor charges according to eggdonor. Sperm donor charges perdonor.



</p>



</td>



</tr>



<tr>



<td colspan="6"><strong>Note:</strong> Booking amount not refundable and 25% of package cost should be deposited within 10 days of Registration, failing to



which the package will automatically stand cancelled without prior notification.



</td>



</tr>



<tr>



<td><strong>►We do not do preconception sex selection and we don’t allow sex determination</strong></td>



</tr>



</tbody>



</table>




         <table width="100%" class="">
                  <tbody>
                     <tr><td colspan="6"><strong>Payment Details: </strong></td></tr>
                     <tr>
                        <td colspan="2" style="font-size:12px;">Total Package</td>
                        <td colspan="2">Rs: <?php echo $select_result['total_after_discount']; ?><input type="text" id="total_after_discount" name="total_after_discount" value="<?php echo $select_result['total_after_discount']; ?>" style="border:0px;" readonly></td>
                        <td colspan="2">Date: <input type="date" name="package_date" value="<?php echo $select_result['package_date']; ?>" style="border:0px;" required></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:12px;">Booking Amount (10 %)</td>
                        <td colspan="2">Rs: <input type="text" name="booking_amount" value="<?php echo $select_result['booking_amount']; ?>" style="border:0px;" required></td>
                        <td colspan="2">Date: <input type="date" name="booking_date" value="<?php echo $select_result['booking_date']; ?>" required style="border:0px;"></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="font-size:12px;">Deposit on start of treatment</td>
                        <td colspan="2">Rs: <input type="text" style="border:0px;"></td>
                        <td colspan="2">Date: <input type="date" style="border:0px;"></td>
                     </tr>
                  </tbody>
               </table>
        
        <table style="width:100%; margin-top:30px;" border="1">
            <tbody>
                <tr>

<td colspan="2" style="font-size:14px;width:40%">Husband Signature: <input type="text" id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Wife Signature: <input type="text" id="name29" value=""style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Counsellor Signature: <input type="text" name="" id="" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

</tr>
<tr>

<td colspan="2" style="font-size:14px;width:40%">Husband Signature: <input type="text" id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Wife Signature: <input type="text" id="name29" value=""style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>

<td colspan="2" style="font-size:14px;">Counsellor Signature: <input type="text" name="" id="" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

</tr>

<tr>

<td colspan="2" style="font-size:14px;width:40%">Coordinator Signature : <input type="text" id="coordinator_signature" name="coordinator_signature" value="<?php echo $select_result['coordinator_signature']; ?>" style="width:100px;border-top:0px;border-left:0px;border-right:0px;"></td>

<td colspan="2">Date: <input type="date" id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

<td colspan="2">Time: <input type="time" id="name30" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>

</tr>
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // --- 1. HANDLE DROPDOWN CHANGE ---
    function getProcedureDetails(id) {
        var select = document.getElementById('procedure_ID_' + id);
        var selectedOption = select.options[select.selectedIndex];

        var code = selectedOption.getAttribute('data-code');
        var price = selectedOption.getAttribute('data-price');
        var name = selectedOption.text;

        document.getElementById('code_' + id).value = code;
        document.getElementById('price_' + id).value = price;
        document.getElementById('procedure_name_' + id).value = name;

        calculateRow(id);
    }

    // --- 2. GLOBAL CALCULATION ---
    function calculateRow(id) {
        var price = parseFloat(document.getElementById('price_' + id).value) || 0;
        var discount = parseFloat(document.getElementById('discount_' + id).value) || 0;
        
        var total = price - discount;
        if(total < 0) total = 0;

        document.getElementById('after_discount_' + id).value = total.toFixed(2);
        calculateTotal(); 
    }

    function calculateTotal() {
        var grandTotal = 0;
        var inputs = document.querySelectorAll("input[id^='after_discount_']");
        inputs.forEach(function(input) {
            grandTotal += parseFloat(input.value) || 0;
        });

        var totalField = document.getElementById('total_after_discount');
        if(totalField) {
            totalField.value = grandTotal.toFixed(2);
        }
    }

    // --- 3. ADD NEW ROW ---
    var procedureOptions = `<?php echo $js_options; ?>`;

    function addNewRow() {
        var counterInput = document.getElementById('last_counter_value');
        var newCount = parseInt(counterInput.value) + 1;
        counterInput.value = newCount;

        var html = `
            <tr id="row_${newCount}">
                <td colspan="2" style="border:1px solid #000;">
                    <select name="procedure_ID_${newCount}" id="procedure_ID_${newCount}" class="form-control" onchange="getProcedureDetails(${newCount})">
                        ${procedureOptions}
                    </select>
                    <input type="hidden" id="procedure_name_${newCount}" name="procedure_name_${newCount}" value="">
                </td>
                <td style="border:1px solid #000;">
                    <input type="text" style="width:100%" id="code_${newCount}" name="code_${newCount}" readonly>
                </td>
                <td style="border:1px solid #000;">
                    <input type="number" style="width:100%" id="price_${newCount}" name="price_${newCount}" readonly oninput="calculateRow(${newCount})">
                </td>
                <td style="border:1px solid #000;">
                    <input type="number" style="width:100%" id="discount_${newCount}" name="discount_${newCount}" placeholder="0" oninput="calculateRow(${newCount})">
                </td>
                <td style="border:1px solid #000;">
                    <input type="text" style="width:100%" id="after_discount_${newCount}" name="after_discount_${newCount}" readonly>
                </td>
                <td style="border:1px solid #000; text-align:center;">
                    <button type="button" onclick="removeRow(${newCount})" style="color:red; font-weight:bold; cursor:pointer; border:none; background:none;">X</button>
                </td>
            </tr>`;

        document.getElementById('procedure_body').insertAdjacentHTML('beforeend', html);
    }

    function removeRow(id) {
        var row = document.getElementById('row_' + id);
        if (row) row.remove();
        calculateTotal();
    }

    // --- 4. PREVENT DOUBLE SUBMISSION ---
    $(document).ready(function() {
        $('#procedureForm').on('submit', function() {
            var $btn = $('#btnsubmit');
            $btn.attr('disabled', true);
            $btn.val('Submitting...');
        });
    });

    // --- 5. UPDATED PRINT FUNCTION (The Fix) ---
    function printDiv2() {
        var printBody = document.getElementById('print_procedure_body');
        printBody.innerHTML = ""; 

        $('#procedure_body tr').each(function() {
            var $row = $(this);
            var name = "";
            var code = "";
            var price = "";
            var disc = "";
            var after = "";

            // CHECK 1: Is it an input row (Edit Mode)?
            if ($row.find('select').length > 0 || $row.find('input').length > 0) {
                // Try to get name from Dropdown
                var $select = $row.find('select');
                if($select.length > 0) {
                    name = $select.find('option:selected').text();
                    if(name === "Select Procedure") name = ""; // Ignore default
                } else {
                    // Fallback to text input
                    name = $row.find('input[name^="procedure_name"]').val();
                }

                code = $row.find('input[name^="code"]').val();
                price = $row.find('input[name^="price"]').val();
                disc = $row.find('input[name^="discount"]').val();
                after = $row.find('input[name^="after_discount"]').val();
            } 
            // CHECK 2: Is it a text row (View Mode)?
            else {
                name = $row.find('td').eq(0).text().trim();
                code = $row.find('td').eq(1).text().trim();
                price = $row.find('td').eq(2).text().trim();
                disc = $row.find('td').eq(3).text().trim();
                after = $row.find('td').eq(4).text().trim();
            }

            // Only add if we found a valid name
            if(name && name !== "") { 
                var printRow = `
                    <tr>
                        <td colspan="2" style="padding:5px;">${name}</td>
                        <td style="padding:5px;">${code}</td>
                        <td style="padding:5px;">${price}</td>
                        <td style="padding:5px;">${disc}</td>
                        <td style="padding:5px;">${after}</td>
                    </tr>`;
                printBody.insertAdjacentHTML('beforeend', printRow);
            }
        });

        // Copy Totals
        var total = document.getElementById('total_after_discount').value;
        var printTotalEl = document.getElementById('print_total_package');
        if(printTotalEl) printTotalEl.innerText = total;
        
        var booking = document.querySelector('input[name="booking_amount"]').value;
        var printBookingEl = document.getElementById('print_booking_amount');
        if(printBookingEl) printBookingEl.innerText = booking;

        var divToPrint = document.getElementById('print_this_section2');
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head><style>body{font-family:sans-serif;} table { border-collapse: collapse; width: 100%; } td, th { border: 1px solid black; padding: 5px; }</style></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();}, 10);
    }
    
    document.addEventListener("DOMContentLoaded", function () {
        calculateTotal();
    });
</script>