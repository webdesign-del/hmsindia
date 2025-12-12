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
	.heading{margin-bottom:10px;margin-top: 0; padding-top:0px;}
</style>

<form class="col-sm-12 col-xs-12" id="add_billing_form" method="post" action="" no>
  <input type="hidden" name="action" value="add_billing_item" />
  
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku" id="procedure_details">
      <div class="panel-heading">
        <h3 class="heading">Patient Items</h3>
      </div>
      <div class="panel-body profile-edit">
     	<p id="msg_area" class="delete"></p>
        <p>
            <div id="main_div">
            	<div class="row">            
                   <div class="form-group col-sm-6 col-xs-12">
                        <label for="item_name">IIC ID (Required)</label>
						<input value="" placeholder="IIC ID" id="patient_id" name="patient_id" type="text" class="form-control required_value" required>
                   </div>
                   <div class="form-group col-sm-6 col-xs-12">
                        <label for="item_name">Patient Name (Required)</label>
						<input value="" placeholder="Patient Name" readonly id="patient_name" name="patient_name" type="text" class="form-control required_value" required>
				   
                        <input value="0" placeholder="Receipt number" id="receipt_number" name="receipt_number" type="hidden" class="form-control required_value" required>
                        <input type="hidden" class="form-control" value="<?php echo $_SESSION['logged_stock_manager']['employee_number']?>" id="employee_number" name="employee_number">
                        <input type="hidden" class="form-control" value="<?php echo $_SESSION['logged_stock_manager']['center']?>" id="center_number" name="center_number">
                    
					</div>
					<section class="col-sm-12 col-xs-12 medicine_section">
						<table>
							<thead>
								<tr>
									<th></th>
									<th>Procedure Name</th>
								</tr>
							</thead>
							<tbody id="">
									<tr class="medicine_row_1">
										<td><input type="checkbox" class="active-statuss" rel="medicine" index="1"></td>
										<td class="role medic_cls_1">
										<select name="procedure_name" class="item_select  medic-cls-1" id="procedure_name" required>
										<option value="">Select</option>
										<?php 
										$sql1 = "Select * from ".$this->config->item('db_prefix')."procedures where status='1'"; 
										$query = $this->db->query($sql1);
										$select_result1 = $query->result(); 
										foreach ($select_result1 as $res_val){
									?>
									<option value="<?php echo $res_val->procedure_name; ?> - <?php echo $res_val->code; ?>"><?php echo $res_val->procedure_name; ?> - <?php echo $res_val->code; ?></option>
									<?php } ?>
									<option value="Embryo Transfer">Embryo Transfer </option>
									<option value="Embryo Transfer Under GA">Embryo Transfer Under GA</option>
									<option value="IUI">IUI</option>
									<option value="tesa">Tesa</option>
									<option value="OPU">Opu</option>
									<option value="Ivf">Ivf</option>
									<option value="FROZEN THAW OOCYTE ICSI">FROZEN THAW OOCYTE ICSI (FTOI)</option>
									<option value="Embryo Biopsy">Embryo Biopsy </option>
									<option value="Egg Freezing">Egg Thawing </option>
									<option value="Blastocyst Culture">Blastocyst Culture </option>
									<option value="DFI">DFI</option>
									<option value="Embryo Transfer Under GA">Candore </option>
									<option value="Sperm Mobile">Sperm Mobile </option>
									<option value="Oocyte Activation AOA">Oocyte Activation AOA</option>
									<option value="MICRO TESA">MICRO TESA </option>
									<option value="DEPARTMENTAL">DEPARTMENTAL </option>
									</select>
									</td>
								</tr>
							</tbody>
						</table>
					</section>
                 </div>
                <section class="col-sm-12 col-xs-12 medicine_section">
                  <h4 class="heading">Patient Embrology</h4>
                  <div class="clearfix"></div>
                  <input type="button" class="add-medicine-row btn btn-large" value="Add Embrology">
                  <input type="button" class="delete-medicine-row btn btn-large pull-right" value="Delete Selected Embrology">
                  <table>
				  
                    <thead>
                        <tr>
                            <th></th>
                            <th>Serial Number</th>
                            <th>Medicine</th>
							 <!--<th>ID</th>-->
                            <th>Consumption/patient (unit)</th>
							<th>Batch Number</th>
                            <th>Open Stock</th>
                            <th>Price (<i class="fa fa-inr" aria-hidden="true"></i>)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="medicine_table_body">
					    <tr class="medicine_row_1" trcount="1">
                            <td><input type="checkbox" class="active-statuss" rel="medicine" index="1"></td>
                            <td><input value="" placeholder="Serial Number" readonly="readonly" id="medicine_serial_1" class="medicine_serial_1 medic-cls-1 form-control" name="medicine_serial_1" type="text" required></td>
                            <td class="role medic_cls_1">
                                <select disabled name="medicine_name_1" class="item_select medicine_select medic-cls-1" id="medicine_name_1" count="1" required>
                                    <option value="">Select</option>
                                    <?php foreach($medicine as $key => $val){ ?>
                                        <option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" batch_number="<?php echo $val['batch_number']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" expiry="<?php echo $val['expiry']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" pack_size="<?php echo $val['pack_size']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option>
                                    <?php } ?>
                                </select>
                            </td>
							
                        
                           <td>
						       <input value="" item_number="" placeholder="ID" readonly="readonly" id="medicine_ID_1" class="cons-cls-1 medicine_ID_1 form-control" name="medicine_ID_1" type="hidden" required>
							   <input value="" item_number="" placeholder="company" id="medicine_company_1" class="cons-cls-1 medicine_company_1 form-control" name="medicine_company_1" type="hidden">
						       <input value="" item_number="" placeholder="Item Name" id="medicine_item_name_1" class="cons-cls-1 medicine_item_name_1 form-control" name="medicine_item_name_1" type="hidden">
						       <input value="" item_number="" placeholder="HSN" id="medicine_hsn_1" class="cons-cls-1 medicine_hsn_1 form-control" name="medicine_hsn_1" type="hidden">
							   <input disabled value="" item_number="" placeholder="Consumption/patient (unit)" id="medicine_quantity_1" qcount="1" onkeyup="medicine_quantity_update(this)" class="medic-cls-1 medicine_quantity medicine_quantity_1 form-control" name="medicine_quantity_1" type="number" min="0" required>
						   </td>
                           <td><input value="" item_number="" placeholder="Batch Number" readonly="readonly" id="medicine_batch_number_1" class="cons-cls-1 medicine_batch_number_1 form-control" name="medicine_batch_number_1" type="text" required></td> 
                        
						  <td><input value="" placeholder="Open Stock" readonly="readonly" id="medicine_stock_1" class="cons-cls-1 medicine_stock_1 form-control" name="medicine_stock_1" type="text" required></td> 
                          <td><input value="" item_number="" placeholder="MRP" id="medicine_mrp_1" class="cons-cls-1 medicine_mrp_1 form-control" name="medicine_mrp_1" readonly="readonly" type="text">
                          <input value="" item_number="" id="medicine_vendor_price_1" class="cons-cls-1 medicine_vendor_price_1 form-control" name="medicine_vendor_price_1" type="hidden">
                          <input value="" item_number="" id="medicine_pack_size_1" class="cons-cls-1 medicine_pack_size_1 form-control" name="medicine_pack_size_1" type="hidden">
                          <input value="" item_number="" id="medicine_expiry_1" class="cons-cls-1 medicine_expiry_1 form-control" name="medicine_expiry_1" type="hidden">
						  <input value="" item_number="" id="medicine_gstrate_1" class="cons-cls-1 medicine_gstrate_1 form-control" name="medicine_gstrate_1" type="hidden">
						  <input value="" item_number="" id="medicine_gstdivision_1" class="cons-cls-1 medicine_gstdivision_1 form-control" name="medicine_gstdivision_1" type="hidden">
						  </td>
                          <td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                    </tbody>
                </table>                
                </section>
                <div class="clearfix"></div>
                <hr />
                <section class="col-sm-12 col-xs-12 injections_section">
                  <h4 class="heading">Patient Injections</h4>
                  <div class="clearfix"></div>
                  <input type="button" class="add-injections-row btn btn-large" value="Add Injection">
                  <input type="button" class="delete-injections-row btn btn-large pull-right" value="Delete Selected Injection">
                  <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Serial Number</th>
                            <th>Injections</th>
							<!--<th>ID</th>-->
                            <th>Consumption/patient (unit)</th>
							<th>Batch Number</th>
                            <th>Open Stock</th>
                            <th>Price (<i class="fa fa-inr" aria-hidden="true"></i>)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="injections_table_body">
                        <tr class="injections_row_1" trcount="1">
                            <td><input type="checkbox" class="active-statuss" rel="injections" index="1"></td>
                            <td><input value="" placeholder="Serial Number" readonly="readonly" id="injections_serial_1" class="injc-cls-1 injections_serial_1 form-control" name="injections_serial_1" type="text" required></td>
                            <td class="role injc_cls_1">
                                <select disabled name="injections_name_1" class="item_select injc-cls-1 injections_select" id="injections_name_1" count="1" required>
                                    <option value="">Select</option>
                                <?php foreach($injections as $key => $val){ ?>
                                    <option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" batch_number="<?php echo $val['batch_number']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" expiry="<?php echo $val['expiry']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" pack_size="<?php echo $val['pack_size']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option>
                                <?php } ?>
                                </select>
                            </td>
							 
                        
                            <td><input value="" item_number="" placeholder="ID" readonly="readonly" id="injections_ID_1" class="cons-cls-1 injections_ID_1 form-control" name="injections_ID_1" type="hidden" required>
							<input value="" item_number="" placeholder="injections" id="injections_company_1" class="cons-cls-1 injections_company_1 form-control" name="injections_company_1" type="hidden">
						       <input value="" item_number="" placeholder="Item Name" id="injections_item_name_1" class="cons-cls-1 injections_item_name_1 form-control" name="injections_item_name_1" type="hidden">
						       <input value="" item_number="" placeholder="HSN" id="injections_hsn_1" class="cons-cls-1 injections_hsn_1 form-control" name="injections_hsn_1" type="hidden">
							<input disabled value="" item_number="" placeholder="Consumption/patient (unit)" id="injections_quantity_1" qcount="1" onkeyup="injections_quantity_update(this)" class="injc-cls-1 injections_quantity injections_quantity_1 form-control" name="injections_quantity_1" type="number" min="0"></td>
                           
						    <td><input value="" item_number="" placeholder="Batch Number" readonly="readonly" id="injections_batch_number_1" class="cons-cls-1 injections_batch_number_1 form-control" name="injections_batch_number_1" type="text" required></td> 
                        <td><input value="" placeholder="Open Stock" readonly="readonly" id="injections_stock_1" class="cons-cls-1 injections_stock_1 form-control" name="injections_stock_1" type="text" required></td> 
                          <td><input value="" placeholder="Price" readonly="readonly" id="injections_price_1" class="injc-cls-1 injections_price form-control" name="injections_price_1" type="hidden" required>
						  <input value="" item_number="" id="injections_vendor_price_1" class="cons-cls-1 injections_vendor_price_1 form-control" name="injections_vendor_price_1" type="hidden">
						  <input value="" item_number="" id="injections_expiry_1" class="cons-cls-1 injections_expiry_1 form-control" name="injections_expiry_1" type="hidden">
						  <input value="" item_number="" placeholder="MRP" id="injections_mrp_1" class="cons-cls-1 injections_mrp_1 form-control" readonly="" name="injections_mrp_1" type="text">
						  <input value="" item_number="" placeholder="MRP" id="injections_pack_size_1" class="cons-cls-1 injections_pack_size_1 form-control" readonly="" name="injections_pack_size_1" type="hidden">     
						  <input value="" item_number="" id="injections_gstrate_1" class="cons-cls-1 injections_gstrate_1 form-control" name="injections_gstrate_1" type="hidden">
						  <input value="" item_number="" id="injections_gstdivision_1" class="cons-cls-1 injections_gstdivision_1 form-control" name="injections_gstdivision_1" type="hidden">
						  </td>
                          <td><input type="checkbox" class="statuss" name="record"></td>  
                        </tr>
                    </tbody>
                </table>
                </section>
                <div class="clearfix"></div>
                <hr />
                <section class="col-sm-12 col-xs-12 consumables_section">
                  <h4 class="heading">OT Consumables</h4>
                  <div class="clearfix"></div>
                  <input type="button" class="add-consumables-row btn btn-large" value="Add OT Consumables">
                  <input type="button" class="delete-consumables-row btn btn-large pull-right" value="Delete Selected OT Consumables">
                  <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Serial Number</th>
                            <th>Consumable</th>
							<!--<th>ID</th>-->
                            <th>Consumption/patient (unit)</th>
                            <th>Batch Number</th>
							<th>Open Stock</th>
                            <th>Price (<i class="fa fa-inr" aria-hidden="true"></i>)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="consumables_table_body">
                        <tr class="consumables_row_1" trcount="1">
                            <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                            <td><input value="" placeholder="Serial Number" readonly="readonly" id="consumables_serial_1" class="cons-cls-1 consumables_serial_1 form-control" name="consumables_serial_1" type="text" required></td>
                            <td class="role cons_cls_1">
                                <select disabled name="consumables_name_1" class="item_select consumables_select cons-cls-1" id="consumables_name_1" count="1" required>
                                    <option value="">Select</option>
                                <?php foreach($consumables as $key => $val){ ?>
                                    <option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" batch_number="<?php echo $val['batch_number']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" expiry="<?php echo $val['expiry']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" pack_size="<?php echo $val['pack_size']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option>
                                <?php } ?>
                                </select>
                            </td>
							 
                        
                            <td><input value="" item_number="" placeholder="ID" readonly="readonly" id="consumables_ID_1" class="cons-cls-1 consumables_ID_1 form-control" name="consumables_ID_1" type="hidden">
							<input value="" item_number=""  id="consumables_company_1" class="cons-cls-1 consumables_company_1 form-control" name="consumables_company_1" type="hidden">
						       <input value="" item_number="" id="consumables_item_name_1" class="cons-cls-1 consumables_item_name_1 form-control" name="consumables_item_name_1" type="hidden">
						       <input value="" item_number="" id="consumables_hsn_1" class="cons-cls-1 consumables_hsn_1 form-control" name="consumables_hsn_1" type="hidden">
							<input disabled value="" item_number="" placeholder="Consumption/patient (unit)" id="consumables_quantity_1" qcount="1"  onkeyup="consumables_quantity_update(this)" class="cons-cls-1 consumables_quantity consumables_quantity_1 form-control" name="consumables_quantity_1" type="number" min="0" required></td>
                            <td><input value="" item_number="" placeholder="Batch Number" readonly="readonly" id="consumables_batch_number_1" class="cons-cls-1 consumables_batch_number_1 form-control" name="consumables_batch_number_1" type="text" required></td> 
                            <td><input value="" placeholder="Open Stock" readonly="readonly" id="consumables_stock_1" class="cons-cls-1 consumables_stock_1 form-control" name="consumables_stock_1" type="text" required></td> 
                            <td><input value="" placeholder="Price" readonly="readonly" id="consumables_price_1" class="cons-cls-1 consumables_price form-control" name="consumables_price_1" type="hidden" required>
							<input value="" item_number="" readonly="" id="consumables_mrp_1" class="cons-cls-1 consumables_mrp_1 form-control" name="consumables_mrp_1" type="text">
						    <input value="" item_number="" id="consumables_vendor_price_1" class="cons-cls-1 consumables_vendor_price_1 form-control" name="consumables_vendor_price_1" type="hidden">
							<input value="" item_number="" id="consumables_pack_size_1" class="cons-cls-1 consumables_pack_size_1 form-control" name="consumables_pack_size_1" type="hidden">
							<input value="" item_number="" id="consumables_expiry_1" class="cons-cls-1 consumables_expiry_1 form-control" name="consumables_expiry_1" type="hidden">
							<input value="" item_number="" id="consumables_gstrate_1" class="cons-cls-1 consumables_gstrate_1 form-control" name="consumables_gstrate_1" type="hidden">
							<input value="" item_number="" id="consumables_gstdivision_1" class="cons-cls-1 consumables_gstdivision_1 form-control" name="consumables_gstdivision_1" type="hidden">
							</td>                 
                            <td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                    </tbody>
                </table>                
                </section>
                <div class="clearfix"></div>
                <hr /> 
                <div class="form-group col-sm-12 col-xs-12">
                	<button type="button" id="create_billing"> Create Billing </button>
                    <p id="error" class="error delete"></p>
                </div>
            </div>
      </div>
      </p>
    </div>
  </div>
