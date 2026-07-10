<div class="card">
    <div class="card-content">
        <div class="row" id="myfrm2">
		<div class="col-lg-12"> <?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></div>
            <div class="ga-pro">
			
                <h2 style="text-align: center;">IN VITRO FERTILIZATION CENTER</h2>
                <h3 style="text-align: center; margin: 10px 0px;">INFORMATION AND CONSENT FORM</h3>
                <form action="" enctype="" method="post">
				<input type="hidden" name="action" value="add_testicular_stem_cell">
				<input type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>" name="on_date" id="on_date">
				<?php $uniqueNumber = date('YmdHis') . rand(100, 999); ?>
				<input type="hidden" name="receipt_number" value="<?php echo $uniqueNumber; ?>">

                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
									<strong><p>THE INTRATESTICULAR INJECTION OF  AUTOLOGOUS (YOUR OWN) MESENCHYMAL STEM CELLS FROM ILIAC CREST BONE MARROW FOR THE RECREATION OF TESTICULAR FUCTION BACK GROUND AND GENERAL INFORMATION</p></strong>
									<p>THE USE OF DONATED SPERMS OR ADOPTION ARE CURRENTLY THE ONLY VIABLE OPTIONS FOR PEOPLE WITH NIL SPERM IN TESA . THIS PROCESS IS EMOTIONALLY DIFFICULT, AND MAY NOT BE ACCEPTABLE TO MANY COUPLES ON EMOTIONAL OR RELIGIOUS GROUNDS. </p>
									<p>SOME  STUDIES SUGGEST THAT SOME TESTICULAR TISSUE  MAY ACTUALLY CONTAIN IMMATURE SPERM  CELLS OR STEM CELLS THAT CAN BE MORPHED INTO SPERMATOZOA, WHEN ACTIVATED BY AN APPROPRIATE BIOLOGICAL STIMULUS. STEM CELLS, WHICH ARE CAPABLE OF DEVELOPING INTO AN INFINITE VARIETY OF BODY TISSUES, ARE AT THE CORE OF HUMAN EMBRYONIC DEVELOPMENT. THEY
										ARE THE CELLS THAT ULTIMATELY DEVELOP INTO THE ORGAN SYSTEMS THAT MAKE UP THE HUMAN ORGANISM. STEM CELLS HAVE BEEN SHOWN TO BE PRESENT IN MANY DIFFERENT TYPES OF HUMAN TISSUES, INCLUDING THE OVARIES AND MAY GROW INTO HEALTHY ADULT CELLS UNDER CERTAIN
										BIOLOGICAL CONDITIONS. ADULT TISSUE STEM CELLS MAY POTENTIALLY SERVE AS THE WAY THAT THE BODY CAN REPAIR ITS OWN INJURED OR POORLY FUNCTIONING TISSUES. THE ACTIVATION OF STEM
										CELLS TO STIMULATE THE HEALING PEOCESS BY INJECTING COMPONENTS OF A PATIENT’S OWN BLOOD INTO AN INJURED AREA HAS BEEN SUCCESSFULLYAND SAFELY USED IN FOR OVER TEN YEARS TO SPEED
										THE HEALING OF THE BONE, SKIN SPORTS INJURIES. SINCE THE STEM CELL ACTIVATOR IS MADE FROM THE PATIENTS OWN BLOOD, IT IS EXTREMELY SAFE, DOES NOT INVOLVE SYNTHETIC DRUGS OR
										CHEMICALS, ELIMINATES IMMUNOLOGIC AND ALLERGIC REACTIONS, OR RISKS OF VIRAL INFECTIONS THAT ARE ASSOCIATED WITH BLOOD TRANSFUSIONS. COMPLICATIONS FROM THIS PROCEDURE ARE
										RARE IN THE WORLD’S MEDICAL LITERATURE, BUT THE COMPLICATIONS OF INFECTION AND BLEEDING MAY BE RESULT FROM MEDICAL PROCEDURE.</p>
									<strong><p>DESCRIPTION OF THE EXPERIMENTAL PROCEDURE FOR TESTICULAR REJUVINATION FOR THE POTENTIAL RESTORATION OF SPERMATOGENESIS AND FERTILITY </p></strong> 
                                    <p>WE WILL USE AUTOLOGOUS BONE MARROW  FOR THE PREPARATION OF THE STEM CELL STIMULATING AGENT WHICH WILL BE  INJECTED INTO THE PATIENT’S TESTES USING A  NEEDLE , WHICH REQUIRES GENERAL ANESTHESIA. </p>
									<p>THIS BASICALLY  IS THE SAME PROCEDURE THAT IS USED TO DO TESA PROCEDURE. COMPLICATIONS RESULTING FROM THIS PROCEDURE ARE BASICALLY SIMILAR TO THOSE EXPERIENCED AS RESULT OF TESA THEY ARE INTERNAL BLEEDING, INFECTION, PERFORATION OF INTERNAL ORGANS,TESTICULAR PAIN,  PAIN OR BLEEDING. ALTHOUGH SUCH COMPLICATIONS ARE RARE, HOSPITAL ADMISSION, REPARATIVE SURGERY, LOSS OF ONE OR BOTH TESTES, ANESTHETIC COMPLICATIONS AND DEATH ARE POSSIBILITIES.</p>
								<p>WE STATE CLEARLY THAT INTRATESTICULAR INJECTION OF PBMNC OR ANY OTHER TYPE OF PLATELET
									DERIVED GROWTH FACTORS OR BONE MARROW MADE FROM THE PATIENTS OWN BLOOD /MESENCHYMAL STEM CELLS IS PURELY EXPERIMENTAL. IN ACCEPTING TO DO THIS PROCEDURE, YOU FULLY REALIZE AND ACCEPT ITS EXPERIMENTAL NATURE
									AND UNDERSTAND THAT NO STATISTICS OR INFORMATION IS AVAILABLE OTHER THAT THE ONEPUBLISHED CASE REPORT IN THE MEDICAL LITERATURE, WHICH HAS NOT BEEN REVIEWED BY ANY
									MEDICAL OR SCIENTIFIC REVIEW BOARD INTERNATIONALLY. YOU FURTHER UNDERSTAND THAT THERE IS NO IMPLIED GUARANTEE THAT THIS PROCEDURE WILL HELP YOU BECOME PREGNANT. YOU
									FURTHER UNDERSTAND THAT SPERMATOGENESIS  RATES, PREGNANCY RATES, MISCARRIAGE RATES, LONG TERM COMPLICATION RATES, ANOMALY (‘BIRTH DEFECT”) RATES, PREMATURITY OR TERM PREGNANCY RATES OR THE INCIDENCE OF BEHAVIORAL OR DEVELOPMENTAL DEFFECTS IN OFFSPRING RSULTING FROM PREGNANCIES ARE NOT KNOWN AT THIS TIME. YOU THEREFORE AGREE TO HOLD DR.RICHIKA,AND ANY OTHER PHYSICIAN OR PERSONELL EMPLOYED BY INDIA IVF CLINIC HARMLESS SHOULD YOU SHOULD FAIL TO CONDEIVE OR IF ANY ADVERSE, HARMFUL OR UNDESIRANLE EFFECTS OR OUTCOMES SHOULD OCCUR. 
