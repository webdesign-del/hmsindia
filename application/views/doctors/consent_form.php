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
<h2 style="text-align:center;">FORM 6</h2>
<h3 style="text-align:center;margin: 10px 0px;">[See rule 13(f) (i) ]</h3>
<h3 style="text-align:center;margin: 10px 0px;">INFORMED CONSENT FORM TO BE SIGNED BY THE COUPLE OR WOMAN </h3>
<form action="" enctype='multipart/form-data' method="post">  
<table width="100%" class="">
<tbody>
<tr>
<td colspan="3">
<p style="padding: 9px 10px;">I/We have requested the clinic <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border:0px; width:300px"></span>  (name and address of clinic) to provide us with treatment services to help us bear a child.</p>
</td>
</tr>
<tr><td colspan="3" style="padding: 10px 10px;">We understand and accept (as applicable) that:</td></tr>
<tr><td colspan="3" style="padding: 10px 10px;">1. The drugs that are used to stimulate the ovaries for ovulation induction have temporary side- effects
like nausea,headaches and abdominal bloating. Only in a small proportion of cases, a condition called
ovarian hyperstimulation occurs where there is an exaggerated ovarian response. Such cases can be
identified ahead of time but only to a limited extent. Further, at times the ovarian response is poor or
absent in spite of using a high dose of drugs. Under these circumstances, the treatment cycle will be
cancelled.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">2. There is no guarantee that:</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">(i) The oocytes will be retrieved in all cases.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">(ii) The oocytes will be fertilized.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">(iii) Even if there were fertilization, the resulting embryos would be of suitable quality to be transferred.</td><br></tr>

<tr><td colspan="3" style="padding: 9px 10px;">All these unforeseen situations will result in the cancellation of any treatment.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">3. I/ We fully consent to these procedures and to the administration of such drugs and anesthetics as
may be necessary. We also consent to any other operative measures, which may be found to be necessary in the course of the treatment.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">4. I/ We have been told of the risks of ultrasound directed follicle aspiration.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">5. I/ We are aware that we are free to withdraw or vary the terms of this consent until the gametes and/
or embryos have been used in accordance with my/ our wishes. I am aware that this will have to be a written request.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">6. There is no certainty that a pregnancy will result from these procedures even in cases where good
quality embryos are transferred.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">7. If a clinical pregnancy does result from assisted conception treatment, I/ we understand there is an
accepted risk of multiple pregnancy, an ectopic pregnancy or of a miscarriage. I/ We understand that as in natural conception, there is a small risk of fetal abnormality.</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">8. Medical and scientific staff can give no assurance that any pregnancy will result in the delivery of a
normal living child.</td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>Dated: <span id="username5" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name5" value="" style="border:0px; width:300px"></span></strong></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong></td></tr>
<tr><td colspan="1" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>Wife Signature : </strong><span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td><td colspan="1" style="font-size:10px;color:#000000; padding: 5px 10px;"><strong>Husband Signature :</strong><span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">9. The uncertainty of the outcome of the procedure has been fully explained to me/ us.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">I/ We fully understand the risks of treatment including;</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">(i) it is not possible to guarantee that a follicle will develop in a given cycle and that
occasionally cycles have to be abandoned before egg retrieval.</td></tr>

<tr><td colspan="3" style="padding: 9px 10px;">(ii) there is a risk that spontaneous ovulation can happen prior to/or during the egg retrieval.</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">(iii) an egg is not always recovered from a follicle at the time of egg retrieval.</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">(iv) any eggs may be collected and fertilization of any collected eggs will occur</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">(v) is a risk that the cycle will be abandoned before Embryo Transfer if there is failure of fertilization, abnormal fertilization or failure of the embryo to cleave(divide)</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">(vi) a pregnancy may result from treatment.</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">(vii) treatment may be abandoned at any time if there are problems in the laboratory or with the culture system</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">10. I/ We have been fully informed of all that is involved with the IVF/ICSI technique and have been advised regarding the chances of success, the possibility of multiple pregnancy occurring and other possible complications of treatment by the doctor. I/ We have also received information relating to treatment by these techniques in order to assist us to become more fully aware of what is involved.</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">Endorsement by the ART clinic</td></tr>

<tr><td colspan="3"style="padding: 9px 10px;">I/ we have personally explained to <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name2" id="name2" value="" style="border:0px; width:300px"></span> and  <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name3" id="name3" value="" style="border:0px; width:300px"></span>  the details and implications of his / her / their signing this consent / approval form, and made sure to the extent humanly possible that he /she /they understand these details and implications.</td></tr>


<tr><td colspan="3"style="padding: 9px 10px;">signing this consent / approval form, and made sure to the extent humanly possible that he /she /they
understand these details and implications.</td></tr>
<tr><td colspan="3"style="padding: 9px 10px;">This consent would hold good for all the cycles performed at the clinic.</td></tr>
<tr><td colspan="3"style="padding: 9px 10px;">Name and Signature of the couple (husband and wife) or Woman <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr><td colspan="3"style="padding: 9px 10px;">Name, Address &amp;Signature of the Witness from the Clinic <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;"></span></td></tr>
<tr><td colspan="3"style="padding: 9px 10px;">Name and Signature of the Doctor <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr><td colspan="3"style="padding: 9px 10px;">Name and Address of the ART Clinic <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;"></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="1" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>Wife Signature : </strong><span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;"></span></td><td colspan="1" style="font-size:10px;color:#000000; padding: 5px 10px;"><strong>Husband Signature :</strong><span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span></td></tr>
<tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username13" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name13" value="" style="border: 0px; width: 250px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username14" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name14" value="" style="border: 0px; width: 250px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username15" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name15" value="" style="border: 0px; width: 250px;" /></span></td>
</tr>
<tr><td colspan="3" style="font-size:12px;color:#000000;padding: 9px 10px;"><strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000;padding: 9px 10px;"><strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong></td></tr>
<tr><td colspan="3"></td></tr>
</tbody>
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