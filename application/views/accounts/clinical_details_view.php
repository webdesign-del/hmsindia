 <?php $all_method = &get_instance(); ?>
<div class="col-md-12">
    <div class="card">
        <div class="card-action">
            <h3><?php echo $type_title; ?> List - Year <?php echo $year; ?></h3>
        </div>
        <div class="card-content">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Patient ID</th>
                        <th>Procedure Date</th>
                        <th>Center Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($records)): ?>
                        <?php $i=1; foreach ($records as $row): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            
                            <td>
                                <?php 
                                    // Now this will work because we passed $all_method from the controller
                                    if (isset($row['iic_id'])) {
                                        echo $all_method->get_patient_name($row['iic_id']);
                                    } else {
                                        echo "-";
                                    }
                                ?>
                            </td>
                            
                            <td>
                                <?php 
                                echo isset($row['iic_id']) ? $row['iic_id'] : (isset($row['iic_id']) ? $row['iic_id'] : '-'); 
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo isset($row['date_of_procedure']) ? $row['date_of_procedure'] : (isset($row['date_of_procedure']) ? $row['date_of_procedure'] : '-'); 
                                ?>
                            </td>
                            <td><?php echo $all_method->get_center_name($row['center']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No records found for this year.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <br>
            <a href="<?php echo base_url('accounts/clinical_reports?year='.$year); ?>" class="btn btn-secondary">
                ← Back to Monthly Summary
            </a>
        </div>
    </div>
</div>