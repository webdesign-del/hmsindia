<?php 

$sql = "SELECT * From hms_doctor_consultation where ID='".$data['ID']."'";
$select_result = run_select_query($sql);

 $sql3 = "SELECT * From hms_patients where  patient_id='".$data['patient_id']."'";
 $select_result3 = run_select_query($sql3);
   
?>
<div class="card">
<div class="card-content">
<div class="row">
<div class="ga-pro">
<h2 style="text-align:center;"></h2>
<form action="" method="post" >
<input type="hidden"  name="action" value="add_product">
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
<td colspan="2" rowspan="2"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>	
<td colspan="1" style="font-size:14px;">Name of Wife : </td>
<td colspan="1"><input type="text" id="" name="" class="form-control" value="<?php echo $select_result3['wife_name']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
<td colspan="1" style="font-size:14px;">Age :</td>
<td colspan="1"><input type="text" id="" name="" class="form-control" value="<?php echo $select_result3['wife_age']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
</tr>
<tr>
<td colspan="1" style="font-size:14px;">Name of Husband:</td>
<td colspan="1"><input type="text" name="" id="" class="form-control" value="<?php echo $select_result3['husband_name']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
<td colspan="1" style="font-size:14px;">Age :</td>
<td colspan="1"><input type="text" id="" name="" class="form-control" value="<?php echo $select_result3['husband_age']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
</tr>
</tbody>
</table>

<table style="width:100%; border:1px solid #000;margin-top:20px;" id="">
<tbody>
<tr>
<th colspan="1" style="width:25%; border:1px solid #000;">Package Name</th>
<th colspan="1" style="width:25%; border:1px solid #000;">Name</th>
<th colspan="1" style="width:10%; border:1px solid #000;">Code</th>
<th colspan="1" style="width:15%; border:1px solid #000;">Amount</th>
<th colspan="1" style="width:15%; border:1px solid #000;">Discount</th>
<th colspan="1" style="width:5%; border:1px solid #000;">After Discount</th>
</tr>
<?php
$sub_procedure_counter = 0;
$package_suggestion_list = unserialize($data['package_suggestion_list']);

if ($package_suggestion_list !== false && is_array($package_suggestion_list)) {
    foreach ($package_suggestion_list as $package_name => $group) {
        if (is_numeric($package_name)) {
            $package_name = "Package " . ($package_name + 1); // fallback label
        }

        $procedure_ids = explode(',', $group);
        $rowspan = count($procedure_ids); // Count rows under this package
        $firstRow = true;

        foreach ($procedure_ids as $procedure_id) {
            $procedure_id = trim($procedure_id);
			
			$sql4 = "SELECT * From hms_procedure_package where  procedure_id='$procedure_id'";
			$select_result4 = run_select_query($sql4);
			
			if (isset($select_result4['Includes'])) {
                $last_includes = $select_result4['Includes'];
            }
			if (isset($select_result4['excludes'])) {
                $last_excludes = $select_result4['excludes'];
            }
			if (isset($select_result4['restrictions'])) {
                $last_restrictions = $select_result4['restrictions'];
            }
			if (isset($select_result4['treatment_conclusion'])) {
                $last_treatment_conclusion = $select_result4['treatment_conclusion'];
            }
			if (isset($select_result4['package_id'])) {
                $last_package_id = $select_result4['package_id'];
            }
			
            if (!empty($procedure_id)) {
                $sql_quefe = "SELECT * FROM `hms_procedures` WHERE ID = '$procedure_id'";
                $femalemed_result = run_select_query($sql_quefe);

                if ($femalemed_result) {
                    $sub_procedure_counter++;
                    ?>
                    <tr>
                        <?php if ($firstRow): ?>
                            <td colspan="1" rowspan="<?= $rowspan ?>" style="font-size:14px; border:1px solid #000; vertical-align: middle;">
                                <input type="text" id="package_name" name="package_name" value="<?php echo $select_result4['package_name']; ?>" readonly>	
                            </td>
                        <?php 
                            $firstRow = false;
                        endif; ?>
                        <td colspan="1" style="font-size:14px; border:1px solid #000;">
							 <input type="hidden" id="procedure_ID_<?= $sub_procedure_counter ?>" name="procedure_ID_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($femalemed_result['ID']) ?>" readonly>
                            <input type="text" id="procedure_name_<?= $sub_procedure_counter ?>" name="procedure_name_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($femalemed_result['procedure_name']) ?>" readonly>
                        </td>
                        <td colspan="1" style="font-size:14px; border:1px solid #000;">
                            <input type="text" id="code_<?= $sub_procedure_counter ?>" name="code_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($femalemed_result['code']) ?>" readonly>
                        </td>
                        <td colspan="1" style="font-size:14px; border:1px solid #000;">
                            <input type="text" id="price_<?= $sub_procedure_counter ?>" name="price_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($femalemed_result['price']) ?>" readonly>
                        </td>
						<td colspan="1" style="font-size:14px; border:1px solid #000;">
							<input type="text" id="discount_<?= $sub_procedure_counter ?>" name="discount_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($femalemed_result['discount']) ?>" oninput="calculateAfterDiscount(<?= $sub_procedure_counter ?>)">
                        </td>
                       <td colspan="1" style="font-size:14px; border:1px solid #000;">
							<input type="text" id="after_discount_<?= $sub_procedure_counter ?>" name="after_discount_<?= $sub_procedure_counter ?>" value="<?= htmlspecialchars($femalemed_result['after_discount']) ?>" readonly>
						</td>
                    </tr>
                    <?php
                }
            }
        }
    }
} else {
    echo "<tr><td colspan='6'>No valid data found.</td></tr>";
}
?>

