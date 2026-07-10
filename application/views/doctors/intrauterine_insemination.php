<?php
   
?>

<div class="card">
<div class="card-content">
<div class="row" id="myfrm2">
<div class="ga-pro">
<div class="col-lg-12"> <?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></div>
<h2 style="text-align:center;">FORM 7</h2>
<h3 style="text-align:center;margin: 10px 0px;">[See rule 13(f) (ii) ]</h3>
<h3 style="text-align:center;margin: 10px 0px;">INFORMED CONSENT FOR INTRAUTERINE INSEMINATION /ART WITH HUSBAND’S SEMEN/ SPERM</h3>
<form action="" enctype='multipart/form-data' method="post">  
<table width="100%" class="">
<tbody>
<tr>
<td colspan="3">
<p style="padding: 9px 10px;"><span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border:0px; width:350px"></span> and <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border:0px; width:350px"></span>  being husband and wife and both of legal age, authorize</p>
</td>
</tr>
<tr>
<td colspan="3">
<p style="padding: 9px 10px;">Dr.<span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border:0px; width:350px"></span> to inseminate the wife intrauterine with the semen / sperm of the husband for achieving conception.</p>
</td>
</tr>
<tr><td colspan="3" style="padding: 10px 10px;">We understand that even though the insemination may be repeated as often as recommended by the doctor, there is no guarantee or assurance that pregnancy or a live birth will result.</td></tr>
<tr><td colspan="3" style="padding: 10px 10px;">We have also been told that the outcome of pregnancy may not be the same as those of the general pregnant population, for example in respect of abortion, multiple pregnancies, anomalies or complications of pregnancy or delivery.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">The procedure carried out does not ensure a positive result, nor does it guarantee a mentally and physically normal child. This consent holds good for all the cycles performed at the clinic.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">Signature of intending couple</td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">Husband : <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">Wife: <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td><br></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">Endorsement by the ART Clinic</td></tr>

<tr>
<td colspan="3" style="padding: 9px 10px;">
<p>I / we have personally explained to <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border:0px; width:350px"></span> and <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border:0px; width:350px"></span>  the details and implications of
his /her / their signing this consent / approval form, and made sure to the extent humanly possible that he / she / they understand these details and implications</p>
</td>
</tr>

<tr><td colspan="3" style="padding: 9px 10px;">Name, Address and Signature of the Witness from the clinic <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;"></span></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="color:#000000; padding: 5px 10px;">Signed (Husband) :<span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="color:#000000; padding: 5px 10px;">Signed (Wife) : <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="color:#000000; padding: 5px 10px;">Name and Signature of the Doctor <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr><td colspan="3" style="color:#000000; padding: 5px 10px;">Name and Address of the ART clinic <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border:0px; width:350px"></span></td></tr>

<tr><td colspan="3"></td></tr>
<tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username13" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name13" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username14" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name14" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username15" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name15" value="" style="border: 0px; width: 150px;" /></span></td>
</tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3"></td></tr>

<tr><td colspan="3"></td></tr>
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

			
            var printdata = document.getElementById(myfrm2);
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