<div class="card a4-sheet">
    <div class="card-content">
        <div class="row" id="myfrm2">
            <div class="col-lg-12">
                <div class="letterhead">
                     <?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?>
                </div>
            </div>
            <div class="ga-pro">
                <h3 class="subtitle">AFFIDAVIT OF COMMISSIONING COUPLE</h3>
                <p class="meta-text">(In terms of provisions of Section 22(1)(b) of Assisted Reproductive Technology (Regulation) Act 2021 &amp; Rule 12(ii) of Assisted Reproductive Technology  (Regulation) Rules 2022)</p>
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    I, Mrs <span id="username" class="underline"><input type="text" name="name" id="name" value="" class="fill-in wide" /></span> wife of Mr <span id="username2" class="underline"><input type="text" name="husband_name" id="name2" value="" class="fill-in medium" /></span> aged about <span id="username3" class="underline"><input type="text" name="age_wife" id="name3" value="" class="fill-in short" /></span> years and Mr. <span id="username4" class="underline"><input type="text" name="husband_name2" id="name4" value="" class="fill-in medium" /></span> son of Mr <span id="username5" class="underline"><input type="text" name="son_of" id="name5" value="" class="fill-in medium" /></span> aged about <span id="username6" class="underline"><input type="text" name="age_husband" id="name6" value="" class="fill-in short" /></span> years having residence at <span id="username7" class="underline"><input type="text" name="address" id="name7" value="" class="fill-in wide" /></span> do hereby solemnly affirm and declare as hereunder.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    1. We say that we are taking treatment for Assisted Reproductive Technology for infertility at India IVF Clinic, <span id="username8" class="underline"><input type="text" name="address2" id="name8" value="" class="fill-in wide" /></span>.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    2. We say that we are married couple for the last <span id="username9" class="underline"><input type="text" name="marriage_years" id="name9" value="" class="fill-in short" /></span> years and our identity proof, proof of residence and medical documents are enclosed as Annexure- A, Annexure-B and Annexure-C respectively.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    3. We say that we did not have not get our marriage registered and thus we do not have made our marriage certificate and hence it is not available.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    4. We say that we have had full discussion with Dr. <span id="username10" class="underline"><input type="text" name="doctor_name_discussion" id="name10" value="Richika Sahay" class="fill-in medium" /></span> regarding the treatment on <span id="username11" class="underline"><input type="text" name="discussion_date" id="name11" value="<?php echo date('d-m-Y'); ?>" class="fill-in short" /></span>.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    5. We say that we have been counselled by Dr. <span id="username12" class="underline"><input type="text" name="doctor_name_counselling" id="name12" value="Richika Sahay" class="fill-in medium" /></span> on <span id="username13" class="underline"><input type="text" name="counselling_date" id="name13" value="<?php echo date('d-m-Y'); ?>" class="fill-in short" /></span>.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    6. We say that we would require a sperm donor for our treatment.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    7. We say that we have recruited an sperm donor for our treatment and he has given his consent for the same in terms of the Assisted Reproductive Technology (Regulation) Act 2021 &amp; the Assisted Reproductive Technology (Regulation) Rules 2022.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    8. We say that we have been informed that the child(ren) born out of ART process have all the rights of a naturally conceived child of us.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    9. We say that we have been informed that the sperm donor has already relinquished parental rights on our prospective child.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    10. That the contents stated in this affidavit are true and correct and nothing material particulars in our knowledge has been concealed therefrom. If any statement or facts stated by the deponent is found to be false and incorrect, we shall be responsible in accordance with law.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    11. We say that we have read over the contents of this affidavit and have understood the contents of same which are true and correct to our knowledge.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    12. We say that we are executing this affidavit out of our free will and consent without any coercion and influence from any quarters.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    DEPONENT NO. 1 &amp; DEPONENT NO. 2
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    VERIFICATION
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px 5px;">
                                    Verified at <span id="username14" class="underline"><input type="text" name="verified_place" id="name14" value="New Delhi" class="fill-in short" /></span> on this day <span id="username15" class="underline"><input type="text" name="verified_date" id="name15" value="<?php echo date('d-m-Y'); ?>" class="fill-in short" /></span> that the contents are true and correct to the best of my knowledge and nothing materials have been concealed therefrom.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="actions" style="text-align: center; height: 50px;">
	<input type="button" class="btn btn-primary btn-print" onclick="myPrint2('myfrm2')" value="Print" />
</div>
<script>
	function myPrint2(containerId) {
		var source = document.getElementById(containerId);
		if (!source) return;
		var clone = source.cloneNode(true);
		clone.querySelector('.letterhead').style.display = 'block'; // Ensure the logo is displayed
		var inputs = clone.querySelectorAll('input');
		inputs.forEach(function(input){
			var parent = input.parentElement;
			var textValue = input.value || '';
			if (parent) {
				parent.textContent = textValue;
				parent.classList.add('underline');
			}
		});
		var printWindow = window.open('', '_blank');
		printWindow.document.write('<html><head><title>FORM 1 - Affidavit</title>');
		printWindow.document.write('<style>body{font-family:Segoe UI, Arial, Helvetica, sans-serif;color:#222;}@page{size:A4;margin:14mm 14mm 16mm;} .letterhead{text-align:center;margin-bottom:8px} .letterhead img{height:70px} .form-title{text-align:center;font-size:20px;font-weight:700;margin:6px 0 0} .subtitle{text-align:center;font-size:15px;font-weight:600;margin:2px 0 8px} .meta-text{text-align:center;color:#555;margin:0 0 6px;font-size:12px} table{width:100%;border-collapse:separate} td{padding:6px 0;vertical-align:top} .underline{border-bottom:1px dotted #444;min-width:80px;display:inline-block;padding:0 2px} .a4-sheet{border:none;box-shadow:none;padding:0} @media print{.btn-print{display:none}}</style>');
		printWindow.document.write('</head><body>');
		printWindow.document.body.appendChild(clone);
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.focus();
		printWindow.print();
		printWindow.close();
	}
</script>
<style>
	.a4-sheet { max-width: 95%; margin: 0 auto; border: 1px solid #e5e5e5; }
	.card-content { padding: 24px 32px; }
	.letterhead { text-align: center; margin-bottom: 8px; }
	.letterhead img { height: 70px; display: block; margin: 0 auto; }
	.form-title { text-align: center; font-size: 20px; font-weight: 700; letter-spacing: 0.3px; margin: 6px 0 0; }
	.subtitle { text-align: center; font-size: 15px; font-weight: 600; margin: 2px 0 8px; }
	.meta-text { text-align: center; font-size: 12px; color: #555; margin: 0 0 6px; }
	.underline {  display: inline-block; min-width: 80px; padding: 0 2px; }
	.fill-in { border: none; outline: none; background: transparent; padding: 2px 4px; }
	.fill-in.wide { min-width: 260px; }
	.fill-in.medium { min-width: 140px; }
	.fill-in.short { min-width: 80px; }
	table th, table td { padding: 6px 0; text-align: left; border: none; }
	@media print {
		.a4-sheet { border: none; }
		.card-content { padding: 0; }
		.btn-print, .actions { display: none; }
	}
</style>