</tbody>
</table>

<table width="100%" class="">
<tbody>
<tr><td colspan="6"><strong style="margin-left:20px;">Terms &amp; Conditions (The above-mentioned package)</strong></td></tr>
<tr><td colspan="6"><strong>Includes:</strong></td></tr>
<tr><td colspan="6"><p style="margin-left:20px;font-size:14px;"><?= nl2br($last_includes) ?></p></td></tr>
<tr><td colspan="6"><strong>Excludes:</strong></td></tr>
<tr><td colspan="6"><p style="margin-left:20px;font-size:14px;"><?= nl2br($last_excludes) ?></p></td></tr>
<?php if (!empty($last_restrictions)) : ?>
<tr><td colspan="6"><strong>Restrictions:</strong></td></tr>
<tr><td colspan="6"><p style="margin-left:20px;font-size:14px;"><?= nl2br($last_restrictions) ?></p></td></tr>
<?php endif; ?>
<?php if (!empty($last_treatment_conclusion)) : ?>
<tr><td colspan="6"><strong>Restrictions:</strong></td></tr>
<tr><td colspan="6"><p style="margin-left:20px;font-size:14px;"><?= nl2br($last_treatment_conclusion) ?></p></td></tr>
<?php endif; ?>
<tr>
<td colspan="6"><strong>Payment Structure:</strong></td>
</tr>
<tr>
<td colspan="6">

<?= nl2br($payment_structure) ?>
</td>
</tr>

<tr>
<td colspan="6"><strong>Embryology-Related Terms & Conditions</strong></td>
</tr>
<tr>
<td colspan="6">
<p style="margin-left:20px;font-size:14px;">Outcomes related to oocyte quality, fertilization, embryo development, freezing, thawing, and transfer cannot be guaranteed, as they depend on biological factors beyond human control.
Charges for embryology services are applicable once the service is initiated, irrespective of the outcome.
</p>
</td>
</tr>

<tr>
<td colspan="6">
<p style="margin-left:20px;font-size:14px;">IVF success rates are statistical and depend on medical variables including age, ovarian reserve, sperm quality, uterine receptivity, and other biological factors; accordingly, the Clinic gives no assurance, representation, or Guarantee of IVF success, pregnancy, implantation, live birth, or the genetic normalcy of the child. The Clinic shall not be held liable for treatment failure, miscarriage, congenital anomalies, or medical complications arising from inherent biological limitations.
</p>
</td>
</tr>

