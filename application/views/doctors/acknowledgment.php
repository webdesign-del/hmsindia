<?php
   
?>

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
            
<h2 style="text-align:center;">Acknowledgement</h2>
<form action="" enctype='multipart/form-data' method="post">  
<table width="100%" class="">
<tbody>
<tr>
<td colspan="2">
<p style="padding: 9px 10px;">We understand that signing this Acknowledgment of Parentage will establish parentage of our child with the same force and effect as an Order of Parentage entered after a court hearing including an obligation to provide support for our child except that, only if this Acknowledgment of Parentage is filed with the Registrar where the birth certificate is filed, will the Acknowledgment of Parentage have such force and effect with respect to inheritance rights. We have received written and oral notice of our legal rights (including the timeframes to withdraw), responsibilities, alternatives and the consequences of signing the Acknowledgment of Parentage, and we understand what the notice states. A copy of the written notice has been provided to us.</p>
</td>
</tr>

<tr><td colspan="2"></td></tr>
<tr><td colspan="2" style="color:#000000; padding: 5px 10px;">Signed Intended Mother <span id="username" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;"></span></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="2" style="color:#000000; padding: 5px 10px;">Signed Intended Father <span id="username2" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name2" value="" style="border-bottom: 1px dotted;border-top:0px;border-left:0px;border-right:0px;width: 250px;"></span></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="2" style="color:#000000; padding: 5px 10px;"><strong>The contents above have been read over, explained to and understood by me in vernacular also and I intend to be legally bound thereby.</strong></td></tr>
<tr><td colspan="2" style="color:#000000; padding: 5px 10px;"><strong>उपरोक्त को मेरे द्वारा स्थानीय भाषा में भी पढ़ा, समझाया और समझा गया है और मैं इसके लिए कानूनी रूप से बाध्य होने का इरादा रखता हूं।</strong></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="2"></td></tr>

<tr><td colspan="2"></td></tr>
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
			
            var printdata = document.getElementById(myfrm2);
            newwin = window.open("");
            newwin.document.write(printdata.outerHTML);
            newwin.print();
            newwin.close();
        }
    </script>
<style>
input[type=text], textarea {
  height: 24px;
} 
table, th, td {
    border: 0px;
}
input[type=checkbox], input[type=radio] {
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
.vb45rt td {text-align: left; padding-left: 10px;}
table th, table td {
    padding: 10px 10px;
    text-align: left;
}
</style>      