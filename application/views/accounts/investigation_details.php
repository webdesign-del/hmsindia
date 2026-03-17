<?php $all_method =&get_instance();
$center= get_center_name($data['billing_at']);
	$patient_data = get_patient_detail($data['patient_id']);
	$center_data = center_detail($center);
	$currency = '';
$status = check_billing_status($data['patient_id'], $data['receipt_number'], 'investigation');
$status = check_billing_status($data['patient_id'], $data['receipt_number'], 'investigations');

 $sql = "SELECT * FROM `hms_doctor_consultation` WHERE appointment_id=".$data['appointment_id']."";
$select_result = run_select_query($sql);

$sql2 = "SELECT * FROM `hms_doctors` WHERE ID=".$select_result['doctor_id']."";
$select_result2 = run_select_query($sql2);

$sql_appointments = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$patient_data['wife_phone']."' and paitent_type='new_patient'";
$appointments_result = run_select_query($sql_appointments);

$sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$appointments_result['appoitment_for']."'";
$select_result3 = run_select_query($sql3);

$sql_center = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$data['billing_at']."'";
$center_result = run_select_query($sql_center);
?>
<style>
#print_this_section{margin-top:50px;}
</style>
<div class="col-sm-12 col-xs-12  panel panel-piluku ml-5">
    <button class="btn btn-primary" onclick="window.history.go(-1)">Back</button>
<input type='button' id='btn' value='Print Receipt' class="btn btn-primary pull-right" onclick='printReceipt();'>
	<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>
<input type='button' id='btn' value='Print and Send' class="btn btn-primary pull-right" onclick='sendonwhatsapp("<?php echo $patient_data['wife_phone']; ?>");'>

  <div class="panel-body profile-edit">
	<h3>Billing Details</h3>
  <hr />
  <?php echo $status; ?>
	<table style="width:100%">
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Receipt number:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['receipt_number'];?></td>
  </tr>
   <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">UHID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $select_result3['center_code']."/".$appointments_result['uhid']; ?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">IIC ID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['patient_id'];?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Patient Name:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo strtoupper($patient_data['wife_name']);?></td>
  </tr>
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Total package:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['totalpackage'];?></td>
  </tr>
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discounted package:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['fees'];?></td>
  </tr> 
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Paid:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['payment_done'];?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Balance:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['remaining_amount'];?></td>
  </tr>
  <?php if($data['discount_amount'] > 0){?>
      <tr>
        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount amount:</th>
        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['discount_amount'];?></td>
      </tr>
  <?php } ?>
    <tr>
      <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Payment Method:</th>
      <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo strtoupper($data['payment_method']);?></td>
    </tr>
  <?php if($data['payment_method'] == "insurance"){?>
    <tr>
      <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Subvention charges:</th>
      <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['subvention_charges'];?></td>
    </tr>
  <?php } ?>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Transaction ID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['transaction_id'];?>
    	<?php if(!empty($data['transaction_img'])){?> <a href="<?php echo $data['transaction_img']; ?>" class="hide_print"  target="_blank">Download transaction Image</a> <?php }?>
    </td>
    
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">On date:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo date('d/m/Y H:i:s', strtotime($data['on_date']));?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Billing Source:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php if($data['billing_from'] == 'IndiaIVF') echo $data['billing_from']; else echo get_center_name($data['billing_from']);?></td>
  </tr>
  
    <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Billing At:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo get_center_name($data['billing_at']);?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Hospital ID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['hospital_id'];?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Reason of visit:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['reason_of_visit'];?></td>
  </tr>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Status:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo ucwords($data['status']);?></td>
  </tr>
  <?php if($data['status'] == 'disapproved'){?>
  <tr>
    <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Reason of disapprove:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo ucwords($data['reason_of_disapprove']);?></td>
  </tr>
 <?php } ?>