</form>
<!--****** MEDICINE SCRIPT *******-->
<script>

$(document).on('blur',"#patient_id",function(e) {
        $('#patient_name').empty();
		
        var patient_id = $(this).val();
        if(patient_id != ""){
            $.ajax({
                url: '<?php echo base_url('patients/patient_detail_name2')?>',
                data: {patient_id : patient_id},
                dataType: 'json',
                method:'post',
                success: function(data)
                {
					$('#patient_name').val(data);
                   // $('#patient_detail_name').append(data); 
                }
            });
        }
    });

	 $(document).on('change',".medicine_select",function(e) {
        $('#msg_area').empty();
		var serial = $(this).val();
		var count = $(this).attr('count');
		
		$('#medicine_ID_'+count).val('');
		$('#medicine_serial_'+count).val('');
		$('#medicine_company_'+count).val('');
		$('#medicine_item_name_'+count).val('');
		$('#medicine_mrp_'+count).val('');
		$('#medicine_hsn_'+count).val('');
		$('#medicine_quantity_'+count).val('');
		$('#medicine_stock_'+count).val('');
		//$('#medicine_price_'+count).val('');
		$('#medicine_batch_number_'+count).val('');
        $('#medicine_quantity_'+count).attr("item_number", "");
		
		
		$('#medicine_sub_total').val('');
		$('#medicine_discount').val(0);
		$('#medicine_total').val('');
		$('#medicine_vendor_price_'+count).val('');
		$('#medicine_pack_size_'+count).val('');
		$('#medicine_expiry_'+count).val('');
		$('#medicine_gstrate_'+count).val('');
		$('#medicine_gstdivision_'+count).val('');
		
		
		if(serial != ''){
			var serial = $(this).val();
			var ID = $(this).find(':selected').attr('ID');
			var company = $(this).find(':selected').attr('company');
			var item_name = $(this).find(':selected').attr('item_name');
			var mrp = $(this).find(':selected').attr('mrp');
			var hsn = $(this).find(':selected').attr('hsn');
			var batch_number = $(this).find(':selected').attr('batch_number');
			var quantity = $(this).find(':selected').attr('quantity');
			var fees = $(this).find(':selected').attr('fees');
			var vendor_price = $(this).find(':selected').attr('vendor_price');
			var pack_size = $(this).find(':selected').attr('pack_size');
			var expiry = $(this).find(':selected').attr('expiry');
			var gstrate = $(this).find(':selected').attr('gstrate');
			var gstdivision = $(this).find(':selected').attr('gstdivision');
			
			
			$('#medicine_serial_'+count).val(serial);
			$('#medicine_ID_'+count).val(ID);
			$('#medicine_company_'+count).val(company);
			$('#medicine_item_name_'+count).val(item_name);
			$('#medicine_mrp_'+count).val(mrp);
			$('#medicine_hsn_'+count).val(hsn);
            $('#medicine_batch_number_'+count).val(batch_number);
			$('#medicine_stock_'+count).val(quantity);
			
			$('#medicine_quantity_'+count).attr({'max': parseInt(quantity), 'min': 0});
            $('#medicine_quantity_'+count).attr("item_number", serial);
		    $('#medicine_quantity_'+count).attr("item_quantity", quantity);
			
            //$('#medicine_quantity_'+count).attr("item_number", serial);
			//$('#medicine_price_'+count).val(fees);
			$('#medicine_vendor_price_'+count).val(vendor_price);
			$('#medicine_pack_size_'+count).val(pack_size);
			$('#medicine_expiry_'+count).val(expiry);
			$('#medicine_gstrate_'+count).val(gstrate);
			$('#medicine_gstdivision_'+count).val(gstdivision);
		}
			var fee_total = 0;
			$('.medicine_price').each(function(){
				var price_total = 0;
				var price_total = $(this).val();
				fee_total += +price_total;
			});
			$('#medicine_sub_total').val(fee_total);
			$('#medicine_total').val(fee_total);
			//calculate_fees();
    });
	
	 $(document).ready(function(){
        $(".add-medicine-row").click(function(){
			var rows= $('#medicine_table_body tr:last').attr('trcount');
			var count = parseFloat(rows) + 1;
            var markup = '<tr class="medicine_row_'+count+'" trcount="'+count+'"><td><input type="checkbox" class="active-statuss" rel="medicine"  index="'+count+'"></td><td><input value="" placeholder="Serial Number" readonly="readonly" id="medicine_serial_'+count+'" class="medic-cls-'+count+' form-control medicine_serial_'+count+'" name="medicine_serial_'+count+'" type="text" required></td><td class="role medic_cls_'+count+'"><select disabled name="medicine_name_'+count+'" class="medic-cls-'+count+' item_select medicine_select form-control" id="medicine_name_'+count+'" count="'+count+'" required><option value="">Select</option><?php foreach($medicine as $key => $val){ ?><option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" batch_number="<?php echo $val['batch_number']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" expiry="<?php echo $val['expiry']; ?>" pack_size="<?php echo $val['pack_size']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option><?php } ?></select></td><td><input value="" placeholder="ID" readonly="readonly" id="medicine_ID_'+count+'" class="medic-cls-'+count+' medicine_ID_'+count+' form-control" name="medicine_ID_'+count+'" type="hidden" required><input value="" qcount="'+count+'" placeholder="Consumption/patient (unit)" onkeyup="medicine_quantity_update(this)" disabled item_quantity="" item_number="" id="medicine_quantity_'+count+'" class="medic-cls-'+count+' medicine_quantity medicine_quantity_'+count+' form-control" name="medicine_quantity_'+count+'" type="number" min="0" required></td><td><input value="" placeholder="Batch Number" readonly="readonly" id="medicine_batch_number_'+count+'" class="cons-cls-'+count+' medicine_batch_number form-control "  name="medicine_batch_number_'+count+'" type="text" required></td><td><input value="" placeholder="Open Stock" readonly="readonly" id="medicine_stock_'+count+'" class="medic-cls-'+count+' medicine_stock_'+count+' form-control" name="medicine_stock_'+count+'" type="text" required></td><td><input value="" placeholder="MRP" readonly="readonly" id="medicine_mrp_'+count+'" class="medic-cls-'+count+' medicine_mrp form-control" name="medicine_mrp_'+count+'" type="text" required><input value="" placeholder="Price" readonly="readonly" id="medicine_vendor_price_'+count+'" class="medic-cls-'+count+' medicine_vendor_price form-control" name="medicine_vendor_price_'+count+'" type="hidden" required><input value="" id="medicine_gstrate_'+count+'" class="medic-cls-'+count+' medicine_gstrate form-control" name="medicine_gstrate_'+count+'" type="hidden"><input value="" id="medicine_gstdivision_'+count+'" class="medic-cls-'+count+' medicine_gstdivision form-control" name="medicine_gstdivision_'+count+'" type="hidden"><input value="" id="medicine_item_name_'+count+'" class="medic-cls-'+count+' medicine_item_name form-control" name="medicine_item_name_'+count+'" type="hidden"><input value="" id="medicine_company_'+count+'" class="medic-cls-'+count+' medicine_company form-control" name="medicine_company_'+count+'" type="hidden"><input value="" id="medicine_hsn_'+count+'" class="medic-cls-'+count+' medicine_hsn form-control" name="medicine_hsn_'+count+'" type="hidden"><input value="" id="medicine_expiry_'+count+'" class="medic-cls-'+count+' medicine_expiry form-control" name="medicine_expiry_'+count+'" type="hidden"><input value="" id="medicine_pack_size_'+count+'" cpacmedic-cls-'+count+' medicine_pack_size form-control" name="medicine_pack_size_'+count+'" type="hidden"></td><td><input type="checkbox" class="statuss" name="record"></td></tr>';
            $("table tbody#medicine_table_body").append(markup);
		//	calculate_fees();
        });
        
        // Find and remove selected table rows
        $(".delete-medicine-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
				var fee_total = 0;
				$('.medicine_price').each(function(){
					var price_total = 0;
					var price_total = $(this).val();
					fee_total += +price_total;
				});
				$('#medicine_sub_total').val(fee_total);
				$('#medicine_discount').val(0);
				$('#medicine_total').val(fee_total);
            });
			//calculate_fees();
        });
    });

