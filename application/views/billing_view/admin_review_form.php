<!DOCTYPE html>
<html>
<head>
    <title>Administrator Refund Review</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body style="background:#f4f6f9; padding-top:40px;">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>Administrator Review - Refund Request #<?php echo $refund_data['receipt_number']; ?></b></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr><th>Patient Name</th><td><?php echo $refund_data['patient_name']; ?> (ID: <?php echo $refund_data['patient_id']; ?>)</td></tr>
                        <tr><th>Receipt Number</th><td><?php echo $refund_data['receipt_number']; ?></td></tr>
                        <tr><th>Refund Reason</th><td><?php echo $refund_data['refund_reason']; ?></td></tr>
                        <tr><th>Requested By</th><td><?php echo $refund_data['created_by_email']; ?></td></tr>
                    </table>

                    <form method="post" action="<?php echo site_url('Accounts/submit_admin_decision'); ?>">
                        <input type="hidden" name="refund_id" value="<?php echo $refund_id; ?>">
                        
                        <div class="form-group">
                            <label>Administrator Remarks / Decision Comments:</label>
                            <textarea name="admin_remarks" class="form-control" rows="3" required placeholder="Enter reasons for approval/disapproval..."></textarea>
                        </div>

                        <div class="text-center" style="margin-top:20px;">
                            <button type="submit" name="decision" value="approved" class="btn btn-success btn-lg" style="margin-right:15px;">
                                <i class="fa fa-check"></i> APPROVE REQUEST
                            </button>
                            <button type="submit" name="decision" value="disapproved" class="btn btn-danger btn-lg">
                                <i class="fa fa-times"></i> DISAPPROVE REQUEST
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>