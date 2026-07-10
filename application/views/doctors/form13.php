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
                <h2 style="text-align: center;">FORM 13</h2>
                <h3 style="text-align: center; margin: 10px 0px;">[See rule 13 (f) (viii)]</h3>
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FORM FOR THE DONOR OF OOCYTES</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    I, Ms. <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span> , Address
                                    <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span> Mobile number
                                    <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 350px;" /></span> AADHAR card number
                                    <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border: 0px; width: 350px;" /></span> Willingly consent to donate my oocyte to
                                    couple/individual who are unable to have a child by other means. At this stage and to the best of my knowledge I am free of any infectious diseases or genetic disorders
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    I have had a full discussion with Dr <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 350px;" /></span> (name and
                                    address of the clinician) on <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    I have been counselled by <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 350px;" /></span> (name and address of
                                    independent counsellor) on <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    (I understand that there will be no direct or indirect contact between me and the recipient, and my personal identity will not be disclosed to the recipient or to the child born through the use of my
                                    gamete.)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">I understand that I shall have no rights whatsoever on the resulting offspring and vice versa.</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    I understand that the method of treatment may include:
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">1. Stimulating my ovaries for multifollicular development.</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">2. The recovery of one or more of my eggs under ultrasound-guidance or by laparoscopy under sedation or general anesthesia.</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">3. The fertilization of my oocytes with recipient’s husband’s or donor sperm and transferring the resulting embryo into the recipient.</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    I understand and accept that the drugs that are used to stimulate the ovaries to raise oocytes have temporary side- effects like nausea, headaches and abdominal bloating. Only in a small proportion of
                                    cases, a condition called ovarian hyperstimulation occurs where there is an exaggerated ovarian response. Such cases can be identified ahead of time but only to a limited extent. Further, at times the
                                    ovarian response is poor or absent in spite of using a high dose of drugs. Under these circumstances, the treatment cycle will be cancelled.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Name, address and signature of woman <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">Endorsement by the ART clinic</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    I / we have personally explained to <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border: 0px; width: 350px;" /></span> the details
                                    and implications of her signing this consent / approval form, and made sure to the extent humanly possible that she understands these details and implications.
                                </td>
                            </tr>
							 <tr>
                                <td colspan="3" style="padding: 9px 10px;"><input type="hidden" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></td>
                            </tr>
							<tr>
                                <td colspan="3" style="color: #000000; padding: 5px 10px;">
                                    <strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="color: #000000; padding: 5px 10px;">
                                    <strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="3" style="padding: 9px 10px;"><input type="hidden" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">Name, address and signature of the Witness from the clinic <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Name and signature of the Doctor <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Name and address of the ART clinic <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Name and address of the ART Bank that recruited and screened the donor <span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span>
                                </td>
                            </tr>
                            <tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username15" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name15" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username16" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name16" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name17" value="" style="border: 0px; width: 150px;" /></span></td>
</tr>
                            <tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    (This form will be filled by the ART clinic but a copy of the same has to be maintained by the ART bank in case the donor was recruited and screened by the bank)
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="color: #000000; padding: 5px 10px;">
                                    <strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="color: #000000; padding: 5px 10px;">
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
<div style="text-align: center; height: 50px;"><input type="button" class="btn btn-primary" onclick="myPrint2('myfrm2')" value="print" /></div>
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