</script>
<!--****** MEDICINE SCRIPT *******-->

<!--****** Injections SCRIPT *******-->
<script>
	 $(document).on('change',".injections_select",function(e) {
        $('#msg_area').empty();
		var serial = $(this).val();
		var count = $(this).attr('count');
		
		$('#injections_ID_'+count).val('');
		$('#injections_serial_'+count).val('');
		$('#injections_item_name_'+count).val('');
		$('#injections_company_'+count).val('');
		$('#injections_mrp_'+count).val('');
		$('#injections_hsn_'+count).val('');
		$('#injections_quantity_'+count).val('');
		$('#injections_stock_'+count).val('');
		$('#injections_price_'+count).val('');
		$('#injections_batch_number_'+count).val('');
        $('#injections_quantity_'+count).attr("item_number", "");
		$('#injections_sub_total').val('');
		$('#injections_discount').val(0);
		$('#injections_total').val('');
		$('#injections_vendor_price_'+count).val('');
		$('#injections_expiry_'+count).val('');
		$('#injections_gstrate_'+count).val('');
		$('#injections_gstdivision_'+count).val('');
		$('#injections_pack_size_'+count).val('');
		
		
		if(serial != ''){
			var serial = $(this).val();
			var ID = $(this).find(':selected').attr('ID');
			var item_name = $(this).find(':selected').attr('item_name');
			var company = $(this).find(':selected').attr('company');
			var mrp = $(this).find(':selected').attr('mrp');
			var hsn = $(this).find(':selected').attr('hsn');
			var batch_number = $(this).find(':selected').attr('batch_number');
			var quantity = $(this).find(':selected').attr('quantity');
			var fees = $(this).find(':selected').attr('fees');
			var vendor_price = $(this).find(':selected').attr('vendor_price');
			var expiry = $(this).find(':selected').attr('expiry');
			var gstrate = $(this).find(':selected').attr('gstrate');
			var gstdivision = $(this).find(':selected').attr('gstdivision');
			var pack_size = $(this).find(':selected').attr('pack_size');
			
			$('#injections_ID_'+count).val(ID);
			$('#injections_serial_'+count).val(serial);
			$('#injections_item_name_'+count).val(item_name);
			$('#injections_company_'+count).val(company);
			$('#injections_mrp_'+count).val(mrp);
			$('#injections_hsn_'+count).val(hsn);
			$('#injections_batch_number_'+count).val(batch_number);
			$('#injections_stock_'+count).val(quantity);
			$('#injections_quantity_'+count).attr({'max': parseInt(quantity), 'min': 0});
            $('#injections_quantity_'+count).attr("item_number", serial);
		    $('#injections_quantity_'+count).attr("item_quantity", quantity);
			//$('#injections_quantity_'+count).attr("item_number", serial);
			$('#injections_price_'+count).val(fees);
			$('#injections_vendor_price_'+count).val(vendor_price);
			$('#injections_expiry_'+count).val(expiry);
			$('#injections_gstrate_'+count).val(gstrate);
			$('#injections_gstdivision_'+count).val(gstdivision);
			$('#injections_pack_size_'+count).val(pack_size);
		}
			var fee_total = 0;
			$('.injections_price').each(function(){
				var price_total = 0;
				var price_total = $(this).val();
				fee_total += +price_total;
			});
			$('#injections_sub_total').val(fee_total);
			$('#injections_total').val(fee_total);
		//	calculate_fees();
    });
	
	 $(document).ready(function(){
        $(".add-injections-row").click(function(){
			var rows= $('#injections_table_body tr:last').attr('trcount');
			var count = parseFloat(rows) + 1;
            var markup = '<tr class="injections_row_'+count+'" trcount="'+count+'"><td><input type="checkbox" class="active-statuss"  rel="injections"  index="'+count+'"></td><td><input value="" placeholder="Serial Number" readonly="readonly" id="injections_serial_'+count+'" class="injc-cls-'+count+' injections_serial_'+count+' form-control" name="injections_serial_'+count+'" type="text" required></td><td class="role injc_cls_'+count+'"><select disabled name="injections_name_'+count+'" class="injc-cls-'+count+' item_select injections_select form-control" id="injections_name_'+count+'" count="'+count+'" required><option value="">Select</option><?php foreach($injections as $key => $val){ ?><option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" batch_number="<?php echo $val['batch_number']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" expiry="<?php echo $val['expiry']; ?>" pack_size="<?php echo $val['pack_size']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option><?php } ?></select></td><td><input value="" placeholder="ID" readonly="readonly" id="injections_ID_'+count+'" class="cons-cls-'+count+' injections_ID form-control"  name="injections_ID_'+count+'" type="hidden" required><input value="" qcount="'+count+'" disabled placeholder="Consumption/patient (unit)" item_number=""  onkeyup="injections_quantity_update(this)" id="injections_quantity_'+count+'" class="injc-cls-'+count+' injections_quantity disabled item_quantity="" injections_quantity_'+count+' form-control" name="injections_quantity_'+count+'" type="number" min="0" required></td><td><input value="" placeholder="Batch Number" readonly="readonly" id="injections_batch_number_'+count+'" class="cons-cls-'+count+' injections_batch_number form-control "  name="injections_batch_number_'+count+'" type="text" required></td><td><input value="" placeholder="Open Stock" readonly="readonly" id="injections_stock_'+count+'" class="injc-cls-'+count+' injections_stock_'+count+' form-control " name="injections_stock_'+count+'" type="text" required></td><td><input value="" placeholder="Price" readonly="readonly" id="injections_price_'+count+'" class="injc-cls-'+count+' injections_price form-control " name="injections_price_'+count+'" type="hidden" required><input value="" placeholder="Price" readonly="readonly" id="injections_vendor_price_'+count+'" class="injc-cls-'+count+' injections_vendor_price form-control " name="injections_vendor_price_'+count+'" type="hidden"><input value="" id="injections_expiry_'+count+'" class="injc-cls-'+count+' injections_expiry form-control " name="injections_expiry_'+count+'" type="hidden"><input value="" id="injections_gstrate_'+count+'" class="injc-cls-'+count+' injections_gstrate form-control " name="injections_gstrate_'+count+'" type="hidden"><input value="" id="injections_gstdivision_'+count+'" class="injc-cls-'+count+' injections_gstdivision form-control " name="injections_gstdivision_'+count+'" type="hidden"><input value="" id="injections_item_name_'+count+'" class="injc-cls-'+count+' injections_item_name form-control " name="injections_item_name_'+count+'" type="hidden"><input value="" id="injections_company_'+count+'" class="injc-cls-'+count+' injections_company form-control " name="injections_company_'+count+'" type="hidden"><input value="" readonly="" id="injections_mrp_'+count+'" class="injc-cls-'+count+' injections_mrp form-control " name="injections_mrp_'+count+'" type="text"><input value="" id="injections_hsn_'+count+'" class="injc-cls-'+count+' injections_hsn form-control " name="injections_hsn_'+count+'" type="hidden"><input value="" id="injections_pack_size_'+count+'" class="injc-cls-'+count+' injections_pack_size form-control " name="injections_pack_size_'+count+'" type="hidden"></td><td><input type="checkbox" class="statuss" name="record"></td></tr>';
            $("table tbody#injections_table_body").append(markup);
		//	calculate_fees();
        });
        
        // Find and remove selected table rows
        $(".delete-injections-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
				var fee_total = 0;
				$('.injections_price').each(function(){
					var price_total = 0;
					var price_total = $(this).val();
					fee_total += +price_total;
				});
				$('#injections_sub_total').val(fee_total);
				$('#injections_discount').val(0);
				$('#injections_total').val(fee_total);
            });
		//	calculate_fees();
        });
    });
