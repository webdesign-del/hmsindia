<?php $all_method =&get_instance(); 

?>
<div class="col-md-12">
    <div class="card">
        <div class="card-action"><h3>Procedure Reports</h3></div>
        <div class="clearfix"></div>
        <form action="<?php echo base_url().'accounts/procedure_advice'; ?>" method="get">
            <div class="col-sm-2 col-xs-12" style="margin-top: 10px;">
                <label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
            <div class="col-sm-2" style="margin-top: 30px;">
                <button name="btnsearch" id="btnsearch" type="submit" class="btn btn-primary">Search</button>
                <a href="<?php echo base_url().'accounts/procedure_advice'; ?>" style="text-decoration: none;">
                    <button name="btnreset" id="btnreset" type="button" class="btn btn-secondary">RESET</button>
                </a>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="card-content">
            <div id="msg_area" class="error"></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="doctor_appointments1">
                    <thead class="topheader" id="myHeader">
                        <tr>
                            <th>Appointment Id</th>
                            <th>IIC ID</th>
                            <th>Patient name</th>
                            <th>Advised Date</th>
                            <th>Advised Procedure Name</th>
                            <th>
                                <table class="table-bordered">
                                    <tr>
                                        <th style="width: 300px; padding: 10px;">Billed Procedure Name</th>
                                        <th style="width: 150px; padding: 10px;">Package MRP</th>
                                        <th style="width: 150px; padding: 10px;">Discount Amount</th>
                                        <th style="width: 150px; padding: 10px;">Total Paid Amount</th>
                                        <th style="width: 150px; padding: 10px;">Pending Amount</th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="appointment_body">
                        <?php  foreach($app_result as $ky =>$vl){ ?>
                        <tr class="odd gradeX">
                            <td><?php echo $vl['appointment_id']; ?></td>
                            <td><?php echo $vl['patient_id']; ?></td>
                            <td><?php $patient_name = $all_method->get_patient_name($vl['patient_id']); echo strtoupper($patient_name); ?></td>
                            <td><?php echo $vl['consultation_date']; ?></td>
                            <td><?php echo $res_val->receipt_number; ?><?php $serializedString = $vl['sub_procedure_suggestion_list'];
                                      $unserializedArray = unserialize($serializedString);
							                        foreach ($unserializedArray as $key =>
                                $value) { $sql2 = "SELECT * FROM `hms_procedures` WHERE ID IN ($value)"; $select_result2 = run_select_query($sql2); echo $select_result2['procedure_name'] . ",\n"; echo '<br /><br />';
                                 } ?></td>
                            <td>
                                <table class="table-bordered">
                                    <?php   $sql1 = "SELECT * FROM hms_patient_procedure WHERE appointment_id=".$vl['appointment_id']."";
                        $query = $this->db->query($sql1); $select_result3 = $query->result(); foreach ($select_result3 as $res_val) { $procedure_data = unserialize($res_val->data); if ($procedure_data !== false || $res_val->data === 'b:0;')
                                    { if (is_array($procedure_data) && isset($procedure_data['patient_procedures'])) { foreach ($procedure_data['patient_procedures'] as $procedure) { 
                                      $ci = &get_instance();  // Get the CI instance if you're outside a controller

                                      // SQL query to sum the payment_done where patient_id matches and status = 3
                                     $done_sql = "SELECT SUM(payment_done) as payment_done FROM hms_patient_payments WHERE billing_id = '" .$res_val->receipt_number . "'  AND status = '1'";
                                     $done_q = $ci->db->query($done_sql);
                                     $done_result = $done_q->row_array();
                                     $total_payment_done = isset($done_result['payment_done']) ? $done_result['payment_done'] : 0;

                                     $toalpay = $total_payment_done + $res_val->payment_done;
                                      ?>
                                    <tr>
                                        <td width="240"><?php //echo $res_val->receipt_number; ?>
                                            <?php $sql1 = "select * from hms_procedures where code='".htmlspecialchars($procedure['sub_procedures_code'])."'";
                                                          $select_result3 = run_select_query($sql1);
                                                          echo $select_result3['procedure_name']; ?>
                                        </td>
                                        <td width="150"><?php echo $res_val->totalpackage; ?></td>
                                        <td width="150"><?php echo $res_val->discount_amount; ?></td>
                                        <td width="150"><?php echo $total_payment_done + $res_val->payment_done; ?></td>
                                        <td width="150"><?php echo $res_val->fees - $toalpay; ?></td>
                                    </tr>
                                    <?php }}}} ?>
                                </table>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .form-control {
        height: 30px !important;
        border: 1px solid #9e9e9e !important;
    }
    .form-control#billing_at {
        height: 40px !important;
        border: 1px solid #9e9e9e !important;
    }
.topheader {
  padding: 10px 16px;
  background: #555;
  color: #f1f1f1;
}
.sticky {
  position: fixed;
  top: 60px;
  width: 100%;
}
.sticky + .content {
  padding-top: 102px;
}
</style>

<script>
window.onscroll = function() {myFunction()};
var topheader = document.getElementById("myHeader");
var sticky = topheader.offsetTop;
function myFunction() {
  if (window.pageYOffset > sticky) {
    topheader.classList.add("sticky");
  } else {
    topheader.classList.remove("sticky");
  }
}
</script>