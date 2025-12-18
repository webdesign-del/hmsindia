<?php
if (isset($_POST['submit'])) {
    extract($_POST);
	$ID = $_GET['ID'];
	$sql2 = "UPDATE `hms_clinical_reports` SET `consultations`='$consultations',`tele_consult`='$tele_consult',`opu`='$opu',`fresh_cycle_et`='$fresh_cycle_et',`thawed_cycle_fet`='$thawed_cycle_fet',`iui`='$iui',`ivf`='$ivf',`tesa_mtese`='$tesa_mtese',`donor_cycle`='$donor_cycle',`surrogacy`='$surrogacy',`uterine_prp`='$uterine_prp',`ovarian_prp`='$ovarian_prp',`testicular_prp`='$testicular_prp',`hysteroscopy_diagnostic`='$hysteroscopy_diagnostic',`laparoscopy_hysteroscopy`='$laparoscopy_hysteroscopy',`beta_hcg_positive`='$beta_hcg_positive',`cardic_activity`='$cardic_activity',`live_birth`='$live_birth',`male`='$male',`female`='$female',`ongoing`='$ongoing',`ivm`='$ivm',`pregnant_couples`='$pregnant_couples',`egg_freezing`='$egg_freezing',`semen_freezing`='$semen_freezing',`embryo_freezing`='$embryo_freezing',
	    `consultationsfeb`='$consultationsfeb',`tele_consultfeb`='$tele_consultfeb',`opufeb`='$opufeb',`fresh_cycle_etfeb`='$fresh_cycle_etfeb',`thawed_cycle_fetfeb`='$thawed_cycle_fetfeb',`iuifeb`='$iuifeb',`ivffeb`='$ivffeb',`tesa_mtesefeb`='$tesa_mtesefeb',`donor_cyclefeb`='$donor_cyclefeb',`surrogacyfeb`='$surrogacyfeb',`uterine_prpfeb`='$uterine_prpfeb',`ovarian_prpfeb`='$ovarian_prpfeb',`testicular_prpfeb`='$testicular_prpfeb',`hysteroscopy_diagnosticfeb`='$hysteroscopy_diagnosticfeb',`laparoscopy_hysteroscopyfeb`='$laparoscopy_hysteroscopyfeb',`beta_hcg_positivefeb`='$beta_hcg_positivefeb',`cardic_activityfeb`='$cardic_activityfeb',`live_birthfeb`='$live_birthfeb',`malefeb`='$malefeb',`femalefeb`='$femalefeb',`ongoingfeb`='$ongoingfeb',`ivmfeb`='$ivmfeb',`pregnant_couplesfeb`='$pregnant_couplesfeb',
		`consultationsmar`='$consultationsmar',`tele_consultmar`='$tele_consultmar',`opumar`='$opumar',`fresh_cycle_etmar`='$fresh_cycle_etmar',`thawed_cycle_fetmar`='$thawed_cycle_fetmar',`iuimar`='$iuimar',`ivfmar`='$ivfmar',`tesa_mtesemar`='$tesa_mtesemar',`donor_cyclemar`='$donor_cyclemar',`surrogacymar`='$surrogacymar',`uterine_prpmar`='$uterine_prpmar',`ovarian_prpmar`='$ovarian_prpmar',`testicular_prpmar`='$testicular_prpmar',`hysteroscopy_diagnosticmar`='$hysteroscopy_diagnosticmar',`laparoscopy_hysteroscopymar`='$laparoscopy_hysteroscopymar',`beta_hcg_positivemar`='$beta_hcg_positivemar',`cardic_activitymar`='$cardic_activitymar',`live_birthmar`='$live_birthmar',`malemar`='$malemar',`femalemar`='$femalemar',`ongoingmar`='$ongoingmar',`ivmmar`='$ivmmar',`pregnant_couplesmar`='$pregnant_couplesmar',
		`consultationsapr`='$consultationsapr',`tele_consultapr`='$tele_consultapr',`opuapr`='$opuapr',`fresh_cycle_etapr`='$fresh_cycle_etapr',`thawed_cycle_fetapr`='$thawed_cycle_fetapr',`iuiapr`='$iuiapr',`ivfapr`='$ivfapr',`tesa_mteseapr`='$tesa_mteseapr',`donor_cycleapr`='$donor_cycleapr',`surrogacyapr`='$surrogacyapr',`uterine_prpapr`='$uterine_prpapr',`ovarian_prpapr`='$ovarian_prpapr',`testicular_prpapr`='$testicular_prpapr',`hysteroscopy_diagnosticapr`='$hysteroscopy_diagnosticapr',`laparoscopy_hysteroscopyapr`='$laparoscopy_hysteroscopyapr',`beta_hcg_positiveapr`='$beta_hcg_positiveapr',`cardic_activityapr`='$cardic_activityapr',`live_birthapr`='$live_birthapr',`maleapr`='$maleapr',`femaleapr`='$femaleapr',`ongoingapr`='$ongoingapr',`ivmapr`='$ivmapr',`pregnant_couplesapr`='$pregnant_couplesapr',
		`consultationsmay`='$consultationsmay',`tele_consultmay`='$tele_consultmay',`opumay`='$opumay',`fresh_cycle_etmay`='$fresh_cycle_etmay',`thawed_cycle_fetmay`='$thawed_cycle_fetmay',`iuimay`='$iuimay',`ivfmay`='$ivfmay',`tesa_mtesemay`='$tesa_mtesemay',`donor_cyclemay`='$donor_cyclemay',`surrogacymay`='$surrogacymay',`uterine_prpmay`='$uterine_prpmay',`ovarian_prpmay`='$ovarian_prpmay',`testicular_prpmay`='$testicular_prpmay',`hysteroscopy_diagnosticmay`='$hysteroscopy_diagnosticmay',`laparoscopy_hysteroscopymay`='$laparoscopy_hysteroscopymay',`beta_hcg_positivemay`='$beta_hcg_positivemay',`cardic_activitymay`='$cardic_activitymay',`live_birthmay`='$live_birthmay',`malemay`='$malemay',`femalemay`='$femalemay',`ongoingmay`='$ongoingmay',`ivmmay`='$ivmmay',`pregnant_couplesmay`='$pregnant_couplesmay',
		`consultationsjun`='$consultationsjun',`tele_consultjun`='$tele_consultjun',`opujun`='$opujun',`fresh_cycle_etjun`='$fresh_cycle_etjun',`thawed_cycle_fetjun`='$thawed_cycle_fetjun',`iuijun`='$iuijun',`ivfjun`='$ivfjun',`tesa_mtesejun`='$tesa_mtesejun',`donor_cyclejun`='$donor_cyclejun',`surrogacyjun`='$surrogacyjun',`uterine_prpjun`='$uterine_prpjun',`ovarian_prpjun`='$ovarian_prpjun',`testicular_prpjun`='$testicular_prpjun',`hysteroscopy_diagnosticjun`='$hysteroscopy_diagnosticjun',`laparoscopy_hysteroscopyjun`='$laparoscopy_hysteroscopyjun',`beta_hcg_positivejun`='$beta_hcg_positivejun',`cardic_activityjun`='$cardic_activityjun',`live_birthjun`='$live_birthjun',`malejun`='$malejun',`femalejun`='$femalejun',`ongoingjun`='$ongoingjun',`ivmjun`='$ivmjun',`pregnant_couplesjun`='$pregnant_couplesjun',
		`consultationsjul`='$consultationsjul',`tele_consultjul`='$tele_consultjul',`opujul`='$opujul',`fresh_cycle_etjul`='$fresh_cycle_etjul',`thawed_cycle_fetjul`='$thawed_cycle_fetjul',`iuijul`='$iuijul',`ivfjul`='$ivfjul',`tesa_mtesejul`='$tesa_mtesejul',`donor_cyclejul`='$donor_cyclejul',`surrogacyjul`='$surrogacyjul',`uterine_prpjul`='$uterine_prpjul',`ovarian_prpjul`='$ovarian_prpjul',`testicular_prpjul`='$testicular_prpjul',`hysteroscopy_diagnosticjul`='$hysteroscopy_diagnosticjul',`laparoscopy_hysteroscopyjul`='$laparoscopy_hysteroscopyjul',`beta_hcg_positivejul`='$beta_hcg_positivejul',`cardic_activityjul`='$cardic_activityjul',`live_birthjul`='$live_birthjul',`malejul`='$malejul',`femalejul`='$femalejul',`ongoingjul`='$ongoingjul',`ivmjul`='$ivmjul',`pregnant_couplesjul`='$pregnant_couplesjul',
		`consultationsaug`='$consultationsaug',`tele_consultaug`='$tele_consultaug',`opuaug`='$opuaug',`fresh_cycle_etaug`='$fresh_cycle_etaug',`thawed_cycle_fetaug`='$thawed_cycle_fetaug',`iuiaug`='$iuiaug',`ivfaug`='$ivfaug',`tesa_mteseaug`='$tesa_mteseaug',`donor_cycleaug`='$donor_cycleaug',`surrogacyaug`='$surrogacyaug',`uterine_prpaug`='$uterine_prpaug',`ovarian_prpaug`='$ovarian_prpaug',`testicular_prpaug`='$testicular_prpaug',`hysteroscopy_diagnosticaug`='$hysteroscopy_diagnosticaug',`laparoscopy_hysteroscopyaug`='$laparoscopy_hysteroscopyaug',`beta_hcg_positiveaug`='$beta_hcg_positiveaug',`cardic_activityaug`='$cardic_activityaug',`live_birthaug`='$live_birthaug',`maleaug`='$maleaug',`femaleaug`='$femaleaug',`ongoingaug`='$ongoingaug',`ivmaug`='$ivmaug',`pregnant_couplesaug`='$pregnant_couplesaug',
		`consultationssep`='$consultationssep',`tele_consultsep`='$tele_consultsep',`opusep`='$opusep',`fresh_cycle_etsep`='$fresh_cycle_etsep',`thawed_cycle_fetsep`='$thawed_cycle_fetsep',`iuisep`='$iuisep',`ivfsep`='$ivfsep',`tesa_mtesesep`='$tesa_mtesesep',`donor_cyclesep`='$donor_cyclesep',`surrogacysep`='$surrogacysep',`uterine_prpsep`='$uterine_prpsep',`ovarian_prpsep`='$ovarian_prpsep',`testicular_prpsep`='$testicular_prpsep',`hysteroscopy_diagnosticsep`='$hysteroscopy_diagnosticsep',`laparoscopy_hysteroscopysep`='$laparoscopy_hysteroscopysep',`beta_hcg_positivesep`='$beta_hcg_positivesep',`cardic_activitysep`='$cardic_activitysep',`live_birthsep`='$live_birthsep',`malesep`='$malesep',`femalesep`='$femalesep',`ongoingsep`='$ongoingsep',`ivmsep`='$ivmsep',`pregnant_couplessep`='$pregnant_couplessep',
		`consultationsoct`='$consultationsoct',`tele_consultoct`='$tele_consultoct',`opuoct`='$opuoct',`fresh_cycle_etoct`='$fresh_cycle_etoct',`thawed_cycle_fetoct`='$thawed_cycle_fetoct',`iuioct`='$iuioct',`ivfoct`='$ivfoct',`tesa_mteseoct`='$tesa_mteseoct',`donor_cycleoct`='$donor_cycleoct',`surrogacyoct`='$surrogacyoct',`uterine_prpoct`='$uterine_prpoct',`ovarian_prpoct`='$ovarian_prpoct',`testicular_prpoct`='$testicular_prpoct',`hysteroscopy_diagnosticoct`='$hysteroscopy_diagnosticoct',`laparoscopy_hysteroscopyoct`='$laparoscopy_hysteroscopyoct',`beta_hcg_positiveoct`='$beta_hcg_positiveoct',`cardic_activityoct`='$cardic_activityoct',`live_birthoct`='$live_birthoct',`maleoct`='$maleoct',`femaleoct`='$femaleoct',`ongoingoct`='$ongoingoct',`ivmoct`='$ivmoct',`pregnant_couplesoct`='$pregnant_couplesoct',
		`consultationsnov`='$consultationsnov',`tele_consultnov`='$tele_consultnov',`opunov`='$opunov',`fresh_cycle_etnov`='$fresh_cycle_etnov',`thawed_cycle_fetnov`='$thawed_cycle_fetnov',`iuinov`='$iuinov',`ivfnov`='$ivfnov',`tesa_mtesenov`='$tesa_mtesenov',`donor_cyclenov`='$donor_cyclenov',`surrogacynov`='$surrogacynov',`uterine_prpnov`='$uterine_prpnov',`ovarian_prpnov`='$ovarian_prpnov',`testicular_prpnov`='$testicular_prpnov',`hysteroscopy_diagnosticnov`='$hysteroscopy_diagnosticnov',`laparoscopy_hysteroscopynov`='$laparoscopy_hysteroscopynov',`beta_hcg_positivenov`='$beta_hcg_positivenov',`cardic_activitynov`='$cardic_activitynov',`live_birthnov`='$live_birthnov',`malenov`='$malenov',`femalenov`='$femalenov',`ongoingnov`='$ongoingnov',`ivmnov`='$ivmnov',`pregnant_couplesnov`='$pregnant_couplesnov',
		`consultationsdec`='$consultationsdec',`tele_consultdec`='$tele_consultdec',`opudec`='$opudec',`fresh_cycle_etdec`='$fresh_cycle_etdec',`thawed_cycle_fetdec`='$thawed_cycle_fetdec',`iuidec`='$iuidec',`ivfdec`='$ivfdec',`tesa_mtesedec`='$tesa_mtesedec',`donor_cycledec`='$donor_cycledec',`surrogacydec`='$surrogacydec',`uterine_prpdec`='$uterine_prpdec',`ovarian_prpdec`='$ovarian_prpdec',`testicular_prpdec`='$testicular_prpdec',`hysteroscopy_diagnosticdec`='$hysteroscopy_diagnosticdec',`laparoscopy_hysteroscopydec`='$laparoscopy_hysteroscopydec',`beta_hcg_positivedec`='$beta_hcg_positivedec',`cardic_activitydec`='$cardic_activitydec',`live_birthdec`='$live_birthdec',`maledec`='$maledec',`femaledec`='$femaledec',`ongoingdec`='$ongoingdec',`ivmdec`='$ivmdec',`pregnant_couplesdec`='$pregnant_couplesdec' WHERE ID = '$ID'  ";
    $query2 = $this->db->query($sql2);
	$num = (int) $query2;
    if ($num > 0) {
        $_SESSION['MSG'] = "Your profile has been successfully updated.!!";
    } else {
        $_SESSION['MSG'] = "Your profile has not been updated.!!";
    }
}
    $ID = $_GET['ID'];
    $sql1 = "SELECT * FROM hms_clinical_reports WHERE ID='$ID'";
	$query = $this->db->query($sql1);
    $select_result1 = $query->result(); 
        foreach ($select_result1 as $res_val){ 
			$total_consultations = 0;
			$total_tele_consult = 0;
			$total_opu = 0;
			$total_fresh_cycle_et = 0;
			$total_thawed_cycle_fet = 0;
			$total_iui = 0;
			$total_ivf = 0;
			$total_icsi = 0;
			$total_tesa_mtese = 0;
			$total_donor_cycle = 0;
			$total_surrogacy = 0;
			$total_uterine_prp = 0;
			$total_ovarian_prp = 0;
			$total_testicular_prp = 0;
			$total_macs_qualis_candor = 0;
			$total_lah = 0;
			$total_pgd = 0;
			$total_embryo_glue = 0;
			$total_sperm_mobil = 0;
			$total_blastocyst_transfer = 0;
			$total_hysteroscopy_diagnostic = 0;
			$total_laparoscopy_hysteroscopy = 0;
			$total_beta_hcg_positive = 0;
			$total_cardic_activity = 0;
			$total_live_birth = 0;
			$total_male = 0;
			$total_female = 0;
			$total_ongoing = 0;
			$total_egg_freezing = 0;
			$total_semen_freezing = 0;
			$total_embryo_freezing = 0;
			$total_et = 0; $total_etfeb = 0; $total_etmar = 0;$total_etapr = 0; $total_etmay = 0; $total_etjun = 0; $total_etjul = 0; $total_etaug = 0; $total_etsep = 0; $total_etoct = 0; $total_etnov = 0; $total_etdec = 0;
			
			    $total_et = $res_val->fresh_cycle_et + $res_val->thawed_cycle_fet;
				$total_etfeb = $res_val->fresh_cycle_etfeb + $res_val->thawed_cycle_fetfeb;
				$total_etmar = $res_val->fresh_cycle_etmar + $res_val->thawed_cycle_fetmar;
                $total_etapr = $res_val->fresh_cycle_etapr + $res_val->thawed_cycle_fetapr;
				$total_etmay = $res_val->fresh_cycle_etmay + $res_val->thawed_cycle_fetmay; 
				$total_etjun = $res_val->fresh_cycle_etjun + $res_val->thawed_cycle_fetjun;
             $fresh_et = isset($res_val->fresh_cycle_etjul) ? $res_val->fresh_cycle_etjul : 0;
$thawed_jul = isset($res_val->thawed_cycle_jul) ? $res_val->thawed_cycle_jul : 0;

$total_etjul = $fresh_et + $thawed_jul;	$total_etaug = $res_val->fresh_cycle_etaug + $res_val->thawed_cycle_fetaug;
				$total_etsep = $res_val->fresh_cycle_etsep + $res_val->thawed_cycle_fetsep;
                $total_etoct = $res_val->fresh_cycle_etoct + $res_val->thawed_cycle_fetoct; 
				$total_etnov = $res_val->fresh_cycle_etnov + $res_val->thawed_cycle_fetnov;
				$total_etdec = $res_val->fresh_cycle_etdec + $res_val->thawed_cycle_fetdec;
				
				$total_et = 0; $total_prpfeb = 0; $total_prpmar = 0;$total_prpapr = 0; $total_prpmay = 0; $total_prpjun = 0; $total_prpjul = 0; $total_prpaug = 0; $total_prpsep = 0; $total_prpoct = 0; $total_prpnov = 0; $total_prpdec = 0;
			
			    $total_prp = $res_val->uterine_prp + $res_val->ovarian_prp + $res_val->testicular_prp;
				$total_prpfeb = $res_val->uterine_prpfeb + $res_val->ovarian_prpfeb + $res_val->testicular_prpfeb;
				$total_prpmar = $res_val->uterine_prpmar + $res_val->ovarian_prpmar + $res_val->testicular_prpmar;
                $total_prpapr = $res_val->uterine_prpapr + $res_val->ovarian_prpapr + $res_val->testicular_prpapr;
				$total_prpmay = $res_val->uterine_prpmay + $res_val->ovarian_prpmay + $res_val->testicular_prpmay; 
				$total_prpjun = $res_val->uterine_prpjun + $res_val->ovarian_prpjun + $res_val->testicular_prpjun;
                $total_prpjul = $res_val->uterine_prpjul + $res_val->ovarian_prpjul + $res_val->testicular_prpjul; 
				$total_prpaug = $res_val->uterine_prpaug + $res_val->ovarian_prpaug + $res_val->testicular_prpaug;
				$total_prpsep = $res_val->uterine_prpsep + $res_val->ovarian_prpsep + $res_val->testicular_prpsep;
                $total_prpoct = $res_val->uterine_prpoct + $res_val->ovarian_prpoct + $res_val->testicular_prpoct; 
				$total_prpnov = $res_val->uterine_prpnov + $res_val->ovarian_prpnov + $res_val->testicular_prpnov;
				$total_prpdec = $res_val->uterine_prpdec + $res_val->ovarian_prpdec + $res_val->testicular_prpdec;
                
				$total_consultations = $res_val->consultations + $res_val->consultationsfeb + $res_val->consultationsmar + $res_val->consultationsapr + $res_val->consultationsmay + $res_val->consultationsjun + $res_val->consultationsjul + $res_val->consultationsaug + $res_val->consultationssep + $res_val->consultationsoct + $res_val->consultationsnov + $res_val->consultationsdec; 
                $total_tele_consult = $res_val->tele_consult + $res_val->tele_consultfeb + $res_val->tele_consultmar + $res_val->tele_consultapr + $res_val->tele_consultmay + $res_val->tele_consultjun + $res_val->tele_consultjul + $res_val->tele_consultaug + $res_val->tele_consultsep + $res_val->tele_consultoct + $res_val->tele_consultnov + $res_val->tele_consultdec; 
                $total_opu = $res_val->opu + $res_val->opufeb + $res_val->opumar + $res_val->opuapr + $res_val->opumay + $res_val->opujun + $res_val->opujul + $res_val->opuaug + $res_val->opusep + $res_val->opuoct + $res_val->opunov + $res_val->opudec; 
                $total_fresh_cycle_et = $res_val->fresh_cycle_et + $res_val->fresh_cycle_etfeb + $res_val->fresh_cycle_etmar + $res_val->fresh_cycle_etapr + $res_val->fresh_cycle_etmay + $res_val->fresh_cycle_etjun + $res_val->fresh_cycle_etjul + $res_val->fresh_cycle_etaug + $res_val->fresh_cycle_etsep + $res_val->fresh_cycle_etoct + $res_val->fresh_cycle_etnov + $res_val->fresh_cycle_etdec;
			    $total_thawed_cycle_fet = $res_val->thawed_cycle_fet + $res_val->thawed_cycle_fetfeb + $res_val->thawed_cycle_fetmar + $res_val->thawed_cycle_fetapr + $res_val->thawed_cycle_fetmay + $res_val->thawed_cycle_fetjun + $res_val->thawed_cycle_fetjul + $res_val->thawed_cycle_fetaug + $res_val->thawed_cycle_fetsep + $res_val->thawed_cycle_fetoct + $res_val->thawed_cycle_fetnov + $res_val->thawed_cycle_fetdec;
			    
				$total_iui = $res_val->iui + $res_val->iuifeb + $res_val->iuimar + $res_val->iuiapr + $res_val->iuimay + $res_val->iuijun + $res_val->iuijul + $res_val->iuiaug + $res_val->iuisep + $res_val->iuioct + $res_val->iuinov + $res_val->iuidec;
				$total_ivf = $res_val->ivf + $res_val->ivffeb + $res_val->ivfmar + $res_val->ivfapr + $res_val->ivfmay + $res_val->ivfjun + $res_val->ivfjul + $res_val->ivfaug + $res_val->ivfsep + $res_val->ivfoct + $res_val->ivfnov + $res_val->ivfdec;
				$total_icsi = $res_val->icsi + $res_val->icsifeb + $res_val->icsimar + $res_val->icsiapr + $res_val->icsimay + $res_val->icsijun + $res_val->icsijul + $res_val->icsiaug + $res_val->icsisep + $res_val->icsioct + $res_val->icsinov + $res_val->icsidec;
				$total_tesa_mtese = $res_val->tesa_mtese + $res_val->tesa_mtesefeb + $res_val->tesa_mtesemar + $res_val->tesa_mteseapr + $res_val->tesa_mtesemay + $res_val->tesa_mtesejun + $res_val->tesa_mtesejul + $res_val->tesa_mteseaug + $res_val->tesa_mtesesep + $res_val->tesa_mteseoct + $res_val->tesa_mtesenov + $res_val->tesa_mtesedec;
				$total_donor_cycle = $res_val->donor_cycle + $res_val->donor_cyclefeb + $res_val->donor_cyclemar + $res_val->donor_cycleapr + $res_val->donor_cyclemay + $res_val->donor_cyclejun + $res_val->donor_cyclejul + $res_val->donor_cycleaug + $res_val->donor_cyclesep + $res_val->donor_cycleoct + $res_val->donor_cyclenov + $res_val->donor_cycledec;
				$total_surrogacy = $res_val->surrogacy + $res_val->surrogacyfeb + $res_val->surrogacymar + $res_val->surrogacyapr + $res_val->surrogacymay + $res_val->surrogacyjun + $res_val->surrogacyjul + $res_val->surrogacyaug + $res_val->surrogacysep + $res_val->surrogacyoct + $res_val->surrogacynov + $res_val->surrogacydec;
				$total_uterine_prp = $res_val->uterine_prp + $res_val->uterine_prpfeb + $res_val->uterine_prpmar + $res_val->uterine_prpapr + $res_val->uterine_prpmay + $res_val->uterine_prpjun + $res_val->uterine_prpjul + $res_val->uterine_prpaug + $res_val->uterine_prpsep + $res_val->uterine_prpoct + $res_val->uterine_prpnov + $res_val->uterine_prpdec;
				$total_ovarian_prp = $res_val->ovarian_prp + $res_val->ovarian_prpfeb + $res_val->ovarian_prpmar + $res_val->ovarian_prpapr + $res_val->ovarian_prpmay + $res_val->ovarian_prpjun + $res_val->ovarian_prpjul + $res_val->ovarian_prpaug + $res_val->ovarian_prpsep + $res_val->ovarian_prpoct + $res_val->ovarian_prpnov + $res_val->ovarian_prpdec;
				$total_testicular_prp = $res_val->testicular_prp + $res_val->testicular_prpfeb + $res_val->testicular_prpmar + $res_val->testicular_prpapr + $res_val->testicular_prpmay + $res_val->testicular_prpjun + $res_val->testicular_prpjul + $res_val->testicular_prpaug + $res_val->testicular_prpsep + $res_val->testicular_prpoct + $res_val->testicular_prpnov + $res_val->testicular_prpdec;
				$total_macs_qualis_candor = $res_val->macs_qualis_candor + $res_val->macs_qualis_candorfeb + $res_val->macs_qualis_candormar + $res_val->macs_qualis_candorapr + $res_val->macs_qualis_candormay + $res_val->macs_qualis_candorjun + $res_val->macs_qualis_candorjul + $res_val->macs_qualis_candoraug + $res_val->macs_qualis_candorsep + $res_val->macs_qualis_candoroct + $res_val->macs_qualis_candornov + $res_val->macs_qualis_candordec;
				$total_lah = $res_val->lah + $res_val->lahfeb + $res_val->lahmar + $res_val->lahapr + $res_val->lahmay + $res_val->lahjun + $res_val->lahjul + $res_val->lahaug + $res_val->lahsep + $res_val->lahoct + $res_val->lahnov + $res_val->lahdec;
				$total_pgd = $res_val->pgd + $res_val->pgdfeb + $res_val->pgdmar + $res_val->pgdapr + $res_val->pgdmay + $res_val->pgdjun + $res_val->pgdjul + $res_val->pgdaug + $res_val->pgdsep + $res_val->pgdoct + $res_val->pgdnov + $res_val->pgddec;
				$total_embryo_glue = $res_val->embryo_glue + $res_val->embryo_gluefeb + $res_val->embryo_gluemar + $res_val->embryo_glueapr + $res_val->embryo_gluemay + $res_val->embryo_gluejun + $res_val->embryo_gluejul + $res_val->embryo_glueaug + $res_val->embryo_gluesep + $res_val->embryo_glueoct + $res_val->embryo_gluenov + $res_val->embryo_gluedec;
				$total_sperm_mobil = $res_val->sperm_mobil + $res_val->sperm_mobilfeb + $res_val->sperm_mobilmar+ $res_val->sperm_mobilapr + $res_val->sperm_mobilmay + $res_val->sperm_mobiljun + $res_val->sperm_mobiljul + $res_val->sperm_mobilaug + $res_val->sperm_mobilsep + $res_val->sperm_mobiloct + $res_val->sperm_mobilnov + $res_val->sperm_mobildec;
				$total_blastocyst_transfer = $res_val->blastocyst_transfer + $res_val->blastocyst_transferfeb + $res_val->blastocyst_transfermar + $res_val->blastocyst_transferapr + $res_val->blastocyst_transfermay + $res_val->blastocyst_transferjun + $res_val->blastocyst_transferjul + $res_val->blastocyst_transferaug + $res_val->blastocyst_transfersep + $res_val->blastocyst_transferoct + $res_val->blastocyst_transfernov + $res_val->blastocyst_transferdec;
				$total_hysteroscopy_diagnostic = $res_val->hysteroscopy_diagnostic + $res_val->hysteroscopy_diagnosticfeb + $res_val->hysteroscopy_diagnosticmar + $res_val->hysteroscopy_diagnosticapr + $res_val->hysteroscopy_diagnosticmay + $res_val->hysteroscopy_diagnosticjun + $res_val->hysteroscopy_diagnosticjul + $res_val->hysteroscopy_diagnosticaug + $res_val->hysteroscopy_diagnosticsep + $res_val->hysteroscopy_diagnosticoct + $res_val->hysteroscopy_diagnosticnov + $res_val->hysteroscopy_diagnosticdec;
				$total_laparoscopy_hysteroscopy = $res_val->laparoscopy_hysteroscopy + $res_val->laparoscopy_hysteroscopyfeb + $res_val->laparoscopy_hysteroscopymar + $res_val->laparoscopy_hysteroscopyapr + $res_val->laparoscopy_hysteroscopymay + $res_val->laparoscopy_hysteroscopyjun + $res_val->laparoscopy_hysteroscopyjul + $res_val->laparoscopy_hysteroscopyaug + $res_val->laparoscopy_hysteroscopysep + $res_val->laparoscopy_hysteroscopyoct + $res_val->laparoscopy_hysteroscopynov + $res_val->laparoscopy_hysteroscopydec;
				$total_beta_hcg_positive = $res_val->beta_hcg_positive + $res_val->beta_hcg_positivefeb + $res_val->beta_hcg_positivemar + $res_val->beta_hcg_positiveapr + $res_val->beta_hcg_positivemay + $res_val->beta_hcg_positivejun + $res_val->beta_hcg_positivejul + $res_val->beta_hcg_positiveaug + $res_val->beta_hcg_positivesep + $res_val->beta_hcg_positiveoct + $res_val->beta_hcg_positivenov + $res_val->beta_hcg_positivedec;
				$total_cardic_activity = $res_val->cardic_activity + $res_val->cardic_activityfeb + $res_val->cardic_activitymar + $res_val->cardic_activityapr + $res_val->cardic_activitymay + $res_val->cardic_activityjun + $res_val->cardic_activityjul + $res_val->cardic_activityaug + $res_val->cardic_activitysep + $res_val->cardic_activityoct + $res_val->cardic_activitynov + $res_val->cardic_activitydec;
				$total_live_birth = $res_val->live_birth + $res_val->live_birthfeb + $res_val->live_birthmar + $res_val->live_birthapr + $res_val->live_birthmay + $res_val->live_birthjun + $res_val->live_birthjul + $res_val->live_birthaug + $res_val->live_birthsep + $res_val->live_birthoct + $res_val->live_birthnov + $res_val->live_birthdec;
				$total_male = $res_val->male + $res_val->malefeb + $res_val->malemar + $res_val->maleapr + $res_val->malemay + $res_val->malejun + $res_val->malejul + $res_val->maleaug + $res_val->malesep + $res_val->maleoct + $res_val->malenov + $res_val->maledec;
				$total_female = $res_val->female + $res_val->femalefeb + $res_val->femalemar + $res_val->femaleapr + $res_val->femalemay + $res_val->femalejun + $res_val->femalejul + $res_val->femaleaug + $res_val->femalesep + $res_val->femaleoct + $res_val->femalenov + $res_val->femaledec;
				$total_ongoing = $res_val->ongoing + $res_val->ongoingfeb + $res_val->ongoingmar + $res_val->ongoingapr + $res_val->ongoingmay + $res_val->ongoingjun + $res_val->ongoingjul + $res_val->ongoingaug + $res_val->ongoingsep + $res_val->ongoingoct + $res_val->ongoingnov + $res_val->ongoingdec;
				$total_ivm = $res_val->ivm + $res_val->ivmfeb + $res_val->ivmmar + $res_val->ivmapr + $res_val->ivmmay + $res_val->ivmjun + $res_val->ivmjul + $res_val->ivmaug + $res_val->ivmsep + $res_val->ivmoct + $res_val->ivmnov + $res_val->ivmdec;
                //$total_egg_freezing = $res_val->egg_freezing + $res_val->egg_freezingfeb + $res_val->egg_freezingmar + $res_val->egg_freezingapr + $res_val->egg_freezingmay + $res_val->egg_freezingjun + $res_val->egg_freezingjul + $res_val->egg_freezingaug + $res_val->egg_freezingsep + $res_val->egg_freezingoct + $res_val->egg_freezingnov + $res_val->egg_freezingdec;
				$total_egg_freezing = (float)$res_val->egg_freezing + (float)$res_val->egg_freezingfeb + (float)$res_val->egg_freezingmar + (float)$res_val->egg_freezingapr + (float)$res_val->egg_freezingmay + (float)$res_val->egg_freezingjun + (float)$res_val->egg_freezingjul + (float)$res_val->egg_freezingaug + (float)$res_val->egg_freezingsep + (float)$res_val->egg_freezingoct + (float)$res_val->egg_freezingnov + (float)$res_val->egg_freezingdec;
				$total_semen_freezing = (float)$res_val->semen_freezing + (float)$res_val->semen_freezingfeb + (float)$res_val->semen_freezingmar + (float)$res_val->semen_freezingapr + (float)$res_val->semen_freezingmay + (float)$res_val->semen_freezingjun + (float)$res_val->semen_freezingjul + (float)$res_val->semen_freezingaug + (float)$res_val->semen_freezingsep + (float)$res_val->semen_freezingoct + (float)$res_val->semen_freezingnov + (float)$res_val->semen_freezingdec;
				$total_embryo_freezing = (float)$res_val->embryo_freezing + (float)$res_val->embryo_freezingfeb + (float)$res_val->embryo_freezingmar + (float)$res_val->embryo_freezingapr + (float)$res_val->embryo_freezingmay + (float)$res_val->embryo_freezingjun + (float)$res_val->embryo_freezingjul + (float)$res_val->embryo_freezingaug + (float)$res_val->embryo_freezingsep + (float)$res_val->embryo_freezingoct + (float)$res_val->embryo_freezingnov + (float)$res_val->embryo_freezingdec;								
                $totalet =  $total_et + $total_etfeb + $total_etmar + $total_etapr  + $total_etmay + $total_etjun + $total_etjul + $total_etaug + $total_etsep + $total_etoct + $total_etnov + $total_etdec;

?>
<div class="page-wrapper">
<form class="col-sm-12 col-xs-12" action="" enctype='multipart/form-data' method="post">
<div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku">
      <div class="panel-heading">
        <h3 class="heading">Update Clinical Reports <?php echo $res_val->year; ?></h3>
      </div>
      <div class="panel-body profile-edit">
	<div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
				  <th>S.No.</th>
                  <th>Month</th>
                  <th>CONSULTATIONS</th>
                  <th>Tele Consult</th>
                  <th>OPU</th>
                  <th>FRESH CYCLE ET</th>
                  <th>THAWED CYCLE / FET</th>
				  <th>TOTAL ET</th>
				  <th>IUI</th>
                  <th>IVF</th>
                  <th>ICSI</th>
				  <th>TESA/MTESE</th>
				  <th>DONOR CYCLE</th>
                  <th>SURROGACY</th>
                  <th>Uterine PRP</th>
                  <th>Ovarian PRP</th>
                  <th>Testicular PRP</th>
                  <th>Total PRP</th>
                  <th>MACS/QUALIS/ Candor</th>
				  <th>LAH</th>
                  <th>PGD</th>
                  <th>EMBRYO GLUE</th>
				  <th>Sperm Mobil</th>
				  <th>Blastocyst</th>
                  <th>HYSTEROSCOPY DIAGNOSTIC</th>
				  <th>LAPAROSCOPY PLUS HYSTEROSCOPY</th>
				  <th>BETA HCG POSITIVE</th>
                  <th>CARDIAC ACTIVITY</th>
                  <th>LIVE BIRTH </th>
				  <th>MALE</th>
				  <th>FEMALE</th>
				  <th>ONGOING</th>
				  <th>IVM</th>
				  <th>NAME OF PREGNANT COUPLES</th>
				  <th>EGG FREEZING</th>
				  <th>SEMEN FREEZING</th>
				  <th>EMBRYO FREEZING</th>
				</tr>
              </thead>
			  <tbody id="procedure_result">
                            <tr class="odd gradeX">
								<td>1</td>
								<td>JANUARY</td>
								<td><input placeholder="Consultations" name="consultations" id="consultations" value="<?php echo $res_val->consultations; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consult" id="tele_consult" value="<?php echo $res_val->tele_consult; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opu" id="opu" value="<?php echo $res_val->opu; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_et" id="fresh_cycle_et" value="<?php echo $res_val->fresh_cycle_et; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fet" id="thawed_cycle_fet" value="<?php echo $res_val->thawed_cycle_fet; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_et; ?></td>
								<td><input placeholder="IUI" name="iui" id="iui" value="<?php echo $res_val->iui; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivf" id="ivf" value="<?php echo $res_val->ivf; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsi; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtese" id="tesa_mtese" value="<?php echo $res_val->tesa_mtese; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cycle" id="donor_cycle" value="<?php echo $res_val->donor_cycle; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacy" id="surrogacy" value="<?php echo $res_val->surrogacy; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prp" id="uterine_prp" value="<?php echo $res_val->uterine_prp; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prp" id="ovarian_prp" value="<?php echo $res_val->ovarian_prp; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prp" id="testicular_prp" value="<?php echo $res_val->testicular_prp; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prp; ?></td>
								<td><?php echo $res_val->macs_qualis_candor; ?></td>
								<td><?php echo $res_val->lah; ?></td>
								<td><?php echo $res_val->pgd; ?></td>
								<td><?php echo $res_val->embryo_glue; ?></td>
								<td><?php echo $res_val->sperm_mobil; ?></td>
								<td><?php echo $res_val->blastocyst_transfer; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnostic" id="hysteroscopy_diagnostic" value="<?php echo $res_val->hysteroscopy_diagnostic; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopy" id="laparoscopy_hysteroscopy" value="<?php echo $res_val->laparoscopy_hysteroscopy; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positive" id="beta_hcg_positive" value="<?php echo $res_val->beta_hcg_positive; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activity" id="cardic_activity" value="<?php echo $res_val->cardic_activity; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birth" id="live_birth" value="<?php echo $res_val->live_birth; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="male" id="male" value="<?php echo $res_val->male; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="female" id="female" value="<?php echo $res_val->female; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoing" id="ongoing" value="<?php echo $res_val->ongoing; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivm" id="ivm" value="<?php echo $res_val->ivm; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couples" id="pregnant_couples" value="<?php echo $res_val->pregnant_couples; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezing; ?></td>
								<td><?php echo $res_val->semen_freezing; ?></td>
								<td><?php echo $res_val->embryo_freezing; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>2</td>
								<td>FEBRUARY</td>
								<td><input placeholder="Consultations" name="consultationsfeb" id="consultationsfeb" value="<?php echo $res_val->consultationsfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultfeb" id="tele_consultfeb" value="<?php echo $res_val->tele_consultfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opufeb" id="opufeb" value="<?php echo $res_val->opufeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etfeb" id="fresh_cycle_etfeb" value="<?php echo $res_val->fresh_cycle_etfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetfeb" id="thawed_cycle_fetfeb" value="<?php echo $res_val->thawed_cycle_fetfeb; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etfeb; ?></td>
								<td><input placeholder="IUI" name="iuifeb" id="iuifeb" value="<?php echo $res_val->iuifeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivffeb" id="ivffeb" value="<?php echo $res_val->ivffeb; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsifeb; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesefeb" id="tesa_mtesefeb" value="<?php echo $res_val->tesa_mtesefeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclefeb" id="donor_cyclefeb" value="<?php echo $res_val->donor_cyclefeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacyfeb" id="surrogacyfeb" value="<?php echo $res_val->surrogacyfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpfeb" id="uterine_prpfeb" value="<?php echo $res_val->uterine_prpfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpfeb" id="ovarian_prpfeb" value="<?php echo $res_val->ovarian_prpfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpfeb" id="testicular_prpfeb" value="<?php echo $res_val->testicular_prpfeb; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpfeb; ?></td>
								<td><?php echo $res_val->macs_qualis_candorfeb; ?></td>
								<td><?php echo $res_val->lahfeb; ?></td>
								<td><?php echo $res_val->pgdfeb; ?></td>
								<td><?php echo $res_val->embryo_gluefeb; ?></td>
								<td><?php echo $res_val->sperm_mobilfeb; ?></td>
								<td><?php echo $res_val->blastocyst_transferfeb; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticfeb" id="hysteroscopy_diagnosticfeb" value="<?php echo $res_val->hysteroscopy_diagnosticfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopyfeb" id="laparoscopy_hysteroscopyfeb" value="<?php echo $res_val->laparoscopy_hysteroscopyfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivefeb" id="beta_hcg_positivefeb" value="<?php echo $res_val->beta_hcg_positivefeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activityfeb" id="cardic_activityfeb" value="<?php echo $res_val->cardic_activityfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthfeb" id="live_birthfeb" value="<?php echo $res_val->live_birthfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malefeb" id="malefeb" value="<?php echo $res_val->malefeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalefeb" id="femalefeb" value="<?php echo $res_val->femalefeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingfeb" id="ongoingfeb" value="<?php echo $res_val->ongoingfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmfeb" id="ivmfeb" value="<?php echo $res_val->ivmfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesfeb" id="pregnant_couplesfeb" value="<?php echo $res_val->pregnant_couplesfeb; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingfeb; ?></td>
								<td><?php echo $res_val->semen_freezingfeb; ?></td>
								<td><?php echo $res_val->embryo_freezingfeb; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>3</td>
								<td>MARCH</td>
								<td><input placeholder="Consultations" name="consultationsmar" id="consultationsmar" value="<?php echo $res_val->consultationsmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultmar" id="tele_consultmar" value="<?php echo $res_val->tele_consultmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opumar" id="opumar" value="<?php echo $res_val->opumar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etmar" id="fresh_cycle_etmar" value="<?php echo $res_val->fresh_cycle_etmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetmar" id="thawed_cycle_fetmar" value="<?php echo $res_val->thawed_cycle_fetmar; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etmar; ?></td>
								<td><input placeholder="IUI" name="iuimar" id="iuimar" value="<?php echo $res_val->iuimar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfmar" id="ivfmar" value="<?php echo $res_val->ivfmar; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsimar; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesemar" id="tesa_mtesemar" value="<?php echo $res_val->tesa_mtesemar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclemar" id="donor_cyclemar" value="<?php echo $res_val->donor_cyclemar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacymar" id="surrogacymar" value="<?php echo $res_val->surrogacymar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpmar" id="uterine_prpmar" value="<?php echo $res_val->uterine_prpmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpmar" id="ovarian_prpmar" value="<?php echo $res_val->ovarian_prpmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpmar" id="testicular_prpmar" value="<?php echo $res_val->testicular_prpmar; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpmar; ?></td>
								<td><?php echo $res_val->macs_qualis_candormar; ?></td>
								<td><?php echo $res_val->lahmar; ?></td>
								<td><?php echo $res_val->pgdmar; ?></td>
								<td><?php echo $res_val->embryo_gluemar; ?></td>
								<td><?php echo $res_val->sperm_mobilmar; ?></td>
								<td><?php echo $res_val->blastocyst_transfermar; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticmar" id="hysteroscopy_diagnosticmar" value="<?php echo $res_val->hysteroscopy_diagnosticmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopymar" id="laparoscopy_hysteroscopymar" value="<?php echo $res_val->laparoscopy_hysteroscopymar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivemar" id="beta_hcg_positivemar" value="<?php echo $res_val->beta_hcg_positivemar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activitymar" id="cardic_activitymar" value="<?php echo $res_val->cardic_activitymar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthmar" id="live_birthmar" value="<?php echo $res_val->live_birthmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malemar" id="malemar" value="<?php echo $res_val->malemar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalemar" id="femalemar" value="<?php echo $res_val->femalemar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingmar" id="ongoingmar" value="<?php echo $res_val->ongoingmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmmar" id="ivmmar" value="<?php echo $res_val->ivmmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesmar" id="pregnant_couplesmar" value="<?php echo $res_val->pregnant_couplesmar; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingmar; ?></td>
								<td><?php echo $res_val->semen_freezingmar; ?></td>
								<td><?php echo $res_val->embryo_freezingmar; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>4</td>
								<td>APRIL</td>
								<td><input placeholder="Consultations" name="consultationsapr" id="consultationsapr" value="<?php echo $res_val->consultationsapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultapr" id="tele_consultapr" value="<?php echo $res_val->tele_consultapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opuapr" id="opuapr" value="<?php echo $res_val->opuapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etapr" id="fresh_cycle_etapr" value="<?php echo $res_val->fresh_cycle_etapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetapr" id="thawed_cycle_fetapr" value="<?php echo $res_val->thawed_cycle_fetapr; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etapr; ?></td>
								<td><input placeholder="IUI" name="iuiapr" id="iuiapr" value="<?php echo $res_val->iuiapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfapr" id="ivfapr" value="<?php echo $res_val->ivfapr; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsiapr; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mteseapr" id="tesa_mteseapr" value="<?php echo $res_val->tesa_mteseapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cycleapr" id="donor_cycleapr" value="<?php echo $res_val->donor_cycleapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacyapr" id="surrogacyapr" value="<?php echo $res_val->surrogacyapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpapr" id="uterine_prpapr" value="<?php echo $res_val->uterine_prpapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpapr" id="ovarian_prpapr" value="<?php echo $res_val->ovarian_prpapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpapr" id="testicular_prpapr" value="<?php echo $res_val->testicular_prpapr; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpapr; ?></td>
								<td><?php echo $res_val->macs_qualis_candorapr; ?></td>
								<td><?php echo $res_val->lahapr; ?></td>
								<td><?php echo $res_val->pgdapr; ?></td>
								<td><?php echo $res_val->embryo_glueapr; ?></td>
								<td><?php echo $res_val->sperm_mobilapr; ?></td>
								<td><?php echo $res_val->blastocyst_transferapr; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticapr" id="hysteroscopy_diagnosticapr" value="<?php echo $res_val->hysteroscopy_diagnosticapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopyapr" id="laparoscopy_hysteroscopyapr" value="<?php echo $res_val->laparoscopy_hysteroscopyapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positiveapr" id="beta_hcg_positiveapr" value="<?php echo $res_val->beta_hcg_positiveapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activityapr" id="cardic_activityapr" value="<?php echo $res_val->cardic_activityapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthapr" id="live_birthapr" value="<?php echo $res_val->live_birthapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="maleapr" id="maleapr" value="<?php echo $res_val->maleapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femaleapr" id="femaleapr" value="<?php echo $res_val->femaleapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingapr" id="ongoingapr" value="<?php echo $res_val->ongoingapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmapr" id="ivmapr" value="<?php echo $res_val->ivmapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesapr" id="pregnant_couplesapr" value="<?php echo $res_val->pregnant_couplesapr; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingapr; ?></td>
								<td><?php echo $res_val->semen_freezingapr; ?></td>
								<td><?php echo $res_val->embryo_freezingapr; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>5</td>
								<td>MAY</td>
								<td><input placeholder="Consultations" name="consultationsmay" id="consultationsmay" value="<?php echo $res_val->consultationsmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultmay" id="tele_consultmay" value="<?php echo $res_val->tele_consultmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opumay" id="opumay" value="<?php echo $res_val->opumay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etmay" id="fresh_cycle_etmay" value="<?php echo $res_val->fresh_cycle_etmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetmay" id="thawed_cycle_fetmay" value="<?php echo $res_val->thawed_cycle_fetmay; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etmay; ?></td>
								<td><input placeholder="IUI" name="iuimay" id="iuimay" value="<?php echo $res_val->iuimay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfmay" id="ivfmay" value="<?php echo $res_val->ivfmay; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsimay; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesemay" id="tesa_mtesemay" value="<?php echo $res_val->tesa_mtesemay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclemay" id="donor_cyclemay" value="<?php echo $res_val->donor_cyclemay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacymay" id="surrogacymay" value="<?php echo $res_val->surrogacymay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpmay" id="uterine_prpmay" value="<?php echo $res_val->uterine_prpmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpmay" id="ovarian_prpmay" value="<?php echo $res_val->ovarian_prpmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpmay" id="testicular_prpmay" value="<?php echo $res_val->testicular_prpmay; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpmay; ?></td>
								<td><?php echo $res_val->macs_qualis_candormay; ?></td>
								<td><?php echo $res_val->lahmay; ?></td>
								<td><?php echo $res_val->pgdmay; ?></td>
								<td><?php echo $res_val->embryo_gluemay; ?></td>
								<td><?php echo $res_val->sperm_mobilmay; ?></td>
								<td><?php echo $res_val->blastocyst_transfermay; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticmay" id="hysteroscopy_diagnosticmay" value="<?php echo $res_val->hysteroscopy_diagnosticmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopymay" id="laparoscopy_hysteroscopymay" value="<?php echo $res_val->laparoscopy_hysteroscopymay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivemay" id="beta_hcg_positivemay" value="<?php echo $res_val->beta_hcg_positivemay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activitymay" id="cardic_activitymay" value="<?php echo $res_val->cardic_activitymay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthmay" id="live_birthmay" value="<?php echo $res_val->live_birthmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malemay" id="malemay" value="<?php echo $res_val->malemay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalemay" id="femalemay" value="<?php echo $res_val->femalemay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingmay" id="ongoingmay" value="<?php echo $res_val->ongoingmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmmay" id="ivmmay" value="<?php echo $res_val->ivmmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesmay" id="pregnant_couplesmay" value="<?php echo $res_val->pregnant_couplesmay; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingmay; ?></td>
								<td><?php echo $res_val->semen_freezingmay; ?></td>
								<td><?php echo $res_val->embryo_freezingmay; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>6</td>
								<td>JUNE</td>
								<td><input placeholder="Consultations" name="consultationsjun" id="consultationsjun" value="<?php echo $res_val->consultationsjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultjun" id="tele_consultjun" value="<?php echo $res_val->tele_consultjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opujun" id="opujun" value="<?php echo $res_val->opujun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etjun" id="fresh_cycle_etjun" value="<?php echo $res_val->fresh_cycle_etjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetjun" id="thawed_cycle_fetjun" value="<?php echo $res_val->thawed_cycle_fetjun; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etjun; ?></td>
								<td><input placeholder="IUI" name="iuijun" id="iuijun" value="<?php echo $res_val->iuijun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfjun" id="ivfjun" value="<?php echo $res_val->ivfjun; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsijun; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesejun" id="tesa_mtesejun" value="<?php echo $res_val->tesa_mtesejun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclejun" id="donor_cyclejun" value="<?php echo $res_val->donor_cyclejun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacyjun" id="surrogacyjun" value="<?php echo $res_val->surrogacyjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpjun" id="uterine_prpjun" value="<?php echo $res_val->uterine_prpjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpjun" id="ovarian_prpjun" value="<?php echo $res_val->ovarian_prpjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpjun" id="testicular_prpjun" value="<?php echo $res_val->testicular_prpjun; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpjun; ?></td>
								<td><?php echo $res_val->macs_qualis_candorjun; ?></td>
								<td><?php echo $res_val->lahjun; ?></td>
								<td><?php echo $res_val->pgdjun; ?></td>
								<td><?php echo $res_val->embryo_gluejun; ?></td>
								<td><?php echo $res_val->sperm_mobiljun; ?></td>
								<td><?php echo $res_val->blastocyst_transferjun; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticjun" id="hysteroscopy_diagnosticjun" value="<?php echo $res_val->hysteroscopy_diagnosticjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopyjun" id="laparoscopy_hysteroscopyjun" value="<?php echo $res_val->laparoscopy_hysteroscopyjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivejun" id="beta_hcg_positivejun" value="<?php echo $res_val->beta_hcg_positivejun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activityjun" id="cardic_activityjun" value="<?php echo $res_val->cardic_activityjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthjun" id="live_birthjun" value="<?php echo $res_val->live_birthjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malejun" id="malejun" value="<?php echo $res_val->malejun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalejun" id="femalejun" value="<?php echo $res_val->femalejun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingjun" id="ongoingjun" value="<?php echo $res_val->ongoingjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmjun" id="ivmjun" value="<?php echo $res_val->ivmjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesjun" id="pregnant_couplesjun" value="<?php echo $res_val->pregnant_couplesjun; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingjun; ?></td>
								<td><?php echo $res_val->semen_freezingjun; ?></td>
								<td><?php echo $res_val->embryo_freezingjun; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>7</td>
								<td>JULY</td>
								<td><input placeholder="Consultations" name="consultationsjul" id="consultationsjul" value="<?php echo $res_val->consultationsjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultjul" id="tele_consultjul" value="<?php echo $res_val->tele_consultjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opujul" id="opujul" value="<?php echo $res_val->opujul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etjul" id="fresh_cycle_etjul" value="<?php echo $res_val->fresh_cycle_etjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetjul" id="thawed_cycle_fetjul" value="<?php echo $res_val->thawed_cycle_fetjul; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etjul; ?></td>
								<td><input placeholder="IUI" name="iuijul" id="iuijul" value="<?php echo $res_val->iuijul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfjul" id="ivfjul" value="<?php echo $res_val->ivfjul; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsijul; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesejul" id="tesa_mtesejul" value="<?php echo $res_val->tesa_mtesejul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclejul" id="donor_cyclejul" value="<?php echo $res_val->donor_cyclejul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacyjul" id="surrogacyjul" value="<?php echo $res_val->surrogacyjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpjul" id="uterine_prpjul" value="<?php echo $res_val->uterine_prpjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpjul" id="ovarian_prpjul" value="<?php echo $res_val->ovarian_prpjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpjul" id="testicular_prpjul" value="<?php echo $res_val->testicular_prpjul; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpjul; ?></td>
								<td><?php echo $res_val->macs_qualis_candorjul; ?></td>
								<td><?php echo $res_val->lahjul; ?></td>
								<td><?php echo $res_val->pgdjul; ?></td>
								<td><?php echo $res_val->embryo_gluejul; ?></td>
								<td><?php echo $res_val->sperm_mobiljul; ?></td>
								<td><?php echo $res_val->blastocyst_transferjul; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticjul" id="hysteroscopy_diagnosticjul" value="<?php echo $res_val->hysteroscopy_diagnosticjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopyjul" id="laparoscopy_hysteroscopyjul" value="<?php echo $res_val->laparoscopy_hysteroscopyjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivejul" id="beta_hcg_positivejul" value="<?php echo $res_val->beta_hcg_positivejul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activityjul" id="cardic_activityjul" value="<?php echo $res_val->cardic_activityjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthjul" id="live_birthjul" value="<?php echo $res_val->live_birthjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malejul" id="malejul" value="<?php echo $res_val->malejul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalejul" id="femalejul" value="<?php echo $res_val->femalejul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingjul" id="ongoingjul" value="<?php echo $res_val->ongoingjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmjul" id="ivmjul" value="<?php echo $res_val->ivmjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesjul" id="pregnant_couplesjul" value="<?php echo $res_val->pregnant_couplesjul; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingjul; ?></td>
								<td><?php echo $res_val->semen_freezingjul; ?></td>
								<td><?php echo $res_val->embryo_freezingjul; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>8</td>
								<td>AUGUST</td>
								<td><input placeholder="Consultations" name="consultationsaug" id="consultationsaug" value="<?php echo $res_val->consultationsaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultaug" id="tele_consultaug" value="<?php echo $res_val->tele_consultaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opuaug" id="opuaug" value="<?php echo $res_val->opuaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etaug" id="fresh_cycle_etaug" value="<?php echo $res_val->fresh_cycle_etaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetaug" id="thawed_cycle_fetaug" value="<?php echo $res_val->thawed_cycle_fetaug; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etaug; ?></td>
								<td><input placeholder="IUI" name="iuiaug" id="iuiaug" value="<?php echo $res_val->iuiaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfaug" id="ivfaug" value="<?php echo $res_val->ivfaug; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsiaug; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mteseaug" id="tesa_mteseaug" value="<?php echo $res_val->tesa_mteseaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cycleaug" id="donor_cycleaug" value="<?php echo $res_val->donor_cycleaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacyaug" id="surrogacyaug" value="<?php echo $res_val->surrogacyaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpaug" id="uterine_prpaug" value="<?php echo $res_val->uterine_prpaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpaug" id="ovarian_prpaug" value="<?php echo $res_val->ovarian_prpaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpaug" id="testicular_prpaug" value="<?php echo $res_val->testicular_prpaug; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpaug; ?></td>
								<td><?php echo $res_val->macs_qualis_candoraug; ?></td>
								<td><?php echo $res_val->lahaug; ?></td>
								<td><?php echo $res_val->pgdaug; ?></td>
								<td><?php echo $res_val->embryo_glueaug; ?></td>
								<td><?php echo $res_val->sperm_mobilaug; ?></td>
								<td><?php echo $res_val->blastocyst_transferaug; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticaug" id="hysteroscopy_diagnosticaug" value="<?php echo $res_val->hysteroscopy_diagnosticaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopyaug" id="laparoscopy_hysteroscopyaug" value="<?php echo $res_val->laparoscopy_hysteroscopyaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positiveaug" id="beta_hcg_positiveaug" value="<?php echo $res_val->beta_hcg_positiveaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activityaug" id="cardic_activityaug" value="<?php echo $res_val->cardic_activityaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthaug" id="live_birthaug" value="<?php echo $res_val->live_birthaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="maleaug" id="maleaug" value="<?php echo $res_val->maleaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femaleaug" id="femaleaug" value="<?php echo $res_val->femaleaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingaug" id="ongoingaug" value="<?php echo $res_val->ongoingaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmaug" id="ivmaug" value="<?php echo $res_val->ivmaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesaug" id="pregnant_couplesaug" value="<?php echo $res_val->pregnant_couplesaug; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingaug; ?></td>
								<td><?php echo $res_val->semen_freezingaug; ?></td>
								<td><?php echo $res_val->embryo_freezingaug; ?></td>
							</tr>
                            <tr class="odd gradeX">
								<td>9</td>
								<td>SEPTEMBER</td>
								<td><input placeholder="Consultations" name="consultationssep" id="consultationssep" value="<?php echo $res_val->consultationssep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultsep" id="tele_consultsep" value="<?php echo $res_val->tele_consultsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opusep" id="opusep" value="<?php echo $res_val->opusep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etsep" id="fresh_cycle_etsep" value="<?php echo $res_val->fresh_cycle_etsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetsep" id="thawed_cycle_fetsep" value="<?php echo $res_val->thawed_cycle_fetsep; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etsep; ?></td>
								<td><input placeholder="IUI" name="iuisep" id="iuisep" value="<?php echo $res_val->iuisep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfsep" id="ivfsep" value="<?php echo $res_val->ivfsep; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsisep; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesesep" id="tesa_mtesesep" value="<?php echo $res_val->tesa_mtesesep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclesep" id="donor_cyclesep" value="<?php echo $res_val->donor_cyclesep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacysep" id="surrogacysep" value="<?php echo $res_val->surrogacysep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpsep" id="uterine_prpsep" value="<?php echo $res_val->uterine_prpsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpsep" id="ovarian_prpsep" value="<?php echo $res_val->ovarian_prpsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpsep" id="testicular_prpsep" value="<?php echo $res_val->testicular_prpsep; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpsep; ?></td>
								<td><?php echo $res_val->macs_qualis_candorsep; ?></td>
								<td><?php echo $res_val->lahsep; ?></td>
								<td><?php echo $res_val->pgdsep; ?></td>
								<td><?php echo $res_val->embryo_gluesep; ?></td>
								<td><?php echo $res_val->sperm_mobilsep; ?></td>
								<td><?php echo $res_val->blastocyst_transfersep; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticsep" id="hysteroscopy_diagnosticsep" value="<?php echo $res_val->hysteroscopy_diagnosticsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopysep" id="laparoscopy_hysteroscopysep" value="<?php echo $res_val->laparoscopy_hysteroscopysep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivesep" id="beta_hcg_positivesep" value="<?php echo $res_val->beta_hcg_positivesep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activitysep" id="cardic_activitysep" value="<?php echo $res_val->cardic_activitysep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthsep" id="live_birthsep" value="<?php echo $res_val->live_birthsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malesep" id="malesep" value="<?php echo $res_val->malesep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalesep" id="femalesep" value="<?php echo $res_val->femalesep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingsep" id="ongoingsep" value="<?php echo $res_val->ongoingsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmsep" id="ivmsep" value="<?php echo $res_val->ivmsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplessep" id="pregnant_couplessep" value="<?php echo $res_val->pregnant_couplessep; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingsep; ?></td>
								<td><?php echo $res_val->semen_freezingsep; ?></td>
								<td><?php echo $res_val->embryo_freezingsep; ?></td>
							</tr>	
							<tr class="odd gradeX">
								<td>10</td>
								<td>OCTOBER</td>
								<td><input placeholder="Consultations" name="consultationsoct" id="consultationsoct" value="<?php echo $res_val->consultationsoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultoct" id="tele_consultoct" value="<?php echo $res_val->tele_consultoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opuoct" id="opuoct" value="<?php echo $res_val->opuoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etoct" id="fresh_cycle_etoct" value="<?php echo $res_val->fresh_cycle_etoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetoct" id="thawed_cycle_fetoct" value="<?php echo $res_val->thawed_cycle_fetoct; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etoct; ?></td>
								<td><input placeholder="IUI" name="iuioct" id="iuioct" value="<?php echo $res_val->iuioct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfoct" id="ivfoct" value="<?php echo $res_val->ivfoct; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsioct; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mteseoct" id="tesa_mteseoct" value="<?php echo $res_val->tesa_mteseoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cycleoct" id="donor_cycleoct" value="<?php echo $res_val->donor_cycleoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacyoct" id="surrogacyoct" value="<?php echo $res_val->surrogacyoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpoct" id="uterine_prpoct" value="<?php echo $res_val->uterine_prpoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpoct" id="ovarian_prpoct" value="<?php echo $res_val->ovarian_prpoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpoct" id="testicular_prpoct" value="<?php echo $res_val->testicular_prpoct; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpoct; ?></td>
								<td><?php echo $res_val->macs_qualis_candoroct; ?></td>
								<td><?php echo $res_val->lahoct; ?></td>
								<td><?php echo $res_val->pgdoct; ?></td>
								<td><?php echo $res_val->embryo_glueoct; ?></td>
								<td><?php echo $res_val->sperm_mobiloct; ?></td>
								<td><?php echo $res_val->blastocyst_transferoct; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticoct" id="hysteroscopy_diagnosticoct" value="<?php echo $res_val->hysteroscopy_diagnosticoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopyoct" id="laparoscopy_hysteroscopyoct" value="<?php echo $res_val->laparoscopy_hysteroscopyoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positiveoct" id="beta_hcg_positiveoct" value="<?php echo $res_val->beta_hcg_positiveoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activityoct" id="cardic_activityoct" value="<?php echo $res_val->cardic_activityoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthoct" id="live_birthoct" value="<?php echo $res_val->live_birthoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="maleoct" id="maleoct" value="<?php echo $res_val->maleoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femaleoct" id="femaleoct" value="<?php echo $res_val->femaleoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingoct" id="ongoingoct" value="<?php echo $res_val->ongoingoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmoct" id="ivmoct" value="<?php echo $res_val->ivmoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesoct" id="pregnant_couplesoct" value="<?php echo $res_val->pregnant_couplesoct; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingoct; ?></td>
								<td><?php echo $res_val->semen_freezingoct; ?></td>
								<td><?php echo $res_val->embryo_freezingoct; ?></td>
							</tr>
							<tr class="odd gradeX">
								<td>11</td>
								<td>NOVEMBER</td>
								<td><input placeholder="Consultations" name="consultationsnov" id="consultationsnov" value="<?php echo $res_val->consultationsnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultnov" id="tele_consultnov" value="<?php echo $res_val->tele_consultnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opunov" id="opunov" value="<?php echo $res_val->opunov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etnov" id="fresh_cycle_etnov" value="<?php echo $res_val->fresh_cycle_etnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetnov" id="thawed_cycle_fetnov" value="<?php echo $res_val->thawed_cycle_fetnov; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etnov; ?></td>
								<td><input placeholder="IUI" name="iuinov" id="iuinov" value="<?php echo $res_val->iuinov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfnov" id="ivfnov" value="<?php echo $res_val->ivfnov; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsinov; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesenov" id="tesa_mtesenov" value="<?php echo $res_val->tesa_mtesenov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cyclenov" id="donor_cyclenov" value="<?php echo $res_val->donor_cyclenov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacynov" id="surrogacynov" value="<?php echo $res_val->surrogacynov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpnov" id="uterine_prpnov" value="<?php echo $res_val->uterine_prpnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpnov" id="ovarian_prpnov" value="<?php echo $res_val->ovarian_prpnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpnov" id="testicular_prpnov" value="<?php echo $res_val->testicular_prpnov; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpnov; ?></td>
								<td><?php echo $res_val->macs_qualis_candornov; ?></td>
								<td><?php echo $res_val->lahnov; ?></td>
								<td><?php echo $res_val->pgdnov; ?></td>
								<td><?php echo $res_val->embryo_gluenov; ?></td>
								<td><?php echo $res_val->sperm_mobilnov; ?></td>
								<td><?php echo $res_val->blastocyst_transfernov; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticnov" id="hysteroscopy_diagnosticnov" value="<?php echo $res_val->hysteroscopy_diagnosticnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopynov" id="laparoscopy_hysteroscopynov" value="<?php echo $res_val->laparoscopy_hysteroscopynov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivenov" id="beta_hcg_positivenov" value="<?php echo $res_val->beta_hcg_positivenov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activitynov" id="cardic_activitynov" value="<?php echo $res_val->cardic_activitynov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthnov" id="live_birthnov" value="<?php echo $res_val->live_birthnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="malenov" id="malenov" value="<?php echo $res_val->malenov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femalenov" id="femalenov" value="<?php echo $res_val->femalenov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingnov" id="ongoingnov" value="<?php echo $res_val->ongoing; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmnov" id="ivmnov" value="<?php echo $res_val->ivmnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="NAME OF PREGNANT COUPLES" name="pregnant_couplesnov" id="pregnant_couplesnov" value="<?php echo $res_val->pregnant_couplesnov; ?>" type="text" class="form-control"></td>
							    <td><?php echo $res_val->egg_freezingnov; ?></td>
								<td><?php echo $res_val->semen_freezingnov; ?></td>
								<td><?php echo $res_val->embryo_freezingnov; ?></td>
							</tr>
							<tr class="odd gradeX">
								<td>12</td>
								<td>DECEMBER</td>
								<td><input placeholder="Consultations" name="consultationsdec" id="consultationsdec" value="<?php echo $res_val->consultationsdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Tele Consult" name="tele_consultdec" id="tele_consultdec" value="<?php echo $res_val->tele_consultdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="OPU" name="opudec" id="opudec" value="<?php echo $res_val->opudec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FRESH CYCLE ET" name="fresh_cycle_etdec" id="fresh_cycle_etdec" value="<?php echo $res_val->fresh_cycle_etdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="THAWED CYCLE / FET" name="thawed_cycle_fetdec" id="thawed_cycle_fetdec" value="<?php echo $res_val->thawed_cycle_fetdec; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_etdec; ?></td>
								<td><input placeholder="IUI" name="iuidec" id="iuidec" value="<?php echo $res_val->iuidec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVF" name="ivfdec" id="ivfdec" value="<?php echo $res_val->ivfdec; ?>" type="text" class="form-control"></td>
								<td><?php echo $res_val->icsidec; ?></td>
								<td><input placeholder="TESA/MTESE" name="tesa_mtesedec" id="tesa_mtesedec" value="<?php echo $res_val->tesa_mtesedec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="DONOR CYCLE" name="donor_cycledec" id="donor_cycledec" value="<?php echo $res_val->donor_cycledec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SURROGACY" name="surrogacydec" id="surrogacydec" value="<?php echo $res_val->surrogacydec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Uterine PRP" name="uterine_prpdec" id="uterine_prpdec" value="<?php echo $res_val->uterine_prpdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Ovarian PRP" name="ovarian_prpdec" id="ovarian_prpdec" value="<?php echo $res_val->ovarian_prpdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Testicular PRP" name="testicular_prpdec" id="testicular_prpdec" value="<?php echo $res_val->testicular_prpdec; ?>" type="text" class="form-control"></td>
								<td><?php echo $total_prpdec; ?></td>
								<td><?php echo $res_val->macs_qualis_candordec; ?></td>
								<td><?php echo $res_val->lahdec; ?></td>
								<td><?php echo $res_val->pgddec; ?></td>
								<td><?php echo $res_val->embryo_gluedec; ?></td>
								<td><?php echo $res_val->sperm_mobildec; ?></td>
								<td><?php echo $res_val->blastocyst_transferdec; ?></td>
								<td><input placeholder="HYSTEROSCOPY DIAGNOSTIC" name="hysteroscopy_diagnosticdec" id="hysteroscopy_diagnosticdec" value="<?php echo $res_val->hysteroscopy_diagnosticdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAPAROSCOPY PLUS HYSTEROSCOPY" name="laparoscopy_hysteroscopydec" id="laparoscopy_hysteroscopydec" value="<?php echo $res_val->laparoscopy_hysteroscopydec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="BETA HCG POSITIVE" name="beta_hcg_positivedec" id="beta_hcg_positivedec" value="<?php echo $res_val->beta_hcg_positivedec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="CARDIAC ACTIVITY" name="cardic_activitydec" id="cardic_activitydec" value="<?php echo $res_val->cardic_activitydec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LIVE BIRTH " name="live_birthdec" id="live_birthdec" value="<?php echo $res_val->live_birthdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MALE" name="maledec" id="maledec" value="<?php echo $res_val->maledec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="FEMALE" name="femaledec" id="femaledec" value="<?php echo $res_val->femaledec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ONGOING" name="ongoingdec" id="ongoingdec" value="<?php echo $res_val->ongoingdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="IVM" name="ivmdec" id="ivmdec" value="<?php echo $res_val->ivmdec; ?>" type="text" class="form-control"></td>
								<td><textarea placeholder="NAME OF PREGNANT COUPLES" class="form-control" name="pregnant_couplesdec" id="pregnant_couplesdec"><?php echo $res_val->pregnant_couplesdec; ?></textarea>
							    <td><?php echo $res_val->egg_freezingdec; ?></td>
								<td><?php echo $res_val->semen_freezingdec; ?></td>
								<td><?php echo $res_val->embryo_freezingdec; ?></td>
							</tr>
							<tr class="odd gradeX">
								<th></th>
								<th>Total</th>
								<th><?php echo $total_consultations; ?></th>
								<th><?php echo $total_tele_consult; ?></th>
								<th><?php echo $total_opu; ?></th>
								<th><?php echo $total_fresh_cycle_et; ?></th>
								<th><?php echo $total_thawed_cycle_fet; ?></th>
								<th><?php echo $totalet; ?></th>
								<th><?php echo $total_iui; ?></th>
								<th><?php echo $total_ivf; ?></th>
								<th><?php echo $total_icsi; ?></th>
								<th><?php echo $total_tesa_mtese; ?></th>
								<th><?php echo $total_donor_cycle; ?></th>
								<th><?php echo $total_surrogacy; ?></th>
								<th><?php echo $total_uterine_prp; ?></th>
								<th><?php echo $total_ovarian_prp; ?></th>
								<th><?php echo $total_testicular_prp; ?></th>
								<th><?php echo $total_prp + $total_prpfeb + $total_prpmar + $total_prpapr + $total_prpmay + $total_prpjun + $total_prpjul + $total_prpaug + $total_prpsep + $total_prpoct + $total_prpnov + $total_prpdec; ?></th>
								<th><?php echo $total_macs_qualis_candor; ?></th>
								<th><?php echo $total_lah; ?></th>
								<th><?php echo $total_pgd; ?></th>
								<th><?php echo $total_embryo_glue; ?></th>
								<th><?php echo $total_sperm_mobil; ?></th>
								<th><?php echo $total_blastocyst_transfer; ?></th>
								<th><?php echo $total_hysteroscopy_diagnostic; ?></th>
								<th><?php echo $total_laparoscopy_hysteroscopy; ?></th>
								<th><?php echo $total_beta_hcg_positive; ?></th>
								<th><?php echo $total_cardic_activity; ?></th>
								<th><?php echo $total_live_birth; ?></th>
								<th><?php echo $total_male; ?></th>
								<th><?php echo $total_female; ?></th>
								<th><?php echo $total_ongoing ?></th>
								<th><?php echo $total_ivm ?></th>
								<th><?php echo $total_egg_freezing; ?></th>
								<th><?php echo $total_semen_freezing; ?></th>
								<th><?php echo $total_embryo_freezing; ?></th>
							</tr>
                </tbody>		
		    </table>
          </div>	
<input type="submit" name="submit" value="submit"> 
</div>  
</div>
</div>  
</div>  
</form>
</div>
 <?php } ?>
<style>
.table-bordered > tbody > tr > td {
    padding: 0px!important;
}
input[type=text] {
    margin: 0px;
    border-bottom: 0px;
	height: 20px;
	text-align: center;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td {
  border: 1px solid #000;
  text-align: center;
}
form {
    padding-left: 10px;
    margin-bottom: 4px;
}
select {
    display: block!important;
}
textarea.form-control {
    height: 30px!important;
    width: 300px!important;
}
</style>    