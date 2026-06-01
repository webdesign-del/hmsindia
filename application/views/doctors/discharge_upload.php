<?php $all_method =&get_instance();
$data['appointment_id'] = $appointment_id;
$data['patient_id'] = $patient_id;
$data['receipt_number'] = $receipt_number;
?>
<style type="text/css">
    table{
        width: 100%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
    [type="radio"]:not(:checked)+label:before, [type="radio"]:not(:checked)+label:after, [type="radio"]:checked+label:after, [type="radio"]:checked+label:before{display:none!important;}
    [type="radio"]:not(:checked), [type="radio"]:checked { position: relative!important; left: 0!important; opacity: 1!important; }
    [type="radio"]:not(:checked)+label, [type="radio"]:checked+label{padding-left:0px!important;}
</style>

<div class="col-md-12">
  <!-- Advanced Tables -->
  <div class="card">
    <div class="card-action"><h3>Procedure form</h3></div>
      <div class="clearfix"></div>
    <div class="card-content">
        <?php 
                if($_SERVER['REMOTE_ADDR'] == "122.179.206.176"){?>
    				<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>
    		<?php	} ?>
       <div class="row" id="print_this_section">
            
           <?php $this->load->view('discharge-forms/'.$form_name, $data);  ?>
            <!-- <form id="prodedure_form" action="" method="post" >
              <input type="hidden" value="prodedure_form" name="action" class="form-control" />
              <input type="hidden" value="<?php echo $form_from; ?>" name="form_from" class="form-control" />
             
              <input type="submit" class="btn btn-primary" value="Submit" />
            </form> -->
      </div>
	  </div>
    </div>
  </div>
  <!--End Advanced Tables -->
</div>

<script>
function printDiv() 
{
  $('.hide_print').hide();
  $('input[type="submit"]').css('visibility', 'hidden');
  $('p#last_updated').css('visibility', 'hidden');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>