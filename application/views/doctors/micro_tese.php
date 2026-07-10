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
                <h3 style="text-align: center; margin: 10px 0px;">INFORMED CONSENT FOR PROCEDURE OF PESA/ TESA/TESE/MICRO TESE</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
						    <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                We hereby request and give consent to the procedure of <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span> for ICSI/ cryopreservation, to be performed on the male partner.  
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  We understand that
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 a) There is no guarantee that the sperm will be successfully removed or that sperm will necessarily fertilise our oocytes. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  b) Should the sperm retrieval fail, the following options will be available for the retrieved oocytes. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                i) Insemination of all or some oocytes using donor sperm 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               ii) Donation of oocytes to another infertile couple 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               iii) Disposal of oocytes according to the ethical guidelines 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               <strong>Tick The appropriate option </strong>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                Each of the above points has been explained to us by  <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border: 0px; width: 450px;" /></span> The procedure(s) carried out does (do) not ensure a positive result, nor do they guarantee a mentally and physically normal baby. This consent holds good for all the cycles performed at the clinic. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               <strong> Endorsement by the ART clinic </strong>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                 I/we have personally explained to <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 450px;" /></span> and  <span id="username4" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name4" value="" style="border: 0px; width: 450px;" /></span> the details and implications of his/her/their signing this consent/approval form, and made sure to the extent humanly possible that he/she/they understand these details and implications. 
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                Signature of Male Partner :  <input type="text" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" />
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                Signature of Female Partner:  <input type="text" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" />
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               Name, Address andSignature of the Witness from the clinic: <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
								</td>
                            </tr>
							<tr>
                                <td colspan="3" style="padding: 10px 10px;">
                               Name and Signature of the Doctor: <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" /></span>
								</td>
                            </tr>
							<tr>
    <td colspan="1" style="padding: 9px 10px;">Dated: <span id="username7" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name7" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Time: <span id="username8" style="border-bottom: 1px dotted;"><input type="time" name="name" id="name8" value="" style="border: 0px; width: 150px;" /></span></td>
    <td colspan="1" style="padding: 9px 10px;">Place: <span id="username9" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name9" value="" style="border: 0px; width: 150px;" /></span></td>
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