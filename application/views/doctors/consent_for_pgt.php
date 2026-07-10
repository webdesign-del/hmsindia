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
<h3 style="text-align:center;margin: 10px 0px;">Consent for PGT (Pre implantation Genetic Testing ) </h3>
<form action="" enctype='multipart/form-data' method="post">  
<table width="100%" class="">
<tbody>
<tr><td colspan="3" style="padding: 10px 10px;">We hereby request and give consent to the procedure of -PGT   --  , to be performed on <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border:0px; width:300px"></span>Number of embryos.</td></tr>
<tr><td colspan="3" style="padding: 10px 10px;"><strong>Introduction:</strong> </td></tr>
<tr><td colspan="3" style="padding: 10px 10px;">This consent form is intended to provide you with information about Preimplantation Genetic Testing (PGT) and to obtain your consent for the procedure. Please read this document carefully and ask any questions you may have before signing.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>1. Description of the Procedure:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Embryo biopsy: A few cells are removed from the embryos at the blastocyst stage.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Genetic analysis: The biopsied cells are analyzed for specific genetic conditions or chromosomal abnormalities.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Embryo selection: Embryos free of the tested genetic abnormalities are selected for transfer to the uterus.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>2. Purpose and Benefits:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">The primary purpose of PGT is to:</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Identify embryos with genetic or chromosomal abnormalities to reduce the risk of genetic disorders.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Increase the likelihood of a successful pregnancy and healthy baby.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Allow couples with known genetic conditions to have children free from those conditions.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>3. Types of PGT:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>PGT-A (Preimplantation Genetic Testing for Aneuploidy): Screens for chromosomal abnormalities such as Down syndrome.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>PGT-M (Preimplantation Genetic Testing for Monogenic Disorders): Screens for specific genetic disorders like cystic fibrosis or sickle cell anemia.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>PGT-SR (Preimplantation Genetic Testing for Structural Rearrangements): Screens for chromosomal structural rearrangements like translocations.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong><li>NO SEX SELECTION IS DONE IT IS PROHIBITED AND PUNISHABLE AS PER INDIAN LAWS </li></strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>4. Potential Risks and Complications:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">While PGT can offer significant benefits, it also carries potential risks and limitations, including:</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Misdiagnosis or no diagnosis due to technical limitations.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Damage to embryos during the biopsy process, potentially affecting their viability.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>The possibility of not having any embryos suitable for transfer.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Emotional and psychological stress associated with the outcomes of genetic testing.</li></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong></td></tr>
<tr><td colspan="3" style="font-size:12px;color:#000000; padding: 5px 10px;"><strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong></td></tr>
<tr><td colspan="3" style="padding-top: 50px;"></td></tr>

<tr><td colspan="3" style="padding: 9px 10px;"><strong>5. Alternatives to PGT:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">Patients should be informed about alternatives to PGT, such as:</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Proceeding with IVF without genetic testing.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Using donor gametes (sperm or eggs) to reduce the risk of genetic disorders.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><li>Prenatal testing after conception, such as chorionic villus sampling (CVS) or amniocentesis.</li></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>6. Confidentiality and Data Protection:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">Your medical information and the results of your PGT will be kept confidential. They will only be shared with authorized personnel involved in your care and for research purposes with your explicit consent.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>7. Voluntary Participation:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">Participation in PGT is voluntary. You have the right to refuse without affecting your standard care. Your decision will not impact your relationship with your healthcare providers or the institution.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;"><strong>8. Documentation and Signatures:</strong></td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">By signing this form, you acknowledge that you have read and understood the information provided, have had the opportunity to ask questions, and voluntarily consent to undergo PGT.</td></tr>
<tr><td colspan="3" style="padding: 9px 10px;">The procedure(s) carried out does (do) not ensure a positive result, nor do they guarantee a mentally and physically normal baby. /td></tr>
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

            let name12 = document.getElementById("name12").value; 
			document.getElementById("username12").innerHTML = name12;
			
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