 <?php $all_method =&get_instance(); ?>

    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Clinical Reports  </h3></div>
       <div class="clearfix"></div>
	   <?php if($_SESSION['logged_administrator']){ ?>
	    <form action=""<?php echo base_url().'accounts/clinical_reports'; ?>" method="get">
		     <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Filter by billing at</label>
                <select class="form-control" id="center" name="center">
                	<option value=''>--Select From--</option>
                    <?php $all_centers = $all_method->get_all_centers();
						            foreach($all_centers as $key => $val){ //var_dump($val);die;
                          if($billing_at == $val['center_number']){
                            echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
                          }else{
		                        echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
                          }
                    	  } 
					    ?>
                </select>
            </div>
			<div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Payment Type </label>
                <select class="form-control" id="year" name="year">
				    <option value=''>--Select From--</option>
                    <option value="2023" mode="2023">2023</option>
					<option value="2022" mode="2022">2022</option>
                    <option value="2021" mode="2021">2021</option>
                    <option value="2020" mode="2020">2020</option>
                    <option value="2019" mode="2019">2019</option>
                    <option value="2018" mode="2018">2018</option>
					<option value="2017" mode="2017">2017</option>
					<option value="2016" mode="2016">2016</option>
				</select>
            </div>
           <div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'accounts/clinical_reports'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            </form>  
	   <?php } ?>
        <div class="clearfix"></div>
	    <div class="card-content">
          <div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
    <thead>
        <tr>
            <th>YEAR</th>
            <th>TOTAL CONSULTATION</th>
        </tr>
    </thead>
    <tbody id="procedure_result">
        <?php 
        // 1. Fetch the Center Name once for the logged-in doctor
        $center_id = $_SESSION['logged_doctor']['center'];
        $sql_center = "SELECT center_name FROM hms_centers WHERE center_number = " . $this->db->escape($center_id);
        $center_query = $this->db->query($sql_center);
        $center_row = $center_query->row();
        $center_name = ($center_row) ? $center_row->center_name : "Unknown Center";

        // 2. Fetch Yearly Appointment Statistics
        $sql2 = "SELECT 
                    YEAR(`appoitmented_date`) AS appointment_year, 
                    COUNT(*) AS total_appointments
                 FROM `hms_appointments` 
                 WHERE `status` = 'consultation_done' 
                 AND `appoitment_for` = " . $this->db->escape($center_id) . "
                 GROUP BY YEAR(`appoitmented_date`)
                 ORDER BY appointment_year DESC";

        $query = $this->db->query($sql2);
        $yearly_results = $query->result();  

        // 3. Loop through yearly results to create merged rows
        foreach ($yearly_results as $row) {
        ?>
            <tr class="odd gradeX">
                <td><!--<a href="updatereports?ID=<?php echo $center_id; ?>&year=<?php echo $row->appointment_year; ?>"></a>--><?php echo $row->appointment_year; ?></td>
                
                <td><?php echo $row->total_appointments; ?></td>
                
                </tr>
        <?php } ?>
    </tbody>