</table>
    <hr />
    <div class="row medical_infor">
        <?php echo patient_medical_info($data['patient_id']); ?>
    </div>
    <hr />
    <div>
    <?php if(!empty($data['investigations'])){?>
          <h3>Investigation details</h3>
          <hr />
          <?php
             $investigations = unserialize($data['investigations']);
             if(!empty($investigations['male_investigation'])){
          ?>  
            <h4>Male Investigations</h4>
              <table style="width:100%">
                    <tr>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>
                    </tr>
                    <?php $total_fees = 0;
                    foreach($investigations['male_investigation'] as $key => $val){
                    ?>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php
    $inv = $val['male_investigation_name'] ?? '';

    if (!empty($inv) && !is_numeric($inv)) {
        echo htmlspecialchars($inv); // Already a name, show as-is
    } else {
        echo htmlspecialchars($all_method->get_investigation_name($inv)); // ID, so look it up
    }
?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['male_investigation_code']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['male_investigation_price']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['male_investigation_discount']?></td>
                    </tr>
                    <?php $total_fees += $val['male_investigation_price']; } ?>
              </table>
            <?php } if(!empty($investigations['female_investigation'])){ ?>
              <h4>Female Investigations</h4>
              <table style="width:100%">
                    <tr>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>
                    </tr>
                    <?php $total_fees = 0;
                foreach($investigations['female_investigation'] as $key => $val){ //var_dump($val);die;
                    ?>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php
    $inv = $val['female_investigation_name'] ?? '';

    if (!empty($inv) && !is_numeric($inv)) {
        echo htmlspecialchars($inv); // Already a name, show as-is
    } else {
        echo htmlspecialchars($all_method->get_investigation_name($inv)); // ID, so look it up
    }
?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['female_investigation_code']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['female_investigation_price']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['female_investigation_discount']?></td>
                    </tr>
                    <?php $total_fees += $val['female_investigation_price']; } ?>
              </table>
            <?php } ?>
    <?php } ?>
    </div>
</div>

<div class="panel-body profile-edit" id="print_this_section" style="display:none;">

   <table style="width:100%;">
  <tr>
    <td  style="padding:5px; text-align:left;width:70%;" colspan="1">
	<p><strong>Tax Invoice/Bill of Supply/Cash Memo</strong></p>
	<p>(Original for Recipient)</p>
	</td>
	<td colspan="1"></td>
    <td style="padding:5px; text-align:left;width:30%;" colspan="1">
	<strong>Invoice no: <?php echo $data['receipt_number'];?></strong><br/>
	<strong>Date : <?php echo date('d/m/Y', strtotime($data['on_date']));?></strong>
	</td>
  </tr>
</table>

<table style="width:100%; margin-top:20px;">
  <tr>
    <td rowspan="2" colspan="1" style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;width:50%;">
<?php
$fields = [
     'company_name' => '',
    'dl_number' => 'DL Number:',
    'fssai_license_no' => 'FSSAI License No:',
    'center_gst' => 'GSTIN:',
    'art' => 'ART No:',
    'hospital_licence' => 'Hospital License No:',
    'cin' => 'CIN:',
    'center_address' => 'Premise Address:'
];

foreach($fields as $key => $label){
    if(!empty($center_data[$key])){
        if($key == 'company_name'){
            echo "<p><strong>{$center_data[$key]}</strong></p>";
        }else{
            echo "<p><strong>{$label}</strong> {$center_data[$key]}</p>";
        }
    }
}
?>

<?php if($center_data['operate_other'] == "1"){ 

echo $center_data['investigation_others'];

 } ?>
	</td>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;">
	<p>Bill To</p>
	<p><strong>Patient Name :</strong><?php echo strtoupper($patient_data['wife_name']);?></p>
	<p><strong>Address :</strong><?php $sql4 = "Select * from ".$this->config->item('db_prefix')."patients where patient_id='".$data['patient_id']."'"; 
			            $query = $this->db->query($sql4);
                            $select_result4 = $query->result(); 
							foreach ($select_result4 as $res_val4){
							echo $res_val4->wife_address;
							} ?></p>
    <p><strong>Patient Id :</strong> <?php echo $data['patient_id'];?></p>
	<p><strong>UHID : </strong><?php echo $select_result3['center_code']."/".$appointments_result['uhid']; ?>
	 </p>
    <p><strong>Gender :</strong> F</p>
	</td>
  </tr>
   <tr>
    <td colspan="1" style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;width:50%;"><?php echo $select_result2['doctor_details']; ?></td>
  </tr>
</table>