</script>
<!--****** Injections SCRIPT *******-->

<!--****** consumables SCRIPT *******-->
<script>
	 $(document).on('change',".consumables_select",function(e) {
        $('#msg_area').empty();
		var serial = $(this).val();
		var count = $(this).attr('count');
		
		$('#consumables_ID_'+count).val('');
		$('#consumables_serial_'+count).val('');
		$('#consumables_item_name_'+count).val('');
		$('#consumables_company_'+count).val('');
		$('#consumables_mrp_'+count).val('');
		$('#consumables_hsn_'+count).val('');
		$('#consumables_quantity_'+count).val('');
		$('#consumables_stock_'+count).val('');
		$('#consumables_price_'+count).val('');
		$('#consumables_batch_number_'+count).val('');
		$('#consumables_quantity_'+count).attr("item_number", "");
        $('#consumables_sub_total').val('');
		$('#consumables_discount').val(0);
		$('#consumables_total').val('');
		$('#consumables_vendor_price_'+count).val('');
		$('#consumables_expiry_'+count).val('');
		$('#consumables_gstrate_'+count).val('');
		$('#consumables_gstdivision_'+count).val('');
		$('#consumables_pack_size_'+count).val('');
		
		if(serial != ''){
			var serial = $(this).val();
			var ID = $(this).find(':selected').attr('ID');
			var item_name = $(this).find(':selected').attr('item_name');
			var company = $(this).find(':selected').attr('company');
			var mrp = $(this).find(':selected').attr('mrp');
			var hsn = $(this).find(':selected').attr('hsn');
			var batch_number = $(this).find(':selected').attr('batch_number');
			var quantity = $(this).find(':selected').attr('quantity');
			var fees = $(this).find(':selected').attr('fees');
			var vendor_price = $(this).find(':selected').attr('vendor_price');
			var expiry = $(this).find(':selected').attr('expiry');
			var gstrate = $(this).find(':selected').attr('gstrate');
			var gstdivision = $(this).find(':selected').attr('gstdivision');
			var pack_size = $(this).find(':selected').attr('pack_size');
			
			$('#consumables_ID_'+count).val(ID);
			$('#consumables_serial_'+count).val(serial);
			$('#consumables_item_name_'+count).val(item_name);
			$('#consumables_company_'+count).val(company);
			$('#consumables_mrp_'+count).val(mrp);
			$('#consumables_hsn_'+count).val(hsn);
			$('#consumables_batch_number_'+count).val(batch_number);
			$('#consumables_stock_'+count).val(quantity);
            $('#consumables_quantity_'+count).attr({'max': parseInt(quantity), 'min': 0});
            $('#consumables_quantity_'+count).attr("item_number", serial);
		    $('#consumables_quantity_'+count).attr("item_quantity", quantity);
			//$('#consumables_quantity_'+count).attr("item_number", serial);
			$('#consumables_price_'+count).val(fees);
			$('#consumables_vendor_price_'+count).val(vendor_price);
			$('#consumables_expiry_'+count).val(expiry);
			$('#consumables_gstrate_'+count).val(gstrate);
			$('#consumables_gstdivision_'+count).val(gstdivision);
			$('#consumables_pack_size_'+count).val(pack_size);
			
		}
			var fee_total = 0;
			$('.consumables_price').each(function(){
				var price_total = 0;
				var price_total = $(this).val();
				fee_total += +price_total;
			});
			$('#consumables_sub_total').val(fee_total);
			$('#consumables_total').val(fee_total);
		//	calculate_fees();
    });
	
	 $(document).ready(function(){
        $(".add-consumables-row").click(function(){
			var rows= $('#consumables_table_body tr:last').attr('trcount');
			var count = parseFloat(rows) + 1;
            //var markup = '<tr class="consumables_row_'+count+'" trcount="'+count+'"><td><input type="checkbox" class="active-statuss"  rel="consumables"  index="'+count+'"></td><td><input value="" placeholder="Serial Number" readonly="readonly" id="consumables_serial_'+count+'" class="cons-cls-'+count+' consumables_serial_'+count+' form-control" name="consumables_serial_'+count+'" type="text" required></td><td class="role cons_cls_'+count+'"><select disabled name="consumables_name_'+count+'" class="cons-cls-'+count+' item_select consumables_select form-control" id="consumables_name_'+count+'" count="'+count+'" required><option value="">Select</option><?php foreach($consumables as $key => $val){ ?><option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" batch_number="<?php echo $val['batch_number']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" expiry="<?php echo $val['expiry']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option><?php } ?></select></td><td><input value="" placeholder="ID" readonly="readonly" id="consumables_ID_'+count+'" class="cons-cls-'+count+' consumables_ID form-control"  name="consumables_ID_'+count+'" type="hidden" required><input value="" qcount="'+count+'" onkeyup="consumables_quantity_update(this)" placeholder="Consumption/patient (unit)"  item_quantity="" item_number="" id="consumables_quantity_'+count+'" disabled class="consumables_quantity consumables_quantity_'+count+' form-control cons-cls-'+count+'" name="consumables_quantity_'+count+'" type="number" min="0" required></td><td><input value="" placeholder="Batch Number" readonly="readonly" id="consumables_batch_number_'+count+'" class="cons-cls-'+count+' consumables_batch_number form-control "  name="consumables_batch_number_'+count+'" type="text"></td><td><input value="" placeholder="Open Stock" readonly="readonly" id="consumables_stock_'+count+'" class="cons-cls-'+count+' consumables_stock_'+count+' form-control " name="consumables_stock_'+count+'" type="text"></td><td><input value="" placeholder="Price" readonly="readonly" id="consumables_price_'+count+'" class="cons-cls-'+count+' consumables_price form-control "  name="consumables_price_'+count+'" type="text"><input value="" id="consumables_vendor_price_'+count+'" class="cons-cls-'+count+' consumables_vendor_price_'+count+' form-control " name="consumables_vendor_price_'+count+'" type="hidden"><input value="" id="consumables_expiry_'+count+'" class="cons-cls-'+count+' consumables_expiry_'+count+' form-control " name="consumables_expiry_'+count+'" type="hidden"><input value="" id="consumables_gstrate_'+count+'" class="cons-cls-'+count+' consumables_gstrate_'+count+' form-control " name="consumables_gstrate_'+count+'" type="hidden"><input value="" id="consumables_gstdivision_'+count+'" class="cons-cls-'+count+' consumables_gstdivision_'+count+' form-control " name="consumables_gstdivision_'+count+'" type="hidden"><input value="" id="consumables_item_name_'+count+'" class="cons-cls-'+count+' consumables_item_name_'+count+' form-control " name="consumables_item_name_'+count+'" type="hidden"><input value="" id="consumables_company_'+count+'" class="cons-cls-'+count+' consumables_company_'+count+' form-control " name="consumables_company_'+count+'" type="hidden"><input value="" id="consumables_mrp_'+count+'" class="cons-cls-'+count+' consumables_mrp_'+count+' form-control " name="consumables_mrp_'+count+'" type="hidden"></td><td><input type="checkbox" class="statuss" name="record"></td></tr>';
              var markup = '<tr class="consumables_row_'+count+'" trcount="'+count+'"><td><input type="checkbox" class="active-statuss"  rel="consumables"  index="'+count+'"></td><td><input value="" placeholder="Serial Number" readonly="readonly" id="consumables_serial_'+count+'" class="cons-cls-'+count+' consumables_serial_'+count+' form-control" name="consumables_serial_'+count+'" type="text" required></td><td class="role cons_cls_'+count+'"><select disabled name="consumables_name_'+count+'" class="cons-cls-'+count+' item_select consumables_select form-control" id="consumables_name_'+count+'" count="'+count+'" required><option value="">Select</option><?php foreach($consumables as $key => $val){ ?><option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" batch_number="<?php echo $val['batch_number']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" gstrate="<?php echo $val['gstrate']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" expiry="<?php echo $val['expiry']; ?>" pack_size="<?php echo $val['pack_size']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['expiry']; ?>)</option><?php } ?></select></td><td><input value="" placeholder="ID" readonly="readonly" id="consumables_ID_'+count+'" class="cons-cls-'+count+' consumables_ID form-control"  name="consumables_ID_'+count+'" type="hidden" required><input value="" qcount="'+count+'" disabled placeholder="Consumption/patient (unit)" item_number=""  onkeyup="consumables_quantity_update(this)" id="consumables_quantity_'+count+'" class="cons-cls-'+count+' consumables_quantity disabled item_quantity="" consumables_quantity_'+count+' form-control" name="consumables_quantity_'+count+'" type="number" min="0" required></td><td><input value="" placeholder="Batch Number" readonly="readonly" id="consumables_batch_number_'+count+'" class="cons-cls-'+count+' consumables_batch_number form-control "  name="consumables_batch_number_'+count+'" type="text" required></td><td><input value="" placeholder="Open Stock" readonly="readonly" id="consumables_stock_'+count+'" class="cons-cls-'+count+' consumables_stock_'+count+' form-control " name="consumables_stock_'+count+'" type="text" required></td><td><input value="" placeholder="Price" readonly="readonly" id="consumables_price_'+count+'" class="cons-cls-'+count+' consumables_price form-control " name="consumables_price_'+count+'" type="hidden" required><input value="" placeholder="Price" readonly="readonly" id="consumables_vendor_price_'+count+'" class="cons-cls-'+count+' consumables_vendor_price form-control " name="consumables_vendor_price_'+count+'" type="hidden"><input value="" id="consumables_expiry_'+count+'" class="cons-cls-'+count+' consumables_expiry form-control " name="consumables_expiry_'+count+'" type="hidden"><input value="" id="consumables_gstrate_'+count+'" class="cons-cls-'+count+' consumables_gstrate form-control " name="consumables_gstrate_'+count+'" type="hidden"><input value="" id="consumables_gstdivision_'+count+'" class="cons-cls-'+count+' consumables_gstdivision form-control " name="consumables_gstdivision_'+count+'" type="hidden"><input value="" id="consumables_item_name_'+count+'" class="cons-cls-'+count+' consumables_item_name form-control " name="consumables_item_name_'+count+'" type="hidden"><input value="" id="consumables_company_'+count+'" class="cons-cls-'+count+' consumables_company form-control " name="consumables_company_'+count+'" type="hidden"><input value="" id="consumables_mrp_'+count+'" readonly="" class="cons-cls-'+count+' consumables_mrp form-control " name="consumables_mrp_'+count+'" type="text"><input value="" id="consumables_hsn_'+count+'" class="cons-cls-'+count+' consumables_hsn form-control " name="consumables_hsn_'+count+'" type="hidden"><input value="" id="consumables_pack_size_'+count+'" class="cons-cls-'+count+' consumables_pack_size form-control " name="consumables_pack_size_'+count+'" type="hidden"></td><td><input type="checkbox" class="statuss" name="record"></td></tr>';
            
			$("table tbody#consumables_table_body").append(markup);
		//	calculate_fees();
        });
        
        // Find and remove selected table rows
        $(".delete-consumables-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
				var fee_total = 0;
				$('.consumables_price').each(function(){
					var price_total = 0;
					var price_total = $(this).val();
					fee_total += +price_total;
				});
				$('#consumables_sub_total').val(fee_total);
				$('#consumables_discount').val(0);
				$('#consumables_total').val(fee_total);
            });
			//calculate_fees();
        });
    });
