<?php $all_method =&get_instance();
 
 ?>
<style>
  #print_this_section{margin-top:50px;}
</style>
<div class="col-sm-12 col-xs-12  panel panel-piluku ml-5">
<div class="row" id="disapprove_pop">
      <div class="col-sm-12 disapprove_pop_inner">
        <a href="javascript:void(0);" class="close_disapprove btn btn-large">close</a>
        <form id="disapproved_form" method="post" action="<?php echo base_url('report_status');?>">
          <input type="text" class="hidden_field undisable" readonly="readonly" value="" name="status" id="status" />
          <input type="text" class="hidden_field undisable" readonly="readonly" value="" name="form_id" id="form_id" />
          <input type="text" class="hidden_field undisable" readonly="readonly" value="" name="appointment_id" id="appointment_id" />
          <input type="text" class="hidden_field undisable" readonly="readonly" value="" name="data_id" id="data_id" />
          <input type="text" class="hidden_field undisable" readonly="readonly" value="" name="patient_procedure_id" id="patient_procedure_id" />
          
          <label class="pop_lable">Disapprove Reason</label>
          <p class="error hidden_field"></p>
          <textarea class="form-control undisable" name="status_reason" required></textarea>
          <input type="submit" class="btn btn-large submit undisable" value="submit">
        </form>
      </div>
    </div>

<div class="panel-body profile-edit">
  <style>
    input.btn.btn-large.submit {
      background: #2bbbad;
      max-width: 100%;
      margin-top: 20px;
      width: 50%;
      margin: 20px auto;
    }
    .hidden_field{display:none;}
    div#disapprove_pop {
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      background: rgba(255,255,255,0.6);
      z-index: 999999999;
      height: 100%;
      height: 100%;
      box-shadow: 0px 0px 3px 0px #000;
      display:none;
    }
    .pop_lable {
      width: 100%;
      color: #000!important;
      font-weight: 800;
      font-size: 15px;
      margin-bottom: 10px!important;
    }
    .disapprove_pop_inner {
      width: 50%;
      margin: 80px 25%;
      height: 200px;
      box-shadow: 0px 0px 10px 0px #000;
      background: #fff;
    }
    a.close_disapprove {
      float: right;
      margin-top: 10px;
    }
    a.now_disapprove.btn.btn-large {
      margin: 10px 0px;
    }
</style>

<!--<hr />-->
	<!--<h3><?php echo $form_data['form_name']; ?></h3>-->
<hr />
<?php if(isset($_SESSION['logged_doctor'])){ 
    if($procedure_form_data['status'] == 'pending'){ ?>    
        <a href="<?php echo base_url('discharge_report_status/'.$form_id.'/'.$patient_procedure_id.'/'.$appointment_id.'/'.$discharge_form_data['id'].'?s=approved'); ?>"  class="btn btn-large">Approve</a> |
        <a href="javascript:void(0);" status="disapproved" data_id="<?php echo $procedure_form_data['id']; ?>"  form_id="<?php echo $form_id; ?>" appointment_id="<?php echo $appointment_id; ?>" patient_procedure_id="<?php echo $patient_procedure_id; ?>" class="disaprove_first btn btn-large">Disapprove</a>
  <?php } 
} ?>
    <div class="row" id="print_this_section">
        <?php
          $data = array();
          $procedure_data = get_procedure($patient_procedure_id);
          $data['patient_id'] = isset($procedure_data['patient_id'])?$procedure_data['patient_id']:"";
          $data['receipt_number'] = isset($procedure_data['receipt_number'])?$procedure_data['receipt_number']:"";
       
          $this->load->view('discharge-forms/'.$form_data['form_name'], $data);
        ?>
    </div>
</div>
</div>
</div>

<script>
function printDiv() 
{
  $('.hide_print').hide();
  $('#print_this_section').css('visibility', 'visible');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>

<script>
    $('.card-footer input[type="submit"]').hide();
    $("input").attr('disabled','disabled');
    $("textarea").attr('disabled','disabled');
    $("select").attr('disabled','disabled');
    $(".undisable").removeAttr('disabled');
    
    $("a.disaprove_first").on("click", function(){
    $('#status').val($(this).attr('status'));
    $('#form_id').val($(this).attr('form_id'));
    $('#patient_procedure_id').val($(this).attr('patient_procedure_id'));
    $('#appointment_id').val($(this).attr('appointment_id'));
    $('#data_id').val($(this).attr('data_id'));
    var action_src = '<?php echo base_url('procedure_report_status/');?>'+$(this).attr('form_id')+'/'+$(this).attr('patient_procedure_id')+'/'+$(this).attr('appointment_id')+'/'+$(this).attr('data_id')+'?s='+$(this).attr('status');
    $('#disapproved_form').attr('action', action_src);
    $('div#disapprove_pop').show();
  });
  $("a.close_disapprove").on("click", function(){
    $('#status').val('');
    $('#patient_id').val('');
    $('#report_id').val('');
    var action_src = '<?php echo base_url('report_status/');?>';
    $('#disapproved_form').attr('action', action_src);
    $('div#disapprove_pop').hide();
  });
  </script>