</p>
								</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   <p>I, <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name" value="<?php echo $data['name']; ?>" style="border: 0px; width: 350px;" /></span>HAVE BEEN SEEN
THOROUGHLY AND ADEQUATELY COUNSELED BY DR. RICHIKA , AND/OR ANOTHER PHYSICIAN OR MEMBER OR THE INDIA IVF CLINIC  STAFF ABOUT MY CURRENT TESTICULAR STATUS AND I
UNDERSTAND THAT I HAVE LITTLE CHANCE OF CONCEIVING NATURALLY. I HAVE BEEN GIVEN ALTERNATIVE CHOICES, INCLUDING THE USE OF DONOR SPERM, ADOPTION AND REMAINING CHILDLESS.</p>
 <p>THE PROCEDURE OF THE INTRATESTICULAR INJECTION OF PERIPHERAL MESENCHYMAL STEM CELLS  FOR IMPROVEMENT OF TESTICULAR  RESERVE HAS BEEN FULLY EXPLAINED TO ME AND ALL MY QUESTIONS ABOUT IT HAVE BEEN FULLY ANSWERED. I FULLY UNDERSTAND THE RISKS INVOLVED IN THIS PROCEDURE AND ALL INFORMATION CONTAINED IN THIS CONSENT FORM. I HAVE BEEN CLEARLY INFORMED OF THE EXPERIMENTAL NATURE OF THIS PROCEDURE, BUT WISH TO ASSUME ALL RISKS OF SUCH PROCEDURE SINCE I ONLY WISH TO CONCEIVE USING MY OWN SPERMS THEREFORE I AM WILLING TO PARTICIPATE IN THIS EXPERIMENTAL PROCEDURE AT MT OWN RISK AND EXPENSE WITH NO GUARANTEES OF SUCCESS OR THE NATURE OF THE OUTCOME AS DETAILED IN THIS CONSENT.
