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
            
                <h2 style="text-align: center;">Form 19</h2>
                <h3 style="text-align: center; margin: 10px 0px;">Screening of the Surrogate</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
						    <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   Date of Screening Surrogate: <span id="username" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name" value="" style="border: 0px; width: 250px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                  Basic Information
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   1. Name & Identification number (Surrogate ID) <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 2. Aadhar number <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 350px;" /></span> 
                                 </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 3. Age / Date of birth <span id="username4" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name4" value="" style="border: 0px; width: 350px;" /></span> 
                                  </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 4. Place of birth  <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                5. Marital status   <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 350px;" /></span> 
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 6. Education <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 350px;" /></span>  
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                7. Occupation <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 8. Monthly income <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border: 0px; width: 350px;" /></span> 
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 9. Religion <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                              10. Address <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border: 0px; width: 200px;" /></span> 
								</td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               11. Telephone <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border: 0px; width: 200px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                12. Email <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border: 0px; width: 200px;" /></span>
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                  History
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 13. Obstetric history
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               i) Number of deliveries <span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border: 0px; width: 500px;" /></span>
								  </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                ii) Number of living children <span id="username15" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name15" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                  iii) Number of abortions <span id="username16" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name16" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                  iv) Any History of still birth/Bad obstetric history <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name17" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 v) Any complication in previous pregnancy like hyperemesis, preeclampsia, gestational diabetes <span id="username18" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name18" value="" style="border: 0px; width: 350px;" /></span>
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
                                  vi) Other points of note <span id="username19" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name19" value="" style="border: 0px; width: 350px;" /></span>
                               </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                   <input type="hidden" name="name" id="" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 14. Menstrual history <span id="username20" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name20" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                 15. History of use of contraceptives <span id="username21" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name21" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                16. Medical history <span id="username22" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name22" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               17. Family history from the medical point of view <span id="username23" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name23" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               18. History of blood transfusion <span id="username24" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name24" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               19. History of substance abuse <span id="username25" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name25" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               20. History of any Genetic abnormality, if available, any results of tests undertaken in relation to that abnormality. <span id="username26" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name26" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Investigations
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               21. Blood group and Rh status <span id="username27" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name27" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             22. Complete blood picture: <span id="username28" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name28" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              a. Hb <span id="username29" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name29" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              b. Total RBC count <span id="username30" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name30" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              c. Total WBC count <span id="username31" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name31" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              d. Differential WBC count <span id="username32" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name32" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             e. Platelet count <span id="username33" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name33" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             f. Peripheral smear <span id="username34" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name34" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              23. Random blood sugar (HbA1C) <span id="username35" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name35" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              24. FBS, PPBS <span id="username36" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name36" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              25. Blood urea / Serum creatinine <span id="username37" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name37" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              26. SGPT <span id="username38" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name38" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             27. Routine urine examination <span id="username39" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name39" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             28. HBsAg status <span id="username40" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name40" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                            29. Hepatitis C status <span id="username41" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name41" value="" style="border: 0px; width: 350px;" /></span>
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
                             30. HIV status with date of the tests done <span id="username42" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name42" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             31. Hemoglobin A2 (for thallasemia) status <span id="username43" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name43" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             32. Any other specific test <span id="username44" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name44" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             33. Sexually transmitted diseases <span id="username45" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name45" value="" style="border: 0px; width: 350px;" /></span> 
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Features
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             34. Height <span id="username46" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name46" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             35. Weight <span id="username47" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name47" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             36. BMI <span id="username48" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name48" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             Details Physical Examination
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             37. Pulse <span id="username49" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name49" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             38. Blood pressure <span id="username50" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name50" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             39. Temperature <span id="username51" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name51" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             40. Pallor, Oedema <span id="username52" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name52" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             41. Respiratory system <span id="username53" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name53" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             42. Cardiovascular system <span id="username54" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name54" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                             43. Per abdominal examination (PS/PV) <span id="username55" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name55" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                44. Ultrasound between day 2-day 5 <span id="username56" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name56" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                45. Ultrasound between day 10-15 to check for Endometrial thickness and blood flow Other Systems: <span id="username57" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name57" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Footnotes:
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                1. To be carried out within three months prior to embryo transfer All blood investigation will hold a validity of six months and will have to be repeated if duration since last investigation is more than six months.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                2. Any additional test carried out on the basis of the history and examination of Surrogate.
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Name <span id="username58" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name58" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Signature <span id="username60" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name60" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" />
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Date: <span id="username59" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name59" value="" style="border: 0px; width: 350px;" /></span>
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
