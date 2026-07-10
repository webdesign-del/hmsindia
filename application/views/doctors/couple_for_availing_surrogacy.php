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
                <h3 style="text-align: center; margin: 10px 0px;">Application Form for Intending Couple for availing Surrogacy</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
						    <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 1. Basic Information
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   1.1 Details of Intended Father: <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   1. Name: <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 2. Surname:  <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 350px;" /></span> 
                                 </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   3. DOB: <span id="username4" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name4" value="" style="border: 0px; width: 350px;" /></span> 
                                  </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 4. Blood Group:  <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 5. Age in years:   <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 350px;" /></span> 
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 6. Sex: Male/ Female <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 350px;" /></span>  
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                7. Nationality:<span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 8. Occupation: <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border: 0px; width: 350px;" /></span> 
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 9. Marital Status: Married/ Divorced /Widow. <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 10. Address
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               i) Present: <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border: 0px; width: 200px;" /></span> 
								</td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                ii) Permanent <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border: 0px; width: 200px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                11. Telephone/Mob. No. <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border: 0px; width: 200px;" /></span>
                               </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               12. Email: <span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border: 0px; width: 500px;" /></span>
								  </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                13. Medical History: (Please specify the condition if any) <span id="username15" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name15" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                   1.2 Details of the Intended Mother: <span id="username16" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name16" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                  1. Name: <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name17" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 2. Surname <span id="username18" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name18" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                  3. DOB: <span id="username19" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name19" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                   <input type="hidden" name="name" id="" value="" style="border: 0px; width: 350px;" /></span> 
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
                                <td colspan="2" style="padding: 9px 10px;">
                                  4. Blood Group: <span id="username20" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name20" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 5. Age in years <span id="username21" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name21" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                6. Sex:(Male / Female) <span id="username22" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name22" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               7. Nationality: <span id="username23" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name23" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               8. Occupation: <span id="username24" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name24" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               9. Marital Status: Married/ Divorced /Widow. <span id="username25" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name25" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                10. Address
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               i) Present: <span id="username26" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name26" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               ii) Permanent <span id="username27" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name27" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              11. Telephone/Mob. No. <span id="username28" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name28" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              12. Email: <span id="username29" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name29" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              13. Do you have a History of Miscarriages? <span id="username30" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name30" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              14. If yes Number:  <span id="username31" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name31" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              15. Any other Medical History: (Please specify the condition if any) <span id="username32" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name32" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              1.3 Duration of Marriage in years <span id="username33" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name33" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              1.4 Trying to conceive since (years) <span id="username34" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name34" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              1.5 Do you have any child before? <span id="username35" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name35" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              1.6 If yes, Does the child suffer from any abnormality or life-threatening condition. <span id="username36" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name36" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              1.7 Please describe <span id="username37" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name37" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              1.8 Have you tried any other ART procedure before availing Surrogacy? <span id="username38"><input type="text" name="name" id="name38" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             1.9 If yes, please specify <span id="username39"><input type="text" name="name" id="name39" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             a) Specify the procedure <span id="username40"><input type="text" name="name" id="name40" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                            b) Did the procedure result in pregnancy or birth or failed attempt? <span id="username41"><input type="text" name="name" id="name41" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             c) How many failed attempts. <span id="username42"><input type="text" name="name" id="name42" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
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
                                <td colspan="2" style="padding: 9px 10px;">
                             d) Any medical complications of the procedures if any <span id="username43"><input type="text" name="name" id="name43" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             1.10 Briefly describe the reason for availing surrogacy <span id="username44"><input type="text" name="name" id="name44" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Declaration
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             I hereby declare that the above statements are true to the best of my knowledge and belief.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Date: <span id="username45"><input type="date" name="name" id="name45" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span> 
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Signature of the Intended father : <span id="username49"><input type="text" name="name" id="name49" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Place: <span id="username46"><input type="text" name="name" id="name46" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Signature of the Intended Mother <span id="username50"><input type="text" name="name" id="name50" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             1. Proof of marriage / Marriage Certificate
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             2. Proof of age/ Birth certificate/10th certificate/ or any equivalent.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             3. Proof of divorce/Divorce Certificate
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                            4. Proof of Medical record/condition for availing surrogacy
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                            5. Proof of abnormality/ life threatening condition in case of previous abnormal child
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
