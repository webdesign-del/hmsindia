<div class="card">
<div class="card-content">
<div class="row" id="myfrm2">
<div class="ga-pro" style="min-height:1000px;">
<h2 style="text-align:center;"></h2>
<form action="" enctype='multipart/form-data' method="post"> 
<input type="hidden" name="action" value="add_oocyte_activation">
<input type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>" name="on_date" id="on_date">
<?php $uniqueNumber = date('YmdHis') . rand(100, 999); ?>
<input type="hidden" name="receipt_number" value="<?php echo $uniqueNumber; ?>">
<table>
<tr>
<th style="color: red;">
 <?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?>
</th>
<td>
<table width="100%">
<tbody>
<tr>
<td colspan="1"><p style="color: red;">Patient's Name: </p></td>
<td colspan="3" ><span id="username"><input type="text" id="pname" value="<?php echo $data['pname']; ?>" name="pname" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">UHID</p></td>
<td colspan="1"><span id="username2"><input type="text" value="<?php echo $data['uhid']; ?>" id="uhid" name="uhid" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">IPID </p></td>
<td colspan="1"><span id="username3"><input type="text" value="<?php echo $data['ipid']; ?>" id="ipid" name="ipid" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">Age:</p></td>
<td colspan="1"><span id="username4"><input type="text" value="<?php echo $data['age']; ?>" id="age" name="age" class="form-control"></td>
<td colspan="1"><p style="color: red;">Sex</p></td>
<td colspan="1"><span id="username5"><input type="text" value="<?php echo $data['gender']; ?>" id="gender" name="gender" class="form-control"></span></td>
</tr>
<tr>
<td><p style="color: red;">D.O.A </p></td>
<td><span id="username6"><input type="date" value="<?php echo $data['date']; ?>" id="date" name="date" class="form-control"></span></td>
</tr>
</tbody></table>
</td>
</tr>
</table> 
<table width="100%" class="">
<tbody>
<tr>
<td colspan="2">
<h3>Consent for Oocyte activation</h3>
</td>
</tr>
<tr>
<td colspan="2">
<p style="padding: 9px 10px;">We hereby request and give consent to the procedure of oocyte activation to be performed on all the oocytes obtained after ovum pick up ,</p>
</td>
</tr>
<tr>
<td colspan="2">
<p style="padding: 9px 10px;">Description of Procedure:</p>
<p style="padding: 9px 10px;">I understand that oocyte activation technology will be used after ovum pick up on my oocytes</p>
<p style="padding: 9px 10px;">Purpose and Benefits:</p>
<p style="padding: 9px 10px;">The purpose of using ooactiv is to enhance the efficiency and success rates of the IVF process by improving fertilization rate by oocyte activation through calcium ionophore channels .</p>
<p style="padding: 9px 10px;">Potential Risks:</p>
<p style="padding: 9px 10px;">I understand that, while the use of oocyte activation offers potential benefits, there are also risks, including technical failures , no fertilization and unanticipated biological responses. These risks have been explained to me in detail.</p>
<p style="padding: 9px 10px;">Alternatives:</p>
<p style="padding: 9px 10px;">I am aware of alternative methods available that do not involve oocyte activation , and I have discussed these options with my healthcare provider.</p>
<p style="padding: 9px 10px;">Confidentiality:</p>
<p style="padding: 9px 10px;">I understand that my medical information will be kept confidential and will only be shared for research or technological improvement purposes with my explicit consent.</p>
<p style="padding: 9px 10px;">Voluntary Participation:</p>
<p style="padding: 9px 10px;">I understand that my participation in this procedure is voluntary, and I have the right to withdraw my consent at any time without affecting my standard care.</p>
<p style="padding: 9px 10px;">Questions:</p>
<p style="padding: 9px 10px;">I have had the opportunity to ask questions about the procedure and have received satisfactory answers.</p>
</td>
</tr>
<tr>
<td colspan="2">
<h3 style="text-align:left;">Endorsement by the ART clinic</h3>
</td>
</tr>

