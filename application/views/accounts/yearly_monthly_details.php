 <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Clinical Reports Monthly Details – Year <?php echo $year; ?></h3></div>
       <div class="clearfix"></div>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Month</th>
            <th>Total Consultations</th>
            <th>Ovarian Stem Cell</th>
            <th>Testicular Stem Cell</th>
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
        'testi'   => 0
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
   DISPLAY TABLE
================================== */
foreach ($months as $m) {
?>
<tr>
    <td><?php echo $m['name']; ?></td>
    <td><?php echo $m['consult']; ?></td>
    <td><?php echo $m['stem']; ?></td>
    <td><?php echo $m['testi']; ?></td>
</tr>
<?php } ?>

    </tbody>
</table>

<a href="<?php echo base_url('accounts/your_summary_page'); ?>" class="btn btn-secondary">
    ← Back to Yearly Summary
</a>
</div>
</div>
