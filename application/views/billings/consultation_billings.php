<?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
    <div class="row card" style="margin-bottom:20px;">
         <div class="col-md-12"><h3> Consultation Patients </h3></div>
      <div class="clearfix"></div>
        <form action="<?php echo base_url().'billings/consultation_billings'; ?>" method="get">
       <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
              <label>Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
              <label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
              <label>IIC ID </label>
                <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo $patient_id;?>" />
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
              <button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
              <a href="<?php echo base_url().'billings/consultation_billings'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            <div class="col-sm-2" style="margin-top: 10px;">
              <a href="<?php echo base_url('billings/Consultation-Patients'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Billings</button>
               </a>
            </div>      
            </form>
         <div class="clearfix"></div>
        <div class="card-content">

          <div class="table-responsive">

            <table class="table table-striped table-bordered table-hover" id="investigation_billing_list">

             <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Receipt number</th>
                  <th>IIC ID</th>
                  <th>Patient Name</th>
                  <th>Discounted package</th>
                  <th data-field="date">On Date</th>
                  <th>Status</th>
                  <th>Action</th> </tr>
              </thead>

              <tbody id="investigate_result">

              <?php $count=1; foreach($consultation_result as $ky => $vl){
                    $patient_data = get_patient_detail($vl['patient_id']);
              ?>

                <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
                  <td><a href="<?php echo base_url(); ?>accounts/details/<?php echo $vl['receipt_number']?>?t=consultation"><?php echo $vl['receipt_number']?></a></td>
                  <td><a href="<?php echo base_url()?>accounts/patient_details/<?php echo $vl['patient_id'];?>"><?php echo $vl['patient_id']; ?></a></td>
                  <td><?php $patient_name = $all_method->get_patient_name($vl['patient_id']); echo  strtoupper($patient_name); ?></td>
                  <td><?php echo $vl['fees']?></td>
                  <td><?php echo $vl['on_date']?></td>
                  <td>
                    <?php 
                    echo ucwords($vl['status']); 
                    if($vl['status'] == 'disapproved'){
                        echo ' <i class="fa fa-exclamation-circle" aria-hidden="true" title="'.$vl['reason_of_disapprove'].'"></i>'; 
                        ?> <a href="<?php echo base_url();?>billings/disapproved/<?php echo $vl['receipt_number']; ?>?t=consultation" class="btn btn-large">edit billing</a><?php 
                    }
                    ?>
                  </td>
                  <td>
                    <?php 
                    if(($vl['status'] == 'pending' || $vl['status'] == 'approved') && floatval($vl['fees']) > 0) {
                        if(isset($vl['return_requested']) && $vl['return_requested'] == 1) {
                            echo '<span class="badge bg-warning text-dark">Return Requested</span>';
                        } else {
                            ?>
                            <button type="button" class="btn btn-danger btn-sm" onclick="openCustomPopup('<?php echo $vl['receipt_number']; ?>', '<?php echo $vl['patient_id']; ?>', '<?php echo $vl['fees']; ?>')">
                                Return Request
                            </button>
                            <?php
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                  </td>
                </tr>

              <?php $count++; } ?>
                 <tr>
                <td colspan="12">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>

              </tbody>

            </table>

          </div>

        </div>

      </div>
    </div>
</div>

<div id="customReturnPopup" class="custom-overlay-bg" style="display: none;">
  <div class="custom-popup-box">
    <div class="custom-popup-header">
      <span class="custom-popup-close" onclick="closeCustomPopup()">&times;</span>
      <h4><strong>Submit Return Request</strong></h4>
    </div>
    <div class="custom-popup-body">
      <input type="hidden" id="modal_receipt_number">
      <input type="hidden" id="modal_patient_id">
      <input type="hidden" id="modal_amount">
      
      <div style="margin-bottom: 15px; text-align: left;">
          <label style="font-weight: bold; display: block; margin-bottom: 8px; color: #333;">Reason for Return</label>
          <textarea id="modal_return_reason" class="form-control" style="height: 120px !important; width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #ccc !important; resize: vertical;" placeholder="Enter specific reason here..."></textarea>
      </div>
    </div>
    <div class="custom-popup-footer">
      <button type="button" class="btn btn-secondary" onclick="closeCustomPopup()">Cancel</button>
      <button type="button" class="btn btn-danger" onclick="submitReturnRequest()">Send Request</button>
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

  // 🎯 Pure JS popup controller (Kisi framework par depend nahi hai)
  function openCustomPopup(receipt, patient, amount) {
      document.getElementById('modal_receipt_number').value = receipt;
      document.getElementById('modal_patient_id').value = patient;
      document.getElementById('modal_amount').value = amount;
      document.getElementById('modal_return_reason').value = '';
      
      // Force display overlay
      document.getElementById('customReturnPopup').style.display = 'block';
  }

  function closeCustomPopup() {
      document.getElementById('customReturnPopup').style.display = 'none';
  }

  function submitReturnRequest() {
      var receipt = $('#modal_receipt_number').val();
      var patient = $('#modal_patient_id').val();
      var amount = $('#modal_amount').val();
      var reason = $('#modal_return_reason').val();

      if(reason.trim() == "") {
          alert("Please enter a reason for the return request.");
          return;
      }

      $.ajax({
          url: "<?php echo base_url('billings/submit_return_request'); ?>",
          type: "POST",
          data: { receipt_number: receipt, patient_id: patient, amount: amount, reason: reason },
          dataType: "json",
          success: function(response) {
              if(response.status == true) {
                  alert("Return request processed and mails sent successfully!");
                  location.reload();
              } else {
                  alert("Something went wrong: " + response.message);
              }
          }
      });
  }
</script>

<style>
.custom-pagination{ padding:8px; }
.custom-pagination a{ padding:10px; text-decoration: none; }
.form-control{ height: 30px!important; border: 1px solid #9e9e9e!important; }
.form-control#billing_at{ height: 40px!important; border: 1px solid #9e9e9e!important; }

/* 🎯 CUSTOM INDEPENDENT POPUP CSS STYLES */
.custom-overlay-bg {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.6) !important;
    z-index: 999999 !important; /* Page ke sabse upar dikhega */
}
.custom-popup-box {
    position: relative !important;
    width: 90% !important;
    max-width: 500px !important;
    margin: 10% auto !important;
    background: #fff !important;
    border-radius: 6px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important;
    padding: 20px !important;
    animation: fadeIn 0.3s ease-out;
}
.custom-popup-header {
    border-bottom: 1px solid #eee !important;
    padding-bottom: 10px !important;
    margin-bottom: 15px !important;
}
.custom-popup-header h4 {
    margin: 0 !important;
    font-size: 18px !important;
    color: #333 !important;
}
.custom-popup-close {
    float: right !important;
    font-size: 28px !important;
    font-weight: bold !important;
    line-height: 18px !important;
    color: #aaa !important;
    cursor: pointer !important;
}
.custom-popup-close:hover {
    color: #000 !important;
}
.custom-popup-footer {
    border-top: 1px solid #eee !important;
    padding-top: 15px !important;
    text-align: right !important;
    margin-top: 15px !important;
}
.custom-popup-footer .btn {
    margin-left: 5px !important;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>