</table>
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
				 
				  <th>CENETR NAME</th>
				  <th>YEAR</th>
				  <?php if($_SESSION['logged_embryologist']){ ?>
				  <th>ICSI</th>
				  <th>MACS/QUALIS/ Candor</th>
				  <th>LAH</th>
                  <th>PGD</th>
                  <th>EMBRYO GLUE</th>
				  <th>Sperm Mobil</th>
				  <th>Blastocyst</th>
				  <th>EGG FREEZING</th>
				  <th>SEMEN FREEZING</th>
				  <th>EMBRYO FREEZING</th>
				   <?php }else{ ?>
				  
                  <th>CENETR CONSULTATIONS</th>
                  <th>Tele Consult</th>
                  <th>TOTAL CONSULTATION</th>
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
				  <th>EGG FREEZING</th>
				  <th>SEMEN FREEZING</th>
				  <th>EMBRYO FREEZING</th>
				 
				  <?php } ?>
                </tr>
				
              </thead>
              <tbody id="procedure_result">
				
              <?php 
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
				
			    foreach($procedure_result as $ky => $vl){
				  $total_consultations = $vl['consultations'] + $vl['consultationsfeb'] + $vl['consultationsmar']+ $vl['consultationsapr'] + $vl['consultationsmay'] + $vl['consultationsjun'] + $vl['consultationsjul'] + $vl['consultationsaug'] + $vl['consultationssep'] + $vl['consultationsoct'] + $vl['consultationsnov'] + $vl['consultationsdec']; 
                  $total_tele_consult = $vl['tele_consult'] + $vl['tele_consultfeb'] + $vl['tele_consultmar']+ $vl['tele_consultapr'] + $vl['tele_consultmay'] + $vl['tele_consultjun'] + $vl['tele_consultjul'] + $vl['tele_consultaug'] + $vl['tele_consultsep'] + $vl['tele_consultoct'] + $vl['tele_consultnov'] + $vl['tele_consultdec']; 
                  $total_opu = $vl['opu'] + $vl['opufeb'] + $vl['opumar']+ $vl['opuapr'] + $vl['opumay'] + $vl['opujun'] + $vl['opujul'] + $vl['opuaug'] + $vl['opusep'] + $vl['opuoct'] + $vl['opunov'] + $vl['opudec']; 
                  $total_fresh_cycle_et = $vl['fresh_cycle_et'] + $vl['fresh_cycle_etfeb'] + $vl['fresh_cycle_etmar']+ $vl['fresh_cycle_etapr'] + $vl['fresh_cycle_etmay'] + $vl['fresh_cycle_etjun'] + $vl['fresh_cycle_etjul'] + $vl['fresh_cycle_etaug'] + $vl['fresh_cycle_etsep'] + $vl['fresh_cycle_etoct'] + $vl['fresh_cycle_etnov'] + $vl['fresh_cycle_etdec'];
			      $total_thawed_cycle_fet = $vl['thawed_cycle_fet'] + $vl['thawed_cycle_fetfeb'] + $vl['thawed_cycle_fetmar']+ $vl['thawed_cycle_fetapr'] + $vl['thawed_cycle_fetmay'] + $vl['thawed_cycle_fetjun'] + $vl['thawed_cycle_fetjul'] + $vl['thawed_cycle_fetaug'] + $vl['thawed_cycle_fetsep'] + $vl['thawed_cycle_fetoct'] + $vl['thawed_cycle_fetnov'] + $vl['thawed_cycle_fetdec'];
				  $total_iui = $vl['iui'] + $vl['iuifeb'] + $vl['iuimar']+ $vl['iuiapr'] + $vl['iuimay'] + $vl['iuijun'] + $vl['iuijul'] + $vl['iuiaug'] + $vl['iuisep'] + $vl['iuioct'] + $vl['iuinov'] + $vl['iuidec'];
				  $total_ivf = $vl['ivf'] + $vl['ivffeb'] + $vl['ivfmar']+ $vl['ivfapr'] + $vl['ivfmay'] + $vl['ivfjun'] + $vl['ivfjul'] + $vl['ivfaug'] + $vl['ivfsep'] + $vl['ivfoct'] + $vl['ivfnov'] + $vl['ivfdec'];
				  $total_icsi = $vl['icsi'] + $vl['icsifeb'] + $vl['icsimar']+ $vl['icsiapr'] + $vl['icsimay'] + $vl['icsijun'] + $vl['icsijul'] + $vl['icsiaug'] + $vl['icsisep'] + $vl['icsioct'] + $vl['icsinov'] + $vl['icsidec'];
				  $total_tesa_mtese = $vl['tesa_mtese'] + $vl['tesa_mtesefeb'] + $vl['tesa_mtesemar']+ $vl['tesa_mteseapr'] + $vl['tesa_mtesemay'] + $vl['tesa_mtesejun'] + $vl['tesa_mtesejul'] + $vl['tesa_mteseaug'] + $vl['tesa_mtesesep'] + $vl['tesa_mteseoct'] + $vl['tesa_mtesenov'] + $vl['tesa_mtesedec'];
				  $total_donor_cycle = $vl['donor_cycle'] + $vl['donor_cyclefeb'] + $vl['donor_cyclemar']+ $vl['donor_cycleapr'] + $vl['donor_cyclemay'] + $vl['donor_cyclejun'] + $vl['donor_cyclejul'] + $vl['donor_cycleaug'] + $vl['donor_cyclesep'] + $vl['donor_cycleoct'] + $vl['donor_cyclenov'] + $vl['donor_cycledec'];
				  $total_surrogacy = $vl['surrogacy'] + $vl['surrogacyfeb'] + $vl['surrogacymar']+ $vl['surrogacyapr'] + $vl['surrogacymay'] + $vl['surrogacyjun'] + $vl['surrogacyjul'] + $vl['surrogacyaug'] + $vl['surrogacysep'] + $vl['surrogacyoct'] + $vl['surrogacynov'] + $vl['surrogacydec'];
				  $total_uterine_prp = $vl['uterine_prp'] + $vl['uterine_prpfeb'] + $vl['uterine_prpmar']+ $vl['uterine_prpapr'] + $vl['uterine_prpmay'] + $vl['uterine_prpjun'] + $vl['uterine_prpjul'] + $vl['uterine_prpaug'] + $vl['uterine_prpsep'] + $vl['uterine_prpoct'] + $vl['uterine_prpnov'] + $vl['uterine_prpdec'];
				  $total_ovarian_prp = $vl['ovarian_prp'] + $vl['ovarian_prpfeb'] + $vl['ovarian_prpmar']+ $vl['ovarian_prpapr'] + $vl['ovarian_prpmay'] + $vl['ovarian_prpjun'] + $vl['ovarian_prpjul'] + $vl['ovarian_prpaug'] + $vl['ovarian_prpsep'] + $vl['ovarian_prpoct'] + $vl['ovarian_prpnov'] + $vl['ovarian_prpdec'];
				  $total_testicular_prp = $vl['testicular_prp'] + $vl['testicular_prpfeb'] + $vl['testicular_prpmar']+ $vl['testicular_prpapr'] + $vl['testicular_prpmay'] + $vl['testicular_prpjun'] + $vl['testicular_prpjul'] + $vl['testicular_prpaug'] + $vl['testicular_prpsep'] + $vl['testicular_prpoct'] + $vl['testicular_prpnov'] + $vl['testicular_prpdec'];
				  $total_macs_qualis_candor = $vl['macs_qualis_candor'] + $vl['macs_qualis_candorfeb'] + $vl['macs_qualis_candormar']+ $vl['macs_qualis_candorapr'] + $vl['macs_qualis_candormay'] + $vl['macs_qualis_candorjun'] + $vl['macs_qualis_candorjul'] + $vl['macs_qualis_candoraug'] + $vl['macs_qualis_candorsep'] + $vl['macs_qualis_candoroct'] + $vl['macs_qualis_candornov'] + $vl['macs_qualis_candordec'];
				  $total_lah = $vl['lah'] + $vl['lahfeb'] + $vl['lahmar']+ $vl['lahapr'] + $vl['lahmay'] + $vl['lahjun'] + $vl['lahjul'] + $vl['lahaug'] + $vl['lahsep'] + $vl['lahoct'] + $vl['lahnov'] + $vl['lahdec'];
				  $total_pgd = $vl['pgd'] + $vl['pgdfeb'] + $vl['pgdmar']+ $vl['pgdapr'] + $vl['pgdmay'] + $vl['pgdjun'] + $vl['pgdjul'] + $vl['pgdaug'] + $vl['pgdsep'] + $vl['pgdoct'] + $vl['pgdnov'] + $vl['pgddec'];
				  $total_embryo_glue = $vl['embryo_glue'] + $vl['embryo_gluefeb'] + $vl['embryo_gluemar']+ $vl['embryo_glueapr'] + $vl['embryo_gluemay'] + $vl['embryo_gluejun'] + $vl['embryo_gluejul'] + $vl['embryo_glueaug'] + $vl['embryo_gluesep'] + $vl['embryo_glueoct'] + $vl['embryo_gluenov'] + $vl['embryo_gluedec'];
				  $total_sperm_mobil = $vl['sperm_mobil'] + $vl['sperm_mobilfeb'] + $vl['sperm_mobilmar']+ $vl['sperm_mobilapr'] + $vl['sperm_mobilmay'] + $vl['sperm_mobiljun'] + $vl['sperm_mobiljul'] + $vl['sperm_mobilaug'] + $vl['sperm_mobilsep'] + $vl['sperm_mobiloct'] + $vl['sperm_mobilnov'] + $vl['sperm_mobildec'];
				  $total_blastocyst_transfer = $vl['blastocyst_transfer'] + $vl['blastocyst_transferfeb'] + $vl['blastocyst_transfermar']+ $vl['blastocyst_transferapr'] + $vl['blastocyst_transfermay'] + $vl['blastocyst_transferjun'] + $vl['blastocyst_transferjul'] + $vl['blastocyst_transferaug'] + $vl['blastocyst_transfersep'] + $vl['blastocyst_transferoct'] + $vl['blastocyst_transfernov'] + $vl['blastocyst_transferdec'];
				  $total_hysteroscopy_diagnostic = $vl['hysteroscopy_diagnostic'] + $vl['hysteroscopy_diagnosticfeb'] + $vl['hysteroscopy_diagnosticmar']+ $vl['hysteroscopy_diagnosticapr'] + $vl['hysteroscopy_diagnosticmay'] + $vl['hysteroscopy_diagnosticjun'] + $vl['hysteroscopy_diagnosticjul'] + $vl['hysteroscopy_diagnosticaug'] + $vl['hysteroscopy_diagnosticsep'] + $vl['hysteroscopy_diagnosticoct'] + $vl['hysteroscopy_diagnosticnov'] + $vl['hysteroscopy_diagnosticdec'];
				  $total_laparoscopy_hysteroscopy = $vl['laparoscopy_hysteroscopy'] + $vl['laparoscopy_hysteroscopyfeb'] + $vl['laparoscopy_hysteroscopymar']+ $vl['laparoscopy_hysteroscopyapr'] + $vl['laparoscopy_hysteroscopymay'] + $vl['laparoscopy_hysteroscopyjun'] + $vl['laparoscopy_hysteroscopyjul'] + $vl['laparoscopy_hysteroscopyaug'] + $vl['laparoscopy_hysteroscopysep'] + $vl['laparoscopy_hysteroscopyoct'] + $vl['laparoscopy_hysteroscopynov'] + $vl['laparoscopy_hysteroscopydec'];
				  $total_beta_hcg_positive = $vl['beta_hcg_positive'] + $vl['beta_hcg_positivefeb'] + $vl['beta_hcg_positivemar']+ $vl['beta_hcg_positiveapr'] + $vl['beta_hcg_positivemay'] + $vl['beta_hcg_positivejun'] + $vl['beta_hcg_positivejul'] + $vl['beta_hcg_positiveaug'] + $vl['beta_hcg_positivesep'] + $vl['beta_hcg_positiveoct'] + $vl['beta_hcg_positivenov'] + $vl['beta_hcg_positivedec'];
				  $total_cardic_activity = $vl['cardic_activity'] + $vl['cardic_activityfeb'] + $vl['cardic_activitymar']+ $vl['cardic_activityapr'] + $vl['cardic_activitymay'] + $vl['cardic_activityjun'] + $vl['cardic_activityjul'] + $vl['cardic_activityaug'] + $vl['cardic_activitysep'] + $vl['cardic_activityoct'] + $vl['cardic_activitynov'] + $vl['cardic_activitydec'];
				  $total_live_birth = $vl['live_birth'] + $vl['live_birthfeb'] + $vl['live_birthmar']+ $vl['live_birthapr'] + $vl['live_birthmay'] + $vl['live_birthjun'] + $vl['live_birthjul'] + $vl['live_birthaug'] + $vl['live_birthsep'] + $vl['live_birthoct'] + $vl['live_birthnov'] + $vl['live_birthdec'];
				  $total_male = $vl['male'] + $vl['malefeb'] + $vl['malemar']+ $vl['maleapr'] + $vl['malemay'] + $vl['malejun'] + $vl['malejul'] + $vl['maleaug'] + $vl['malesep'] + $vl['maleoct'] + $vl['malenov'] + $vl['maledec'];
				  $total_female = $vl['female'] + $vl['femalefeb'] + $vl['femalemar']+ $vl['femaleapr'] + $vl['femalemay'] + $vl['femalejun'] + $vl['femalejul'] + $vl['femaleaug'] + $vl['femalesep'] + $vl['femaleoct'] + $vl['femalenov'] + $vl['femaledec'];
				  $total_ongoing = $vl['ongoing'] + $vl['ongoingfeb'] + $vl['ongoingmar']+ $vl['ongoingapr'] + $vl['ongoingmay'] + $vl['ongoingjun'] + $vl['ongoingjul'] + $vl['ongoingaug'] + $vl['ongoingsep'] + $vl['ongoingoct'] + $vl['ongoingnov'] + $vl['ongoingdec'];
				  $total_egg_freezing = (float)$vl['egg_freezing'] + (float)$vl['egg_freezingfeb'] + (float)$vl['egg_freezingmar'] + (float)$vl['egg_freezingapr'] + (float)$vl['egg_freezingmay'] + (float)$vl['egg_freezingjun'] + (float)$vl['egg_freezingjul'] + (float)$vl['egg_freezingaug'] + (float)$vl['egg_freezingsep'] + (float)$vl['egg_freezingoct'] + (float)$vl['egg_freezingnov'] + (float)$vl['egg_freezingdec'];
				  $total_semen_freezing = (float)$vl['semen_freezing'] + (float)$vl['semen_freezingfeb'] + (float)$vl['semen_freezingmar']+ (float)$vl['semen_freezingapr'] + (float)$vl['semen_freezingmay'] + (float)$vl['semen_freezingjun'] + (float)$vl['semen_freezingjul'] + (float)$vl['semen_freezingaug'] + (float)$vl['semen_freezingsep'] + (float)$vl['semen_freezingoct'] + (float)$vl['semen_freezingnov'] + (float)$vl['semen_freezingdec'];
				  $total_embryo_freezing = (float)$vl['embryo_freezing'] + (float)$vl['embryo_freezingfeb'] + (float)$vl['embryo_freezingmar']+ (float)$vl['embryo_freezingapr'] + (float)$vl['embryo_freezingmay'] + (float)$vl['embryo_freezingjun'] + (float)$vl['embryo_freezingjul'] + (float)$vl['embryo_freezingaug'] + (float)$vl['embryo_freezingsep'] + (float)$vl['embryo_freezingoct'] + (float)$vl['embryo_freezingnov'] + (float)$vl['embryo_freezingdec'];
				  $total_consultations_tele_consult = $total_consultations + $total_tele_consult;;
				  $total_opu_fresh_thawed_cycle_fet =  $total_fresh_cycle_et + $total_thawed_cycle_fet;
				  $total_uterine_ovarian_testicular_prp = $total_uterine_prp + $total_ovarian_prp + $total_testicular_prp;

		
				?>
				
				<tr class="odd gradeX">
				    
				    <td><?php  $sql1 = "SELECT * FROM hms_centers WHERE center_number=".$vl['center']."";
	                 $query = $this->db->query($sql1);
                     $select_result1 = $query->result(); 
                     foreach ($select_result1 as $res_val){ 
                           echo $res_val->center_name;
					 }

					
					?></td>
                    
					 <?php if($_SESSION['logged_embryologist']){ ?>
					 <td><a href="<?php base_url(); ?>updatereports_embrology?ID=<?php echo $vl['id']; ?>"><?php echo $vl['year']; ?></td>
					 <td><?php echo $total_icsi; ?></td>
			        <td><?php echo $total_macs_qualis_candor; ?></td>
			        <td><?php echo $total_lah; ?></td>
					<td><?php echo $total_pgd; ?></td>
			        <td><?php echo $total_embryo_glue; ?></td>
			        <td><?php echo $total_sperm_mobil; ?></td>
					<td><?php echo $total_blastocyst_transfer; ?></td>
			        <td><?php echo $total_egg_freezing; ?></td>
			        <td><?php echo $total_semen_freezing; ?></td>
					<td><?php echo $total_embryo_freezing; ?></td>
					  <?php }else{ ?>
					<td><a href="<?php base_url(); ?>updatereports?ID=<?php echo $vl['id']; ?>"><?php echo $vl['year']; ?></td>  
					<td><?php echo $total_consultations;?> </td>
                    <td><?php echo $total_tele_consult; ?></td>
			        <td><?php echo $total_consultations_tele_consult; ?></td>
			        <td><?php echo $total_opu; ?></td>
					<td><?php echo $total_fresh_cycle_et; ?></td>
			        <td><?php echo $total_thawed_cycle_fet; ?></td>
					<td><?php echo $total_opu_fresh_thawed_cycle_fet; ?></td>
			        <td><?php echo $total_iui; ?></td>
					<td><?php echo $total_ivf; ?></td>
			        <td><?php echo $total_icsi; ?></td>
			        <td><?php echo $total_tesa_mtese; ?></td>
					<td><?php echo $total_donor_cycle; ?></td>
			        <td><?php echo $total_surrogacy; ?></td>
			        <td><?php echo $total_uterine_prp; ?></td>
					<td><?php echo $total_ovarian_prp; ?></td>
			        <td><?php echo $total_testicular_prp; ?></td>
					<td><?php echo $total_uterine_ovarian_testicular_prp ?></td>
			        <td><?php echo $total_macs_qualis_candor; ?></td>
					<td><?php echo $total_lah; ?></td>
			        <td><?php echo $total_pgd; ?></td>
			        <td><?php echo $total_embryo_glue; ?></td>
					<td><?php echo $total_sperm_mobil; ?></td>
			        <td><?php echo $total_blastocyst_transfer; ?></td>
			        <td><?php echo $total_hysteroscopy_diagnostic; ?></td>
					<td><?php echo $total_laparoscopy_hysteroscopy; ?></td>
			        <td><?php echo $total_beta_hcg_positive; ?></td>
			        <td><?php echo $total_cardic_activity; ?></td>
					<td><?php echo $total_live_birth; ?></td>
			        <td><?php echo $total_male; ?></td>
			        <td><?php echo $total_female; ?></td>
					<td><?php echo $total_ongoing ?></td>
					<td><?php echo $total_egg_freezing; ?></td>
			        <td><?php echo $total_semen_freezing; ?></td>
					<td><?php echo $total_embryo_freezing; ?></td>
					<?php } ?>
			    </tr>
				<?php //} ?>
                  <?php  
				$total_consultations2 += $total_consultations;
				$total_tele_consult2 += $total_tele_consult;
				$total_consultations_tele_consult2 += $total_consultations_tele_consult;
				$total_opu2 += $total_opu;
                $total_fresh_cycle_et2 += $total_fresh_cycle_et;
				$total_thawed_cycle_fet2 += $total_thawed_cycle_fet;
				$total_opu_fresh_thawed_cycle_fet2 += $total_opu_fresh_thawed_cycle_fet;
				$total_iui2 += $total_iui;
				$total_ivf2 += $total_ivf;
				$total_icsi2 += $total_icsi;
				$total_tesa_mtese2 += $total_tesa_mtese;
				$total_donor_cycle2 += $total_donor_cycle;
				$total_surrogacy2 += $total_surrogacy;
				$total_uterine_prp2 += $total_uterine_prp;
				$total_ovarian_prp2 += $total_ovarian_prp;
				$total_testicular_prp2 += $total_testicular_prp;
				$total_uterine_ovarian_testicular_prp2 += $total_uterine_ovarian_testicular_prp;
				$total_macs_qualis_candor2 += $total_macs_qualis_candor;
				$total_lah2 += $total_lah;
				$total_pgd2 += $total_pgd;
				$total_embryo_glue2 += $total_embryo_glue;
				$total_sperm_mobil2 += $total_sperm_mobil;
				$total_blastocyst_transfer2 += $total_blastocyst_transfer;
				$total_hysteroscopy_diagnostic2 += $total_hysteroscopy_diagnostic;
				$total_laparoscopy_hysteroscopy2 += $total_laparoscopy_hysteroscopy;
				$total_beta_hcg_positive2 += $total_beta_hcg_positive;
				$total_cardic_activity2 += $total_cardic_activity;
				$total_live_birth2 += $total_live_birth;
				$total_male2 += $total_male;
				$total_female2 += $total_female;
				$total_ongoing2 += $total_ongoing;
				$total_egg_freezing2 += $total_egg_freezing;
				$total_semen_freezing2 += $total_semen_freezing;
				$total_embryo_freezing2 += $total_embryo_freezing;
				  ?>
                 
               
              <?php $count++;} ?>
              <tr>
			     <td>TOTAL</td><td></td>
				 <?php if($_SESSION['logged_embryologist']){ ?>
				 <td><?php echo $total_icsi2; ?></td>
			     <td><?php echo $total_macs_qualis_candor2; ?></td>
			     <td><?php echo $total_lah2; ?></td>
				 <td><?php echo $total_pgd2; ?></td>
			     <td><?php echo $total_embryo_glue2; ?></td>
			     <td><?php echo $total_sperm_mobil2; ?></td>
				 <td><?php echo $total_blastocyst_transfer2; ?></td>
			     <td><?php echo $total_egg_freezing2; ?></td>
			     <td><?php echo $total_semen_freezing2; ?></td>
				 <td><?php echo $total_embryo_freezing2; ?></td>
				 <?php }else{ ?>
                 <td><?php echo $total_consultations2; ?></td>
				 <td><?php echo $total_tele_consult2; ?></td>
				 <td><?php echo $total_consultations_tele_consult2; ?></td>
				 <td><?php echo $total_opu2; ?></td>
				 <td><?php echo $total_fresh_cycle_et2; ?></td>
				 <td><?php echo $total_thawed_cycle_fet2; ?></td>
				 <td><?php echo $total_opu_fresh_thawed_cycle_fet2; ?></td>
				 <td><?php echo $total_iui2; ?></td>
				 <td><?php echo $total_ivf2; ?></td>
				 <td><?php echo $total_icsi2; ?></td>
				 <td><?php echo $total_tesa_mtese2; ?></td>
				 <td><?php echo $total_donor_cycle2; ?></td>
				 <td><?php echo $total_surrogacy2; ?></td>
				 <td><?php echo $total_uterine_prp2; ?></td>
				 <td><?php echo $total_ovarian_prp2; ?></td>
				 <td><?php echo $total_testicular_prp2; ?></td>
				 <td><?php echo $total_uterine_ovarian_testicular_prp2; ?></td>
				 <td><?php echo $total_macs_qualis_candor2; ?></td>
				 <td><?php echo $total_lah2; ?></td>
				 <td><?php echo $total_pgd2; ?></td>
				 <td><?php echo $total_embryo_glue2; ?></td>
				 <td><?php echo $total_sperm_mobil2; ?></td>
				 <td><?php echo $total_blastocyst_transfer2; ?></td>
				 <td><?php echo $total_hysteroscopy_diagnostic2; ?></td>
				 <td><?php echo $total_laparoscopy_hysteroscopy2; ?></td>
				 <td><?php echo $total_beta_hcg_positive2; ?></td>
				 <td><?php echo $total_cardic_activity2; ?></td>
				 <td><?php echo $total_live_birth2; ?></td>
				 <td><?php echo $total_male2; ?></td>
				 <td><?php echo $total_female2; ?></td>
				 <td><?php echo $total_ongoing2; ?></td>
				 <td><?php echo $total_egg_freezing2; ?></td>
			     <td><?php echo $total_semen_freezing2; ?></td>
				 <td><?php echo $total_embryo_freezing2; ?></td>
				 <?php } ?>
			  </tr>
			   <tr>
                <td colspan="10">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>
              </tbody>
			  
			  

			  
            </table>
          </div>
        </div>
      </div>
     </div>
   
<script>
      $( function() {
        $( ".particular_date_filter" ).datepicker({
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(dateStr) {
            $('#loader_div').hide();				
            var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
            var data = {appointment_date:startDate, type:'particular_date_filter'};
          }
        });
    });
</script>
<style >
.custom-pagination{
  padding:8px;
}
.custom-pagination a{
  padding:10px;
  text-decoration: none;
}
.form-control{
  height: 30px!important;
  border: 1px solid #9e9e9e!important;
}
.form-control#billing_at{
  height: 40px!important;
  border: 1px solid #9e9e9e!important;
}
</style>