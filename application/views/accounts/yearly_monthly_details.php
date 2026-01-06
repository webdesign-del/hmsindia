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

/* ==================================
   MERGE OVARIAN STEM DATA
================================== */
foreach ($stem_monthly as $s) {
    $months[$s['month']]['stem'] = (int)$s['total'];
}

/* ==================================
   MERGE TESTICULAR STEM DATA
================================== */
foreach ($testi_monthly as $t) {
    $months[$t['month']]['testi'] = (int)$t['total'];
}

/* ==================================
   MERGE OVARIAN PRP DATA
================================== */
foreach ($ovarian_prp_monthly as $s) {
    $months[$s['month']]['ovarian_prp'] = (int)$s['total'];
}

/* ==================================
   MERGE OPU DATA
================================== */
foreach ($ovum_pickup_monthly as $t) {
    $months[$t['month']]['ovum_pickup'] = (int)$t['total'];
}

/* ==================================
   MERGE Embryo Transfer DATA
================================== */
foreach ($embryo_transfer_monthly as $t) {
    $months[$t['month']]['embryo_transfer'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($fet_monthly as $t) {
    $months[$t['month']]['fet'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($iui_monthly as $t) {
    $months[$t['month']]['iui'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($ivf_monthly as $t) {
    $months[$t['month']]['ivf'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($icsi_monthly as $t) {
    $months[$t['month']]['icsi'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($tesa_mtesa_monthly as $t) {
    $months[$t['month']]['tesa_mtesa'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($testicular_prp_monthly as $t) {
    $months[$t['month']]['testicular_prp'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($Sperm_Mobil_monthly as $t) {
    $months[$t['month']]['Sperm_Mobil'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($Blastocyst_monthly as $t) {
    $months[$t['month']]['Blastocyst'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($lah_monthly as $t) {
    $months[$t['month']]['lah'] = (int)$t['total'];
}

/* ==================================
   MERGE FET DATA
================================== */
foreach ($Embryo_Glue_monthly as $t) {
    $months[$t['month']]['Embryo_Glue'] = (int)$t['total'];
}

/* ==================================
   DISPLAY TABLE
================================== */
foreach ($months as $m) {
?>
<tr>
    <td><?php echo $m['name']; ?></td>
    <td><?php echo $m['consult']; ?></td>
    <td><?php echo $m['first_appointment']; ?></td>
    <td><?php echo $m['stem']; ?></td>
    <td><?php echo $m['testi']; ?></td>
    <td><?php echo $m['ovarian_prp']; ?></td>
    <td><?php echo $m['ovum_pickup']; ?></td>
    <td><?php echo $m['embryo_transfer']; ?></td>
    <td><?php echo $m['fet']; ?></td>
    <td><?php echo $m['iui']; ?></td>
    <td><?php echo $m['ivf']; ?></td>
    <td><?php echo $m['icsi']; ?></td>
    <td><?php echo $m['tesa_mtesa']; ?></td>
    <td><?php echo $m['testicular_prp']; ?></td>
    <td><?php echo $m['Sperm_Mobil']; ?></td>
    <td><?php echo $m['Blastocyst']; ?></td>
    <td><?php echo $m['lah']; ?></td>
    <td><?php echo $m['Embryo_Glue']; ?></td>
</tr>
<?php } ?>

    </tbody>
</table>

<a href="<?php echo base_url('accounts/clinical_reports'); ?>" class="btn btn-secondary">
    ← Back to Yearly Summary
</a>
</div>
</div>
