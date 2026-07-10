<?php
   
?>
<div class="card">
<div class="card-content">
<div class="row" id="myfrm">
<div class="ga-pro">
<div class="col-lg-12"> <?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></div>
<h3 style="text-align:center;margin: 10px 0px;">Consent for Microfluidics </h3>
<form action="" enctype='multipart/form-data' method="post">  
<table width="100%" class="">
<tbody>
<tr><td colspan="3" style="padding: 10px 10px;">We hereby request and give consent to the procedure of Microfluidics , to be performed on the gametes.</td></tr>
<tr><td colspan="3" style="padding: 10px 10px;"><strong>Description of Procedure:</strong> </td></tr>
<tr><td colspan="3" style="padding: 10px 10px;">I understand that microfluidics technology will be used in various stages of my IVF treatment, including sperm sorting, oocyte handling, and embryo culture.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>Purpose and Benefits: </strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">The purpose of using microfluidics is to enhance the efficiency and success rates of the IVF process by improving sperm selection, providing optimal embryo culture conditions, and minimizing exposure to potentially harmful substances.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>Potential Risks:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"> I understand that, while the use of microfluidics offers potential benefits, there are also risks, including technical failures and unanticipated biological responses. These risks have been explained to me in detail.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>Alternatives:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">I am aware of alternative methods available that do not involve microfluidics, and I have discussed these options with my healthcare provider.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>Confidentiality:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong> I understand that my medical information will be kept confidential and will only be shared for research or technological improvement purposes with my explicit consent.</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>Voluntary Participation:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"> I understand that my participation in this procedure is voluntary, and I have the right to withdraw my consent at any time without affecting my standard care.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>Questions:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">I have had the opportunity to ask questions about the procedure and have received satisfactory answers.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><h3>Endorsement by the ART clinic </h3></td></tr>

<tr>
<td colspan="3">
<p style="padding: 9px 10px;">I/we have personally explained to   <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border:0px; width:300px"></span>  and <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 250px;" /></span> ,the details and implications of his/her/their signing this consent/approval form, and made sure to the extent humanly possible that he/she/they understand these details and implications. </p>
</td>
</tr>

<tr><td colspan="1"style="padding: 9px 10px;">Signature of Male Partner : <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td><td colspan="1" style="padding: 9px 10px;"> Signature of Female Partner:: <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 250px;" /></span></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><h3 style="margin: 10px 0px;">Name, Address and Signature of the Witness from the clinic: </h3></td></tr>
<tr><td colspan="1"style="padding: 9px 10px;">Name <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td><td colspan="1" style="padding: 9px 10px;">Address : <span id="username7" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name7" value="" style="border: 0px; width: 250px;" /></span></td><td colspan="1" style="padding: 9px 10px;">Signature  : <span id="username8" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name8" value="" style="border: 0px; width: 250px;" /></span></td></tr>
<tr><td colspan="1"style="padding: 9px 10px;">Doctor Name <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td><td colspan="1" style="padding: 9px 10px;"> Signature of the Doctor: <span id="username10" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name10" value="" style="border: 0px; width: 250px;" /></span></td><td colspan="1" style="padding: 9px 10px;">Dated: <span id="username3" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name3" value="" style="border: 0px; width: 250px;" /></span></td></tr>
	
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong></td></tr>
<tr><td colspan="3" style="padding-top: 50px;"></td></tr>

<tr><td colspan="3"></td></tr></tbody>
  </table>
</form>
</div>
</div>
</div>
</div>
<div style="text-align: center; height: 50px;"><input type="button" class="btn btn-primary" onclick="myPrint('myfrm')" value="print" /></div>

<script>
        function myPrint(myfrm) {
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
			
			var printdata = document.getElementById(myfrm);
            newwin = window.open("");
            newwin.document.write(printdata.outerHTML);
            newwin.print();
            newwin.close();
        }
    </script>
<style>
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