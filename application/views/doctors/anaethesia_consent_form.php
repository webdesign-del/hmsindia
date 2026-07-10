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
<td colspan="3" style="border-bottom:1px solid #000;"><span id="username"><input type="text" id="name" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">UHID</p></td>
<td colspan="1" style="border-bottom:1px solid #000;"><span id="username2"><input type="text" id="name2" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">IPID </p></td>
<td colspan="1" style="border-bottom:1px solid #000;"><span id="username3"><input type="text" value="" id="name3" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">Age:</p></td>
<td colspan="1" style="border-bottom:1px solid #000;"><span id="username4"><input type="text" value="" id="name4" class="form-control"></td>
<td colspan="1"><p style="color: red;">Sex</p></td>
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
<td><h3 class="text-center">INFORMED CONSENT FOR ANESTHESIA</h3></td>
</tr>
<tr>
<td colspan="2">
<p style="padding: 9px 10px;">I<span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border:0px; width:300px"></span>
<input type="checkbox" name="name" id="" value=""> the Patient, or <input type="checkbox" name="name" id="" value="">
 (representative of patient <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border:0px; width:300px"></span>) have (please tick the correct option above and below
<br/><span id=""><input type="radio" name="name" id="" value="" style=""> read</span>
<br/><span id=""><input type="radio" name="name" id="" value="" style=""></span> been explained this consent form in 
<span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border:0px; width:48%"></span>
(name of language) which I fully understand, that I would require 'Anesthesiology Services' for getting <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border:0px; width:48%"></span>
(operation/procedure) perfomed. </p>
</td>
</tr>

<tr><td colspan="2" style="padding: 9px 10px;"><p>It has been explained to me that all forms of anesthesia involve some risks, and though rare, anesthesia related complications like
drug reactions, infections, bleeding, blood dlots, loss of sensation, loss of limb function, paralysis, stroke, brain damage, heart attack
and death can occur. I understand that these risks apply to all foms of anesthesia and that additional risks specific for the type of
anesthesia as applicable in my case and marked below have been explained to me as well. I understand that the type(s) of
anesthesia service checked below will be used for my procedure and that the anesthetic technique to be used is determined by many
factors including my physical condition, the type of procedure my doctor is to perform, anesthesiologist's judgement to best suit
requirements of the procedure and accommodating my preference to the extent feasible. It has been explained to me that someti mes an anesthesia technique particularly involving the use of local anesthetics, with or without sedation, may fail to produce desired effect
and therefore may have to be supplemented with another technique including administering general anesthesia.</p></td></tr>
<tr>
<td>
<table>
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><input type="checkbox" name="name" id="" value=""><strong>General Anesthesia</strong></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Drug injected into the bloodstream, breathed into the lungs, or by other routes.</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Total unconscious state, possible placement ofa tube into the windpipe</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major Risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Mouth or throat pain, hoarseness, injury to mouth or teeth, awareness under anesthesia, injury to blood vessels, aspiration, pneumonia, Convulsions, Pneumothorax. </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Spinal or Epidural Analgesia</strong></p><p><input type="checkbox" name="name" id="" value=""><strong>With sedation</strong></p><p><input type="checkbox" name="name" id="" value=""><strong>Without sedation</strong></p></td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Drug injected through a needle/catheter placed either directly in the spinal canal or immediately outside the spinal canal or both</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Temporary numbness and loss of sensation and/or inability to move the affected part of the body</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Headache, backache, buzzing in the ears, convulsions, infection, persistent weakness and/or suppression of normal breathing, numbness, 'total spinal', injury to blood vessels, residual pain, Paralysis. </td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Nerve Block</strong></p><p><input type="checkbox" name="name" id="" value=""><strong>With sedation</strong></p><p><input type="checkbox" name="name" id="" value=""><strong>Without sedation</strong></p></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Drug injected near nerves causing loss of sensation to the area of the operation. </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Temporary loss of feeling and/or movement of a specific limb or area</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Convulsions, persistent numbness, residual pain, infection, injury to blood vessels, Allergic Reaction. </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Intravenous Regional Anesthesia</strong></p><p><input type="checkbox" name="name" id="" value=""><strong>With sedation</strong></p><p><input type="checkbox" name="name" id="" value=""><strong>Without sedation</strong></p></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Drug injected into veins of arm or leg while using a tourniquet</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Temporary loss of sensation and/or movement of a limb.</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Convulsions, weakness, persistent numbness, residual pain, infection, injury to blood vessels, Allergic Reaction</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Monitored Anesthesia Care with sedation</strong></p></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Drug injected into the bloodstream, breathed into the lungs or by other routes producing a semiconscious state. </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Reduced anxiety and pain, partial or total amnesia.</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major Risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">An unconscious state, depressed breathing (requiring Ventilationi to blood vessels.</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Monitored Anesthesia Care without sedation</strong></p></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">None</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Measurement of vital signs, availability of anesthesia provider without sedation for interventions as deemed fit.</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Awareness, anxiety and/or procedure related discomfort. </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Rectal/Vaginal/Dermal Medications</strong></p></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Medicine introduced through Rectum/Vagina/Skin to produce loss of sensation</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result </td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Producing loss of sedation in patients who can't take oral/IV/IM medication or in whom injections are to be avoided.</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major risks</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Discomfort</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;width: 28%;font-size:12px;" colspan="1" rowspan="3"><p><input type="checkbox" name="name" id="" value=""><strong>Vascular Cannulation</strong></p></td>
<td style="border:1px solid #000; padding:10px;width: 22%;font-size:12px;" colspan="1">Technique</td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Cannulation of artery or large veins </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Expected result </td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Measurement of invasive arterial pressures/fill up pressures. </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Major risks </td>
<td style="border:1px solid #000; padding:10px;font-size:12px;" colspan="1">Haematoma, bleeding, ischaemic related complications.</td>
</tr>
</thead>
</table>
</td>
</tr>

<tr><td><p>BY SIGNING THIS FORM, I GIVE MY FULL, FREE, VOLUNTARY CONSENT WITHOUT ANY RESERVATION TO DR.</p></td></tr>
<tr>
<td colspan="2">
<p style="padding: 9px 10px;"><span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border:0px; width:300px"></span>
(name of doctor administering Anesthesia) or his/her associates, for administering anesthesia to <input type="checkbox" name="name" id="" value=""> MYSELF, or <input type="checkbox" name="name" id="" value=""> MY PATIENT <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border:0px; width:300px"></span>(name of patient), being fully aware of the nature, risks and complications. 
</td>
</tr>

<tr><td colspan="2">Signed on <span id="username14" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name14" value="" style="border:0px; width:150px"></span> at <span id="username15" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name15" value="" style="border:0px; width:150px"></span></td></tr>

<tr>
<td>
<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;width: 30%;font-size:13px;" colspan="1"></td>
<td style="border:1px solid #000; padding:10px;width: 30%;font-size:13px;" colspan="1">Signature/ Thumb Impression</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Name</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Patient</td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username16" ><input type="text" name="name" id="name16"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username17" ><input type="text" name="name" id="name17"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Surrogate/Guardian
(if applicable") </td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username18" ><input type="text" name="name" id="name18"></span></td>
<td style="border:1px solid #000; padding:10px;height: 75px;vertical-align: bottom;font-size:12px;" colspan="1"><span id="username26" ><input type="text" name="name" id="name26"></span><br>(Write name &amp; relationship with patient) </td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;vertical-align: text-bottom;font-size:13px;" colspan="1">Reason for surrogate content </td>
<td style="border:1px solid #000;padding:10px;height: 75px;vertical-align: text-bottom;font-size:13px;" colspan="2">Patient is unable to give consent because: <span id="username27" ><input type="text" name="name" id="name27"></span></td>
</tr>
</thead>
</table>
<table style="width:100%; border:1px solid #000;margin-top:10px;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;width: 30%;" colspan="1">Witness</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;width: 30%;" colspan="1"><span id="username19" ><input type="text" name="name" id="name19"></span></td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username20" ><input type="text" name="name" id="name20"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Interpreter
(if applicable)</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username21" ><input type="text" name="name" id="name21"></span></td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username22" ><input type="text" name="name" id="name22"></span></td>
</tr>
</thead>
</table>
</td>
</tr>
<tr><td><p style="text-align:right;"># only if Patient is a minor or unable to give consent</p></td></tr>
<tr><td><p style="">I, the undersigned Doctor, have explained the nature, potential risks and complications, expected benefits, expected post-anesthes
recovery, and possible alternatives to the patient/patient representative. I am confident that he / she has understood the infomau
provided in this document. </p></td></tr>
<tr>
<td>
<table style="width:100%; border:1px solid #000;margin-top:10px;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;width: 30%;" colspan="1">Signature</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username23" ><input type="text" name="name" id="name23"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Name Of Anaesthetist</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username24" ><input type="text" name="name" id="name24"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Designation</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username25" ><input type="text" name="name" id="name25"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1">Date / Time
(if applicable)</td>
<td style="border:1px solid #000; padding:10px;font-size:13px;" colspan="1"><span id="username22" ><input type="text" name="name" id="name22"></span></td>
</tr>
</thead>
</table>
<p>(being person administering Anesthesia or member of his /her team)</p>
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
			
			 var printdata = document.getElementById(myfrm2);
            newwin = window.open("");
            newwin.document.write(printdata.outerHTML);
            newwin.print();
            newwin.close();
        }
    </script>
<style>
td{
	font-size:13px;
}
p{
	font-size:13px;
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