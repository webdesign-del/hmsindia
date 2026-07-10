<div class="card">
<div class="card-content">
<div class="row" id="myfrm2">
<div class="ga-pro" style="min-height:1000px;">
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
<td colspan="1"><p style="color: red;">IPD </p></td>
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
<td colspan="2">
<p style="padding: 9px 10px;">Source of History<span id="username8" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text" id="name8" value="" style="border:0px; width:200px"></span> Reliability:,
<span id="username9" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text" id="name9" value="" style="border:0px; width:150px"></span> Good <span id="username10" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text" id="name10" value="" style="border:0px; width:100px"></span> Fair <span id="username11" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text" id="name11" value="" style="border:0px; width:100px"></span> . Poor <span id="username12" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text" id="name12" value="" style="border:0px; width:100px"></span></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 9px 10px;">Chief Complaint: <span id="username13" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text" id="name13" value="" style="border:0px; width:88%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">History of present illness: <span id="username14" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text"   id="name14" value="" style="border:0px; width:83%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Past medical & Surgical History: <span id="username15" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text"   id="name15" value="" style="border:0px; width:79%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Allergies: <span id="username16" style="border-bottom: 1px dotted;margin:0px 20px"><input type="text"   id="name16" value="" style="border:0px; width:92%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Psychological: <span style="margin-left: 120px;">Anxious<input type="checkbox"   id="" value="Anxious"></span> Combative<input type="checkbox"   id="" value="Combative"> Depressed<input type="checkbox"   id="" value="Depressed"> Normal<input type="checkbox"   id="" value="Normal"></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Nutritional Assessment: <span style="margin-left: 70px;">Obese<input type="checkbox"   id="" value="Obese"></span> Average Built<input type="checkbox"   id="" value="Average Built"> Thin<input type="checkbox"   id="" value="Thin"> Cachetic<input type="checkbox"   id="" value="Cachetic"></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Function Assessment: <span style="margin-left: 40px;">Independent<input type="checkbox"   id="" value="Independent"></span> Need Assistance<input type="checkbox"   id="" value="Need Assistance"> Dependent<input type="checkbox"   id="" value="Dependent"></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;"><strong>Brief examination</strong></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">CVS <span id="username91" style="border-bottom: 1px dotted;"><input type="text"   id="name91" value="" style="border:0px; width:80%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Chest <span id="username92" style="border-bottom: 1px dotted;"><input type="text"   id="name92" value="" style="border:0px; width:80%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Abdomen <span id="username93" style="border-bottom: 1px dotted;"><input type="text"   id="name93" value="" style="border:0px; width:80%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Others: <span id="username17" style="border-bottom: 1px dotted;"><input type="text"   id="name17" value="" style="border:0px; width:93%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Provisional Diagnosis : <span id="username18" style="border-bottom: 1px dotted;"><input type="text"   id="name18" value="" style="border:0px; width:84%"></span></td></tr>
<tr><td colspan="2" style="padding: 9px 10px;">Plan of Care: <span id="username19" style="border-bottom: 1px dotted;"><input type="text"   id="name19" value="" style="border:0px; width:90%"></span></td></tr>
<tr>
<td colspan="2" style="padding: 9px 10px;">Doctors Signature:- <span id="username95" style="border-bottom: 1px dotted;"><input type="text" id="name95" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span>
Name-<span id="username96" style="border-bottom: 1px dotted;"> <input type="text" id="name96" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 180px;" /></span> Date&Time-<span id="username20" style="border-bottom: 1px dotted;"> <input type="date"  id="name20" value="" style="border:0px; width:100px"></span> <span id="username21" style="border-bottom: 1px dotted;"><input type="time" id="name21" value="" style="border:0px; width:100px"></span></td></tr>
</tbody>
</table>
</form>
</div> 
<div class="ga-pro" style="min-height:1050px;">
<h2 style="text-align:center;"></h2>
<form action="" enctype='multipart/form-data' method="post"> 
<table>
<tr>
<th style="color: red;">
<img src="https://indiaivf.website/indiaivf.jpeg" style="width:300px">
</th>
<td>