I FURTHER REALIZE THAT ADDITIONAL FERTILITY TEST AND TREATMENTS SUCH AS BLOOD TESTS,
ULTRASOUNDS, TESTICULAR SURGERY, FERTILITY MEDICATIONS WITH OR WITHOUT  IN VITRO FERTILIZATION, AS WELL AS ADDITIONAL OFFICE VISITS AND COUNSELING MAY
BE NEEDED TO ACHIEVE PREGNANCY AFTER THIS PROCEDURE HAS BEEN PERFORMED AGAIN I ACKNOWLEGE
AND UNDERSTAND THAT THERE IS NO ACTUAL OR IMPLIED REPRESENTATION MADE THAT THIS PROCEDURE
WILL RESULT IN A PREGNANCY OR THE LIVE BIRTH OF A HEALTHY CHILD.
</p> 
                                  <p>I <span id="username8" style="border-bottom: 1px dotted;"><input type="text" name="username" id="username" value="<?php echo $data['username']; ?>" style="border: 0px; width: 350px;" /></span>AGREE TO ALL TERMS AND
UNDERSTAND ALL INFORMATION IN THIS CONSENT FOR INTRATESTICULAR  PBMNC INJECTION AND AGREE TO HOLD DR. RICHIKA, AND ANY AND ALL OTHER PHYSICIANS OR OTHER PERSONNEL OF INDIA IVF CLINIC 
SERVICES HARMLESS FOR ANY MANNER OF IMMEDIATE OR DELAYED ADVERSE OUTCOME TO ME OR TO THE
RESULTING CHILD.
</p> 
                               
								</td>
                            </tr>
							<tr>
								<td colspan="1" style="padding: 9px 10px;"> PATIENT <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="pname" id="pname" value="<?php echo $data['pname']; ?>" style="border: 0px; width: 200px;" /></span></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <span id="username12" style="border-bottom: 1px dotted;"><input type="date" name="date" id="date" value="<?php echo $data['date']; ?>" style="border: 0px; width: 200px;" /></span></td>
							</tr>
							<tr>
								<td colspan="1" style="padding: 9px 10px;"> WITNESS <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="witness" id="witness" value="<?php echo $data['witness']; ?>" style="border: 0px; width: 200px;" /></span></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <span id="username12" style="border-bottom: 1px dotted;"><input type="date" name="date2" id="date2" value="<?php echo $data['date2']; ?>" style="border: 0px; width: 200px;" /></span></td>
							</tr>
							<tr>
								<td colspan="1" style="padding: 9px 10px;"> PHYSICIAN <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="doctor" id="doctor" value="<?php echo $data['doctor']; ?>" style="border: 0px; width: 200px;" /></span></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <span id="username12" style="border-bottom: 1px dotted;"><input type="date" name="date3" id="date3" value="<?php echo $data['date3']; ?>" style="border: 0px; width: 200px;" /></span></td>
							</tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">HAVE READ THE ABOVE INFORMATION AND CONSENT: INTRATESTICULAR INJECTION OF PERIPHERAL MESENCHYMAL STEM CELLS  FOR THE INTENTION OF IMPROVING TESTICULAR(SPERM) RESERVE, HAVE HAD ALL QUESTIONS ANSWERED, AND FULLY UNDERSTAND THE ENTIRE CONTENT OF THE CONSENT AND AGREE WITH ALL POINTS RAISED.</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">I ALSO HAVE BEEN COMPLETELY AND ADEQUATELY COUNSELED BY DR. RICHIKA , AND OR ANOTHER PHYSICIAN OR MEMBER OF THE INDIA IVF STAFF.</td>
                            </tr>
							 <tr>
								<td colspan="1" style="padding: 9px 10px;"> PATIENT ID <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="patient_id" id="patient_id" value="<?php echo $data['patient_id']; ?>" style="border: 0px; width: 200px;" /></span></td>
							</tr>
                           <tr>
								<td colspan="1" style="padding: 9px 10px;"> PATIENT <span id="username11" style="border-bottom: 1px dotted;"><input type="text" name="patient_name" id="patient_name" value="<?php echo $data['patient_name']; ?>" style="border: 0px; width: 200px;" /></span></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <span id="username12" style="border-bottom: 1px dotted;"><input type="date" name="date4" id="date4" value="<?php echo $data['date4']; ?>" style="border: 0px; width: 200px;" /></span></td>
							</tr>
                        </tbody>
                    </table>
					<div>
						<input type="submit" id="submit" name="" class="btn btn-primary" />
						<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="panel-body profile-edit" id="print_this_section" style="display:none;">
