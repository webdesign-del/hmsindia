<?php $all_method =&get_instance(); ?>
<form class="col-sm-12 col-xs-12" method="post" action="" >
    <input type="hidden" name="action" value="transfer_stocks" />
    <input type="hidden" name="product_id" value="<?php echo $data['product_id']; ?>" />
    <input type="hidden" name="item_number" value="<?php echo $data['item_number']; ?>" />
    <input type="hidden" name="employee_number" value="<?php echo $data['employee_number']; ?>" />
    <input type="hidden" name="center_number" value="<?php echo $data['center_number']; ?>" />
    <input type="hidden" name="department" value="<?php echo $data['department']; ?>" />
    <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
        <div class="panel-heading">
          <h3 class="heading">Transfer Stocks</h3>
        </div>
        <div class="panel-body profile-edit">
          <p>          
          <div class="row"> 
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Company (Required)</label>
              <input value="<?php echo $data['company']; ?>" id="company" name="company" readonly="" type="text" class="form-control validate" required>
            </div>          
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Item name (Required)</label>
              <input value="<?php echo $data['item_name']; ?>" id="item_name" name="item_name" readonly="" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Batch Number</label>
              <input value="<?php echo $data['batch_number']?>" readonly="" id="batch_number" name="batch_number" type="text" class="form-control validate" required>
			</div>
            <div class="form-group col-sm-3 col-xs-12">
              <label for="expiry">Currunt Quantity (units)</label>
              <input value="<?php echo $data['quantity']?>" id="openstock" name="openstock" type="number" readonly="" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-3 col-xs-12">
              <label for="expiry">Transfer Quantity (units)</label>
              <input value="" id="quantity_out" name="quantity_out" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Expiry</label>
              <input value="<?php echo $data['expiry']?>" id="expiry" name="expiry" type="text" readonly="" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Expiry (Warning)</label>
              <input value="<?php echo $data['expiry_day']?>" id="expiry_day" readonly="" name="expiry_day" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Vendor Price (Incl GST)</label>
              <input value="<?php echo $data['vendor_price']?>" readonly="" id="vendor_price" name="vendor_price" type="text" class="form-control">
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">MRP</label>
              <input value="<?php echo $data['mrp']?>" readonly="" id="mrp" name="mrp" type="text" class="form-control validate" required>
            </div>
			<div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">HSN Code</label>
              <input value="<?php echo $data['hsn']; ?>" readonly="" id="hsn" name="hsn" type="text" class="form-control validate" required>
			</div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">GST</label>
              <input value="<?php echo $data['gstrate']?>" readonly="" id="gstrate" name="gstrate" type="text" class="form-control validate" required>
			  <input value="<?php echo $data['gstdivision']?>" readonly="" id="gstdivision" name="gstdivision" type="hidden" class="form-control validate" required>
        <input value="<?php echo $data['pack_size']?>" readonly="" id="pack_size" name="pack_size" type="hidden" class="form-control validate" required>
        <input value="<?php echo $data['brand_name']?>" readonly="" id="brand_name" name="brand_name" type="hidden" class="form-control validate" required>
        <input value="<?php echo $data['vendor_number']?>" readonly="" id="vendor_number" name="vendor_number" type="hidden" class="form-control validate" required>
        <input value="<?php echo $data['generic_name']?>" readonly="" id="generic_name" name="generic_name" type="hidden" class="form-control validate" required>
        <input value="<?php echo $data['category']?>" readonly="" id="category" name="category" type="hidden" class="form-control validate" required>
        <input value="<?php echo $data['date_of_purchase']?>" readonly="" id="date_of_purchase" name="date_of_purchase" type="hidden" class="form-control validate">
		<input value="<?php echo $data['invoice_no']?>" readonly="" id="invoice_no" name="invoice_no" type="hidden" class="form-control validate">
      </div>
            <div class="col-sm-6 col-xs-12">
            	<label>Center</label>
                <select class="form-control" id="r_center_number" name="r_center_number">
                	<option value="">--Select From--</option>
					<?php 
					$all_centers = $all_method->get_all_centers();
					foreach ($all_centers as $val) { 
					// Check if the current center matches the selected one
					$selected = ($center == $val['center_number']) ? '' : '';
					// Use `center_number` as the value (if needed) or `ship_to`
					echo '<option value="' . $val['center_number'] . '" ' . $selected . '>' . $val['center_name'] . '</option>';
					} 
					?>
				</select>
            </div>
              <div class="col-sm-6 col-xs-12">
            	<label>Department</label>
                <select class="form-control" id="r_department" name="r_department">
                	<option value="">--Select From--</option>
                  <option value="Embryologist Noida">Embryologist</option>
                  <option value="billing">Cash Billing</option>
                  <option value="Hormonal">Hormonal</option>
                  <option value="OT Noida">OT Noida</option>
                  <option value="Embryologist Basant Lok">Embryologist Basant Lok</option>
                  <option value="OT Basant Lok">OT Basant Lok</option>
                  <option value="Nonsaleable">Nonsaleable</option>
                  <option value="OT Srinagar">OT Srinagar</option>
                  <option value="Embryology Srinagar">Embryology Srinagar</option>
                  <option value="warehouse">Warehouse</option>
	        			</select>
            </div>	
            <div class="col-sm-6 col-xs-12" style="margin-top:10px;">
            	<label>Filter by billing at</label>
                <select class="form-control" id="r_employee_number" name="r_employee_number">
                	<option value=''>--Select From--</option>
                    <?php $all_emplyee = $all_method->get_employee_list();
						            foreach($all_emplyee as $key => $val){ //var_dump($val);die;
                          if($employee_number == $val['name']){
                            echo '<option value="'.$val['employee_number'].'" selected>'.$val['name'].'</option>';
                          }else{
		                        echo '<option value="'.$val['employee_number'].'">'.$val['name'].'</option>';
                          }
                    	  } 
					    ?>
                </select>
			</div>
      <div class="form-group col-sm-6 col-xs-12">
              <label for="expiry">Remarks</label>
              <input value="" id="remarks" name="remarks" type="text" class="form-control validate">
			</div>
		  </div>
           
        </div>        
        <div class="clearfix"></div>
        <div class="form-group col-sm-12 col-xs-12">
          <input type="submit" id="submitbutton" class="btn btn-large" value="Submit" />
        </div>
        </p>
      </div>
    </div>
  </form>