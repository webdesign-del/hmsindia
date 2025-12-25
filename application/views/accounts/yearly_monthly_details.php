 <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Clinical Reports Monthly Details – Year <?php echo $year; ?></h3></div>
       <div class="clearfix"></div>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Month</th>
            <th>CONSULTATION</th>
            <th>STEM CELL</th>
            <th>TESTICULAR STEM CELL</th>
            <th>OVARIAN PRP</th>
            <th>OPU</th>
            <th>FRESH CYCLE ET</th>
            <th>THAWED CYCLE / FET</th>
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
        'stem'    => 0,
        'testi'   => 0,
        'ovarian_prp'   => 0,
        'ovum_pickup'   => 0,
        'embryo_transfer'   => 0,
        'fet'   => 0
    ];
}

/* ==================================
   MERGE CONSULTATION DATA
================================== */
foreach ($consult_monthly as $c) {
    $months[$c['month']]['consult'] = (int)$c['total'];
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
   DISPLAY TABLE
================================== */
foreach ($months as $m) {
?>
<tr>
    <td><?php echo $m['name']; ?></td>
    <td><?php echo $m['consult']; ?></td>
    <td><?php echo $m['stem']; ?></td>
    <td><?php echo $m['testi']; ?></td>
    <td><?php echo $m['ovarian_prp']; ?></td>
    <td><?php echo $m['ovum_pickup']; ?></td>
    <td><?php echo $m['embryo_transfer']; ?></td>
    <td><?php echo $m['fet']; ?></td>
</tr>
<?php } ?>

    </tbody>
</table>

<a href="<?php echo base_url('accounts/clinical_reports'); ?>" class="btn btn-secondary">
    ← Back to Yearly Summary
</a>
</div>
</div>
