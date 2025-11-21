<div class="col-sm-12 col-xs-12">
   <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
         <div class="panel-heading">
            <h3 class="heading">Purchase Order Payment</h3>
         </div>
         <div class="panel-body profile-edit">
            <div class="alert alert-warning">
               <strong>Important:</strong> 
               <ul>
                  <li>File upload supports: PDF, JPG, PNG, WEBP, GIF, BMP formats</li>
                  <li>Maximum file size: 10MB</li>
                  <li>Ensure the file is not corrupted and has a valid extension</li>
                  <li>Amount paid can exceed PO total (overpayment will be recorded as credit)</li>
               </ul>
            </div>
            <form method="post" action="<?php echo base_url('accounts/save-purchase-order-payment/'.$purchase_order['po_number']); ?>" enctype="multipart/form-data">
               <div class="row">
                  <div class="col-sm-6 form-group">
                     <label>PO Basic Amount</label>
                     <input type="hidden" value="<?php echo $purchase_order['po_number']; ?>" name="po_number">
                     <input value="<?php echo $purchase_order['po_basic_amount']; ?>" 
                        readonly id="po_basic_amount" name="po_basic_amount" 
                        type="text" class="form-control" required>
                  </div>
                  <div class="col-sm-6 form-group">
                     <label>PO GST Amount</label>
                     <input value="<?php echo $purchase_order['po_gst_amount']; ?>" 
                        readonly id="po_gst_amount" name="po_gst_amount" 
                        type="text" class="form-control" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6 form-group">
                     <label>PO Other Charges and Taxes</label>
                     <input value="<?php echo $purchase_order['po_other_charges_and_taxes'] ?? 0; ?>" 
                        readonly id="po_other_charges_and_taxes" name="po_other_charges_and_taxes" 
                        type="text" class="form-control" required>
                  </div>
                  <div class="col-sm-6 form-group">
                     <label>PO Total</label>
                     <input value="<?php echo $purchase_order['po_po_total'] ?? 0; ?>" 
                        readonly id="po_po_total" name="po_po_total" 
                        type="text" class="form-control" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6 form-group">
                     <label>Amount Paid <span class="text-danger">*</span></label>
                     <input value="" 
                        placeholder="Enter amount paid" id="amount_paid" name="amount_paid" 
                        type="number" step="0.01" min="0.01" class="form-control" required>
                     <small class="text-muted">Enter the actual amount received</small>
                  </div>
                  <div class="col-sm-6 form-group">
                     <label>Upload Transaction File <span class="text-danger">*</span></label>
                     <input type="file" class="form-control" name="payment_proof" id="payment_proof" 
                        accept=".pdf,.jpg,.jpeg,.png,.webp,.gif,.bmp" required/>
                     <small class="text-muted">Accepted formats: PDF, JPG, PNG, WEBP, GIF, BMP (Max: 10MB)</small>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6 form-group">
                     <label>Payment Status</label>
                     <input value="" placeholder="Payment Status" readonly id="balance" name="balance" 
                        type="text" class="form-control" style="font-weight: bold;">
                     <small class="text-muted">Shows remaining balance or overpayment status</small>
                  </div>
                  <div class="col-sm-6 form-group">
                     <label>PO Summary</label>
                     <div class="alert alert-info">
                        <strong>Total Amount:</strong> ₹<span id="po_total_display"><?php echo number_format($purchase_order['po_po_total'], 2); ?></span><br>
                        <strong>Basic Amount:</strong> ₹<?php echo number_format($purchase_order['po_basic_amount'], 2); ?><br>
                        <strong>GST:</strong> ₹<?php echo number_format($purchase_order['po_gst_amount'], 2); ?><br>
                        <strong>Other Charges:</strong> ₹<?php echo number_format($purchase_order['po_other_charges_and_taxes'], 2); ?>
                     </div>
                  </div>
               </div>
               <div class="row text-center">
                  <div class="col-sm-12 form-group">
                     <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function(){
       $('#amount_paid').on('keyup change', function(){
           var po_total     = parseFloat($('#po_po_total').val()) || 0;
           var amount_paid  = parseFloat($('#amount_paid').val()) || 0;
           
           // Validate amount paid
           if (amount_paid < 0) {
               alert("Amount Paid cannot be negative!");
               $('#amount_paid').val('');
               $('#balance').val('');
               return;
           }
           
           // Calculate balance
           var balance = po_total - amount_paid;
           
           // Display appropriate message based on balance
           if (balance < 0) {
               // Overpayment scenario
               $('#balance').val('Overpayment: ₹' + Math.abs(balance).toFixed(2));
               $('#balance').css('color', 'green');
           } else if (balance === 0) {
               // Full payment
               $('#balance').val('Fully Paid');
               $('#balance').css('color', 'blue');
           } else {
               // Partial payment
               $('#balance').val('₹' + balance.toFixed(2));
               $('#balance').css('color', 'black');
           }
           
           // Show warning for overpayment but don't prevent it
           if (amount_paid > po_total) {
               $('#amount_paid').css('border-color', 'orange');
               $('#amount_paid').attr('title', 'Warning: This is an overpayment. The excess amount will be recorded as credit.');
           } else {
               $('#amount_paid').css('border-color', '');
               $('#amount_paid').removeAttr('title');
           }
       });
       
       // Add form validation
       $('form').on('submit', function(e){
           var po_total = parseFloat($('#po_po_total').val()) || 0;
           var amount_paid = parseFloat($('#amount_paid').val()) || 0;
           
           if (amount_paid <= 0) {
               alert("Amount Paid must be greater than 0!");
               e.preventDefault();
               return false;
           }
           
           if (amount_paid > po_total * 1.5) { // Allow up to 50% overpayment
               if (!confirm("Warning: You are making a significant overpayment (" + ((amount_paid - po_total) / po_total * 100).toFixed(1) + "% over). Do you want to continue?")) {
                   e.preventDefault();
                   return false;
               }
           }
       });
   });
</script>