<hr />
    <?php if(!empty($data['investigations'])){?>
          <h3>Investigation details</h3>
          <hr />
          <?php
             $investigations = unserialize($data['investigations']);
             if(!empty($investigations['male_investigation'])){
          ?>  
            <h4>Male Investigations</h4>
              <table style="width:100%">
                    <tr>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">GST</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Net Ammount</th>
                    </tr>
                    <?php 
					$total_fees = 0;
					$totalmale_invprice = 0;
                    foreach($investigations['male_investigation'] as $key => $val){
						$price = floatval($val['male_investigation_price']);
						$discount = floatval($val['male_investigation_discount']);
						if ($discount != 0) {
						$totalmale_invprice = $price - ($price * $discount / 100);
					} else {
						$totalmale_invprice = 0;
						echo "Warning: Discount value is zero, cannot divide.";
					}
                    ?>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php
    $inv = $val['male_investigation_name'] ?? '';

    if (!empty($inv) && !is_numeric($inv)) {
        echo htmlspecialchars($inv); // Already a name, show as-is
    } else {
        echo htmlspecialchars($all_method->get_investigation_name($inv)); // ID, so look it up
    }
?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['male_investigation_code']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['male_investigation_price']?></td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($val['male_investigation_price'] - $totalmale_invprice,2); ?></td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">0</td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($totalmale_invprice,2); ?></td>
                    	
					</tr>
                    <?php $total_fees += $val['male_investigation_price']; } ?>
              </table>
            <?php } if(!empty($investigations['female_investigation'])){ ?>
              <h4>Female Investigations</h4>
              <table style="width:100%">
                    <tr>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">GST</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Net Ammount</th>
                    </tr>
                    <?php $total_fees = 0;
					$total_invprice = 0;
                foreach($investigations['female_investigation'] as $key => $val){ //var_dump($val);die;
					$price = floatval($val['female_investigation_price']);
					$discount = floatval($val['female_investigation_discount']);

					if ($discount != 0) {
						$total_invprice = $price - ($price * $discount / 100);
					} else {
						$total_invprice = 0;
						echo "Warning: Discount value is zero, cannot divide.";
					}

                    ?>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php
    $inv = $val['female_investigation_name'] ?? '';

    if (!empty($inv) && !is_numeric($inv)) {
        echo htmlspecialchars($inv); // Already a name, show as-is
    } else {
        echo htmlspecialchars($all_method->get_investigation_name($inv)); // ID, so look it up
    }
?>
</td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['female_investigation_code']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['female_investigation_price']?></td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($val['female_investigation_price'] - $total_invprice,2); ?></td>
                       <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">0</td>
					   <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($total_invprice,2); ?></td>
						
					</tr>
                    <?php $total_fees += $val['female_investigation_price']; } ?>
              </table>
            <?php } ?>
    <?php } ?>
	
	<table style="width:60%;float:right;margin-top:40px;"> 
				
				
  <tr>
   
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">GROSS AMOUNT</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency; ?><?php echo $data['totalpackage'];?></th>
  </tr>
  <tr>
   
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">DISCOUNT AMOUNT:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency; ?><?php echo $data['discount_amount'];?></th>
  </tr>
   <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">GST AMOUNT:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1">0</th>
  </tr>
  <tr>
    
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">PAID AMOUNT:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency; ?><?php echo $data['payment_done'];?></th>
  </tr>
</table>

<div style="width:100%; margin-top:300px;text-align:center;">
<p>This is computer generated invoice/receipt, does not need signature</p>
<p>This Healthcare Services is exempted from GST vide Notification No. 12/2017-Central Tax (Rate) dated 28-06-2017 and Notification No.9/2017 – Integrated Tax (Rate) dated 28-06-2017.</p>
</div>   

</div>
</div>


<div class="panel-body profile-edit" id="print_invoice" style="display:none;">

   <table style="width:100%;">
  <tr>
    <td  style="padding:5px; text-align:left;width:60%;" colspan="1">
	<p><strong>Tax Invoice/Bill of Supply/Cash Memo</strong></p>
	<p>(Original for Recipient)</p>
	</td>
	<td colspan="1"></td>
    <td style="padding:5px; text-align:left;width:40%;" colspan="1">
	<strong>Invoice no: <?php echo $data['receipt_number'];?></strong><br/>
	<strong>Date : <?php echo date('d/m/Y', strtotime($data['on_date']));?></strong>
	</td>
  </tr>
