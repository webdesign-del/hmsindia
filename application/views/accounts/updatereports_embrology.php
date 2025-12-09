<?php
if (isset($_POST['submit'])) {
    extract($_POST);
	$ID = $_GET['ID'];
	$sql2 = "UPDATE `hms_clinical_reports` SET `iui`='$iui',`icsi`='$icsi',`macs_qualis_candor`='$macs_qualis_candor',`lah`='$lah',`pgd`='$pgd',`embryo_glue`='$embryo_glue',`sperm_mobil`='$sperm_mobil',`blastocyst_transfer`='$blastocyst_transfer',`egg_freezing`='$egg_freezing',`semen_freezing`='$semen_freezing',`embryo_freezing`='$embryo_freezing',`vilegg_freezing`='$vilegg_freezing',`embryo_freezing`='$embryo_freezing',
	    `iuifeb`='$iuifeb',`icsifeb`='$icsifeb',`macs_qualis_candorfeb`='$macs_qualis_candorfeb',`lahfeb`='$lahfeb',`pgdfeb`='$pgdfeb',`embryo_gluefeb`='$embryo_gluefeb',`sperm_mobilfeb`='$sperm_mobilfeb',`blastocyst_transferfeb`='$blastocyst_transferfeb',`egg_freezingfeb`='$egg_freezingfeb',`semen_freezingfeb`='$semen_freezingfeb',`embryo_freezingfeb`='$embryo_freezingfeb',`vilegg_freezingfeb`='$vilegg_freezingfeb',`vilembryo_freezingfeb`='$vilembryo_freezingfeb',
		`iuimar`='$iuimar',`icsimar`='$icsimar',`macs_qualis_candormar`='$macs_qualis_candormar',`lahmar`='$lahmar',`pgdmar`='$pgdmar',`embryo_gluemar`='$embryo_gluemar',`sperm_mobilmar`='$sperm_mobilmar',`blastocyst_transfermar`='$blastocyst_transfermar',`egg_freezingmar`='$egg_freezingmar',`semen_freezingmar`='$semen_freezingmar',`embryo_freezingmar`='$embryo_freezingmar',`vilegg_freezingmar`='$vilegg_freezingmar',`vilembryo_freezingmar`='$vilembryo_freezingmar',
		`iuiapr`='$iuiapr',`icsiapr`='$icsiapr',`macs_qualis_candorapr`='$macs_qualis_candorapr',`lahapr`='$lahapr',`pgdapr`='$pgdapr',`embryo_glueapr`='$embryo_glueapr',`sperm_mobilapr`='$sperm_mobilapr',`blastocyst_transferapr`='$blastocyst_transferapr',`egg_freezingapr`='$egg_freezingapr',`semen_freezingapr`='$semen_freezingapr',`embryo_freezingapr`='$embryo_freezingapr',`vilegg_freezingapr`='$vilegg_freezingapr',`vilembryo_freezingapr`='$vilembryo_freezingapr',
		`iuimay`='$iuimay',`icsimay`='$icsimay',`macs_qualis_candormay`='$macs_qualis_candormay',`lahmay`='$lahmay',`pgdmay`='$pgdmay',`embryo_gluemay`='$embryo_gluemay',`sperm_mobilmay`='$sperm_mobilmay',`blastocyst_transfermay`='$blastocyst_transfermay',`egg_freezingmay`='$egg_freezingmay',`semen_freezingmay`='$semen_freezingmay',`embryo_freezingmay`='$embryo_freezingmay',`vilegg_freezingmay`='$vilegg_freezingmay',`vilembryo_freezingmay`='$vilembryo_freezingmay',
		`iuijun`='$iuijun',`icsijun`='$icsijun',`macs_qualis_candorjun`='$macs_qualis_candorjun',`lahjun`='$lahjun',`pgdjun`='$pgdjun',`embryo_gluejun`='$embryo_gluejun',`sperm_mobiljun`='$sperm_mobiljun',`blastocyst_transferjun`='$blastocyst_transferjun',`egg_freezingjun`='$egg_freezingjun',`semen_freezingjun`='$semen_freezingjun',`embryo_freezingjun`='$embryo_freezingjun',`vilegg_freezingjun`='$vilegg_freezingjun',`vilembryo_freezingjun`='$vilembryo_freezingjun',
		`iuijul`='$iuijul',`icsijul`='$icsijul',`macs_qualis_candorjul`='$macs_qualis_candorjul',`lahjul`='$lahjul',`pgdjul`='$pgdjul',`embryo_gluejul`='$embryo_gluejul',`sperm_mobiljul`='$sperm_mobiljul',`blastocyst_transferjul`='$blastocyst_transferjul',`egg_freezingjul`='$egg_freezingjul',`semen_freezingjul`='$semen_freezingjul',`embryo_freezingjul`='$embryo_freezingjul',`vilegg_freezingjul`='$vilegg_freezingjul',`vilembryo_freezingjul`='$vilembryo_freezingjul',
		`iuiaug`='$iuiaug',`icsiaug`='$icsiaug',`macs_qualis_candoraug`='$macs_qualis_candoraug',`lahaug`='$lahaug',`pgdaug`='$pgdaug',`embryo_glueaug`='$embryo_glueaug',`sperm_mobilaug`='$sperm_mobilaug',`blastocyst_transferaug`='$blastocyst_transferaug',`egg_freezingaug`='$egg_freezingaug',`semen_freezingaug`='$semen_freezingaug',`embryo_freezingaug`='$embryo_freezingaug',`vilegg_freezingaug`='$vilegg_freezingaug',`vilembryo_freezingaug`='$vilembryo_freezingaug',
		`iuisep`='$iuisep',`icsisep`='$icsisep',`macs_qualis_candorsep`='$macs_qualis_candorsep',`lahsep`='$lahsep',`pgdsep`='$pgdsep',`embryo_gluesep`='$embryo_gluesep',`sperm_mobilsep`='$sperm_mobilsep',`blastocyst_transfersep`='$blastocyst_transfersep',`egg_freezingsep`='$egg_freezingsep',`semen_freezingsep`='$semen_freezingsep',`embryo_freezingsep`='$embryo_freezingsep',`vilegg_freezingsep`='$vilegg_freezingsep',`vilembryo_freezingsep`='$vilembryo_freezingsep',
		`iuioct`='$iuioct',`icsioct`='$icsioct',`macs_qualis_candoroct`='$macs_qualis_candoroct',`lahoct`='$lahoct',`pgdoct`='$pgdoct',`embryo_glueoct`='$embryo_glueoct',`sperm_mobiloct`='$sperm_mobiloct',`blastocyst_transferoct`='$blastocyst_transferoct',`egg_freezingoct`='$egg_freezingoct',`semen_freezingoct`='$semen_freezingoct',`embryo_freezingoct`='$embryo_freezingoct',`vilegg_freezingoct`='$vilegg_freezingoct',`vilembryo_freezingoct`='$vilembryo_freezingoct',
		`iuinov`='$iuinov',`icsinov`='$icsinov',`macs_qualis_candornov`='$macs_qualis_candornov',`lahnov`='$lahnov',`pgdnov`='$pgdnov',`embryo_gluenov`='$embryo_gluenov',`sperm_mobilnov`='$sperm_mobilnov',`blastocyst_transfernov`='$blastocyst_transfernov',`egg_freezingnov`='$egg_freezingnov',`semen_freezingnov`='$semen_freezingnov',`embryo_freezingnov`='$embryo_freezingnov',`vilegg_freezingnov`='$vilegg_freezingnov',`vilembryo_freezingnov`='$vilembryo_freezingnov',
		`iuidec`='$iuidec',`icsidec`='$icsidec',`macs_qualis_candordec`='$macs_qualis_candordec',`lahdec`='$lahdec',`pgddec`='$pgddec',`embryo_gluedec`='$embryo_gluedec',`sperm_mobildec`='$sperm_mobildec',`blastocyst_transferdec`='$blastocyst_transferdec',`egg_freezingdec`='$egg_freezingdec',`semen_freezingdec`='$semen_freezingdec',`embryo_freezingdec`='$embryo_freezingdec',`vilegg_freezingdec`='$vilegg_freezingdec',`vilembryo_freezingdec`='$vilembryo_freezingdec' WHERE ID = '$ID'  ";
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
                $total_etjul = $res_val->fresh_cycle_etjul + $res_val->thawed_cycle_fetjul; 
				$total_etaug = $res_val->fresh_cycle_etaug + $res_val->thawed_cycle_fetaug;
				$total_etsep = $res_val->fresh_cycle_etsep + $res_val->thawed_cycle_fetsep;
                $total_etoct = $res_val->fresh_cycle_etoct + $res_val->thawed_cycle_fetapr; 
				$total_etnov = $res_val->fresh_cycle_etnov + $res_val->thawed_cycle_fetnov;
				$total_etdec = $res_val->fresh_cycle_etdec + $res_val->thawed_cycle_fetdec;
				
				$total_et = 0; $total_prpfeb = 0; $total_prpmar = 0;$total_prpapr = 0; $total_prpmay = 0; $total_prpjun = 0; $total_prpjul = 0; $total_prpaug = 0; $total_prpsep = 0; $total_prpoct = 0; $total_prpnov = 0; $total_prpdec = 0;
			
			    $total_prp = $res_val->ovarian_prp + $res_val->testicular_prp;
				$total_prpfeb = $res_val->ovarian_prpfeb + $res_val->testicular_prpfeb;
				$total_prpmar = $res_val->ovarian_prpmar + $res_val->testicular_prpmar;
                $total_prpapr = $res_val->ovarian_prpapr + $res_val->testicular_prpapr;
				$total_prpmay = $res_val->ovarian_prpmay + $res_val->testicular_prpmay; 
				$total_prpjun = $res_val->ovarian_prpjun + $res_val->testicular_prpjun;
                $total_prpjul = $res_val->ovarian_prpjul + $res_val->testicular_prpjul; 
				$total_prpaug = $res_val->ovarian_prpaug + $res_val->testicular_prpaug;
				$total_prpsep = $res_val->ovarian_prpsep + $res_val->testicular_prpsep;
                $total_prpoct = $res_val->ovarian_prpoct + $res_val->testicular_prpoct; 
				$total_prpnov = $res_val->ovarian_prpnov + $res_val->testicular_prpnov;
				$total_prpdec = $res_val->ovarian_prpdec + $res_val->testicular_prpdec;
                
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
               // $total_egg_freezing = $res_val->egg_freezing + $res_val->egg_freezingfeb + $res_val->egg_freezingmar + $res_val->egg_freezingapr + $res_val->egg_freezingmay + $res_val->egg_freezingjun + $res_val->egg_freezingjul + $res_val->egg_freezingaug + $res_val->egg_freezingsep + $res_val->egg_freezingoct + $res_val->egg_freezingnov + $res_val->egg_freezingdec;
				
				$total_egg_freezing = 
    (int)$res_val->egg_freezing + 
    (int)$res_val->egg_freezingfeb + 
    (int)$res_val->egg_freezingmar + 
    (int)$res_val->egg_freezingapr + 
    (int)$res_val->egg_freezingmay + 
    (int)$res_val->egg_freezingjun + 
    (int)$res_val->egg_freezingjul + 
    (int)$res_val->egg_freezingaug + 
    (int)$res_val->egg_freezingsep + 
    (int)$res_val->egg_freezingoct + 
    (int)$res_val->egg_freezingnov + 
    (int)$res_val->egg_freezingdec;
				
				$total_semen_freezing = 
    (int)$res_val->semen_freezing + 
    (int)$res_val->semen_freezingfeb + 
    (int)$res_val->semen_freezingmar + 
    (int)$res_val->semen_freezingapr + 
    (int)$res_val->semen_freezingmay + 
    (int)$res_val->semen_freezingjun + 
    (int)$res_val->semen_freezingjul + 
    (int)$res_val->semen_freezingaug + 
    (int)$res_val->semen_freezingsep + 
    (int)$res_val->semen_freezingoct + 
    (int)$res_val->semen_freezingnov + 
    (int)$res_val->semen_freezingdec;
	
$total_embryo_freezing = 
    (int)$res_val->embryo_freezing + 
    (int)$res_val->embryo_freezingfeb + 
    (int)$res_val->embryo_freezingmar + 
    (int)$res_val->embryo_freezingapr + 
    (int)$res_val->embryo_freezingmay + 
    (int)$res_val->embryo_freezingjun + 
    (int)$res_val->embryo_freezingjul + 
    (int)$res_val->embryo_freezingaug + 
    (int)$res_val->embryo_freezingsep + 
    (int)$res_val->embryo_freezingoct + 
    (int)$res_val->embryo_freezingnov + 
    (int)$res_val->embryo_freezingdec;
	
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
				  <th></th>
				  <th>IUI</th>
				  <th>ICSI</th>
				  <th>MACS/QUALIS/ Candor</th>
				  <th>LAH</th>
                  <th>PGD</th>
                  <th>EMBRYO GLUE</th>
				  <th>Sperm Mobil</th>
				  <th>Blastocyst</th>
				  <th>EGG FREEZING</th>
				  <th>NO VIAL</th>
				  <th>SEMEN FREEZING</th>
				  <th>EMBRYO FREEZING</th>
				  <th>NO VIAL</th>
                </tr>
              </thead>
			        
                        <tbody id="procedure_result">
                            <tr class="odd gradeX">
							    <td style="text-align:left;">JANUARY</td>
								<td><input placeholder="IUI" name="iui" id="iui" value="<?php echo $res_val->iui; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsi" id="icsi" value="<?php echo $res_val->icsi; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candor" id="macs_qualis_candor" value="<?php echo $res_val->macs_qualis_candor; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lah" id="lah" value="<?php echo $res_val->lah; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgd" id="pgd" value="<?php echo $res_val->pgd; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_glue" id="embryo_glue" value="<?php echo $res_val->embryo_glue; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobil" id="sperm_mobil" value="<?php echo $res_val->sperm_mobil; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transfer" id="blastocyst_transfer" value="<?php echo $res_val->blastocyst_transfer; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezing" id="egg_freezing" value="<?php echo $res_val->egg_freezing; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezing" id="vilegg_freezing" value="<?php echo $res_val->vilegg_freezing; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezing" id="semen_freezing" value="<?php echo $res_val->semen_freezing; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezing" id="embryo_freezing" value="<?php echo $res_val->embryo_freezing; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezing" id="vilembryo_freezing" value="<?php echo $res_val->vilembryo_freezing; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">FEBRUARY</td>
								<td><input placeholder="IUI" name="iuifeb" id="iuifeb" value="<?php echo $res_val->iuifeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsifeb" id="icsifeb" value="<?php echo $res_val->icsifeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candorfeb" id="macs_qualis_candorfeb" value="<?php echo $res_val->macs_qualis_candorfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahfeb" id="lahfeb" value="<?php echo $res_val->lahfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdfeb" id="pgdfeb" value="<?php echo $res_val->pgdfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluefeb" id="embryo_gluefeb" value="<?php echo $res_val->embryo_gluefeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilfeb" id="sperm_mobilfeb" value="<?php echo $res_val->sperm_mobilfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferfeb" id="blastocyst_transferfeb" value="<?php echo $res_val->blastocyst_transferfeb; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingfeb" id="egg_freezingfeb" value="<?php echo $res_val->egg_freezingfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingfeb" id="vilegg_freezingfeb" value="<?php echo $res_val->vilegg_freezingfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingfeb" id="semen_freezingfeb" value="<?php echo $res_val->semen_freezingfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingfeb" id="embryo_freezingfeb" value="<?php echo $res_val->embryo_freezingfeb; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingfeb" id="vilembryo_freezingfeb" value="<?php echo $res_val->vilembryo_freezingfeb; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">MARCH</td>
								<td><input placeholder="IUI" name="iuimar" id="iuimar" value="<?php echo $res_val->iuimar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsimar" id="icsimar" value="<?php echo $res_val->icsimar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candormar" id="macs_qualis_candormar" value="<?php echo $res_val->macs_qualis_candormar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahmar" id="lahmar" value="<?php echo $res_val->lahmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdmar" id="pgdmar" value="<?php echo $res_val->pgdmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluemar" id="embryo_gluemar" value="<?php echo $res_val->embryo_gluemar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilmar" id="sperm_mobilmar" value="<?php echo $res_val->sperm_mobilmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transfermar" id="blastocyst_transfermar" value="<?php echo $res_val->blastocyst_transfermar; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingmar" id="egg_freezingmar" value="<?php echo $res_val->egg_freezingmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingmar" id="vilegg_freezingmar" value="<?php echo $res_val->vilegg_freezingmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingmar" id="semen_freezingmar" value="<?php echo $res_val->semen_freezingmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingmar" id="embryo_freezingmar" value="<?php echo $res_val->embryo_freezingmar; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingmar" id="vilembryo_freezingmar" value="<?php echo $res_val->vilembryo_freezingmar; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">APRIL</td>
								<td><input placeholder="IUI" name="iuiapr" id="iuiapr" value="<?php echo $res_val->iuiapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsiapr" id="icsiapr" value="<?php echo $res_val->icsiapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candorapr" id="macs_qualis_candorapr" value="<?php echo $res_val->macs_qualis_candorapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahapr" id="lahapr" value="<?php echo $res_val->lahapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdapr" id="pgdapr" value="<?php echo $res_val->pgdapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_glueapr" id="embryo_glueapr" value="<?php echo $res_val->embryo_glueapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilapr" id="sperm_mobilapr" value="<?php echo $res_val->sperm_mobilapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferapr" id="blastocyst_transferapr" value="<?php echo $res_val->blastocyst_transferapr; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingapr" id="egg_freezingapr" value="<?php echo $res_val->egg_freezingapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingapr" id="vilegg_freezingapr" value="<?php echo $res_val->vilegg_freezingapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingapr" id="semen_freezingapr" value="<?php echo $res_val->semen_freezingapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingapr" id="embryo_freezingapr" value="<?php echo $res_val->embryo_freezingapr; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingapr" id="vilembryo_freezingapr" value="<?php echo $res_val->vilembryo_freezingapr; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">MAY</td>
								<td><input placeholder="IUI" name="iuimay" id="iuimay" value="<?php echo $res_val->iuimay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsimay" id="icsimay" value="<?php echo $res_val->icsimay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candormay" id="macs_qualis_candormay" value="<?php echo $res_val->macs_qualis_candormay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahmay" id="lahmay" value="<?php echo $res_val->lahmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdmay" id="pgdmay" value="<?php echo $res_val->pgdmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluemay" id="embryo_gluemay" value="<?php echo $res_val->embryo_gluemay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilmay" id="sperm_mobilmay" value="<?php echo $res_val->sperm_mobilmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transfermay" id="blastocyst_transfermay" value="<?php echo $res_val->blastocyst_transfermay; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingmay" id="egg_freezingmay" value="<?php echo $res_val->egg_freezingmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingmay" id="vilegg_freezingmay" value="<?php echo $res_val->vilegg_freezingmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingmay" id="semen_freezingmay" value="<?php echo $res_val->semen_freezingmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingmay" id="embryo_freezingmay" value="<?php echo $res_val->embryo_freezingmay; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingmay" id="vilembryo_freezingmay" value="<?php echo $res_val->vilembryo_freezingmay; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">JUNE</td>
								<td><input placeholder="IUI" name="iuijun" id="iuijun" value="<?php echo $res_val->iuijun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsijun" id="icsijun" value="<?php echo $res_val->icsijun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candorjun" id="macs_qualis_candorjun" value="<?php echo $res_val->macs_qualis_candorjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahjun" id="lahjun" value="<?php echo $res_val->lahjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdjun" id="pgdjun" value="<?php echo $res_val->pgdjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluejun" id="embryo_gluejun" value="<?php echo $res_val->embryo_gluejun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobiljun" id="sperm_mobiljun" value="<?php echo $res_val->sperm_mobiljun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferjun" id="blastocyst_transferjun" value="<?php echo $res_val->blastocyst_transferjun; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingjun" id="egg_freezingjun" value="<?php echo $res_val->egg_freezingjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingjun" id="vilegg_freezingjun" value="<?php echo $res_val->vilegg_freezingjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingjun" id="semen_freezingjun" value="<?php echo $res_val->semen_freezingjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingjun" id="embryo_freezingjun" value="<?php echo $res_val->embryo_freezingjun; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingjun" id="vilembryo_freezingjun" value="<?php echo $res_val->vilembryo_freezingjun; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">JULY</td>
								<td><input placeholder="IUI" name="iuijul" id="iuijul" value="<?php echo $res_val->iuijul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsijul" id="icsijul" value="<?php echo $res_val->icsijul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candorjul" id="macs_qualis_candorjul" value="<?php echo $res_val->macs_qualis_candorjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahjul" id="lahjul" value="<?php echo $res_val->lahjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdjul" id="pgdjul" value="<?php echo $res_val->pgdjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluejul" id="embryo_gluejul" value="<?php echo $res_val->embryo_gluejul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobiljul" id="sperm_mobiljul" value="<?php echo $res_val->sperm_mobiljul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferjul" id="blastocyst_transferjul" value="<?php echo $res_val->blastocyst_transferjul; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingjul" id="egg_freezingjul" value="<?php echo $res_val->egg_freezingjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingjul" id="vilegg_freezingjul" value="<?php echo $res_val->vilegg_freezingjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingjul" id="semen_freezingjul" value="<?php echo $res_val->semen_freezingjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingjul" id="embryo_freezingjul" value="<?php echo $res_val->embryo_freezingjul; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingjul" id="vilembryo_freezingjul" value="<?php echo $res_val->vilembryo_freezingjul; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">AUGUST</td>
								<td><input placeholder="IUI" name="iuiaug" id="iuiaug" value="<?php echo $res_val->iuiaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsiaug" id="icsiaug" value="<?php echo $res_val->icsiaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candoraug" id="macs_qualis_candoraug" value="<?php echo $res_val->macs_qualis_candoraug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahaug" id="lahaug" value="<?php echo $res_val->lahaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdaug" id="pgdaug" value="<?php echo $res_val->pgdaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_glueaug" id="embryo_glueaug" value="<?php echo $res_val->embryo_glueaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilaug" id="sperm_mobilaug" value="<?php echo $res_val->sperm_mobilaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferaug" id="blastocyst_transferaug" value="<?php echo $res_val->blastocyst_transferaug; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingaug" id="egg_freezingaug" value="<?php echo $res_val->egg_freezingaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingaug" id="vilegg_freezingaug" value="<?php echo $res_val->vilegg_freezingaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingaug" id="semen_freezingaug" value="<?php echo $res_val->semen_freezingaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingaug" id="embryo_freezingaug" value="<?php echo $res_val->embryo_freezingaug; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingaug" id="vilembryo_freezingaug" value="<?php echo $res_val->vilembryo_freezingaug; ?>" type="text" class="form-control"></td>
							</tr>
                            <tr class="odd gradeX">
							    <td style="text-align:left;">SEPTEMBER</td>
								<td><input placeholder="IUI" name="iuisep" id="iuisep" value="<?php echo $res_val->iuisep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsisep" id="icsisep" value="<?php echo $res_val->icsisep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candorsep" id="macs_qualis_candorsep" value="<?php echo $res_val->macs_qualis_candorsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahsep" id="lahsep" value="<?php echo $res_val->lahsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdsep" id="pgdsep" value="<?php echo $res_val->pgdsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluesep" id="embryo_gluesep" value="<?php echo $res_val->embryo_gluesep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilsep" id="sperm_mobilsep" value="<?php echo $res_val->sperm_mobilsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transfersep" id="blastocyst_transfersep" value="<?php echo $res_val->blastocyst_transfersep; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingsep" id="egg_freezingsep" value="<?php echo $res_val->egg_freezingsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingsep" id="vilegg_freezingsep" value="<?php echo $res_val->vilegg_freezingsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingsep" id="semen_freezingsep" value="<?php echo $res_val->semen_freezingsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingsep" id="embryo_freezingsep" value="<?php echo $res_val->embryo_freezingsep; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingsep" id="vilembryo_freezingsep" value="<?php echo $res_val->vilembryo_freezingsep; ?>" type="text" class="form-control"></td>
							</tr>	
							<tr class="odd gradeX">
							    <td style="text-align:left;">OCTOBER</td>
								<td><input placeholder="IUI" name="iuioct" id="iuioct" value="<?php echo $res_val->iuioct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsioct" id="icsioct" value="<?php echo $res_val->icsioct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candoroct" id="macs_qualis_candoroct" value="<?php echo $res_val->macs_qualis_candoroct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahoct" id="lahoct" value="<?php echo $res_val->lahoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdoct" id="pgdoct" value="<?php echo $res_val->pgdoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_glueoct" id="embryo_glueoct" value="<?php echo $res_val->embryo_glueoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobiloct" id="sperm_mobiloct" value="<?php echo $res_val->sperm_mobiloct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferoct" id="blastocyst_transferoct" value="<?php echo $res_val->blastocyst_transferoct; ?>" type="text" class="form-control"></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingoct" id="egg_freezingoct" value="<?php echo $res_val->egg_freezingoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingoct" id="vilegg_freezingoct" value="<?php echo $res_val->vilegg_freezingoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingoct" id="semen_freezingoct" value="<?php echo $res_val->semen_freezingoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingoct" id="embryo_freezingoct" value="<?php echo $res_val->embryo_freezingoct; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingoct" id="vilembryo_freezingoct" value="<?php echo $res_val->vilembryo_freezingoct; ?>" type="text" class="form-control"></td>
							</tr>
							<tr class="odd gradeX">
							    <td style="text-align:left;">NOVEMBER</td>
								<td><input placeholder="IUI" name="iuinov" id="iuinov" value="<?php echo $res_val->iuinov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsinov" id="icsinov" value="<?php echo $res_val->icsinov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candornov" id="macs_qualis_candornov" value="<?php echo $res_val->macs_qualis_candornov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="LAH" name="lahnov" id="lahnov" value="<?php echo $res_val->lahnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="PGD" name="pgdnov" id="pgdnov" value="<?php echo $res_val->pgdnov; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluenov" id="embryo_gluenov" value="<?php echo $res_val->embryo_gluenov; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobilnov" id="sperm_mobilnov" value="<?php echo $res_val->sperm_mobilnov; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transfernov" id="blastocyst_transfernov" value="<?php echo $res_val->blastocyst_transfernov; ?>" type="text" class="form-control" ></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingnov" id="egg_freezingnov" value="<?php echo $res_val->egg_freezingnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingnov" id="vilegg_freezingnov" value="<?php echo $res_val->vilegg_freezingnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingnov" id="semen_freezingnov" value="<?php echo $res_val->semen_freezingnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingnov" id="embryo_freezingnov" value="<?php echo $res_val->embryo_freezingnov; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingnov" id="vilembryo_freezingnov" value="<?php echo $res_val->vilembryo_freezingnov; ?>" type="text" class="form-control"></td>
							</tr>
							<tr class="odd gradeX">
							    <td style="text-align:left;">DECEMBER</td>
								<td><input placeholder="IUI" name="iuidec" id="iuidec" value="<?php echo $res_val->iuidec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="ICSI" name="icsidec" id="icsidec" value="<?php echo $res_val->icsidec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="MACS/QUALIS/ Candor" name="macs_qualis_candordec" id="macs_qualis_candordec" value="<?php echo $res_val->macs_qualis_candordec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="LAH" name="lahdec" id="lahdec" value="<?php echo $res_val->lahdec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="PGD" name="pgddec" id="pgddec" value="<?php echo $res_val->pgddec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="EMBRYO GLUE" name="embryo_gluedec" id="embryo_gluedec" value="<?php echo $res_val->embryo_gluedec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="Sperm Mobil" name="sperm_mobildec" id="sperm_mobildec" value="<?php echo $res_val->sperm_mobildec; ?>" type="text" class="form-control" ></td>
								<td><input placeholder="Blastocyst transfer " name="blastocyst_transferdec" id="blastocyst_transferdec" value="<?php echo $res_val->blastocyst_transferdec; ?>" type="text" class="form-control" ></td>
							    <td><input placeholder="EGG FREEZING" name="egg_freezingdec" id="egg_freezingdec" value="<?php echo $res_val->egg_freezingdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EGG FREEZING" name="vilegg_freezingdec" id="vilegg_freezingdec" value="<?php echo $res_val->vilegg_freezingdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="SEMEN FREEZING" name="semen_freezingdec" id="semen_freezingdec" value="<?php echo $res_val->semen_freezingdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="embryo_freezingdec" id="embryo_freezingdec" value="<?php echo $res_val->embryo_freezingdec; ?>" type="text" class="form-control"></td>
								<td><input placeholder="EMBRYO FREEZING" name="vilembryo_freezingdec" id="vilembryo_freezingdec" value="<?php echo $res_val->vilembryo_freezingdec; ?>" type="text" class="form-control"></td>
							</tr>
							<tr class="odd gradeX">
								<th>Total</th>
								<th><?php echo $total_iui; ?></th>
								<th><?php echo $total_icsi; ?></th>
								<th><?php echo $total_macs_qualis_candor; ?></th>
								<th><?php echo $total_lah; ?></th>
								<th><?php echo $total_pgd; ?></th>
								<th><?php echo $total_embryo_glue; ?></th>
								<th><?php echo $total_sperm_mobil; ?></th>
								<th><?php echo $total_blastocyst_transfer; ?></th>
								<th><?php echo $total_egg_freezing; ?></th>
								<th></th>
								<th><?php echo $total_semen_freezing; ?></th>
								<th><?php echo $total_embryo_freezing; ?></th>
								<th></th>
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