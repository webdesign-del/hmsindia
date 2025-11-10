 <?php $all_method =&get_instance(); ?>
   <style type="text/css">
    form{
        margin: 20px 0;
    }
    form input, button{
        padding: 5px;
    }
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
</style>

<div class="col-sm-12 col-xs-12" >
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku">
      <div class="panel-heading">
        <h3 class="heading">Patient billed Items</h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>
        <div class="row">
          <div class="form-group col-sm-4 col-xs-12">
          <input value="" placeholder="IIC ID" id="paitent_id" name="paitent_id" type="text" class="form-control validate" required>
          </div>
          	<div class="form-group col-sm-3 col-xs-12">
                <a id="search_patient" href="javascript:void(0)" class="btn btn-large" required>Search</a>
            </div>
        </div>        
        <hr/>         
         <div class="row">            
           <div class="form-group col-sm-4 col-xs-12">
                <label for="item_name">Paitent Name (Required)</label>
                <input value="" placeholder="Paitent name" readonly="readonly" id="paitent_name" name="paitent_name" type="text" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-4 col-xs-12">
                <label for="item_name">Phone Number (Required)</label>
                 <input value="" placeholder="Phone Number" readonly="readonly" id="phone_number" name="phone_number" type="text" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-4 col-xs-12">
                <label for="item_name">Email ID (Required)</label>
                 <input value="" placeholder="Email ID" readonly="readonly" id="patient_email" name="patient_email" type="text" class="form-control validate" required>
           </div>
         </div>
        <hr/>    
        
        <div class="row patient_data_table">
              <p id="total_consum_price" class="pull-right"></p>
			  <p id="total_consum_vendor_price" class=""></p>
              <table>
                <thead>
                    <tr>
                        <th>Item code</th>
                        <th>Receipt number</th>
                        <th>Item name</th>
                        <th>Batch Number</th>
                        <th>Category</th>
                        <th>Openning Qty</th>
                                    <th>Qty</th>
                        <?php if($_SESSION['logged_administrator']){ ?>
                                    <th>Consumption Price</th>
                        <th>Vendor Price</th>
                        <?php } ?>
                        <th>Added On Date</th>
                    </tr>
                </thead>
                <tbody id="patient_item_table_body">
                    <tr><td colspan="8" align="center">No record!</td></tr>
                </tbody>
            </table>
         </div> 
      </div>
      </p>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).on('click',"#search_patient",function(e) {
    $('#total_consum_price').empty();
	$('#total_consum_vendor_price').empty();
	$('#patient_item_table_body').empty();
	$('#paitent_name').val('');
	$('#phone_number').val('');
	$('#patient_email').val('');
	var patient_id = $("#paitent_id").val();
	if(patient_id != ''){
		$.ajax({
			url: '<?php echo base_url('stocks/get_patient_items_data')?>',
			data: {patient_id : patient_id},
			dataType: 'json',
			method:'post',
			success: function(data)
			{
				$('#paitent_name').val(data.patient_name);
				$('#phone_number').val(data.patient_phone);
				$('#patient_email').val(data.patient_email);
				$('#patient_item_table_body').append(data.data);
				$('#total_consum_price').empty().append("<strong>Total Consumption Price : <i class='fa fa-inr' aria-hidden='true'></i>"+data.total_consum_price+"</strong>");
				$('#total_consum_vendor_price').empty().append("<strong>Total Consumption Vendor Price : <i class='fa fa-inr' aria-hidden='true'></i>"+data.total_consum_vendor_price+"</strong>");
			} 
	   });	
   }
});
</script>