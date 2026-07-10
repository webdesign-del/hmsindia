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
            
                <h2 style="text-align: center;">FORM 15</h2>
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FORM FOR WITHDRAWAL</h3>
				<form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
						    <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   I <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 300px;" /></span>
                                   (Name of the Surrogate mother) age <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 300px;" /></span>
                                   w/d/o <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 300px;" /></span> 
                                   (name of Husband if applicable) wish u to withdraw my consent to act as Surrogate Mother for <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border: 0px; width: 350px;" /></span> 
                                  Mrs. /Ms. and Mr <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 350px;" /></span> 
                                 for the reasons stated below.   
                            	</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
								Specific Reasons for withdrawing <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">Signature of the Surrogate Mother <span id="username8"><input type="text" name="name" id="name8" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span></td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">Signature of Spouse (husband, if any) <span id="username9"><input type="text" name="name" id="name9" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span></td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">Endorsement by the Surrogacy Clinic</td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                 I / we have personally explained to Mrs. /Ms <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 350px;" /></span>  
                                 the details and implications of her withdrawing this consent / and made sure to the extent humanly possible that she understands these details and implications.
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">
								Name, address and signature of the Witness from the clinic <span id="username15"><input type="text" name="" id="name15" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                               </td>
                            </tr>
							 <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                               Name and signature of the Doctor <span id="username16"><input type="text" name="name" id="name16" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span> 
                               </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                                Name and address of the Surrogacy clinic <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border: 0px; width: 200px;" /></span>
                             </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Signature of the Surrogate Mother <span id="username17"><input type="text" name="name" id="name17" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Signature of Spouse (if available) <span id="username18"><input type="text" name="name" id="name18" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Date <span id="username11" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name11" value="" style="border: 0px; width: 350px;" /></span> 
								</td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               * Strike of which is not applicable
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Endorsement by the Surrogacy Clinic
							   </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                              I / we have personally explained to Mrs <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border: 0px; width: 200px;" /></span>
							  surrogate Mother of Ms./ Mrs. <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border: 0px; width: 200px;" /></span>
                              and Mr <span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border: 0px; width: 200px;" /></span>
                              details and implications of his / her / their signing this consent / approval form, and made sure to the extent humanly possible that he / she / they understand these details and implications.
							  </td>
                            </tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">Signed: </td>
                            </tr>
							<tr><td colspan="2" style="padding: 9px 10px;"></td></tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">Signature of the Surrogate Mother: <span id="username19"><input type="text" name="name" id="name19" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td>
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
							<tr><td colspan="2" style="padding: 9px 10px;"></td></tr>
							<tr><td colspan="2" style="padding: 9px 10px;">Signature of the Doctor: <span id="username20"><input type="text" name="" id="name20" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span></td></tr>
							<tr><td colspan="2" style="padding: 9px 10px;"></td></tr>
							<tr>
                                <td colspan="2" style="padding: 9px 10px;">
                               Name, Address and Signature of the Witness from the Surrogacy clinic : <span id="username21"><input type="text" name="name" id="name21" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span>
								 </td>
                            </tr>
							<tr><td colspan="2" style="padding: 9px 10px;"><strong>Terms and Conditions of Abortion</strong></td></tr>
							<tr><td colspan="2" style="padding: 9px 10px;">1. Up to 20 weeks – upon advice of one doctor.</td></tr>
							<tr><td colspan="2" style="padding: 9px 10px;">2. Between 20 - 24 weeks. Two doctors for some categories of pregnant surrogate mother. [For E.g.: In case of grave injury to her physical or mental health or severe physical or mental abnormality of the foetus].</td></tr>
							<tr><td colspan="2" style="padding: 9px 10px;">3. More than 24 weeks - Medical Board in case of substantial foetal abnormality.</td></tr>
							<tr><td colspan="2" style="padding: 9px 10px;">4. Anytime during pregnancy- one doctor if abortion is immediately necessary to save the life of the surrogate mother.</td></tr>
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
