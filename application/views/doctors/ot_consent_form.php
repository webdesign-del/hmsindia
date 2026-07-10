<div class="card">
<div class="card-content">
<div class="row" id="myfrm2">
<div class="ga-pro" style="min-height:1100px;">
<h2 style="text-align:center;"></h2>
<form action="" enctype='multipart/form-data' method="post"> 
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
<td colspan="3" style="border-bottom:1px solid #000;" ><span id="username"><input type="text" id="name" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">UID</p></td>
<td colspan="1" style="border-bottom:1px solid #000;" ><span id="username2"><input type="text" id="name2" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">IPID </p></td>
<td colspan="1" style="border-bottom:1px solid #000;"><span id="username3"><input type="text" value="" id="name3" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">Age:</p></td>
<td colspan="1" style="border-bottom:1px solid #000;"><span id="username4"><input type="text" value="" id="name4" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">Gender</p></td>
<td colspan="1" style="border-bottom:1px solid #000;"><span id="username5"><input type="text" value="" id="name5" class="form-control"></span></td>
</tr>
<tr>
<td><p style="color: red;">D.O.A </p></td>
<td style="border-bottom:1px solid #000;"><span id="username6"><input type="date" value="" id="name6" class="form-control"></span></td>
<td><p style="color: red;">UNIT</p></td>
<td style="border-bottom:1px solid #000;"><span id="username7"><input type="text" value="" id="name7" class="form-control"></span></td>
</tr>
</tbody></table>
</td>
</tr>
</table> 
<table width="100%" class="">
<tbody>
<tr>
<td><h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FOR SURGERY</h3></td>
</tr>
<tr>
<td colspan="2">
<p style="padding: 9px 10px;">I<span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border:0px; width:300px"></span>
<input type="checkbox" name="name" id="" value=""> the Patient, or <input type="checkbox" name="name" id="" value=""> (representative of patient <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border:0px; width:300px"></span>) have (please tick the correct option above and below
<br/><span><input type="radio" name="name" id="" value="" style=""> read</span>
<br/><span><input type="radio" name="name" id="" value="" style=""></span> been explained this consent form in 
<span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border:0px; width:48%"></span>
name of language) which I fully understand, and understood the information provided about <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border:0px; width:48%"></span>
(full name of operation/procedure) given below in this consent form. 
</p>
</td>
</tr>

<tr><td colspan="2" style="padding: 9px 10px;">Brief description of operation/procedure: <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border:0px; width:74%"></span></td></tr>
<tr><td><strong>Intended Benefits</strong></td></tr>
<tr>
<td>
<table width="100%">
<tbody>
<tr>
<td colspan="1"><span>1</span>&nbsp;&nbsp;<span  id="username12"> Relief from diagnosed illness</span></td>
<td colspan="1"><span>2</span>&nbsp;&nbsp;<span  id="username13"><input type="text" id="name13" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>3</span>&nbsp;&nbsp;<span  id="username14"><input type="text" id="name14" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>4</span>&nbsp;&nbsp;<span  id="username15"><input type="text" id="name15" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>5</span>&nbsp;&nbsp;<span  id="username16"><input type="text" id="name16" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>6</span>&nbsp;&nbsp;<span id="username17"><input type="text" id="name17" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
</tbody></table>
</td>
</tr>

<tr><td>I understand that all procedures carry certain risks. The potential risk and complications form this procedure are:</td></tr>
<tr>
<td>
<table width="100%">
<tbody>
<tr>
<td colspan="1"><span>1</span>&nbsp;&nbsp;<span id="username18"><input type="text" id="name18" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>2</span>&nbsp;&nbsp;<span id="username19"><input type="text" id="name19" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>3</span>&nbsp;&nbsp;<span id="username20"><input type="text" id="name20" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>4</span>&nbsp;&nbsp;<span id="username21"><input type="text" id="name21" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>5</span>&nbsp;&nbsp;<span id="username22"><input type="text" id="name22" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>6</span>&nbsp;&nbsp;<span id="username23"><input type="text" id="name23" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>7</span>&nbsp;&nbsp;<span id="username24"><input type="text" id="name24" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>8</span>&nbsp;&nbsp;<span id="username25"><input type="text" id="name25" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>9</span>&nbsp;&nbsp;<span id="username26"><input type="text" id="name26" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>10</span>&nbsp;&nbsp;<span id="username27"><input type="text" id="name27" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
</tbody></table>
</td>
</tr>