<tr><td colspan="6"><strong>Note:</strong> Booking amount not refundable and 25% of package cost should be deposited within 10 days of Registration, failing to
which the package will automatically stand cancelled without prior notification.</td></tr>
<tr><td><strong>►We do not do preconception sex selection and we don’t allow sex determination</strong></td></tr>
</tbody>
</table>
<table width="100%" class="">
<tbody>
<tr><td colspan="6"><strong>Payment Details: </strong></td></tr>	
<tr>
<td colspan="2" style="font-size:12px;">Total Package</td>	
<td colspan="2">Rs: <input type="text" id="total_after_discount" name="total_after_discount" value="<?php echo $select_result['total_after_discount']; ?>" style="border-top:0px;border-left:0px;border-right:0px;"></td>
<td colspan="2">Date: <input type="date" id="package_date" name="package_date" value="<?php echo $select_result['package_date']; ?>" style="border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>
<?php
$excluded_ids = [11, 12, 13, 14, 15];

if (!in_array($last_package_id, $excluded_ids)) {
?>
<tr>
    <td colspan="2" style="font-size:12px;">Booking Amount (10%)</td>    
    <td colspan="2">Rs: 
        <input type="text" id="booking_amount" value="<?= number_format($grand_total_price * 0.10, 2) ?>" name="booking_amount" style="border-top:0px;border-left:0px;border-right:0px;">
    </td>
    <td colspan="2">Date: 
        <input type="date" value="<?php echo $select_result['booking_date']; ?>" id="booking_date" name="booking_date" style="border-top:0px;border-left:0px;border-right:0px;">
    </td>
</tr>
<tr>
    <td colspan="2" style="font-size:12px;">Deposit on the start of treatment Self / Third Party (40%)</td>    
    <td colspan="2">Rs: 
        <input type="text" id="booking_amount_40" name="booking_amount_40" value="<?= number_format($grand_total_price * 0.40, 2) ?>" style="border-top:0px;border-left:0px;border-right:0px;">
    </td>
    <td colspan="2">Date: 
        <input type="date" value="" style="border-top:0px;border-left:0px;border-right:0px;">
    </td>
</tr>
<tr>
    <td colspan="2" style="font-size:12px;">Deposit on the Day of Trigger (50%)</td>    
    <td colspan="2">Rs: 
        <input type="text" id="booking_amount_50" name="booking_amount_50" value="<?= number_format($grand_total_price * 0.50, 2) ?>" style="border-top:0px;border-left:0px;border-right:0px;">
    </td>
    <td colspan="2">Date: 
        <input type="date" value="" style="border-top:0px;border-left:0px;border-right:0px;">
    </td>
</tr>
<?php
}
?>
</thead>
</table>

<table style="width:100%;" id="male_medicine_table" border="1">
<tbody id="male_medicine_suggestion_table" style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td colspan="2" style="font-size:14px;width:40%">Husband Name: <input type="text" readonly="" id="" value="<?php echo $select_result3['husband_name']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>	
<td colspan="2" style="font-size:14px;">Wife Name: <input type="text"  id="" readonly="" value="<?php echo $select_result3['wife_name']; ?>"style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>
<td colspan="2" style="font-size:14px;">Counsellor Name: <input type="text" readonly="" name="counsellor_signature" id="counsellor_signature" value="<?php echo $_SESSION['logged_counselor']['name']?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>

<tr>
<td colspan="2" style="font-size:14px;width:40%">Husband Signature: <input type="text"  id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>	
<td colspan="2" style="font-size:14px;">Wife Signature: <input type="text"  id="name29" value=""style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>
<td colspan="2" style="font-size:14px;">Counsellor Signature: <input type="text"  name="" id="" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>

<tr>
<td colspan="2" style="font-size:14px;width:40%">Coordinator Signature : <input type="text"  id="coordinator_signature" name="coordinator_signature" value="<?php echo $select_result['coordinator_signature']; ?>" style="width:100px;border-top:0px;border-left:0px;border-right:0px;"></td>	
<td colspan="2">Date: <input type="date"  id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
<td colspan="2">Time: <input type="time"   id="name30" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>
</tbody>
</table>

<table id="male_medicine_table">
<tbody id="male_medicine_suggestion_table" style="padding:10px; width:100%;">
<tr>
<td colspan="6" style="font-size:14px;text-align:center;"><strong>Medical Management | Fertility enhancing surgeries | Follicular monitoring | IUI | IVF-ICSI | Egg Donation |
Surrogacy | Embryo Freezing | Male Infertility | TESA/PESA | Laparo-hystero Surgeries |</strong></td>	
</tr>
</tbody>
</table>
<input type='submit' id='btnsubmit' value='Submit' class="btn btn-primary pull-right">
</form>
<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv2();'>
</div> 
</div>
</div>
</div>


