
<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="action" value="save_advance_payment" />
    <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
        <div class="panel-heading">
          <h3 class="heading">Add Advance Payment</h3>
        </div>
        <div class="panel-body profile-edit">
          <p>
          <div class="row">
		    <div class="form-group col-sm-4 col-xs-12">
              <label for="company">Patient Id</label>
			  <input value=""  placeholder="Patient Id" id="patient_id" name="patient_id" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-4 col-xs-12">
              <label for="company">Reference Number</label>
			  <input value="<?php echo date("YmdHis"); ?>"  placeholder="Reference Number" readonly id="receipt_number" name="receipt_number" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-4 col-xs-12">
              <label for="company">Amount</label>
			  <input value=""  placeholder="Amount" id="payment_done" name="payment_done" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-4 col-xs-12">
              <label for="company">Payment Method</label>
              <select name="payment_mode" id="payment_mode">
                <option value="">Select</option>
                <option value="card" mode="Card">Card</option>
                <option value="upi" mode="UPI">UPI</option>
                <option value="cash" mode="Cash">Cash</option>
                <option value="neft" mode="Neft">Neft</option>
              </select>
            </div>
			<div class="form-group col-sm-4 col-xs-12">
              <label for="company">Transaction </label>
			  <input value=""  placeholder="Transaction" id="transaction_id" name="transaction_id" type="text" class="form-control validate" required>
            </div>
            <div class="form-group col-sm-4 col-xs-12">
                <label for="expiry">Payment Date</label>
                <input value="" placeholder="Payment Date" id="on_date" name="on_date" type="date" class="form-control validate" required>
                <input value="<?php echo $_SESSION['logged_billing_manager']['center']?>"  placeholder="" id="center" name="center" type="hidden" class="form-control validate" required>
                <input value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>"  placeholder="" id="employee_number" name="employee_number" type="hidden" class="form-control validate" required>
                <input value="<?php echo $_SESSION['logged_billing_manager']['center']?>"  placeholder="" id="billing_at" name="billing_at" type="hidden" class="form-control validate" required>
                <input value="Pending" id="status" name="status" type="hidden" class="form-control validate" required>
            
              </div>
			<div class="form-group col-sm-4 col-xs-12">
                <label for="expiry">Remarks</label>
                <input value="" placeholder="Remarks" id="remarks" name="remarks" type="text" class="form-control validate" required>
               <input value="<?php echo date('Y-m-d H:i:s'); ?>" 
       type="hidden" 
       id="created_at" 
       name="created_at" 
       class="form-control validate" 
       required>

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

<style type="text/css">
select{
    display: block;
}
</style>