<tr><td>i have been explained the implications of not undergoing this procedure and the alternative methods of treatment like</td></tr>
<tr>
<td>
<table width="100%">
<tbody>
<tr>
<td colspan="1"><span>1</span>&nbsp;&nbsp;<span id="username28"><input type="text" id="name28" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>2</span>&nbsp;&nbsp;<span id="username29"><input type="text" id="name29" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>3</span>&nbsp;&nbsp;<span id="username30"><input type="text" id="name30" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>2</span>&nbsp;&nbsp;<span id="username31"><input type="text" id="name31" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
<tr>
<td colspan="1"><span>5</span>&nbsp;&nbsp;<span id="username32"><input type="text" id="name32" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
<td colspan="1"><span>6</span>&nbsp;&nbsp;<span id="username33"><input type="text" id="name33" class="form-control" style="margin-top: -30px;padding-left: 20px;padding-right: 0px;"></span></td>
</tr>
</tbody></table>
</td>
</tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I am now aware of the intended benefits, possible risks & complications, and available alternatives to the said operation/procedure. Ilam also aware that results of any operation/procedure can vary from patient to patient; and/declare that no guarantees have been made to me regarding success of this operation/procedure. I am aware that while majority of patients have an uneventful operation and recovery, few cases may be associated with complications. I am aware of the common risks and complications associated with this opëration/procedure and understand that it is not possible to list all possible risks and complications of any operation/procedure. </p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I also understand that sometimes a planned operation/procedure may need to be postponed or cancelled if patient's clinical condition worsens or due to any unforeseen technicalreason. I am also aware that l can withdraw my consent at any point or time at my own risk and consequence, by submitting the withdrawal in writing </p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I understand that if medical exigencies demand, further or alternative operative/procedural measures may need to be carried out, and in such case there may be difference in the planned and actual operation/procedure. I am aware that i may require administration of blood and/or blood products during or ater the operation/procedure as found necessary by the doctor (for which a separate consent shall be obtained).</p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I am now also aware that during the course of this operation/procedure the doctor will be assisted by medical aad paramedical team, and that the doctor may seek conaultation/assistance from relevant specialists if the need arises</p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I agree to observing, photography (still/ldeo/televising) of the pi ocedure (including relevant portions of my body) incluing my diagnosis/reports (pathology, radiology, etc), for academic/ medical/medico-legal purposes, provided my identity is not revealed by such acts. I also agree to my clinical details being shared for sclentific publications if my identity is not disclosed.</p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I am also aware of the expected course after the operation/procedure and the care to be provided, and underst sometimes admission to an Intensive Care Unit and/or extension of duration of hospitalization may be required and/or there may be requirement of extra medicines or treatments, thereby leading to increase in the treatment expenses, depending upon the body's response to the treatment/procedure. </p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">l authorize the hospital for disposal of any tissue or body part that may be removed from my body in the a propriate manner during and for the purpose of conducting this operation/procedure.</p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">I declare that I have received & fully understood the information provided in this consent form, that I have been given as opportunity to ask questions relating to my ailment, the operation/procedure being performed, its risks. consec.er :e: alternatives, potential complications and intended benefits and recovery, and that all my questions have been arin:*: my entire satisfactionand there are no misconceptions or false hopesin my mind. I furtber declare that ali fielcs ef :***- requiring insertion or completion were filled in my presence at the time of my signing this form. </p></td></tr>
<tr><td><p style="font-size:12px;margin-top:10px;">For the above mentioned operations(s)/procedures(s) that | have been made aware of, I give my consent voiuntariy to</p></td></tr>
<tr><td>Dr: <span id="username34" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name34" value="" style="border:0px; width:350px"></span> (name of doctor performing the operatin/procedure! fo carrying out the said operation/procedure on <input type="checkbox" name="name" id="" value=""> myself or <input type="checkbox" name="name" id="" value=""> my above named patient being fully aware of the nature, potential risks and complications, intended benefits and possible alternatives.</td></tr>
<tr><td><p style="margin-top:20px;">I, the above named patient/named patlent's representative, do further hereby declare that I am above 18 years of ages on the date of signing this form, mentally sound and am giving consent without any fear, threat or false misconception. </p></td></tr>
<tr><td colspan="2">Signed on <span id="username35" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name35" value="" style="border:0px; width:150px"></span> at <span id="username36" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name36" value="" style="border:0px; width:150px"></span></td></tr>
<tr>
<td>
<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;width: 30%;" colspan="1"><span id="username37"><input type="text" name="name" id="name37"></span></td>
<td style="border:1px solid #000; padding:10px;width: 30%;" colspan="1">Signature/ Thumb Impression</td>
<td style="border:1px solid #000; padding:10px;" colspan="1">Name</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="1">Patient</td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username38"><input type="text" name="name" id="name38"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username39"><input type="text" name="name" id="name39"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="1">Surrogate/Guardian
(if applicable") </td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username40"><input type="text" name="name" id="name40"></span></td>
<td style="border:1px solid #000; padding:10px;height: 75px;vertical-align: bottom;" colspan="1"><span id="username41"><input type="text" name="name" id="name41"></span>(Write name & relationship with patient) </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;vertical-align: text-bottom;" colspan="1">Reason for surrogate content <span id="username42"><input type="text" name="name" id="name42"></span></td>
<td style="border:1px solid #000;padding:10px;height: 75px;vertical-align: text-bottom;" colspan="2">Patient is unable to give consent because: <span id="username43"><input type="text" name="name" id="name43"></span></td>
</tr>
</thead>
</table>
<table style="width:100%; border:1px solid #000;margin-top:10px;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;width: 30%;" colspan="1">Witness</td>
<td style="border:1px solid #000; padding:10px;width: 30%;" colspan="1"><span id="username44"><input type="text" name="name" id="name44"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username45"><input type="text" name="name" id="name45"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="1">Interpreter
(if applicable)</td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username46"><input type="text" name="name" id="name46"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username47"><input type="text" name="name" id="name47"></span></td>
</tr>
</thead>
</table>
</td>
</tr>
<tr><td><p style="margin-top:20px;">I, the underslgned doctor, have explained the nature, potential risk procedure and complications, Intended benefits, expected post-procedure course, and possible alternatives to the planned oepration/procedure, to the patient/patient representative. I am confident that he/she has understood the information Consent obtained by fully as described in this documents.  </p></td></tr>
<tr>
<td colspan="2"style="border:1px solid #000; padding:10px;" colspan="1">
Dr. <span id="username48" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name48" value="" style="border:0px; width:240px"></span>
Designation & Dept. <span id="username49" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name49" value="" style="border:0px; width:240px"></span>
Signature <span id="username50" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name50" value="" style="border:0px; width:240px"></span>
<p>being person performing the procedure or member of his/her team)</p>
</td>
</tr>
</tbody>
</table>
</form>
</div> 
</div>
</div>
</div>
<div style="text-align: center; height: 50px;"><input type="button" class="btn btn-primary" onclick="myPrint2('myfrm2')" value="print"></div>
    <script>
        function myPrint2(myfrm2) {
			let name = document.getElementById("name").value; 
			alert(name);
			document.getElementById("username").innerHTML = name;
			
			let name2 = document.getElementById("name2").value; 
			document.getElementById("username2").innerHTML = name2;
			
			let name3 = document.getElementById("name3").value; 
			document.getElementById("username3").innerHTML = name3;
			
			let name4 = document.getElementById("name4").value; 
			document.getElementById("username4").innerHTML = name4;
			
			let name5 = document.getElementById("name5").value; 
			document.getElementById("username5").innerHTML = name5;
			
			let name6 = document.getElementById("name6").value; 
			document.getElementById("username6").innerHTML = name6;
			
			let name7 = document.getElementById("name7").value; 
			document.getElementById("username7").innerHTML = name7;
			
			let name8 = document.getElementById("name8").value; 
			document.getElementById("username8").innerHTML = name8;
			
			let name9 = document.getElementById("name9").value; 
			document.getElementById("username9").innerHTML = name9;
			
			let name10 = document.getElementById("name10").value; 
			document.getElementById("username10").innerHTML = name10;
			
			let name11 = document.getElementById("name11").value; 
			document.getElementById("username11").innerHTML = name11;
			
			let name12 = document.getElementById("name12").value; 
			document.getElementById("username12").innerHTML = name12;
			
			let name13 = document.getElementById("name13").value; 
			document.getElementById("username13").innerHTML = name13;
			
			let name14 = document.getElementById("name14").value; 
			document.getElementById("username14").innerHTML = name14;
			
			let name15 = document.getElementById("name15").value; 
			document.getElementById("username15").innerHTML = name15;
			
			let name16 = document.getElementById("name16").value; 
			document.getElementById("username16").innerHTML = name16;
			
			let name17 = document.getElementById("name17").value; 
			document.getElementById("username17").innerHTML = name17;
			
			let name18 = document.getElementById("name18").value; 
			document.getElementById("username18").innerHTML = name18;
			
			let name19 = document.getElementById("name19").value; 
			document.getElementById("username19").innerHTML = name19;
			
			let name20 = document.getElementById("name20").value; 
			document.getElementById("username20").innerHTML = name20;
			
			let name21 = document.getElementById("name21").value; 
			document.getElementById("username21").innerHTML = name21;
			
			let name22 = document.getElementById("name22").value; 
			document.getElementById("username22").innerHTML = name22;
			
			let name23 = document.getElementById("name23").value; 
			document.getElementById("username23").innerHTML = name23;
			
			let name24 = document.getElementById("name24").value; 
			document.getElementById("username24").innerHTML = name24;
			
			let name25 = document.getElementById("name25").value; 
			document.getElementById("username25").innerHTML = name25;
			
			let name26 = document.getElementById("name26").value; 
			document.getElementById("username26").innerHTML = name26;
			
			let name27 = document.getElementById("name27").value; 
			document.getElementById("username27").innerHTML = name27;
			
			let name28 = document.getElementById("name28").value; 
			document.getElementById("username28").innerHTML = name28;
			
			let name29 = document.getElementById("name29").value; 
			document.getElementById("username29").innerHTML = name29;
			
			let name30 = document.getElementById("name30").value; 
			document.getElementById("username30").innerHTML = name30;
			
			let name31 = document.getElementById("name31").value; 
			document.getElementById("username31").innerHTML = name31;
			
			let name32 = document.getElementById("name32").value; 
			document.getElementById("username32").innerHTML = name32;
			
			let name33 = document.getElementById("name33").value; 
			document.getElementById("username33").innerHTML = name33;
			
			let name34 = document.getElementById("name34").value; 
			document.getElementById("username34").innerHTML = name34;
			
			let name35 = document.getElementById("name35").value; 
			document.getElementById("username35").innerHTML = name35;
			
			let name36 = document.getElementById("name36").value; 
			document.getElementById("username36").innerHTML = name36;
			
			let name37 = document.getElementById("name37").value; 
			document.getElementById("username37").innerHTML = name37;
			
			let name38 = document.getElementById("name38").value; 
			document.getElementById("username38").innerHTML = name38;
			
			let name39 = document.getElementById("name39").value; 
			document.getElementById("username39").innerHTML = name39;
			
			let name40 = document.getElementById("name40").value; 
			document.getElementById("username40").innerHTML = name40;
			
			let name41 = document.getElementById("name41").value; 
			document.getElementById("username41").innerHTML = name41;
			
			let name42 = document.getElementById("name42").value; 
			document.getElementById("username42").innerHTML = name42;
			
			let name43 = document.getElementById("name43").value; 
			document.getElementById("username43").innerHTML = name43;
			
			let name44 = document.getElementById("name44").value; 
			document.getElementById("username44").innerHTML = name44;
			
			let name45 = document.getElementById("name45").value; 
			document.getElementById("username45").innerHTML = name45;
			
			let name46 = document.getElementById("name46").value; 
			document.getElementById("username46").innerHTML = name46;
			
			let name47 = document.getElementById("name47").value; 
			document.getElementById("username47").innerHTML = name47;
			
			let name48 = document.getElementById("name48").value; 
			document.getElementById("username48").innerHTML = name48;
			
			let name49 = document.getElementById("name49").value; 
			document.getElementById("username49").innerHTML = name49;
			
			let name50 = document.getElementById("name50").value; 
			document.getElementById("username50").innerHTML = name50;
			
			var printdata = document.getElementById(myfrm2);
            newwin = window.open("");
            newwin.document.write(printdata.outerHTML);
            newwin.print();
            newwin.close();
        }
    </script>
<style>
td{
	font-size:12px;
}
p{
	font-size:12px;
}
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