</script>
<!--****** consumables SCRIPT *******-->

<!--****** Billing SCRIPT *******-->
<!--<script>
	$(document).on('click',"#create_billing",function(e) {
       $('#error').empty();
	   var has_empty = false;
	   var patient_id = $('#patient_id').val();
	    var receipt_number = $('#receipt_number').val();
	   if ( patient_id == '' || receipt_number == '') 
	   {
		   $('#error').append('One or more fields are empty!');
	   }else{
	   		var countr = 1;
			var rows= $('#medicine_table_body tr').length;
			$('.medicine_select').each(function(){
				if(countr <= rows){
					if($(this).val() != ''){
						quantity = $('#medicine_quantity_'+countr).val();
						if(quantity == ''){
							has_empty = false;
						}else{
							has_empty = true;
						}
					}
					countr++;
				 }
			});
			
			var inj_count = 1;
			var inj_rows= $('#injections_table_body tr').length;
			$('.injections_select').each(function(){
				if(inj_count <= inj_rows){
					var name, price, serial, quantity, company = '';
					if($(this).val() != ''){
						quantity = $('#injections_quantity_'+inj_count).val();
						if(quantity == ''){
							has_empty = false;
						}else{
							has_empty = true;
						}
					}
					inj_count++;
				 }
			});
			
			var com_count = 1;
			var com_rows= $('#consumables_table_body tr').length;
			$('.consumables_select').each(function(){
				if(com_count <= com_rows){
					var name, price, serial, quantity, company = '';
					if($(this).val() != ''){
						quantity = $('#consumables_quantity_'+com_count).val();
						if(quantity == ''){
							has_empty = false;
						}else{
							has_empty = true;
						}
					}
					com_count++;
				 }
			});
			
			if(has_empty == true){
				$('#add_billing_form').submit();
			}else{
			   $('#error').append('One or more fields are empty!');
		   }	   		
	   }
    });