<div class="card">
    <div class="card-content">
        <div class="row" id="myfrm2">
		<div class="col-lg-12"><img src="https://indiaivf.website//assets/images/India-IVF-Logo-Option-5.png" class="center" style="width:250px;margin:0px 300px;"></div>
            <div class="ga-pro">
				<h3 style="text-align: center; margin: 10px 0px;">IN VITRO FERTILIZATION CENTER</h3>
                <h2 style="text-align: center;">INFORMATION AND CONSENT FORM</h2>
               
                <form action="" enctype="multipart/form-data" method="post">
                    <table width="100%" class="">
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
									<strong><p>THE INTRATESTICULAR INJECTION OF  AUTOLOGOUS (YOUR OWN) MESENCHYMAL STEM CELLS FROM ILIAC CREST BONE MARROW FOR THE RECREATION OF TESTICULAR FUCTION BACK <br/>GROUND AND GENERAL INFORMATION</p></strong>
									<p>THE USE OF DONATED SPERMS OR ADOPTION ARE CURRENTLY THE ONLY VIABLE OPTIONS FOR PEOPLE WITH NIL SPERM IN TESA . THIS PROCESS IS EMOTIONALLY DIFFICULT, AND MAY NOT BE ACCEPTABLE TO MANY COUPLES ON EMOTIONAL OR RELIGIOUS GROUNDS. </p>
									<p>SOME  STUDIES SUGGEST THAT SOME TESTICULAR TISSUE  MAY ACTUALLY CONTAIN IMMATURE SPERM  CELLS OR STEM CELLS THAT CAN BE MORPHED INTO SPERMATOZOA, WHEN ACTIVATED BY AN APPROPRIATE BIOLOGICAL STIMULUS. STEM CELLS, WHICH ARE CAPABLE OF DEVELOPING INTO AN INFINITE VARIETY OF BODY TISSUES, ARE AT THE CORE OF HUMAN EMBRYONIC DEVELOPMENT. THEY
										ARE THE CELLS THAT ULTIMATELY DEVELOP INTO THE ORGAN SYSTEMS THAT MAKE UP THE HUMAN ORGANISM. STEM CELLS HAVE BEEN SHOWN TO BE PRESENT IN MANY DIFFERENT TYPES OF HUMAN TISSUES, INCLUDING THE OVARIES AND MAY GROW INTO HEALTHY ADULT CELLS UNDER CERTAIN
										BIOLOGICAL CONDITIONS. ADULT TISSUE STEM CELLS MAY POTENTIALLY SERVE AS THE WAY THAT THE BODY CAN REPAIR ITS OWN INJURED OR POORLY FUNCTIONING TISSUES. THE ACTIVATION OF STEM
										CELLS TO STIMULATE THE HEALING PEOCESS BY INJECTING COMPONENTS OF A PATIENT’S OWN BLOOD INTO AN INJURED AREA HAS BEEN SUCCESSFULLYAND SAFELY USED IN FOR OVER TEN YEARS TO SPEED
										THE HEALING OF THE BONE, SKIN SPORTS INJURIES. SINCE THE STEM CELL ACTIVATOR IS MADE FROM THE PATIENTS OWN BLOOD, IT IS EXTREMELY SAFE, DOES NOT INVOLVE SYNTHETIC DRUGS OR
										CHEMICALS, ELIMINATES IMMUNOLOGIC AND ALLERGIC REACTIONS, OR RISKS OF VIRAL INFECTIONS THAT ARE ASSOCIATED WITH BLOOD TRANSFUSIONS. COMPLICATIONS FROM THIS PROCEDURE ARE
										RARE IN THE WORLD’S MEDICAL LITERATURE, BUT THE COMPLICATIONS OF INFECTION AND BLEEDING MAY BE RESULT FROM MEDICAL PROCEDURE.</p>
									<strong><p>DESCRIPTION OF THE EXPERIMENTAL PROCEDURE FOR TESTICULAR REJUVINATION FOR THE POTENTIAL RESTORATION OF SPERMATOGENESIS AND FERTILITY </p></strong> 
                                    <p>WE WILL USE AUTOLOGOUS BONE MARROW  FOR THE PREPARATION OF THE STEM CELL STIMULATING AGENT WHICH WILL BE  INJECTED INTO THE PATIENT’S TESTES USING A  NEEDLE , WHICH REQUIRES GENERAL ANESTHESIA. </p>
									<p>THIS BASICALLY  IS THE SAME PROCEDURE THAT IS USED TO DO TESA PROCEDURE. COMPLICATIONS RESULTING FROM THIS PROCEDURE ARE BASICALLY SIMILAR TO THOSE EXPERIENCED AS RESULT OF TESA THEY ARE INTERNAL BLEEDING, INFECTION, PERFORATION OF INTERNAL ORGANS,TESTICULAR PAIN,  PAIN OR BLEEDING. ALTHOUGH SUCH COMPLICATIONS ARE RARE, HOSPITAL ADMISSION, REPARATIVE SURGERY, LOSS OF ONE OR BOTH TESTES, ANESTHETIC COMPLICATIONS AND DEATH ARE POSSIBILITIES.</p>
								<p>WE STATE CLEARLY THAT INTRATESTICULAR INJECTION OF PBMNC OR ANY OTHER TYPE OF PLATELET
									DERIVED GROWTH FACTORS OR BONE MARROW MADE FROM THE PATIENTS OWN BLOOD /MESENCHYMAL STEM CELLS IS PURELY EXPERIMENTAL. IN ACCEPTING TO DO THIS PROCEDURE, YOU FULLY REALIZE AND ACCEPT ITS EXPERIMENTAL NATURE
									AND UNDERSTAND THAT NO STATISTICS OR INFORMATION IS AVAILABLE OTHER THAT THE ONEPUBLISHED CASE REPORT IN THE MEDICAL LITERATURE, WHICH HAS NOT BEEN REVIEWED BY ANY
									MEDICAL OR SCIENTIFIC REVIEW BOARD INTERNATIONALLY. YOU FURTHER UNDERSTAND THAT THERE IS NO IMPLIED GUARANTEE THAT THIS PROCEDURE WILL HELP YOU BECOME PREGNANT. YOU
									FURTHER UNDERSTAND THAT SPERMATOGENESIS  RATES, PREGNANCY RATES, MISCARRIAGE RATES, LONG TERM COMPLICATION RATES, ANOMALY (‘BIRTH DEFECT”) RATES, PREMATURITY OR TERM PREGNANCY RATES OR THE INCIDENCE OF BEHAVIORAL OR DEVELOPMENTAL DEFFECTS IN OFFSPRING RSULTING FROM PREGNANCIES ARE NOT KNOWN AT THIS TIME. YOU THEREFORE AGREE TO HOLD DR.RICHIKA,AND ANY OTHER PHYSICIAN OR PERSONELL EMPLOYED BY INDIA IVF CLINIC HARMLESS SHOULD YOU SHOULD FAIL TO CONDEIVE OR IF ANY ADVERSE, HARMFUL OR UNDESIRANLE EFFECTS OR OUTCOMES SHOULD OCCUR. 