<table width="100%">
<tbody>
<tr>
<td colspan="1"><p style="color: red;">Patient's Name: </p></td>
<td colspan="3"><span id="username22" style="border-bottom: 1px dotted;"><input type="text" value="" name="female_total_pregnancies" id="name22" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">UHID</p></td>
<td colspan="1"><span id="username23" style="border-bottom: 1px dotted;"><input type="text" value="" name="female_live_birth" id="name23" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">IPD </p></td>
<td colspan="1"><span id="username24" style="border-bottom: 1px dotted;"><input type="text" value="" name="female_spontaneous_abortions" id="name24" class="form-control"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">Age:</p></td>
<td colspan="1"><span id="username25" style="border-bottom: 1px dotted;"><input type="text" value="" name="female_termination_pregnancy" id="name25" class="form-control"></span></td>
<td colspan="1"><p style="color: red;">Sex</p></td>
<td colspan="1"><span id="username26" style="border-bottom: 1px dotted;"><input type="text" value="" name="female_still_births" id="name26" class="form-control"></span></td>
</tr>
<tr>
<td><p style="color: red;">D.O.A </p></td>
<td><span id="username27" style="border-bottom: 1px dotted;"><input type="date" value="" name="female_ectopic_pregnancy" class="form-control" id="name27"></span></td>
<td><p style="color: red;">UNIT</p></td>
<td><span id="username28" style="border-bottom: 1px dotted;"><input type="text" value="" name="female_abnormality_child" id="name28" class="form-control"></span></td>
</tr>
</tbody></table>
</td>
</tr>
</table> 
<table width="100%" class="">
<tbody>
<tr>
<td colspan="2">
Address: <span id="username29" style="border-bottom: 1px dotted;"><input type="text"  id="name29" value="" style="border:0px; width:350px"></span>
&nbsp;&nbsp;&nbsp;Discharge Date <span id="username30" style="border-bottom: 1px dotted;"><input type="date"   id="name30" value="" style="border:0px; width:350px"></span>
</td>
</tr>
<tr>
<td colspan="2">
Father's Name: <span id="username31" style="border-bottom: 1px dotted;"><input type="text"  id="name31" value="" style="border:0px; width:250px"></span>
&nbsp;&nbsp;&nbsp;Hospital Days <span id="username32" style="border-bottom: 1px dotted;"><input type="text"  id="name32" value="" style="border:0px; width:250px"></span>
&nbsp;&nbsp;&nbsp;Department <span id="username33" style="border-bottom: 1px dotted;"><input type="text"  id="name33" value="" style="border:0px; width:250px"></span>
</td>
</tr>
<tr>
<td colspan="2">
Phone: <span id="username34" style="border-bottom: 1px dotted;"><input type="text"  id="name34" value="" style="border:0px; width:200px"></span>
&nbsp;&nbsp;&nbsp;Ward <span id="username35" style="border-bottom: 1px dotted;"><input type="text"  id="name35" value="" style="border:0px; width:200px"></span>
&nbsp;&nbsp;&nbsp;R/Bed No <span id="username36" style="border-bottom: 1px dotted;"><input type="text"  id="name36" value="" style="border:0px; width:175px"></span>
&nbsp;&nbsp;&nbsp;Consultant <span id="username37" style="border-bottom: 1px dotted;"><input type="text"  id="name37" value="" style="border:0px; width:175px"></span>
</td>
</tr>
<tr>
<td><table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="3">Provisional Diagnosis</td>
<td style="border:1px solid #000; padding:10px;" colspan="1">ICD CODE</td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="3">Final Diagnosis  <span id="username95" style="border-bottom: 1px dotted;"><input type="text"  id="name95" value="" style="border:0px;"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username38" style="border-bottom: 1px dotted;"><input type="text"  id="name38" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="3">Primary <span id="username96" style="border-bottom: 1px dotted;"><input type="text"  id="name96" value="" style="border:0px;"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username39" style="border-bottom: 1px dotted;"><input type="text"  id="name39" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="3">Secondary <span id="username97" style="border-bottom: 1px dotted;"><input type="text"  id="name97" value="" style="border:0px;"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username40" style="border-bottom: 1px dotted;"><input type="text"  id="name40" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="3">Complications  <span id="username98" style="border-bottom: 1px dotted;"><input type="text"  id="name98" value="" style="border:0px;"></span></td>
<td style="border:1px solid #000; padding:10px;" colspan="1"><span id="username41" style="border-bottom: 1px dotted;"><input type="text"  id="name41" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td colspan="8" style="border:1px solid #000; padding:10px; height: 140px;vertical-align: text-bottom;">Surgical Procedures <span id="username50"><input type="text"  id="name50" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px;" colspan="8">Surgeons Name <span id="username94" style="border-bottom: 1px dotted;margin-left:50px;"><input type="text"   id="name94" value="" style="border:0px; width:80%"></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:10px; height: 140px;vertical-align: text-bottom;" colspan="8">
<p>Result:<span style="margin-left:80px;">Recovered</span><span style="margin-left:80px;">Improved Unachanged</span><span style="margin-left:80px;">Lama</span><span style="margin-left:80px;">Worsened</span><span style="margin-left:80px;">Died</span></p>
<p style="margin-top:30px;">Cause of Death  <span id="username42" style="border-bottom: 1px dotted;"><input type="text"  id="name42" value="" style="border:0px; width:175px"></span></p>
<p style="margin-top:30px;">Autopsy: Yes/No <span id="username43" style="border-bottom: 1px dotted;"><input type="text"  id="name43" value="" style="border:0px; width:175px"></span></p>
</td>
</tr>
<tr>
<th style="border:1px solid #000; padding:10px;"></th>
<th style="border:1px solid #000; padding:10px;">SIGNATURE</th>
<th style="border:1px solid #000; padding:10px;">NAME</th>
<th style="border:1px solid #000; padding:10px;">DATE</th>
</tr>
</thead>
<tbody id="male_medicine_suggestion_table" style="border:1px solid #000; padding:10px; width:100%;">
<tr style="border:1px solid #000; width:40%;">
<td style="border:1px solid #000; width:20%;">JRIMedical Officer: </td>
<td style="border:1px solid #000; width:20%;"><span id="username44" style="border-bottom: 1px dotted;"><input type="text"   id="name44" value="" style="border:0px; width:175px"></span></td>
<td style="border:1px solid #000; width:20%;"><span id="username45" style="border-bottom: 1px dotted;"><input type="text"   id="name45" value="" style="border:0px; width:175px"></span></td>
<td style="border:1px solid #000; width:20%;"><span id="username46" style="border-bottom: 1px dotted;"><input type="date"   id="name46" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr style="border:1px solid #000; width:40%;">
<td style="border:1px solid #000; width:20%;">SRISenior Medical Officer:</td>
<td style="border:1px solid #000; width:20%;"><span id="username47" style="border-bottom: 1px dotted;"><input type="text"   id="name47" value="" style="border:0px; width:175px"></span></td>
 <td style="border:1px solid #000; width:20%;"><span id="username48" style="border-bottom: 1px dotted;"><input type="text"   id="name48" value="" style="border:0px; width:175px"></span></td>
