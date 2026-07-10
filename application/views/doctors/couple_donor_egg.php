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
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FORM FOR COUPLE DONOR EGG</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
						 <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   DONOR OOCYTE PROGRAM—RECIPIENT   Description, Explanation and Informed Consent  
								</td>
                            </tr>
							<tr>
                                 <td colspan="3" style="padding: 10px 10px;">
                            We, <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span>, and <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span>, in connections with our participation in The Assisted Reproductive Technologies (A.R.T.) Donor oocyte Program, we hereby authorize the A.R.T. Team of INDIA IVF CLINIC in connection with the A.R.T. procedures and treatments, to use eggs donated by a woman (hereinafter the Donor) other than the female patient. 
							</td>
                            </tr>
							 <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                We have been informed that the egg donor will be treated with fertility drugs, monitored with ultrasound equipment and will have eggs surgically removed by ultrasound-guided aspirations.  We have also been informed that the A.R.T. Team will attempt to fertilize some or all of such eggs with sperm collected from the Male partner and/or Donor sperm previously chosen. 
								</td>
                            </tr>
							 <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                             It is our understanding that the donor eggs will be used within 24-36 hours after collection and that the use of such freshly collected eggs creates a remote possibility of transmission of infectious disease, including but not limited to HIV (AIDS), to any child born as a result of the procedures as well as to the female patient.  We have been informed that the egg Donor will be screened for infectious diseases prior to egg donation but that such screening does not completely eliminate the risk of infectious disease transmission.
							 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 We have been advised that if fertilization occurs and embryo development takes place, the embryo(s) will be transferred to the uterus of the Female patient.  We have also been informed and understand: 
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                1. That transfer of one or more embryos may not result in the Recipient’s pregnancy. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                2. That if the Recipient pregnancy does result from an embryo transfer that the pregnancy may not go to full term. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                3. That any pregnancy of the Recipient includes possibilities of complications during childbirth. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               4. That any child unborn as a result of the pregnancy may be abnormal or a child may be born with undesirable hereditary tendencies; and 
							   </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                5. That there may be other adverse consequences to the Recipient(s) and/or the child. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                We also understand that other risks, complications, or side effects may result from the use of the Donor’s eggs in connection with Assisted Reproductive Technologies.  We have been given the opportunity to ask questions about the procedures, the methods being used and the risks and hazards involved and we believe that we have sufficient information to give this informed consent.  We have also discussed with the A.R.T. Team alternative treatments and it is our decision to accept the risks described above. 
                                </td
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                We hereby rely upon the discretion of the A.R.T. Team in the selection of qualified egg donors.  We acknowledge and agree that the identity of the Donor will not be revealed to us unless we make use of designated donor as listed here <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 350px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                We hereby agree that any child born as a result of our participation in the Program is a child conceived by our acts and hereby acknowledged that such child is our legitimate child with all the the rights and privileges accompanying such status.  
                                 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="color: #000000; padding: 5px 10px;">
                                    <strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="color: #000000; padding: 5px 10px;">
                                    <strong style="font-size:12px;">उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  We hereby agree and understand that the Donor has exposed herself to the potential risk and/or harm by taking the injectable fertility drugs.
                                 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  We have received a written description of the Ooctye Donor Program and understand the financial responsibility for our involvement in the program as a Recipient couple.  We understand that by entering this program, we are financially responsible for the costs incurred by ourselves as well as those of the Donor.
                                 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               To ensure against risk/harm to the Donor, we agree to purchase the mandatory Donor Insurance coverage as required by INDIA IVF CLINIC  for the amount of <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 200px;" /></span> .We understand that we will provide coverage for any complications associated with the ovarian stimulation and/or oocyte retrieval processes including transportation to and from medical visits.  We understand that all financial responsibility for all costs that may arise from any complications associated with the ovarian stimulation and/or ooctye retrieval processes including transportation to and from medical visits remain our responsibility.  We have been informed and understand that the risks inherent in any A.R.T. procedure are very small.  However, as a result of our agreement to participate in the Oocyte Donor Program, we accept this risk, understanding that the Donor is voluntarily entering this program in an attempt to assist us in achieving a pregnancy. 
                                 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 We had a full discussion with <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 200px;" /></span> about the above procedures and we have given oral and written consent about the procedure.
                                </td>
                            </tr>
							 <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                We declare that we shall not attempt to find out the identity of the donor. 
                                </td>
                            </tr>
							 <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 We have also been told that the outcome of pregnancy may not be the same as those of the general pregnant population, for example in respect of abortion, multiple pregnancies, anomalies or complications of pregnancy or delivery. We release the Doctor, India IVF Clinic and its Employees from any and all liability and responsibility of any nature which may result from the complications of childbirth or delivery, from the hereditary tendencies of such issues, or from any other adverse consequences which may arise in connection with or as a result of the process.
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 I <span id="username11" ><input type="text" name="name" id="name11" value="" style="border: 0px; width: 200px;" /></span> the husband, also declare that should my wife bear any child or children as a result of such procedure, such child or children shall be as my own and shall be my legal heir (s). The procedure(s) carried out does (do) not ensure a positive result, nor do they guarantee a mentally and physically normal baby. This consent holds good for all the cycles performed at the clinic.       
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               <strong>Endorsement by INDIA IVF CLINIC </strong>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                I/we have personally explained to <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border: 0px; width: 200px;" /></span> and <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border: 0px; width: 200px;" /></span> the details and implications of his/her/their signing this consent/approval form, and made sure to the extent humanly possible that he/she/they understand these details and implications. 
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                          <input type="hidden" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" />
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
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                          <input type="hidden" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" />
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                All of our questions regarding INDIA IVF CLINIC Donor Oocyte Program Agreement—Recipient have been answered.  Each of us has had the opportunity to read the consent, have had the content fully reviewed with us in our presence, and acknowledges receipt of a copy of this consent.  By signing below, we agree to this agreement in its entirety. 
								</td>
                            </tr>
							
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                         Signature of Wife: <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                        Signature of Husband: <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                     Name and Signature of the Doctor:  <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username14" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name14" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username15" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name15" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username16" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name16" value="" style="border: 0px; width: 150px;" /></span></td>
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