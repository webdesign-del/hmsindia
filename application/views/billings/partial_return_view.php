<div class="col-md-8 col-md-offset-2" style="margin-top:30px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Manage Return - Receipt No: <?php echo $billing['receipt_number']; ?></h3>
        </div>
        <div class="panel-body">
            <h4>Patient ID: <?php echo $billing['patient_id']; ?></h4>
            <p>नीचे दिए गए टेस्ट्स में से जो टेस्ट <b>नहीं हुआ है</b>, उसके सामने वाले बटन पर क्लिक करें:</p>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(isset($investigations['female_investigation']) && !empty($investigations['female_investigation'])) {
                        foreach($investigations['female_investigation'] as $index => $test) {
                    ?>
                        <tr>
                            <td><?php echo $test['female_investigation_code']; ?> (ID: <?php echo $test['female_investigation_name']; ?>)</td>
                            <td>₹<?php echo $test['female_investigation_price']; ?></td>
                            <td>
                                <a href="<?php echo base_url('billings/process_single_test_return/'.$billing['receipt_number'].'/'.$index); ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('क्या आप वाकई इस टेस्ट को बिल से हटाकर रिफंड करना चाहते हैं?');">
                                   Return This Test
                                </a>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='3'>कोई टेस्ट नहीं मिला या डेटा फ़ॉर्मेट अलग है।</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="<?php echo base_url('billings/investigation_billings'); ?>" class="btn btn-default">Back to List</a>
        </div>
    </div>
</div>