</p>
								</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">
                                   <p>I, <span id="username6" style="border-bottom: 1px dotted;"><input type="text" name="name" id="name6" value="<?php echo $data['name']; ?>" style="border: 0px; width: 350px;" /></span>HAVE BEEN SEEN
THOROUGHLY AND ADEQUATELY COUNSELED BY DR. RICHIKA , AND/OR ANOTHER PHYSICIAN OR MEMBER OR THE INDIA IVF CLINIC  STAFF ABOUT MY CURRENT TESTICULAR STATUS AND I
UNDERSTAND THAT I HAVE LITTLE CHANCE OF CONCEIVING NATURALLY. I HAVE BEEN GIVEN ALTERNATIVE CHOICES, INCLUDING THE USE OF DONOR SPERM, ADOPTION AND REMAINING CHILDLESS.</p>
 <p>THE PROCEDURE OF THE INTRATESTICULAR INJECTION OF PERIPHERAL MESENCHYMAL STEM CELLS  FOR IMPROVEMENT OF TESTICULAR  RESERVE HAS BEEN FULLY EXPLAINED TO ME AND ALL MY QUESTIONS ABOUT IT HAVE BEEN FULLY ANSWERED. I FULLY UNDERSTAND THE RISKS INVOLVED IN THIS PROCEDURE AND ALL INFORMATION CONTAINED IN THIS CONSENT FORM. I HAVE BEEN CLEARLY INFORMED OF THE EXPERIMENTAL NATURE OF THIS PROCEDURE, BUT WISH TO ASSUME ALL RISKS OF SUCH PROCEDURE SINCE I ONLY WISH TO CONCEIVE USING MY OWN SPERMS THEREFORE I AM WILLING TO PARTICIPATE IN THIS EXPERIMENTAL PROCEDURE AT MT OWN RISK AND EXPENSE WITH NO GUARANTEES OF SUCCESS OR THE NATURE OF THE OUTCOME AS DETAILED IN THIS CONSENT.
