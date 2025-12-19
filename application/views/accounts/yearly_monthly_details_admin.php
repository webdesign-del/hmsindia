<div class="col-md-12">
      <div class="card"><?php
$months = [
    1=>'January',2=>'February',3=>'March',4=>'April',
    5=>'May',6=>'June',7=>'July',8=>'August',
    9=>'September',10=>'October',11=>'November',12=>'December'
];

/* =========================
   INDEX DATA [month][center]
=========================*/
$consult = $ovarian = $testicular = [];

foreach ($consult as $c) {
    $consult[$c['month']][$c['center_name']] = $c['total_consultations'];
}

foreach ($stem as $s) {
    $ovarian[$s['month']][$s['center_name']] = $s['total_ovarian'];
}

foreach ($testi as $t) {
    $testicular[$t['month']][$t['center_name']] = $t['total_testicular'];
}

/* Collect all centers */
$centers = [];
foreach ([$consult, $ovarian, $testicular] as $dataset) {
    foreach ($dataset as $m => $rows) {
        foreach ($rows as $center => $v) {
            $centers[$center] = true;
        }
    }
}
$centers = array_keys($centers);
?>

<h3>Clinical Reports Monthly Details – Year <?php echo $year; ?></h3>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Month</th>
            <th>Center Name</th>
            <th>Total Consultations</th>
            <th>Ovarian Stem Cell</th>
            <th>Testicular Stem Cell</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($months as $m_no => $m_name): ?>
        <?php foreach ($centers as $center): ?>
        <tr>
            <td><?php echo $m_name; ?></td>
            <td><?php echo $center; ?></td>
            <td><?php echo $consult[$m_no][$center] ?? 0; ?></td>
            <td><?php echo $ovarian[$m_no][$center] ?? 0; ?></td>
            <td><?php echo $testicular[$m_no][$center] ?? 0; ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo base_url('accounts/yearly_summary'); ?>" class="btn btn-success">
    ← Back to Yearly Summary
</a>
        </div>
        </div>