<div class="row" id="print_this_section2" style="display:none;">
<form action="" enctype='multipart/form-data' method="post">
<div class="ga-pro">
<table style="width:100%;" class="fg45yu">
  <tr>
   <td style="width:20%;padding:5px;" colspan="2" rowspan="2"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td colspan="1" style="font-size:14px;">Name of Wife : </td>
   <td colspan="1"><input type="text" id="wife_name" name="wife_name" class="form-control" value="<?php echo $select_result3['wife_name']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
   <td colspan="1" style="font-size:14px;">Age :</td>
   <td colspan="1"><input type="text" id="wife_age" name="wife_age" class="form-control" value="<?php echo $select_result3['wife_age']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
  </tr>
  <tr>
<td colspan="1" style="font-size:14px;">Name of Husband:</td>
<td colspan="1"><input type="text" name="husband_name" id="husband_name" class="form-control" value="<?php echo $select_result3['husband_name']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
<td colspan="1" style="font-size:14px;">Age :</td>
<td colspan="1"><input type="text" id="husband_age" name="husband_age" class="form-control" value="<?php echo $select_result3['husband_age']; ?>" style="width:150px;border-top:0px;border-left:0px;border-right:0px;" readonly=""></td>
</tr>
</table>

<table style="width:100%; border:1px solid #000;margin-top:20px;" id="">
<tbody>
<tr>
<th colspan="1" style="width:25%; border:1px solid #000;">Package Name</th>
<th colspan="1" style="width:40%; border:1px solid #000;">Package Details</th>
<th colspan="1" style="width:15%; border:1px solid #000;">Code</th>
<th colspan="1" style="width:20%; border:1px solid #000;">Amount</th>
<th colspan="1" style="width:20%; border:1px solid #000;">Discount</th>
<th colspan="1" style="width:20%; border:1px solid #000;">After Discount</th>
</tr>
<?php
$sub_procedure_counter = 0;
$package_suggestion_list = unserialize($data['package_suggestion_list']);

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

                if ($femalemed_result && isset($femalemed_result['price'])) {
                    $package_total_price += $femalemed_result['price'];
                }
            }
        }
		$grand_total_price += $package_total_price;
        ?>
        <tr>
            <td style="font-size:14px; border:1px solid #000; vertical-align: middle; width:25%;">
                <?= htmlspecialchars($select_result4['package_name']) ?>
            </td>
            <td style="font-size:14px; border:1px solid #000; width:25%;">
                <?= htmlspecialchars($select_result4['details']) ?>
            </td>
            <td style="font-size:14px; border:1px solid #000; width:20%;">
                <?= htmlspecialchars($select_result4['Name']) ?>
            </td>
            <td style="font-size:14px; font-weight:bold; border:1px solid #000; text-align:right; width:10%;">
                ₹<?= number_format($package_total_price, 2) ?>
            </td>
			 <td style="font-size:14px; font-weight:bold; border:1px solid #000; text-align:right; width:10%;">
                ₹<?= number_format($package_total_price - $select_result['total_after_discount']); ?>
            </td>
			 <td style="font-size:14px; font-weight:bold; border:1px solid #000; text-align:right; width:10%;">
                ₹<?= $select_result['total_after_discount']; ?>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='6'>No valid data found.</td></tr>";
}
?>
</tbody>
</table>
<table>
<tbody>