</table>

<table style="width:100%; margin-top:20px;">
  <tr>
    <td rowspan="2" colspan="1" style="font-size:12px;line-height:12px;border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;width:50%;">
	<?php
$fields = [
    'company_name' => '',
    'center_gst' => 'GSTIN:',
    'dl_number' => 'DL Number:',
    'fssai_license_no' => 'FSSAI License No:',
    'art' => 'ART No:',
    'hospital_licence' => 'Hospital License No:',
    'cin' => 'CIN:',
    'center_address' => 'Premise Address:'
];

foreach($fields as $key => $label){
    if(!empty($center_data[$key])){
        if($key == 'company_name'){
            echo "<p><strong>{$center_data[$key]}</strong></p>";
        }else{
            echo "<p><strong>{$label}</strong> {$center_data[$key]}</p>";
        }
    }
}
?>

<?php if($center_data['operate_other'] == "1"){ 

echo $center_data['investigation_others'];

 } ?>
	</td>
    <td style="font-size:12px;line-height:12px;border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;">
	<p>Bill To</p>
	<p><strong>Patient Name :</strong><?php echo strtoupper($patient_data['wife_name']);?></p>
	<p><strong>Address :</strong><?php $sql4 = "Select * from ".$this->config->item('db_prefix')."patients where patient_id='".$data['patient_id']."'"; 
			            $query = $this->db->query($sql4);
                            $select_result4 = $query->result(); 
							foreach ($select_result4 as $res_val4){
							echo $res_val4->wife_address;
							} ?></p>
    <p><strong>Patient Id :</strong> <?php echo $data['patient_id'];?></p>
	<p><strong>UHID : </strong><?php echo $select_result3['center_code']."/".$appointments_result['uhid']; ?>
	 </p>
    <p><strong>Gender :</strong> F</p>
	</td>
  </tr>
   <tr>
    <td colspan="1" style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;width:50%;"><?php echo $select_result2['doctor_details']; ?></td>
  </tr>
</table>


<hr />
    <?php if(!empty($data['investigations'])){?>
          <h3>Investigation details</h3>
          <hr />
          <?php
             $investigations = unserialize($data['investigations']);
             if(!empty($investigations['male_investigation'])){
          ?>  
            <h4>Male Investigations</h4>
              <table style="width:100%">
                    <tr>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">GST</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Net Ammount</th>
                    </tr>
                    <?php 
					$total_fees = 0;
					$totalmale_invprice = 0;
                    foreach($investigations['male_investigation'] as $key => $val){
						$price = floatval($val['male_investigation_price']);
						$discount = floatval($val['male_investigation_discount']);
						if ($discount != 0) {
						$totalmale_invprice = $price - ($price * $discount / 100);
					} else {
						$totalmale_invprice = 0;
						echo "Warning: Discount value is zero, cannot divide.";
					}
                    ?>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php
    $inv = $val['male_investigation_name'] ?? '';

    if (!empty($inv) && !is_numeric($inv)) {
        echo htmlspecialchars($inv); // Already a name, show as-is
    } else {
        echo htmlspecialchars($all_method->get_investigation_name($inv)); // ID, so look it up
    }
?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['male_investigation_code']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['male_investigation_price']?></td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($val['male_investigation_price'] - $totalmale_invprice,2); ?></td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">0</td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($totalmale_invprice,2); ?></td>
                    	
					</tr>
                    <?php $total_fees += $val['male_investigation_price']; } ?>
              </table>
            <?php } if(!empty($investigations['female_investigation'])){ ?>
              <h4>Female Investigations</h4>
              <table style="width:100%">
                    <tr>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>
                        <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">GST</th>
						<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Net Ammount</th>
                    </tr>
                    <?php $total_fees = 0;
					$total_invprice = 0;
                foreach($investigations['female_investigation'] as $key => $val){ //var_dump($val);die;
					$price = floatval($val['female_investigation_price']);
					$discount = floatval($val['female_investigation_discount']);

					if ($discount != 0) {
						$total_invprice = $price - ($price * $discount / 100);
					} else {
						$total_invprice = 0;
						echo "Warning: Discount value is zero, cannot divide.";
					}

                    ?>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php
    $inv = $val['female_investigation_name'] ?? '';

    if (!empty($inv) && !is_numeric($inv)) {
        echo htmlspecialchars($inv); // Already a name, show as-is
    } else {
        echo htmlspecialchars($all_method->get_investigation_name($inv)); // ID, so look it up
    }
?>
</td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['female_investigation_code']?></td>
                        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$val['female_investigation_price']?></td>
						<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($val['female_investigation_price'] - $total_invprice,2); ?></td>
                       <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">0</td>
					   <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo round($total_invprice,2); ?></td>
						
					</tr>
                    <?php $total_fees += $val['female_investigation_price']; } ?>
              </table>
            <?php } ?>
    <?php } ?>
	
	<table style="width:60%;float:right;margin-top:40px;"> 
				
				
  <tr>
   
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">GROSS AMOUNT</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency; ?><?php echo $data['totalpackage'];?></th>
  </tr>
  <tr>
   
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">DISCOUNT AMOUNT:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency; ?><?php echo $data['discount_amount'];?></th>
  </tr>
   <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">GST AMOUNT:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1">0</th>
  </tr>
  <tr>
    
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">PAID AMOUNT:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency; ?><?php echo $data['payment_done'];?></th>
  </tr>
