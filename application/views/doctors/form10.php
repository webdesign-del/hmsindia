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
                <h2 style="text-align: center;">FORM 10</h2>
                <h3 style="text-align: center; margin: 10px 0px;">[See rule 13 (f) (v)]</h3>
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FOR FREEZING OF GAMETES/SPERM/OOCYTES</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p style="padding: 9px 10px;">
                                        I/We,<span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span> and,
                                        <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span>, consent to freezing of the my
                                        <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 350px;" /></span> (sperm/oocyte). We understand that the gametes
                                        would be normally kept frozen for <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border: 0px; width: 350px;" /></span> years. In the
                                        exceptional circumstances If I/we wish to extend this period, we would let the ART clinic
                                        <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 350px;" /></span> (Name and address) know at least six months
                                        ahead of time. If you do not hear from us before that time, you will be free to
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">(a) use them for research purposes;</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">(b) or (b) discard and destroy them off.</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    We also understand that sometimes the quality of these
                                    <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 350px;" /></span> sperm/occytes may decrease on subsequent thaw and
                                    that frozen gametes may have a lower pregnancy rate than when fresh gametes are transferred.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">*Husband / Man</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">In the unforeseen event of my death, I would like the gametes</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;"> To perish <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
 To be handed over to my wife/ (specify name and details) <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Used for research purposes <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Signed: <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Dated: <span id="username11" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name11" style="border: 0px; width: 200px;"/></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">*Wife / woman</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">In the unforeseen event of my death, I would like the gametes</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">To perish <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    To be handed over to my husband / (Specify name and details) <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Used for research purposes <span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>
                                        Dated: <span id="username15" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name15" value="" style="border: 0px; width: 350px;" /></span>
                                    </strong>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">Signed: <span id="username16" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name16" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
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
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Dated: <span id="username17" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name17" value="" style="border: 0px; width: 200px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Name, Address and Signature of the couple/woman/man <span id="username18" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name18" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Endorsement by the ART clinic</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    I/ we have personally explained to <span id="username19" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name19" value="" style="border: 0px; width: 350px;" /></span> and
                                    <span id="username20" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name20" value="" style="border: 0px; width: 350px;" /></span> the details and implications of his / her / their
                                    signing this consent / approval form, and made sure to the extent humanly possible that he / she / they understand these details and implications.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Name, Address and Signature of the Witness from the Clinic. <span id="username21" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name21" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Name and Signature of the Doctor <span id="username22" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name22" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Name and Address of the ART Clinic <span id="username23" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name23" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">*The appropriate option may be ticked</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>
                                        Dated: <span id="username24" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name24" value="" style="border: 0px; width: 200px;" /></span>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>
                                        Place: <span id="username25" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name25" value="" style="border: 0px; width: 350px;" /></span>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;"><strong>Terms and Conditions</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;"><strong>1. Provision of Information</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    As long as I have cryopreserved gametes in storage at clinic mentioned above, I hereby agree to contact the above clinic at least annually to provide current information indicating my address, telephone
                                    number, email address and contact details and intention regarding my cryopreserved gametes.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Failure to:</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">(i) contact the clinic for a period of twelve months;</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    (ii) respond to a request for information from clinic within 90 days of receipt; shall constitute abandonment and signify my desire to terminate storage of Cryopreserved gametes.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    <p>
                                        In the event of my failure to comply with (i) and (ii) above, I instruct the above-mentioned clinic and hereby consent to my Cryopreserved gametes either being destroyed and discarded or given for
                                        research.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">2. Payment of Fees</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    I understand that I am responsible for the costs of cryopreservation and storage of my Cryopreserved gametes. Cryopreservation and storage fees are due and payable at the time of gamete cryopreservation,
                                    and at the beginning of each annual storage interval thereafter. I understand these fees are non-refundable and are not subject to prorated adjustment for partial storage intervals. Should the yearly fee
                                    for storage of my Cryopreserved gametes, remain unpaid for a period of one year after the first invoice is forwarded to my address/email/informed to me telephonically the clinic can conclude that I am no
                                    longer interested in storing these specimen(s) and I hereby instruct the clinic to destroy of my Cryopreserved gametes or use for research.
                                </td>
                            </tr>
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
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">3. Alternate Contact/Responsible Party</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                    I hereby name <span id="username26" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name26" value="" style="border: 0px; width: 350px;" /></span> as an alternate contact and my
                                    representative to assume responsibility for sections 1 and 2 above in the event that I am unable due to illness. I have attached a signed acknowledgement by
                                    <span id="username27" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name27" value="" style="border: 0px; width: 350px;" /></span> that they have read this form and will be
                                    responsible for its provisions in the event that I cannot.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 9px 10px;">Contact details of alternate person <span id="username28" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name28" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">Signed (Husband)  <span id="username29" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name29" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">Signed (Wife) <span id="username30" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name30" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">Endorsement by the ART clinic </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    I/ we have personally explained to <span id="username31" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name31" value="" style="border: 0px; width: 350px;" /></span> and
                                    <span id="username32" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name32" value="" style="border: 0px; width: 350px;" /></span> the details and implications of his / her / their
                                    signing this consent / approval form, and made sure to the extent humanly possible that he / she / they understand these details and implications.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">Name, Address and Signature of the Witness from the Clinic <span id="username33" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name33" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">Name and Signature of the Doctor <span id="username34" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name34" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">Name and Address of the ART Clinic <span id="username35" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name35" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">*The appropriate option may be ticked</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username36" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name36" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username37" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name37" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username38" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name38" value="" style="border: 0px; width: 150px;" /></span></td>
</tr>
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

        var printdata = document.getElementById(myfrm2);
        newwin = window.open("");
        newwin.document.write(printdata.outerHTML);
        newwin.print();
        newwin.close();
    }
</script>
<style>
    table,
    th,
    td {
        border: 0px;
    }
    input[type="checkbox"],
    input[type="radio"] {
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
    .vb45rt td {
        text-align: left;
        padding-left: 10px;
    }
    table th,
    table td {
        padding: 10px 10px;
        text-align: left;
    }
</style>