<tr>
<td colspan="6">
<strong>Terms & Conditions (The above-mentioned package)</td>
</tr>
<tr>
<td colspan="6"><strong>Includes:</strong></td>
</tr>
<tr>
<td colspan="6"><?= nl2br($last_includes) ?></td>
</tr>
<tr>
<td colspan="6">
<strong>Excludes:</strong>
</td>
</tr>
<tr>
<td colspan="6"><?= nl2br($last_excludes) ?></td>
</tr>
<?php if (!empty($last_restrictions)) : ?>
<tr><td colspan="6"><strong>Restrictions:</strong></td></tr>
<tr><td colspan="6"><p style="margin-left:20px;font-size:14px;"><?= nl2br($last_restrictions) ?></p></td></tr>
<?php endif; ?>
<?php if (!empty($last_treatment_conclusion)) : ?>
<tr><td colspan="6"><strong>Restrictions:</strong></td></tr>
<tr><td colspan="6"><p style="margin-left:20px;font-size:14px;"><?= nl2br($last_treatment_conclusion) ?></p></td></tr>
<?php endif; ?>
<tr>
<td colspan="6"><strong>Payment Structure:</strong></td>
</tr>
<tr>
<td colspan="6">
<p style="margin-left:20px;font-size:14px;">10% of the total package amount is payable as a booking amount at the time of registration.<br/>
40% of the amount must be paid before initiation of ovarian stimulation.<br/>
The remaining 50% must be paid before the trigger injection.</p>
<p style="margin-left:20px;font-size:14px;">Any add-on procedures or services (such as ICSI, blastocyst culture, freezing, thawing, etc.) will be informed to the patient in advance and charged separately as applicable.<br/>
The booking amount is non-refundable under any circumstances.
<br/>
The booking is valid for a period of 60 days or 02 months from the date of payment. After this period, re-booking charges may apply.
</p>
</td>
</tr>

<tr>
<td colspan="6"><strong>Embryology-Related Terms & Conditions</strong></td>
</tr>
<tr>
<td colspan="6">
<p style="margin-left:20px;font-size:14px;">Outcomes related to oocyte quality, fertilization, embryo development, freezing, thawing, and transfer cannot be guaranteed, as they depend on biological factors beyond human control.
Charges for embryology services are applicable once the service is initiated, irrespective of the outcome.
</p>
</td>
</tr>

<tr>
<td colspan="6">
<p style="margin-left:20px;font-size:14px;">IVF success rates are statistical and depend on medical variables including age, ovarian reserve, sperm quality, uterine receptivity, and other biological factors; accordingly, the Clinic gives no assurance, representation, or Guarantee of IVF success, pregnancy, implantation, live birth, or the genetic normalcy of the child. The Clinic shall not be held liable for treatment failure, miscarriage, congenital anomalies, or medical complications arising from inherent biological limitations.
</p>
</td>
</tr>

<tr>
<td colspan="6">
Note: Booking amount not refundable and 25% of package cost should be deposited within 10 days of Registration, failing to which the package will automatically stand cancelled without prior notification.
</td>
</tr>
<tr>
<td colspan="6">
►We do not do preconception sex selection and we don’t allow sex determination</td>
</tr>
</tbody>
</table> 

<table width="100%" class="">
<tbody>
<tr><td colspan="6"><strong>Payment Details:</strong></td></tr>	
<tr>
<td width="50%" colspan="2" style="font-size:12px;">Total Package</td>	
<td width="25%" colspan="2">Rs: <input type="text" readonly="" id="name29" value="<?php echo $select_result['total_after_discount']; ?>" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
<td width="25%" colspan="2">Date: <input type="text" value="<?php echo $select_result['package_date']; ?>" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
</tr>
<tr>
<td width="50%" colspan="2" style="font-size:12px;">Booking Amount (10 %)</td>	
<td width="25%" colspan="2">Rs: <input type="text" id="name29" value="<?php echo $select_result['booking_amount']; ?>" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
<td width="25%" colspan="2">Date: <input type="text"   id="name30" value="<?php echo $select_result['booking_date']; ?>" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
</tr>
<tr>
<td width="50%" colspan="2" style="font-size:12px;">Deposit on the start of treatment Self /Third Party (40%)</td>	
<td width="25%" colspan="2">Rs: <input type="text" id="name29" value="" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
<td width="25%" colspan="2">Date: <input type="text" id="name30" value="" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
</tr>
<tr>
<td width="50%" colspan="2" style="font-size:12px;">Deposit on the Day of trigger (50%)</td>	
<td width="25%" colspan="2">Rs: <input type="text" id="name29" value="" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
<td width="25%" colspan="2">Date: <input type="text" id="name30" value="" style="border-top:0px;border-left:0px;border-right:0px;width:200px;"></td>
</tr>
</thead>
</table>

