 <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Clinical Reports Monthly Details – Year <?php echo $year; ?></h3></div>
       <div class="clearfix"></div>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Month</th>
            <th>CONSULTATION</th>

            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=first_visit'); ?>">FIRST VISIT</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=stem_cell'); ?>">STEM CELL</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=testicular_stem_cell'); ?>">TESTICULAR STEM CELL</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=ovarian_prp'); ?>">OVARIAN PRP</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=ovum_pickup'); ?>">OPU</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=fresh_cycle_et'); ?>">FRESH CYCLE ET</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=fet'); ?>">THAWED CYCLE / FET</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=iui'); ?>">IUI</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=ivf'); ?>">IVF</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=icsi'); ?>">ICSI</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=tesa_mtesa'); ?>">TESA/MTESE</a></th>
            
            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=testicular_prp'); ?>">TESTICULAR/PRP</a></th>

            <th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=sperm_mobil'); ?>">SPERM MOBIL</a></th>

			<th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=blastocyst'); ?>">BLASTOCYST</a></th>

			<th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=lah'); ?>">LAH</a></th>

			<th><a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&type=embryo_glue'); ?>">EMBRYO GLUE</a></th>
        </tr>
    </thead>
    <tbody>

<?php
/* ==================================
   INITIALIZE ALL MONTHS (JAN–DEC)
================================== */
$months = [];
for ($m = 1; $m <= 12; $m++) {
    $months[$m] = [
        'name'    => date('F', mktime(0, 0, 0, $m, 1)),
        'consult' => 0,
        'first_appointment' => 0,
        'stem'    => 0,
        'testi'   => 0,
        'ovarian_prp'   => 0,
        'ovum_pickup'   => 0,
        'embryo_transfer'   => 0,
        'fet'   => 0,
        'iui'   => 0,
        'ivf'   => 0,
        'icsi'   => 0,
        'tesa_mtesa'  => 0,
        'testicular_prp' => 0,
        'Sperm_Mobil' => 0,
        'Blastocyst' => 0,
        'lah' => 0,
        'Embryo_Glue' => 0
    ];
}

/* ==================================
   MERGE CONSULTATION DATA
================================== */
foreach ($consult_monthly as $c) {
    $months[$c['month']]['consult'] = (int)$c['total'];
}

/* ==================================
   MERGE FIRST APPOINTMENT DATA
================================== */
foreach ($first_appointment_monthly as $c) {
    $months[$c['month']]['first_appointment'] = (int)$c['total'];
}

foreach ($stem_monthly as $s) {
    $months[$s['month']]['stem'] = (int)$s['total'];
}

foreach ($testi_monthly as $t) {
    $months[$t['month']]['testi'] = (int)$t['total'];
}

foreach ($ovarian_prp_monthly as $s) {
    $months[$s['month']]['ovarian_prp'] = (int)$s['total'];
}

foreach ($ovum_pickup_monthly as $t) {
    $months[$t['month']]['ovum_pickup'] = (int)$t['total'];
}

foreach ($embryo_transfer_monthly as $t) {
    $months[$t['month']]['embryo_transfer'] = (int)$t['total'];
}

foreach ($fet_monthly as $t) {
    $months[$t['month']]['fet'] = (int)$t['total'];
}

foreach ($iui_monthly as $t) {
    $months[$t['month']]['iui'] = (int)$t['total'];
}

foreach ($ivf_monthly as $t) {
    $months[$t['month']]['ivf'] = (int)$t['total'];
}

foreach ($icsi_monthly as $t) {
    $months[$t['month']]['icsi'] = (int)$t['total'];
}

foreach ($tesa_mtesa_monthly as $t) {
    $months[$t['month']]['tesa_mtesa'] = (int)$t['total'];
}

foreach ($testicular_prp_monthly as $t) {
    $months[$t['month']]['testicular_prp'] = (int)$t['total'];
}

foreach ($Sperm_Mobil_monthly as $t) {
    $months[$t['month']]['Sperm_Mobil'] = (int)$t['total'];
}

foreach ($Blastocyst_monthly as $t) {
    $months[$t['month']]['Blastocyst'] = (int)$t['total'];
}

foreach ($lah_monthly as $t) {
    $months[$t['month']]['lah'] = (int)$t['total'];
}

foreach ($Embryo_Glue_monthly as $t) {
    $months[$t['month']]['Embryo_Glue'] = (int)$t['total'];
}

/* ==================================
   DISPLAY TABLE
================================== */
foreach ($months as $month_num => $m) { 
    $first_appointment_count = isset($m['first_appointment']) ? $m['first_appointment'] : 0;
    $stem_count = isset($m['stem']) ? $m['stem'] : 0;
    $testi_count = isset($m['testi']) ? $m['testi'] : 0;
    $ovarian_prp_count = isset($m['ovarian_prp']) ? $m['ovarian_prp'] : 0;
    $ovum_pickup_count = isset($m['ovum_pickup']) ? $m['ovum_pickup'] : 0;
    $embryo_transfer_count = isset($m['embryo_transfer']) ? $m['embryo_transfer'] : 0;
    $fet_count = isset($m['fet']) ? $m['fet'] : 0;
    $iui_count = isset($m['iui']) ? $m['iui'] : 0;
    $ivf_count = isset($m['ivf']) ? $m['ivf'] : 0;
    $icsi_count = isset($m['icsi']) ? $m['icsi'] : 0;
    $tesa_mtesa_count = isset($m['tesa_mtesa']) ? $m['tesa_mtesa'] : 0;
    $testicular_prp_count = isset($m['testicular_prp']) ? $m['testicular_prp'] : 0;
    $Sperm_Mobil_count = isset($m['Sperm_Mobil']) ? $m['Sperm_Mobil'] : 0;
    $Blastocyst_count = isset($m['Blastocyst']) ? $m['Blastocyst'] : 0;
    $lah_count = isset($m['lah']) ? $m['lah'] : 0;
    $glue_count = isset($m['Embryo_Glue']) ? $m['Embryo_Glue'] : 0;
?>
<tr>
    <td><?php echo $m['name']; ?></td>
    <td><?php echo $m['consult']; ?></td>
    <td><?php if($first_appointment_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=first_visit'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $first_appointment_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($stem_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=stem_cell'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $stem_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($testi_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=testicular_stem_cell'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $testi_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($ovarian_prp_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=ovarian_prp'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $ovarian_prp_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($ovum_pickup_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=ovum_pickup'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $ovum_pickup_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($embryo_transfer_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=fresh_cycle_et'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $embryo_transfer_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($fet_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=fet'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $fet_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($iui_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=iui'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $iui_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($ivf_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=ivf'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $ivf_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($icsi_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=icsi'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $icsi_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($tesa_mtesa_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=tesa_mtesa'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $tesa_mtesa_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($testicular_prp_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=testicular_prp'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $testicular_prp_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($Sperm_Mobil_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=sperm_mobil'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $Sperm_Mobil_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($Blastocyst_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=blastocyst'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $Blastocyst_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($lah_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=lah'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $lah_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
    <td><?php if($glue_count > 0): ?>
            <a href="<?php echo base_url('accounts/clinical_details?year='.$year.'&month='.$month_num.'&type=embryo_glue'); ?>" 
               target="_blank" 
               style="font-weight:bold; color:blue;">
               <?php echo $glue_count; ?>
            </a>
        <?php else: ?>
            0
        <?php endif; ?>
    </td>
</tr>
<?php } ?>

    </tbody>
</table>

<a href="<?php echo base_url('accounts/clinical_reports'); ?>" class="btn btn-secondary">
    ← Back to Yearly Summary
</a>
</div>
</div>