</table>

<div style="width:100%; margin-top:250px;text-align:center;">
<p>This is computer generated invoice/receipt, does not need signature</p>
<p>This Healthcare Services is exempted from GST vide Notification No. 12/2017-Central Tax (Rate) dated 28-06-2017 and Notification No.9/2017 – Integrated Tax (Rate) dated 28-06-2017.</p>
</div>   

</div>

<div class="panel-body profile-edit" id="print_receipt" style="display:none;">

	<h3>Payment Receipt</h3>

    <hr />

  <table style="width:100%; border: 1px solid black; border-collapse: collapse;">
    <tr style="border:1px solid #000; width:100%; display:none;" id="medinfologo_tr">
        <td style="border:1px solid #000; width:100%;text-align:center;" colspan="3">
             <?php 
  if(!empty($center_data['upload_photo_1'])){
?>
<img src="<?php echo $center_data['upload_photo_1'];?>" class="img-responsive" style="width:200px" />
  <?php } else {?>

 <img src="<?php echo base_url('assets/images/indiaivf-logo.png'); ?>" class="img-responsive" style="width:200px" />
   
<?php  } ?>
        </td>
    </tr>

  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Payment ID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['receipt_number'];?></td>
  </tr>
   <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">UHID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></td>
  </tr>
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">IIC ID:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['patient_id'];?></td>
  </tr>
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Patient Name:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo strtoupper($patient_data['wife_name']);?></td>
  </tr>
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Paid Amount:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['payment_done'];?></td>
  </tr>
  
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Payment Method:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo strtoupper($data['payment_method']); ?></td>
  </tr>
  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">On date:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo date('d/m/Y H:i', strtotime($data['on_date']));?></td>
  </tr>
</table>
</div>

<script>
function printReceipt() 
{
  $('.hide_print').hide();
  $('#print_receipt').css('display', 'block');
  $('tr#medinfologo_tr').css('display', 'table-row');
  var divToPrint=document.getElementById('print_receipt');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
function printDiv() 
{
  $('.hide_print').hide();
  $('#print_invoice').css('display', 'block');
  $('tr#medinfologo_tr').css('display', 'table-row');
  var divToPrint=document.getElementById('print_invoice');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}

function sendonwhatsapp() 
{
    var data = {'iic_id':<?php echo $data['patient_id']; ?>, 'html': $("#print_this_section").html()};
    $('#whatsappmessg').hide();
	$.ajax({
		url: '<?php echo base_url('accounts/billhtmltopdf')?>',
		data: data,
		dataType: 'json',
		method:'post',
		success: function(data)
		{
		    if(data.status == 1){
                $('#whatsappmessg').empty().append('Bill has been sent to patient!');
		    }else{
		        $('#whatsappmessg').empty().append('Oops, something went wrong!');
		    }
		    $('#whatsappmessg').show();
		} 
	});

  $('.hide_print').hide();
  $('#print_this_section').css('display', 'block');
  $('tr#medinfologo_tr').css('display', 'table-row');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>