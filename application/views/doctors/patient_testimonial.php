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
                <h3 style="text-align: center; margin: 10px 0px;">Consent For patient testimonial</h3>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  I/We <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border: 0px; width: 350px;" /></span> have undergone treatment at    India IVF clinic. We have agreed that our photographs/video recording, patient records can be used and displayed as testimonials by the hospital at different platforms including but not limited to India IVF clinic website, Facebook and other online and off line mediums etc.,
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  We have given this consent out of free will and after reading and understanding the consent form.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   We are mentally fit at the time of giving the consent.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   Date : <span id="username2" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name2" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   Place: <span id="username3" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name3" value="" style="border: 0px; width: 350px;" /></span> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    Date of video recording /Photography: <span id="username4" style="border-bottom: 1px dotted;"><input type="date" name="name" id="name4" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    Signature: <input type="text" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                  Endorsement by translator: <span id="username5" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name5" value="" style="border: 0px; width: 350px;" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                   I,Mr. <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="" style="border: 0px; width: 350px;" /></span> the translator of the afore mentioned  patient have correctly translated and communicated to the patient the contents of the testimonial and the patient after understanding the contents have executed this consent form in my presence 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 10px 10px;">
                                    Signature:  <input type="text" name="name" id="" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 300px;" />
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
</style>