<tr><td colspan="2" style="padding: 9px 10px;">I/we have personally explained to: <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="details" id="details" value="<?php echo $data['details']; ?>" style="border:0px; width:38%"> and
<span id="username18" style="border-bottom: 1px dotted;"><input type="text" id="and" value="<?php echo $data['and']; ?>" style="border:0px; width:43%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">the details and implications of his/her/their signing this consent/approval form, and made sure to the extent humanly possible that he/she/they understand these details and implications.</td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Signature of Male Partner:- <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="signature_of_male" name="signature_of_male" value="<?php echo $data['signature_of_male']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 30%;" /></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Signature of Female Partner: <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="signature_of_female" name="signature_of_female" value="<?php echo $data['signature_of_female']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 30%;" /></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">
Name,- <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="uname" name="uname" value="<?php echo $data['uname']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width:30%;" /></span>
Address and Signature of the Witness from the clinic:-<span id="username20" style="border-bottom: 1px dotted;"> <input type="text"  id="witness" name="witness" value="<?php echo $data['witness']; ?>" style="border:0px; width:20%"></span></td>
</tr>
<tr><td colspan="2" style="padding: 9px 10px;">Name and Signature of the Doctor: <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="doctor" name="doctor" value="<?php echo $data['doctor']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 40%;" /></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Date: <span id="username95" style="border-bottom: 1px dotted;"><input type="date" id="date2" name="date2" value="<?php echo $data['date2']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span></td></tr>

</tbody>
</table>
<div>
						<input type="submit" id="submit" name="" class="btn btn-primary" />
						<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>
					</div>
</form>
</div> 
</div>
</div>
</div>

