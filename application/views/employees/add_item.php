
<form class="col-sm-12 col-xs-12" method="post" action="" >
  <input type="hidden" name="action" value="add_item" />
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku">
      <div class="panel-heading">
        <h3 class="heading">Add Employee</h3>
      </div>
      <div class="panel-body profile-edit">
        <p>
        <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Employee Name (Required)</label>
            <input value="" placeholder="Employee name" id="name" name="name" type="text" class="form-control validate" required>
          </div>
          
          <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Phone (Required)</label>
            <input value="" placeholder="Phone" id="phone" name="phone" type="text" class="form-control validate" required>
          </div>
          
          
        </div>
        
         <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Email (Required) <span class="delete">(Email will be username)</span></label>
            <input value="" placeholder="Email" id="email" name="email" type="email" class="form-control validate" required>
          </div>
          
          <div class="form-group col-sm-6 col-xs-12 role">
            <label for="statuss">Employee Role(Required)</label>
            <select name="role" id="role" required>
              <option value="">Select Role</option>
              <option value="accountant">Accountant</option>
              <option value="stock_manager">Stock manager</option>
              <option value="billing_manager">Billing manager</option>
              <option value="central_stock_manager">Central stock manager</option>
              <option value="investigator_manager">Investigator manager</option>
              <option value="embryologist">Embryologist</option>
              <option value="telecaller">Telecaller</option>
			  <option value="counselor">Counselor</option>
            </select>
        </div>
        </div>
        <div class="clearfix"></div>
        
         <div class="row">
		  <div class="form-group col-sm-6 col-xs-12 role">
            <label for="statuss">Debartment(Required)</label>
            <select name="department" id="department">
              <option value="">Select Debartment</option>
              <option value="billing">Billing</option>
              <option value="Nonsaleable">Nonsaleable</option>
              <option value="Hormonal">Hormonal</option>
              <option value="OT Noida">OT Noida</option>
              <option value="Embryologist Noida">Embryologist Noida</option>
              <option value="Embryologist">Embryologist</option>
               <option value="OT">OT</option>
              <option value="House Keeping">House Keeping</option>
              <option value="Stationery">Stationery</option>
			</select>
        </div>
           <div class="form-group col-sm-6 col-xs-12 role">
            <label for="statuss">Employee Center (Required)</label>
            <select name="center_id" required>
            	<option value="">Select Center</option>
            	<?php foreach($centers as $ky => $vl){?>
              	  <option value="<?php echo $vl['center_number']?>"><?php echo $vl['center_name']?></option>
                <?php } ?>
            </select>
        </div>
       
          
        </div>
        
                
        <div class="row">
        	<!-- <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Allowed discount amount (USD) <span class="error"> (Only for billing manager) </span> (Required)</label>
                <input value="" placeholder="Allowed discount amount (USD)" id="allow_discount_us" name="allow_discount_us" type="text" class="form-control validate" required>
            </div> -->
           <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Password (Required)</label>
            <input value="" placeholder="Password" id="password" name="password" type="password" class="form-control validate" required>
          </div>
      	    <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Allowed discount amount (Rupees) <span class="error"> (Only for billing manager) </span> (Required)</label>
                <input value="" placeholder="Allowed discount amount (Rupees)" id="allow_discount_rs" name="allow_discount_rs" type="text" class="form-control validate" required>
            </div>
        </div>
        
        <div class="row">          
      	  <div class="form-group col-sm-6 col-xs-12">
            <label for="statuss">Employee Status (Required)</label>
            <br/>
            <input type="radio" name="status" value="1" class="statuss" checked> Active 
            <input type="radio" name="status" value="0" class="statuss"> Inactive 
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

<script>
	$(document).on('change', '#role', function() {
		var role = $(this).val();
		if(role == 'billing_manager' || role == ''){
			$('#allow_discount_us').prop('required', true);
			$('#allow_discount_rs').prop('required', true);
		}else{
			$('#allow_discount_us').prop('required', false);
			$('#allow_discount_rs').prop('required', false);
		}
	});
</script>