<table style="width:100%;" id="male_medicine_table" border="1">
<tbody id="male_medicine_suggestion_table" style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td colspan="2" style="font-size:14px;width:40%">Husband Name: <input type="text" readonly="" id="" value="<?php echo $select_result3['husband_name']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>	
<td colspan="2" style="font-size:14px;">Wife Name: <input type="text"  id="" readonly="" value="<?php echo $select_result3['wife_name']; ?>"style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>
<td colspan="2" style="font-size:14px;">Counsellor Name: <input type="text" readonly="" name="counsellor_signature" id="counsellor_signature" value="<?php echo $_SESSION['logged_counselor']['name']?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>

<tr>
<td colspan="2" style="font-size:14px;width:40%">Husband Signature: <input type="text"  id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>	
<td colspan="2" style="font-size:14px;">Wife Signature: <input type="text"  id="name29" value=""style="width:200px;border-top:0px;border-left:0px;border-right:0px;" ></td>
<td colspan="2" style="font-size:14px;">Counsellor Signature: <input type="text"  name="" id="" value="<?php echo $select_result['counsellor_signature']; ?>" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>

<tr>
<td colspan="2" style="font-size:14px;width:40%">Coordinator Signature : <input type="text"  id="coordinator_signature" name="coordinator_signature" value="<?php echo $select_result['coordinator_signature']; ?>" style="width:100px;border-top:0px;border-left:0px;border-right:0px;"></td>	
<td colspan="2">Date: <input type="date"  id="name29" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
<td colspan="2">Time: <input type="time"   id="name30" value="" style="width:200px;border-top:0px;border-left:0px;border-right:0px;"></td>
</tr>
</tbody>
</table>

<table id="male_medicine_table">
<tbody id="male_medicine_suggestion_table" style="padding:10px; width:100%;">
<tr>
<td colspan="6" style="font-size:14px;text-align:center;"><strong>Medical Management | Fertility enhancing surgeries | Follicular monitoring | IUI | IVF-ICSI | Egg Donation |
Surrogacy | Embryo Freezing | Male Infertility | TESA/PESA | Laparo-hystero Surgeries |</strong></td>	
</tr>
</tbody>
</table>

</form>
</div>

<style>
td strong {
    font-size: 13px!important;
	line-height:30px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
table tr td{
	line-height:30px;
	font-size:14px;	
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999px;
    opacity: 1!important;
}
</style>
<script>
function printDiv2() {
  $('.hide_print').hide();
  $('input[type="submit"]').css('visibility', 'hidden');
  $('p#last_updated').css('visibility', 'hidden');
  var divToPrint=document.getElementById('print_this_section2');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>  


<script>
function calculateAfterDiscount(counter) {
    const priceField = document.getElementById('price_' + counter);
    const discountField = document.getElementById('discount_' + counter);
    const afterDiscountField = document.getElementById('after_discount_' + counter);

    let price = parseFloat(priceField.value) || 0;
    let discount = parseFloat(discountField.value) || 0;
    let afterDiscount = price - discount;

    afterDiscountField.value = afterDiscount.toFixed(2);

    // After each row calculation, also update total
    calculateTotalAfterDiscount();
}

function calculateTotalAfterDiscount() {
    let total = 0;
    let i = 1;
    let maxIterations = 1000; // Safety limit to prevent infinite loops
    let iterations = 0;

    while (iterations < maxIterations) {
        let field = document.getElementById('after_discount_' + i);
        if (!field) break;

        let val = parseFloat(field.value) || 0;
        total += val;
        i++;
        iterations++;
    }

    const totalField = document.getElementById('total_after_discount');
    if (totalField) {
        totalField.value = total.toFixed(2);
    }
}

// Run on page load and attach listeners
document.addEventListener('DOMContentLoaded', function () {
    const maxCount = 50; // Adjust based on maximum number of rows
    for (let i = 1; i <= maxCount; i++) {
        let discountInput = document.getElementById('discount_' + i);
        if (discountInput) {
            discountInput.addEventListener('input', function () {
                calculateAfterDiscount(i);
            });

            // Also run calculation initially
            calculateAfterDiscount(i);
        }
    }
});
</script>







