<?php
$center= get_center_name($data['billing_at']);
$all_method =&get_instance();
$patient_data = get_patient_detail($data['patient_id']);
$center_data = center_detail($center);
$currency = '';

$sql = "SELECT * FROM `hms_consultation` WHERE appointment_id=".$data['appointment_id']."";
$select_result3 = run_select_query($sql);
$sql2 = "SELECT * FROM `hms_doctors` WHERE ID=".$select_result3['doctor_id']."";
$select_result2 = run_select_query($sql2);

$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$patient_data['wife_phone']."' and paitent_type='new_patient'";
$select_result4 = run_select_query($sql4);
?>

<style>
  #print_this_section{margin-top:50px;}
</style>

<div class="col-sm-12 col-xs-12  panel panel-piluku ml-5">
<button class="btn btn-primary" onclick="window.history.go(-1)">Back</button>
<!--<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>-->
<input type='button' id='btn' value='Send to Patient' class="btn btn-primary pull-right" onclick='sendonwhatsapp("<?php echo $patient_data['wife_phone']; ?>");'>

  <div class="panel-body profile-edit">

	<h3 class="test">Billing Details</h3>
    <p id="whatsappmessg" style="display:none;"></p>
    <hr />

<table style="width:100%; border: 1px solid black; border-collapse: collapse;">

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Receipt number:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['receipt_number']; ?></td>

  </tr>
   
   <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">UHID:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $center_data['center_code']."/".$select_result4['uhid']; ?></td>

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

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Total package:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['totalpackage'];?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discounted package:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['fees'];?></td>

  </tr>

  

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Paid Amount:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['payment_done'];?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Remaining Amount:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['remaining_amount'];?></td>

  </tr>

  <?php if($data['discount_amount'] > 0){?>

      <tr>

        <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount amount:</th>

        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['discount_amount'];?></td>

      </tr>

      <tr>

        <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Reason of discount:</th>

        <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['reason_of_discount'];?></td>

      </tr>

  <?php } ?>

  

    <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Payment Method:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php if($data['fees'] == 0){echo strtoupper("free"); }else{ echo strtoupper($data['payment_method']);};?></td>

  </tr>

  <?php if($data['payment_method'] == "insurance"){?>
    <tr>
      <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Subvention charges:</th>
      <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['subvention_charges'];?></td>
    </tr>
  <?php } ?>

  <?php if($data['fees'] == 0){?>
    <tr>
      <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Free reason:</th>
      <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['free_reason'];?></td>
    </tr>
  <?php } ?>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Transaction ID:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['transaction_id'];?>

    	<?php if(!empty($data['transaction_img'])){?> <a href="<?php echo $data['transaction_img']; ?>" class="hide_print"  target="_blank">Download transaction Image</a> <?php }?>

    </td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">On date:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo date('d/m/Y H:i:s', strtotime($data['on_date']));?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Billing Source:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php if($data['billing_from'] == 'IndiaIVF') echo $data['billing_from']; else echo get_center_name($data['billing_from']);?></td>

  </tr>

  

    <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Billing At:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo get_center_name($data['billing_at']);?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Doctor:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Dr. <?php echo $all_method->get_doctor_name($data['doctor_id']);?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Consultation ID:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['consultation_id'];?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Hospital ID:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['hospital_id'];?></td>

  </tr>

  <tr>

    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Reason of visit:</th>

    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['reason_of_visit'];?></td>

  </tr>

  <tr>
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Status:</th>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo ucwords($data['status']);?></td>
  </tr>
  
  <?php if($data['status'] == "disapproved"){?>
    <tr>
      <th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Disapproved reason:</th>
      <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['reason_of_disapprove'];?></td>
    </tr>
  <?php } ?>

</table>

</div>

 
  <div class="panel-body profile-edit" id="print_this_section" style="display:none">
  
  
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
   <!-- <td rowspan="2" colspan="1" style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;width:50%;">
	<p><strong>Pashupati Lifecare Pvt. Ltd.</strong></p>
	<p><strong>DL Number: </strong> UP16200002826, UP16210002824 & UP1620F000057</p>
	<p><strong>FSSAI License No: </strong> 22723923000301</p>
    <p><strong>GSTIN NO:</strong> 09AAHCP5838M1ZP</p>
	<p><strong>CIN :</strong> U74999DL2014PTC264851</p>
	<p><strong>Premise Address:</strong> India IVF clinic(A unit of Pashupati Lifecare Pvt. Ltd.)
    Third Floor, N-26, Captain Vijayant Thapar Marg, Beside Dr Lal
    PathLabs, Sector 18, Noida, Gautambuddha Nagar, Uttar
    Pradesh, 201301</p>
	</td-->
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

echo $center_data['consultation_other'];

 } ?>
	</td>
	
    <td style="font-size:12px;line-height:12px; border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;">
	<p>Bill To</p>
	<p><strong>Patient Name :</strong><?php echo strtoupper($patient_data['wife_name']);?></p>
	<p><strong>Address :</strong><?php echo $patient_data['wife_address'] ?></p>
    <p><strong>Patient Id :</strong> <?php echo $data['patient_id'];?></p>
	<p><strong>UHID : </strong> <?php echo $center_data['center_code']."/".$select_result4['uhid']; ?></p>
    <p><strong>Gender :</strong> F</p>
	<!--<p><strong>Sac Code :</strong> 999312   </p>
	<p><strong>Place of Supply :</strong> Uttar Pradesh</p>
	<p><strong>State Code :</strong> 09</p>-->
	</td>
  </tr>
   <tr>
    <td colspan="1" style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;width:50%;"><?php echo $select_result2['doctor_details']; ?></td>
  </tr>
</table>

	<h3>Billing Details</h3>

    <hr />

<table style="width:100%; border: 1px solid black; border-collapse: collapse;">

 

  <tr>
     <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Billing For:</th>
     <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Total package:</th>
	 <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discounted:</th>
	 <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">GST:</th>
     <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Paid Amount:</th>
 
   
  </tr>

  <tr>

	<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">OPD CONSULTATION</td>
	<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['totalpackage'];?></td>
    <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">0</td>
	<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">0</td>
	<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $currency.$data['payment_done'];?></td>

  </tr>


 
</table>

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
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php echo $currency.$data['payment_done'];?></th>
  </tr>
  <tr>
    
    <th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:left;background:#f1f1f1;width:30%;" colspan="1">PAYMENT METHOD:</th>
	<th style="border: 1px solid black; border-collapse: collapse;padding:5px;text-align:right;background:#f1f1f1;width:30%;" colspan="1"><?php if($data['fees'] == 0){echo strtoupper("free"); }else{ echo strtoupper($data['payment_method']);};?></th>
  </tr>
</table>

<div style="width:100%; margin-top:300px;text-align:center;">
<p>This is computer generated invoice/receipt, does not need signature</p>
<p>This Healthcare Services is exempted from GST vide Notification No. 12/2017-Central Tax (Rate) dated 28-06-2017 and Notification No.9/2017 – Integrated Tax (Rate) dated 28-06-2017.</p>
</div>

</div>
 
</div>

<script>
function printDiv() 
{
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
  {
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
}
</script>