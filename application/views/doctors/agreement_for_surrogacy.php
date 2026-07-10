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
            
                <h2 style="text-align: center;">FORM 2</h2>
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT OF THE SURROGATE MOTHER & AGREEMENT FOR SURROGACY</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                    I, <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 300px;" /></span> (the woman), aged
                                    <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 300px;" /></span> years with the consent of my husband* (name), of 
                                    <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 300px;" /></span> (address)
                                    <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border: 0px; width: 300px;" /></span> (Aadhar Number), having 
                                    <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 300px;" /></span> (Number of children) child/ children
                                    <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 300px;" /></span>  (age in years) of my own have agreed to act as a surrogate mother for Intending couple/intending women .Name Husband <span id="username30" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name30" value="" style="border: 0px; width: 250px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 Name <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 300px;" /></span>  
                                 Wife/ <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border: 0px; width: 300px;" /></span>
                                 Intending women Age <span id="username32" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name32" value="" style="border: 0px; width: 250px;" /></span> Husband Age <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border: 0px; width: 300px;" /></span> 
                                 Wife/Intending women <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border: 0px; width: 300px;" /></span>
                                had a full discussion with  Dr. <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border: 0px; width: 200px;" /></span>
								of the Surrogacy clinic on  <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border: 0px; width: 200px;" /></span>
								in regard to the matter of my acting as a surrogate mother for the child/children of the above couple.
								</td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                  1. That I understand that the methods of treatment may include:
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                  a) Stimulation of the genetic mother for follicular recruitment
                                </td>
                            </tr>
							  <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 b) The recovery of one or more oocytes from the genetic mother by ultrasound-guided oocyte recovery or by laparoscopy.
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 c) The fertilization of the oocytes from the genetic mother with the sperm of her husband.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 d) The fertilization of a donor oocyte by the sperm of the husband.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               e) The maintenance and storage by cryopreservation of the embryo resulting from such fertilization until, in the view of the medical and scientific staff, it is ready for transfer.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 f) Implantation of the embryo obtained through any of the above possibilities into my uterus, after the necessary treatment if any.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 2. That I have been assured that the genetic mother and the genetic father have been screened for HIV and hepatitis B and C and other sexually transmitted diseases before oocyte recovery and found to be seronegative for all these diseases. I have, however, been also informed that there is a small risk of the mother or / and the father becoming seropositive for HIV during the window period.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                3. That I consent to the above procedures and the administration of such drugs that may be necessary to assist in preparing my uterus for embryos transfer, and for support in the luteal phase.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                4. That I understand and accept that there is no certainty that a pregnancy will result from these procedures.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                5. That I understand and accept that the medical and scientific staff can give no assurance that any pregnancy will result in the delivery of a normal and living child/children.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 6. That I am unrelated/ related (relation) <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border: 0px; width: 200px;" /></span>
                                    to the couple (the would-be genetic parents).
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong style="font-size:12px;">उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong>
                                </td>
                            </tr>
							<tr><td colspan="2" style="color:#000000; padding: 5px 10px;"><input type="hidden" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></td></tr>

							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               7. That I have worked out medical expenses and conditions of the surrogacy with the couple in writing and an appropriately authenticated copy of the agreement has been filed with the clinic, which the clinic will keep confidential. An insurance coverage of such amount and in such manner as will be prescribed in favour of the surrogate mother for a period of thirtysix months covering postpartum delivery complications from an insurance company or an agent recognised by the Insurance Regulatory and Development Authority established under the Insurance Regulatory and Development Authority Act, 1999;
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                  8. That I agree to relinquish all my rights over the child and hand over the child/children to <span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border: 0px; width: 300px;" /></span>
								  , or <span id="username15" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name15" value="" style="border: 0px; width: 300px;" /></span>
                                   and <span id="username16" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name16" value="" style="border: 0px; width: 300px;" /></span>
                                  in case of a intending couple, or to <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name17" value="" style="border: 0px; width: 300px;" /></span>
                                 in case of their separation during my pregnancy, or to the survivor in case of the death of one of them during pregnancy, or to <span id="username18" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name18" value="" style="border: 0px; width: 300px;" /></span>
                                   in case of death of both of them, or to in case of guarantor intending couple/ women , as soon as I am permitted to do so by the hospital / clinic / nursing home where the child/ children are delivered.
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                  9. That I have been provided with the writen consent of all those name (s) mentined above
							   </td>
                            </tr>
                          <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                   10. That I undertake to inform the Surrogacy Clinic, <span id="username20" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name20" value="" style="border: 0px; width: 300px;" /></span> ,of the result of the pregnancy.
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                11. That I take no responsibility that the child /children delivered by me will be normal in all respects. I understand that the biological parent(s) of the child/children has / have a legal obligation to accept the child/ children that I deliver and that the child/ children would have all the inheritance rights of a child/ children of the biological parent(s) as per the prevailing law.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                12. That I will not be asked to go through sex determination tests for the child/children during the pregnancy and that I have the full right to refuse such tests.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                13. That I understand that I would have the right to terminate the pregnancy in case of any complication as advised by the doctors, under the provisions of the MTP Act;
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                14. That I certify that (a) I have not beared any child through surrogacy before.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                15. That I have been tested for HIV, hepatitis B and C and shown to be seronegative for these viruses just before embryo transfer.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               16. That I will not have intercourse of any kind once the cycle preparation is initiated.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                17. That I certify that (a) I have not had any drug intravenously administered into me through a shared syringe; and (b) I have not undergone blood transfusion in the last six months.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               18. That I also declare that I will not use drugs intravenously, or undergo blood transfusion excepting of blood obtained through a certified blood bank on medical advice.
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                19. That I undertake not to disclose the identity of the party seeking the surrogacy.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 20. That In the case of the death or unavailability of any of the party seeking my help as the surrogate mother, I will deliver the child/children to <span id="username21" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name21" value="" style="border: 0px; width: 300px;" /></span> or <span id="username31" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name31" value="" style="border: 0px; width: 300px;" /></span> in this order; I will be provided, before the embryo transfer into me, a written agreement of the above
persons that they will be legally bound to accept the child/ children in the case of the above-mentioned eventuality. (If applicable)
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color: #000000; padding: 5px 10px;">
                                    <strong style="font-size:12px;">उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong>
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 My husband (Name)- <span id="username22" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name22" value="" style="border: 0px; width: 300px;" /></span>
								Aadhar card number- <span id="username23" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name23" value="" style="border: 0px; width: 300px;" /></span>
								</td>
                            </tr>
							
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                (Strike off if not applicable.)
								</td>
                            </tr>
							
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Endorsement by the Surrogacy Clinic
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 I/we have personally explained to <span id="username24" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name24" value="" style="border: 0px; width: 300px;" /></span>
								and <span id="username25" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name25" value="" style="border: 0px; width: 300px;" /></span>
								the details and implications of his / her / their signing this consent / approval form, and made sure to the extent humanly possible that he / she / they understand these details and implications.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Signed (Surrogate Mother): <span id="username33" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name33" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 Name, address and signature of the Witness from the Surrogacy clinic <span id="username26" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name26" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Name and signature of the Doctor <span id="username27" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name27" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Name and address of the Surrogacy Clinic <span id="username28" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name28" value="" style="border: 0px; width: 300px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Dated: <span id="username29" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name29" value="" style="border: 0px; width: 300px;" /></span>
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
		
		let name18 = document.getElementById("name18").value;
        document.getElementById("username18").innerHTML = name18;
		
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