I FURTHER REALIZE THAT ADDITIONAL FERTILITY TEST AND TREATMENTS SUCH AS BLOOD TESTS,
ULTRASOUNDS, TESTICULAR SURGERY, FERTILITY MEDICATIONS WITH OR WITHOUT  IN VITRO FERTILIZATION, AS WELL AS ADDITIONAL OFFICE VISITS AND COUNSELING MAY
BE NEEDED TO ACHIEVE PREGNANCY AFTER THIS PROCEDURE HAS BEEN PERFORMED AGAIN I ACKNOWLEGE
AND UNDERSTAND THAT THERE IS NO ACTUAL OR IMPLIED REPRESENTATION MADE THAT THIS PROCEDURE
WILL RESULT IN A PREGNANCY OR THE LIVE BIRTH OF A HEALTHY CHILD.
</p> 
                                  <p>I <strong><input type="text" name="name" id="name8" value="<?php echo $data['username']; ?>" style="border: 0px; width: 350px;" /><strong>AGREE TO ALL TERMS AND
UNDERSTAND ALL INFORMATION IN THIS CONSENT FOR INTRATESTICULAR  PBMNC INJECTION AND AGREE TO HOLD DR. RICHIKA, AND ANY AND ALL OTHER PHYSICIANS OR OTHER PERSONNEL OF INDIA IVF CLINIC 
SERVICES HARMLESS FOR ANY MANNER OF IMMEDIATE OR DELAYED ADVERSE OUTCOME TO ME OR TO THE
RESULTING CHILD.
</p> 
                               
								</td>
                            </tr>
							<tr>
								<td colspan="1" style="padding: 9px 10px;"> PATIENT <strong><?php echo $data['pname']; ?></strong></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <strong><?php echo $data['date']; ?></strong></td>
							</tr>
							<tr>
								<td colspan="1" style="padding: 9px 10px;"> WITNESS <strong><?php echo $data['witness']; ?></strong></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <strong><?php echo $data['date2']; ?></strong></td>
							</tr>
							<tr>
								<td colspan="1" style="padding: 9px 10px;"> PHYSICIAN <strong><?php echo $data['doctor']; ?></strong></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <strong><?php echo $data['date3']; ?></strong></td>
							</tr>
							<tr>
                                <td colspan="2" style="padding: 10px 10px;">HAVE READ THE ABOVE INFORMATION AND CONSENT: INTRATESTICULAR INJECTION OF PERIPHERAL MESENCHYMAL STEM CELLS  FOR THE INTENTION OF IMPROVING TESTICULAR(SPERM) RESERVE, HAVE HAD ALL QUESTIONS ANSWERED, AND FULLY UNDERSTAND THE ENTIRE CONTENT OF THE CONSENT AND AGREE WITH ALL POINTS RAISED.</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 10px 10px;">I ALSO HAVE BEEN COMPLETELY AND ADEQUATELY COUNSELED BY DR. RICHIKA , AND OR ANOTHER PHYSICIAN OR MEMBER OF THE INDIA IVF STAFF.</td>
                            </tr>
                           <tr>
								<td colspan="1" style="padding: 9px 10px;"> PATIENT <strong><?php echo $data['patient_name']; ?></strong></td>
								<td colspan="1" style="padding: 9px 10px;">	DATE <strong><?php echo $data['date4']; ?></strong></td>
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
</div>
<script>
 function printDiv() 
{
  $('.hide_print').hide();
  $('#print_this_section').css('display', 'block');
  $('tr#medinfologo_tr').css('display', 'table-row');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
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
	.col-lg-8 img{text-align:center;}
</style>
