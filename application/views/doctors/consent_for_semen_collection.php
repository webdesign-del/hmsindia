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
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FORM FOR SEMEN COLLECTION </h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
						    <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   You are here to give your semen  for a semen analysis / semen freezing (please tick the appropriate).  The purpose of a semen analysis is to evaluate the fertility of the male partner.  The collection and handling of the specimen can have a profound effect on the test results.  Please follow the directions on the back of this sheet carefully.  Please allow 1 business days for results to be provided to you .    
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   In order to complete this test, you will need:  
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  1. An appointment for a semen analysis.  Please call our office at 7353873538 to schedule. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  2. This completed form. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 3. A sterile specimen cup (one obtained from your physician, our office, or a local pharmacy); if you are collecting in our office, the container will be provided upon your arrival. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 Please provide the following information: 
								</td>
                            </tr>
							<tr>
                                 <td colspan="3" style="padding: 10px 10px;">
                            Name <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span> Date of birth <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span>
							</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                Address  <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 450px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 Telephone  No. <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border: 0px; width: 300px;" /></span> Email id <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 200px;" /></span>  
                                 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               Medical history  <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 300px;" /></span>  
                                 </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 Current medications <span id="username7" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name7" value="" style="border: 0px; width: 400px;" /></span> 
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                Date of collection: <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name8" value="" style="border: 0px; width: 350px;" /></span> Time specimen was produced: <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border: 0px; width: 350px;" /></span>  
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                     Days/hours of abstinence prior to collection   <span id="username10" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name10" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                     Was the specimen collected by masturbation? (Yes/No) <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name11" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                     Does the cup contain the entire specimen (complete ejaculate)?  <span id="username12" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name12" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Type of container: <span id="username13" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name13" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span> Source of container: <span id="username15" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name15" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 200px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                     If specimen was transported from another location, did you keep it close to your body (inside shirt or pocket, etc) (Yes/No):<span id="username14" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name14" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    If specimen was exposed to extreme heat or cold during the trip to the lab, please describe:
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                     Any other problems in getting the specimen to the lab ? <span id="username16" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name16" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Time specimen was delivered to the lab:  <span id="username17" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name17" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
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
                                <td colspan="3" style="padding: 9px 10px;">
                                   The information provided above is true and complete.
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Patient signature:   <span id="username18" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name18" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                    Date /Place and time:  <span id="username19" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name19" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                  Signature of Female Partner (if for insemination) : <span id="username20" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name20" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                   Signature of Lab incharge: <span id="username21" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name21" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                   Signature of witness from clinic: <span id="username22" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name22" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                  If sample is collected not at the IVF centre . I approve that the semen sample collected is mine and I have not changed or brought semen sample from any other person .
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                   The information provided above is true and complete.
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                  Patient signature: <span id="username23" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name23" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                   Date /Place and time:  <span id="username24" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name24" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                 Signature of Female Partner(if for insemination) : <span id="username25" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name25" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                  Signature of Lab incharge: <span id="username26" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name26" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 9px 10px;">
                                 Signature of witness from clinic: <span id="username27" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name27" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;" /></span>
                                </td>
                            </tr>
							<tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username28" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name28" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username29" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name29" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username30" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name30" value="" style="border: 0px; width: 150px;" /></span></td>
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
	[type="radio"]:not(:checked), [type="radio"]:checked {
    position: relative!important;
    left: 0px!important;
    opacity: 1!important;
}
</style>