<td style="border:1px solid #000; width:20%;"><span id="username49" style="border-bottom: 1px dotted;"><input type="date"   id="name49" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr style="border:1px solid #000; width:40%;">
<td style="border:1px solid #000; width:20%;">Consutlant:</td>
<td style="border:1px solid #000; width:20%;"><span id="username51" style="border-bottom: 1px dotted;"><input type="text"   id="name51" value="" style="border:0px; width:175px"></span></td>
 <td style="border:1px solid #000; width:20%;"><span id="username52" style="border-bottom: 1px dotted;"><input type="text"   id="name52" value="" style="border:0px; width:175px"></span></td>
<td style="border:1px solid #000; width:20%;"><span id="username53" style="border-bottom: 1px dotted;"><input type="date"   id="name53" value="" style="border:0px; width:175px"></span></td>
</tr>
</tbody>  </table></td>
</tr>
</tbody>
</table>
</form>
</div> 
<div class="ga-pro" style="min-height:1000px;">
<h2 style="text-align:center;"></h2>
<form action="" enctype='multipart/form-data' method="post"> 
<table>
<tr>
<th style="color: red;">
<img src="https://indiaivf.website/indiaivf.jpeg" style="width:300px">
</th>
<td>

<table width="100%">
<tbody>
<tr>
<td colspan="1"><p style="color: red;">Patient's Name: </p></td>
<td colspan="3"><span id="username54" style="border-bottom: 1px dotted;"><input type="text"   id="name54" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">UHID</p></td>
<td colspan="1"><span id="username55" style="border-bottom: 1px dotted;"><input type="text"   id="name55" value="" style="border:0px; width:175px"></span></td>
<td colspan="1"><p style="color: red;">IPD </p></td>
<td colspan="1"><span id="username56" style="border-bottom: 1px dotted;"><input type="text"   id="name56" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td colspan="1"><p style="color: red;">Age:</p></td>
<td colspan="1"><span id="username57" style="border-bottom: 1px dotted;"><input type="text"   id="name57" value="" style="border:0px; width:175px"></span></td>
<td colspan="1"><p style="color: red;">Sex</p></td>
<td colspan="1"><span id="username58" style="border-bottom: 1px dotted;"><input type="text"   id="name58" value="" style="border:0px; width:175px"></span></td>
</tr>
<tr>
<td><p style="color: red;">D.O.A </p></td>
<td><span id="username59" style="border-bottom: 1px dotted;"><input type="date"   id="name59" value="" style="border:0px; width:175px"></span></td>
<td><p style="color: red;">UNIT</p></td>
<td><span id="username60" style="border-bottom: 1px dotted;"><input type="text"   id="name60" value="" style="border:0px; width:175px"></span></td>
</tr>
</tbody></table>
</td>
</tr>
</table> 
<tr>
<td><table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
<thead style="border:1px solid #000; padding:10px; width:100%;">
<tr>
<td style="border:1px solid #000; padding:10px;width: 10%;">DATE / TIME</td>
<td style="border:1px solid #000; padding:10px;"></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username61"><input type="text"  id="name61" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username62"><input type="text"  id="name62" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username63"><input type="text"  id="name63" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username64"><input type="text"  id="name64" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username65"><input type="text"  id="name65" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username66"><input type="text"  id="name66" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username67"><input type="text"  id="name67" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username68"><input type="text"  id="name68" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username69"><input type="text"  id="name69" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username70"><input type="text"  id="name70" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username71"><input type="text"  id="name71" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username72"><input type="text"  id="name72" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username73"><input type="text"  id="name73" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username74"><input type="text"  id="name74" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username75"><input type="text"  id="name75" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username76"><input type="text"  id="name76" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username77"><input type="text"  id="name77" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username78"><input type="text"  id="name78" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username79"><input type="text"  id="name79" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username80"><input type="text"  id="name80" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username81"><input type="text"  id="name81" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username82"><input type="text"  id="name82" value=""></span></td>
</tr>
<tr>
<td style="border:1px solid #000; padding:20px;"><span id="username83"><input type="text"  id="name83" value=""></span></td>
<td style="border:1px solid #000; padding:10px;"><span id="username84"><input type="text"  id="name84" value=""></span></td>
</tr>
</thead>
</table>
</td>
</tr>
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
			
			let name51 = document.getElementById("name51").value; 
			document.getElementById("username51").innerHTML = name51;
			
			let name52 = document.getElementById("name52").value; 
			document.getElementById("username52").innerHTML = name52;
			
			let name53 = document.getElementById("name53").value; 
			document.getElementById("username53").innerHTML = name53;
			
			let name54 = document.getElementById("name54").value; 
			document.getElementById("username54").innerHTML = name54;
			
			let name55 = document.getElementById("name55").value; 
			document.getElementById("username55").innerHTML = name55;
			
			let name56 = document.getElementById("name56").value; 
			document.getElementById("username56").innerHTML = name56;
			
			let name57 = document.getElementById("name57").value; 
			document.getElementById("username57").innerHTML = name57;
			
			let name58 = document.getElementById("name58").value; 
			document.getElementById("username58").innerHTML = name58;
			
			let name59 = document.getElementById("name59").value; 
			document.getElementById("username59").innerHTML = name59;
			
			let name60 = document.getElementById("name60").value; 
			document.getElementById("username60").innerHTML = name60;
			
			let name61 = document.getElementById("name61").value; 
			document.getElementById("username61").innerHTML = name61;
			
			let name62 = document.getElementById("name62").value; 
			document.getElementById("username62").innerHTML = name62;
			
			let name63 = document.getElementById("name63").value; 
			document.getElementById("username63").innerHTML = name63;
			
			let name64 = document.getElementById("name64").value; 
			document.getElementById("username64").innerHTML = name64;
			
			let name65 = document.getElementById("name65").value; 
			document.getElementById("username65").innerHTML = name65;
			
			let name66 = document.getElementById("name66").value; 
			document.getElementById("username66").innerHTML = name66;
			
			let name67 = document.getElementById("name67").value; 
			document.getElementById("username67").innerHTML = name67;
			
			let name68 = document.getElementById("name68").value; 
			document.getElementById("username68").innerHTML = name68;
			
			let name69 = document.getElementById("name69").value; 
			document.getElementById("username69").innerHTML = name69;
			
			let name70 = document.getElementById("name70").value; 
			document.getElementById("username70").innerHTML = name70;
			
			let name71 = document.getElementById("name71").value; 
			document.getElementById("username71").innerHTML = name71;
			
			let name72 = document.getElementById("name72").value; 
			document.getElementById("username72").innerHTML = name72;
			
			let name73 = document.getElementById("name73").value; 
			document.getElementById("username73").innerHTML = name73;
			
			let name74 = document.getElementById("name74").value; 
			document.getElementById("username74").innerHTML = name74;
			
			let name75 = document.getElementById("name75").value; 
			document.getElementById("username75").innerHTML = name75;
			
			let name76 = document.getElementById("name76").value; 
			document.getElementById("username76").innerHTML = name76;
			
			let name77 = document.getElementById("name77").value; 
			document.getElementById("username77").innerHTML = name77;
			
			let name78 = document.getElementById("name78").value; 
			document.getElementById("username78").innerHTML = name78;
			
			let name79 = document.getElementById("name79").value; 
			document.getElementById("username79").innerHTML = name79;
			
			let name80 = document.getElementById("name80").value; 
			document.getElementById("username80").innerHTML = name80;
			
			let name81 = document.getElementById("name81").value; 
			document.getElementById("username81").innerHTML = name81;
			
			let name82 = document.getElementById("name82").value; 
			document.getElementById("username82").innerHTML = name82;
			
			let name83 = document.getElementById("name83").value; 
			document.getElementById("username83").innerHTML = name83;
			
			let name84 = document.getElementById("name84").value; 
			document.getElementById("username84").innerHTML = name84;
			
			let name91 = document.getElementById("name91").value; 
			document.getElementById("username91").innerHTML = name91;
			
			let name92 = document.getElementById("name92").value; 
			document.getElementById("username92").innerHTML = name92;
			
			let name93 = document.getElementById("name93").value; 
			document.getElementById("username93").innerHTML = name93;
			
			let name94 = document.getElementById("name94").value; 
			document.getElementById("username94").innerHTML = name94;
			
			let name95 = document.getElementById("name95").value; 
			document.getElementById("username95").innerHTML = name95;
			
			let name96 = document.getElementById("name96").value; 
			document.getElementById("username96").innerHTML = name96;
			
			var printdata = document.getElementById(myfrm2);
            newwin = window.open("");
            newwin.document.write(printdata.outerHTML);
            newwin.print();
            newwin.close();
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