<div class="panel-body profile-edit" id="print_this_section" style="display:none;">
<div class="card">
<div class="card-content">
<div class="row" id="myfrm2">
<div class="ga-pro" style="min-height:1000px;">
<h2 style="text-align:center;"></h2>
<form action="" enctype='multipart/form-data' method="post"> 
<table>
<tr>
<th style="color: red;">
<img src="https://indiaivf.website//assets/images/India-IVF-Logo-Option-5.png" style="width:300px">
</th>
<td>
<table width="100%">
<tbody>
<tr>
<td colspan="1"><p style="color: red;">Patient's Name: </p></td>
<td colspan="3" ><span id="username"><input type="text" id="pname" value="<?php echo $data['pname']; ?>" name="pname" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">UHID</p></td>
<td colspan="1"><span id="username2"><input type="text" value="<?php echo $data['uhid']; ?>" id="uhid" name="uhid" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">IPID </p></td>
<td colspan="1"><span id="username3"><input type="text" value="<?php echo $data['ipid']; ?>" id="ipid" name="ipid" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">Age:</p></td>
<td colspan="1"><span id="username4"><input type="text" value="<?php echo $data['age']; ?>" id="age" name="age" class="form-control"></td>
<td colspan="1"><p style="color: red;">Sex</p></td>
<td colspan="1"><span id="username5"><input type="text" value="<?php echo $data['gender']; ?>" id="gender" name="gender" class="form-control"></span></td>
</tr>
<tr>
<td><p style="color: red;">D.O.A </p></td>
<td><span id="username6"><input type="date" value="<?php echo $data['date']; ?>" id="date" name="date" class="form-control"></span></td>
</tr>
</tbody></table>
</td>
</tr>
</table> 
<table width="100%" class="">
<tbody>
<tr>
<td colspan="2">
<h3 style="text-align:left;padding: 0px 10px;">Consent for Oocyte activation</h3>
</td>
</tr>
<tr>
<td colspan="2">
<p style="padding: 0px 10px;">We hereby request and give consent to the procedure of oocyte activation to be performed on all the oocytes obtained after ovum pick up ,</p>
</td>
</tr>
<tr>
<td colspan="2">
<p style="padding: 0px 10px;">Description of Procedure:</p>
<p style="padding: 0px 10px;">I understand that oocyte activation technology will be used after ovum pick up on my oocytes</p>
<p style="padding: 0px 10px;">Purpose and Benefits:</p>
<p style="padding: 0px 10px;">The purpose of using ooactiv is to enhance the efficiency and success rates of the IVF process by improving fertilization rate by oocyte activation through calcium ionophore channels .</p>
<p style="padding: 0px 10px;">Potential Risks:</p>
<p style="padding: 0px 10px;">I understand that, while the use of oocyte activation offers potential benefits, there are also risks, including technical failures , no fertilization and unanticipated biological responses. These risks have been explained to me in detail.</p>
<p style="padding: 0px 10px;">Alternatives:</p>
<p style="padding: 0px 10px;">I am aware of alternative methods available that do not involve oocyte activation , and I have discussed these options with my healthcare provider.</p>
<p style="padding: 0px 10px;">Confidentiality:</p>
<p style="padding: 0px 10px;">I understand that my medical information will be kept confidential and will only be shared for research or technological improvement purposes with my explicit consent.</p>
<p style="padding: 0px 10px;">Voluntary Participation:</p>
<p style="padding: 0px 10px;">I understand that my participation in this procedure is voluntary, and I have the right to withdraw my consent at any time without affecting my standard care.</p>
<p style="padding: 0px 10px;">Questions:</p>
<p style="padding: 0px 10px;">I have had the opportunity to ask questions about the procedure and have received satisfactory answers.</p>
</td>
</tr>
<tr>
<td colspan="2">
<h3 style="text-align:left;padding: 0px 10px;">Endorsement by the ART clinic</h3>
<p style="padding: 0px 10px;">I/we have personally explained to: <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="<?php echo $data['details']; ?>" style="border:0px; width:38%"> and
<span id="username18" style="border-bottom: 1px dotted;"><input type="text" id="and" value="<?php echo $data['and']; ?>" style="border:0px; width:43%"></span>the details and implications of his/her/their signing this consent/approval form, and made sure to the extent humanly possible that he/she/they understand these details and implications.</p></td></tr>
<tr><td colspan="2" style="padding: 0px 10px;">Signature of Male Partner:- <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="name95" value="<?php echo $data['signature_of_male']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span></td></tr>
<tr><td colspan="2" style="padding: 0px 10px;">Signature of Female Partner: <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="name95" value="<?php echo $data['signature_of_female']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span></td></tr>
<tr><td colspan="2" style="padding: 0px 10px;">
Name,- <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="name95" value="<?php echo $data['uname']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span>
Address and Signature of the Witness from the clinic:-<span id="username20" style="border-bottom: 1px dotted;"> <input type="text"  id="name20" value="<?php echo $data['witness']; ?>" style="border:0px; width:100px"></span></td>
</tr>
<tr><td colspan="2" style="padding: 0px 10px;">Name and Signature of the Doctor: <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="name95" value="<?php echo $data['doctor']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span></td></tr>
<tr><td colspan="2" style="padding: 0px 10px;">Date: <span id="username95" style="border-bottom: 1px dotted;"><input type="date" value="<?php echo $data['date2']; ?>" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span></td></tr>
  <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong>
                                </td>
                            </tr>
</tbody>
</table>
</form>
</div> 
</div>
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
</script>
<style>
div.footer {
    display: block; text-align: center;
    position: running(footer);
}
@page {
    @top-center { content: element(header) }
}
@page { 
    @bottom-center { content: element(footer) }
}
input[type=text], textarea {
  height: 24px;
} 
table, th, td {
    border: 0px;
}
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
.sec3 {  
    border: 1px solid #000;
    padding: 5px;
}
.sec2 {
    border: 1px solid #000;
}
.sec2 p {
    margin: 0px;
    padding: 2px 10px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
.ga-pro h3 {
      text-align: center;
    font-size: 25px;
}
form {
    padding-left: 10px;
    margin-bottom: 4px;
}
.nb56ty {
    border: 1px solid #000;
}
.nb56ty input {
    width: 100%;
}
.vb45rt td {text-align: left; padding-left: 10px;}
table th, table td {
    padding: 10px 10px;
    text-align: left;
}
</style>      