</script>-->
<script>
    $(document).on('click',".active-statuss",function(e) {
        var count = $(this).attr('index');
        var type = $(this).attr('rel');
        if($(this).is(':checked'))
        {
           // console.log(count+"---------"+type);
            if(type =="medicine"){
                $('td.role.medic_cls_'+count+' select.item_select').select2({tags: true});
                $('.medic-cls-'+count).prop("disabled", false);
                $('.medic-cls-'+count).addClass("required_value");
            } if(type =="consumables"){
                $('td.role.cons_cls_'+count+' select.item_select').select2({tags: true});
                $('.cons-cls-'+count).prop("disabled", false);
                $('.cons-cls-'+count).addClass("required_value");
            } if(type =="injections"){
                $('td.role.injc_cls_'+count+' select.item_select').select2({tags: true});
                $('.injc-cls-'+count).prop("disabled", false);
                $('.injc-cls-'+count).addClass("required_value");
            }if(type =="procedure"){
                $('td.role.injc_cls_pro_'+count+' select.item_select').select2({tags: true});
                $('.injc-cls-pro-'+count).prop("disabled", false);
                $('.injc-cls-pro-'+count).addClass("required_value");
            }
			
        }else
        {
            if(type =="medicine"){
                $('.medic-cls-'+count).prop("disabled", true);
                $('.medic-cls-'+count).removeClass("required_value");
            }
            if(type =="consumables"){
                $('.cons-cls-'+count).prop("disabled", true);
                $('.cons-cls-'+count).removeClass("required_value");
            }
            if(type =="injections"){
                $('.injc-cls-'+count).prop("disabled", true);
                $('.injc-cls-'+count).removeClass("required_value");
            }
        }       
    });
	$(document).on('click',"#create_billing",function(e) {
	 	  var value = $('.required_value').filter(function () {
			return this.value === '';
		  });
		  if (value.length == 0) {$('#add_billing_form').submit();} else if (value.length > 0) { alert('Please fill out all fields.'); }
    });


   // $('.consumables_quantity').on("keyup", function() {
	function consumables_quantity_update(el) {
      var count =  $(el).attr('qcount');
       var item_number =  $(el).attr('item_number');
	   var item_quantity =  $(el).attr('item_quantity');
       $('#consumables_price_'+count).val(" ");
       var units = $(el).val();

       if(units > 0){
		   if(parseInt(item_quantity) < parseInt(units)){
			   alert('Unit must be less than or equal to quantity');
			   $(el).val("0");
			   return false;
		   }else{
            $.ajax({
                url: '<?php echo base_url('stocks/get_stock_item_price')?>',
                data: {item_number : item_number, units:units},
                dataType: 'json',
                method:'post',
                success: function(data)
                {
                    $('#consumables_price_'+count).val(data.toFixed(2)); 
                }
            });
        } 
	   }
	}
   // });

    
    //$('.injections_quantity').on("keyup", function() {
	function injections_quantity_update(el) { 
       var count =  $(el).attr('qcount');
       var item_number =  $(el).attr('item_number');
	   var item_quantity =  $(el).attr('item_quantity');
       $('#injections_price_'+count).val(" ");
       var units = $(el).val();

       if(units > 0){
		   if(parseInt(item_quantity) < parseInt(units)){
			   alert('Unit must be less than or equal to quantity');
			   $(el).val("0");
			   return false;
		   }else{
            $.ajax({
                url: '<?php echo base_url('stocks/get_stock_item_price')?>',
                data: {item_number : item_number, units:units},
                dataType: 'json',
                method:'post',
                success: function(data)
                {
                    $('#injections_price_'+count).val(data.toFixed(2)); 
                }
            });
        }
	   }
	}
   // });

    
   // $('.medicine_quantity').on("keyup", function() {
		function medicine_quantity_update(el) { 
       var count =  $(el).attr('qcount');
       var item_number =  $(el).attr('item_number');
	   var item_quantity =  $(el).attr('item_quantity');
       $('#medicine_price_'+count).val(" ");
       var units = $(el).val();

       if(units > 0){
		   if(parseInt(item_quantity) < parseInt(units)){
			   alert('Unit must be less than or equal to quantity');
			   $(el).val("0");
			   return false;
		   }else{
            $.ajax({
                url: '<?php echo base_url('stocks/get_stock_item_price')?>',
                data: {item_number : item_number, units:units},
                dataType: 'json',
                method:'post',
                success: function(data)
                {
                    $('#medicine_price_'+count).val(data.toFixed(2)); 
                }
            });
        }
	   }
		}
   // });
</script>
<!--****** Billing SCRIPT *******-->

<script>
// $(function(){
// 	  // turn the element to select2 select style
// 	$('.selectitem').selectitem({
// 		placeholder: "Select stock item."
// 	  }).on('change', function(e) {
// 		var data = $(".selectitem option:selected").val();
			
// 	  });
	
// });

   $('#create_billing').click(function(e){
	e.preventDefault();
		if(this.form.reportValidity()){
	      	$(this).prop('disabled',true);
		this.form.submit();
	}
   });
</script>
