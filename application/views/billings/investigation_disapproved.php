<style type="text/css">
    form {
        margin: 20px 0;
    }
    form input, button {
        padding: 5px;
    }
    table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid #cdcdcd;
    }
    table th, table td {
        padding: 10px;
        text-align: left;
    }
    .return-header {
        background: #f2dede !important; 
        color: #a94442 !important; 
        text-align: center !important;
        font-weight: bold;
    }
    .return-cell {
        text-align: center !important; 
        background: #fff5f5;
    }
    .return-actions-box {
        background: #fdf8e2; 
        padding: 20px; 
        border: 1px solid #fbeed5; 
        margin-top: 20px; 
        border-radius: 4px;
    }
	[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
		position: static!important;
		left: -9999px;
		opacity: 1;
	}
</style>

<?php 
    $all_method =&get_instance();
    $patient_id = $data['patient_id'];
    $investigation_data = unserialize($data['investigations']);
    $patient_data = $all_method->get_patient_details($patient_id);
    $employee_data = get_employee_detail($_SESSION['logged_billing_manager']['username']);
    $allowed_amount = 0;
    $allowed_amount = $employee_data['allow_discount_rs'];
?>

<form class="col-sm-12 col-xs-12" id="billing_edit_form" method="post" action="<?php echo base_url();?>billings/disapproved/<?php echo $data['receipt_number']?>?t=<?php echo $_GET['t'];?>" enctype="multipart/form-data" >
    <input type="hidden" name="action" value="update_disapproved_billing" />
    <input type="hidden" name="type" value="investigation" />
    <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>" id="patient_id" />
    <input type="hidden" name="receipt_number" value="<?php echo $data['receipt_number']; ?>" id="receipt_number" />
    
    <div class="row">
        <div class="col-sm-12 col-xs-12 panel panel-piluku" id="consultation_details">
            <div class="panel-heading">
                <h3 class="heading">Investigation Details</h3>
            </div>
            <div class="panel-body profile-edit">
                <div id="msg_area" class="delete"></div>
                
                <div class="row">            
                    <div class="form-group col-sm-6 col-xs-12 role">
                        <label for="paramedic_name">Paramedic Name (Required)</label>
                        <input value="<?php echo $data['paramedic_name']; ?>" placeholder="Paramedic Name" id="paramedic_name" name="paramedic_name" type="text" class="form-control validate" required>
                    </div>
                   
                    <div class="form-group col-sm-6 col-xs-12">
                        <label>Billing date (Required)</label>
                        <p><b><?php echo $data['on_date'];?></b></p>
                    </div>
                </div>
                 
                <!-- Investigation Table with Return Target checkboxes -->
                <div class="row invastigatiton_table" style="margin-top:15px; margin-bottom:15px;">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr>
                                    <th>Investigations</th>
                                    <th>Code</th>
                                    <th>Price (<i class="fa fa-inr" aria-hidden="true"></i>)</th>
                                    <th>Discount amount</th>
                                    <th class="return-header">Select for Return / Refund</th>
                                </tr>
                            </thead>
                            <tbody id="investigation_table_body">
                                <?php 
                                $i=1;
                                $readonly = '';
                                if($discound_applied > 0){
                                    $readonly = ' readonly="readonly"';
                                }
                                if(isset($investigation_data['male_investigation']) && !empty($investigation_data['male_investigation'])){
                                    foreach($investigation_data['male_investigation'] as $key => $val){ ?>
                                        <tr class="investigation_row_<?php echo $i; ?>" trcount="<?php echo $i; ?>">
                                            <td class="role">
                                                <?php echo $all_method->get_investigation_name($val['male_investigation_name']); ?>
                                            </td>
                                            <td><?php echo $val['male_investigation_code']; ?></td>
                                            <td><?php echo $val['male_investigation_price']; ?></td>
                                            <td><input value="<?php echo $val['male_investigation_discount']?>" placeholder="Discount" class="form-control investigation_discount required_value" type="text" required <?php echo $readonly; ?>></td>
                                            <td class="return-cell">
                                                <input type="checkbox" name="return_tests[]" class="return_checkbox" value="<?php echo $val['male_investigation_code'].' | '.$val['male_investigation_price'].' | '.$all_method->get_investigation_name($val['male_investigation_name']); ?>" style="transform: scale(1.3); cursor:pointer;">
                                            </td>
                                        </tr>
                                <?php $i++;} 
                                } ?>
                                
                                <?php 
                                $i=1;
                                if(isset($investigation_data['female_investigation']) && !empty($investigation_data['female_investigation'])){
                                    foreach($investigation_data['female_investigation'] as $key => $val){ ?>
                                        <tr class="investigation_row_<?php echo $i; ?>" trcount="<?php echo $i; ?>">
                                            <td class="role">
                                                <?php echo $all_method->get_investigation_name($val['female_investigation_name']); ?>
                                            </td>
                                            <td><?php echo $val['female_investigation_code']; ?></td>
                                            <td><?php echo $val['female_investigation_price']; ?></td>
                                            <td><input value="<?php echo $val['female_investigation_discount']?>" placeholder="Discount" class="form-control investigation_discount required_value" type="text" required <?php echo $readonly; ?>></td>
                                            <td class="return-cell">
                                                <input type="checkbox" name="return_tests[]" class="return_checkbox" value="<?php echo $val['female_investigation_code'].' | '.$val['female_investigation_price'].' | '.$all_method->get_investigation_name($val['female_investigation_name']); ?>" style="transform: scale(1.3); cursor:pointer;">
                                            </td>
                                        </tr>
                                <?php $i++;} 
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                 
                <div class="row">  
                    <div class="form-group col-sm-12 col-xs-12">
                        <label>Paid In</label>
                        <h4>
                            <?php if($data['payment_in'] == 'rs_payment'){
                                echo 'In Rupee';
                            }else {
                                echo 'In USD';
                            } ?>
                        </h4>
                    </div>          
                    <div class="form-group col-sm-6 col-xs-12">
                        <label>Receipt number (Required)</label>
                        <p><b><?php echo $data['receipt_number'];?></b></p>
                    </div>
                   
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="after_discount">Investigation fees (Required)</label>
                        <input value="<?php echo $data['totalpackage']; ?>" name="totalpackage" class="dhee" id="fees" type="hidden">
                        <input value="<?php echo $data['fees']; ?>" placeholder="Investigation fees" readonly="readonly" name="fees" id="after_discount" type="text" class="form-control validate" required>
                    </div>
                </div>
                 
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12 role">
                        <label for="payment_method">Payment mode (Required)</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">Select</option>
                            <?php if($patient_data['nationality'] == 'indian'){?>
                                <option value="neft" <?php if($data['payment_method'] == 'neft'){ echo 'selected="selected"'; } ?>>NEFT</option>
                                <option value="rtgs" <?php if($data['payment_method'] == 'rtgs'){ echo 'selected="selected"'; } ?>>RTGS</option>
                                <option value="upi" <?php if($data['payment_method'] == 'upi'){ echo 'selected="selected"'; } ?>>UPI</option>
                                <option value="cash" <?php if($data['payment_method'] == 'cash'){ echo 'selected="selected"'; } ?>>Cash</option>
                                <option value="cheque" <?php if($data['payment_method'] == 'cheque'){ echo 'selected="selected"'; } ?>>Cheque</option>
                                <option value="card" <?php if($data['payment_method'] == 'card'){ echo 'selected="selected"'; } ?>>Card</option>
                                <option value="insurance" <?php if($data['payment_method'] == 'insurance'){ echo 'selected="selected"'; } ?>>Insurance</option>
                            <?php }else{ ?>
                                <option value="cheque" <?php if($data['payment_method'] == 'cheque'){ echo 'selected="selected"'; } ?>>Cheque</option>
                                <option value="cash" <?php if($data['payment_method'] == 'cash'){ echo 'selected="selected"'; } ?>>Cash</option>
                                <option value="international_card" <?php if($data['payment_method'] == 'international_card'){ echo 'selected="selected"'; } ?>>International Card</option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-sm-6 col-xs-12" id="subvention_box" style="<?php if($data['payment_method'] == 'insurance'){ echo 'display:block;'; }else{echo "display:none;";} ?>">
                        <label for="subvention_charges">Subvention charges (Required)</label>
                        <input value="<?php echo $data['subvention_charges']; ?>" placeholder="Subvention charges" id="subvention_charges" name="subvention_charges" type="text" class="form-control validate">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12" id="transaction" style="<?php echo (!empty($data['payment_method'])) ? 'display:block;' : 'display:none;'; ?>">
                        <label for="transaction_id">Reference no. (Required)</label>
                        <input value="<?php echo $data['transaction_id']; ?>" placeholder="Reference no." id="transaction_id" name="transaction_id" type="text" class="form-control validate">
                        <label style="margin-top:10px;">Upload screenshot/document here</label>
                        <input type="file" name="transaction_img" id="transaction_img" />
                        <?php if(!empty($data['transaction_img'])){ ?>
                            <a href="<?php echo $data['transaction_img']; ?>" target="_blank" style="display:block; margin-top:10px;"><img src="<?php echo $data['transaction_img']; ?>" class="img_show" id="transaction_img_src" style="max-width:150px;" /></a>
                        <?php } ?>
                    </div>
                </div>
                 
                <div class="row">            
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="discount_amount">Discount amount</label>
                        <input value="<?php echo $data['discount_amount']; ?>" placeholder="Discount amount" id="discount_amount" readonly="readonly" name="discount_amount" type="text" class="form-control validate" required>
                        <input value="<?php echo $_SESSION['logged_billing_manager']['allow_discount_rs'];?>" id="allow_discount" type="hidden">
                        <p id="show_disc_app" class="error" style="display:none; color:red; font-weight:bold; margin-top:5px;">Given discount is more than allowed</p>
                    </div>
                   
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="payment_done">Payment received (Required)</label>
                        <input value="<?php echo $data['payment_done']; ?>" placeholder="Payment received" id="payment_done" name="payment_done" type="number" class="form-control validate" required>
                    </div>
                </div>   
                 
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="remaining_amount">Remaining amount (Required)</label>
                        <input value="<?php echo $data['remaining_amount']; ?>" placeholder="Remaining amount" readonly="readonly" id="remaining_amount" name="remaining_amount" type="text" class="form-control validate" required>
                    </div>
                    
                    <?php if($data['billing_from'] != 'IndiaIVF'){?>
                        <div class="form-group col-sm-6 col-xs-12">
                            <label for="billing_id">Billing ID</label>
                            <input value="" placeholder="Billing ID" id="billing_id" name="billing_id" type="text" class="form-control validate">
                        </div>
                    <?php } ?>
                </div>
                 
                <?php if(isset($discound_applied) && $discound_applied > 0){ ?>
                    <div class="row">
                        <div class="form-group col-sm-6 col-xs-12 role">
                            <div class="col-sm-8 col-xs-12 no-pad">
                                <label for="discount_code">Discount code</label>
                                <input value="" placeholder="Discount code" id="discount_code" name="discount_code" type="text" class="form-control validate">
                                <img class="remove_code" style="display:none; cursor:pointer;" src="<?php echo base_url('assets/images/close-icon.png');?>" />
                                <p id="code_msg" style="display:none;"></p>
                            </div>
                            <div class="col-sm-4 col-xs-12" style="margin-top:25px;">
                                <a href="javascript:void(0)" id="apply_discount" class="btn btn-warning">Apply</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                 
            </div>
        </div>
    </div>
         
    <!-- रिफंड / रिटर्न एक्शन्स बॉक्स (नया ब्लॉक) -->
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="return-actions-box">
                <h4><i class="fa fa-reply-all"></i> Return & Refund Requests System</h4>
                <p class="text-muted">यदि इस बिल का आंशिक (Partial) या पूर्ण (Full) रिटर्न अनुरोध <b>drnoida@indiaivf.in</b> को भेजना है, तो उपयुक्त विकल्प चुनें:</p>
                <hr style="margin: 10px 0; border-color: #fbeed5;" />
                
                <button type="button" class="btn btn-warning" onclick="executeReturnSubmission('partial')" style="font-weight:bold; margin-bottom: 5px;">
                    <i class="fa fa-list-ul"></i> Request Partial Return (Selected Tests Only)
                </button>
                
                <button type="button" class="btn btn-danger" onclick="executeReturnSubmission('full')" style="font-weight:bold; margin-left:10px; margin-bottom: 5px;">
                    <i class="fa fa-money"></i> Request Full Bill Return
                </button>
                
                <div class="pull-right">
                    <input type="submit" id="submitbutton" class="btn btn-primary" style="font-weight:bold; padding: 6px 20px;" value="Update Billing" />
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</form>

<!-- स्क्रिप्ट्स -->
<script type="text/javascript">
  $(document).on('keyup',"#subvention_charges",function(e) {
    $('#payment_done').val('');
    $('#remaining_amount').val(''); 
    var subvention_charges = $(this).val();
    var fees = parseFloat($('.dhee').val());
    var discount = parseFloat($('#discount_amount').val());
    fees = (parseFloat(fees) - parseFloat(discount));
    if(subvention_charges != ""){
      var subvention = (parseFloat(subvention_charges) + parseFloat(fees));
      $('#after_discount').val(parseFloat(subvention));
    }else{
      $('#after_discount').val(parseFloat(fees));
    }
  });
  
  $(document).on('keyup',"#discount_code",function(e) {
        $('#code_msg').hide();
        $('#apply_discount').show();
        var code = $(this).val();
        if(code != ''){ $('.remove_code').show(); }else{ $('.remove_code').hide(); }
        if(code.length == 8){ $("#apply_discount").click();}else{$('#submitbutton').hide();}
  });
  
  $(document).on('click',".remove_code",function(e) {
        $('#code_msg').hide();
        var fees = $('#fees').val();
        $('#after_discount').empty().val(fees);
        $('#discount_code').val('');
        $('#discount_amount').val('0 ');
        if($("#payment_discount").val() == 'discount'){ $('#submitbutton').hide();}
        $("#payment_method").prop("selectedIndex", 0);
        $('#transaction_img_src').hide();
        $('#transaction_id').val('');
        $('#discount_code').attr('readonly', false);
        $('#apply_discount').show();
        $('#show_disc_app').hide();
        $(this).hide();
  });
  
  $(document).on('click',"#apply_discount",function(e) {
        $('#discount_code').attr('readonly', true);
        $('#code_msg').hide();
        if(typeof $('#loader_div') !== 'undefined') { $('#loader_div').show(); }
        var discount_code = $('#discount_code').val();
        if(discount_code != ''){
            $.ajax({
                url: '<?php echo base_url('billings/check_discount_code')?>',
                data: {discount_code : discount_code,patient_id:<?php echo $patient_id; ?>,receipt_number:<?php echo $data['receipt_number']; ?>,type:'investigation'},
                dataType: 'json',
                method:'post',
                success: function(data)
                {
                    if(data.status == 1){
                        var amount = data.amount;
                        var fees = $('#fees').val();
                        var actual = parseFloat(fees)-parseFloat(amount);
                        $('#discount_amount').empty().val(amount);
                        $('#after_discount').empty().val(actual);
                        $("input#payment_done").val('');
                        $("input#remaining_amount").val('');
                        $('#code_msg').empty().append(data.message);
                        $("#payment_method").prop("selectedIndex", 0);
                        $('#transaction_img_src').hide();
                        $('#transaction_id').val('');
                        $('.investigation_discount').val('0 ');
                        $('#show_disc_app').hide();
                        $('#apply_discount').hide();
                        $('#submitbutton').show();
                    }else{
                        $('#code_msg').empty().append(data.message);
                    }
                    $('#code_msg').show();
                    if(typeof $('#loader_div') !== 'undefined') { $('#loader_div').hide(); }
                } 
           });
      }
      if(typeof $('#loader_div') !== 'undefined') { $('#loader_div').hide(); }
    });

    <?php if(isset($discound_applied) && $discound_applied > 0){ ?>
        get_allowed_discount();
    <?php } ?>
    
    function get_allowed_discount(){
           var discount_amount = $('#discount_amount').val();
           var allow_discount = <?php echo $allowed_amount?>;
           $('#allow_discount').empty().val(parseFloat(allow_discount));
           if(discount_amount > allow_discount){
                $('#submitbutton').hide();
                $('#show_disc_app').show();
           }
    }
    
    function add_delete_method(){
        var fee_total = 0;
        $('.investigation_price').each(function(){
            var price_total = $(this).val();
            fee_total += +price_total;
        });
        $('#after_discount').val(fee_total);
        $('.investigation_discount').val(0);
        $('#discount_amount').val(0);
        $('#payment_done').val('');
        $('#remaining_amount').val('');
        $('#transaction_id').val('');
        $('#transaction').hide();
        $('#transaction_img_src').attr('src', '');
        $('#payment_method').prop('selectedIndex',0);   
        $('.dhee').val(fee_total);
    }

   $(document).on('change',"#payment_method",function(e) {
        $('#payment_done').val('');
        $('#remaining_amount').val(''); 
        $('#subvention_charges').val("");
        $('#subvention_charges').prop('required', false);
        $('#subvention_box').hide();
        var method = $(this).val();
        if(method == ''){
             $('#transaction_id').prop('required',false);
             $('#transaction').hide();      
        }else{
             $('#transaction_id').prop('required',true); // इसे रिवायर्ड किया गया है क्योंकि रिफरेन्स नंबर जरूरी होता है ऑनलाइन पेमेंट्स में
             $('#transaction').show();
        }   
        if(method == "insurance"){
            $('#subvention_charges').prop('required', true);
            $('#subvention_box').show();
        }
        cal_billing_cost();
    }); 

   $(document).on('keyup',"#payment_done",function(e) {
        var fees = $('#after_discount').val();
        var payment_done = $(this).val();
        var remaining_amount = fees - payment_done;
        $('#remaining_amount').val(remaining_amount.toFixed(2));
    });
    
    $(document).on('keyup',".investigation_discount",function(e) {
            var given_discount = 0;
            $('.investigation_discount').each(function(){
                var dicount_amount = $(this).val();
                given_discount += +parseFloat((dicount_amount || 0));
            });
            $('#discount_amount').val(given_discount.toFixed(2));
            <?php if(isset($discound_applied) && $discound_applied == 0){ ?>
                cal_discount();
            <?php } ?>
    });
    
    function cal_discount(){
        $('#subvention_charges').val("");
        $('#subvention_charges').prop('required', false);
        $('#subvention_box').hide();
        $('#payment_done').val('');
        $('#remaining_amount').val('');
        
        var fees = $('.dhee').val();
        var allowd = $('#allow_discount').val();
        var discount_amount = $("#discount_amount").val();
        
        var after_cal_price = ( fees * allowd / 100 ).toFixed(2);

        if(parseFloat(discount_amount) > parseFloat(after_cal_price)){
            $('#after_discount').val(parseFloat(fees));
            $('#show_disc_app').show();
            $('#submitbutton').hide();
        }else{
            $("#payment_method").prop("selectedIndex", 0);
            $('#transaction_id').val('');
            var listPrice = parseFloat(fees);
            var discount  = parseFloat(discount_amount);
            var remaining_amount =  listPrice - discount;
            if(remaining_amount < 1){
                $('#payment_done').val('');
                $('#after_discount').val('');
                $("#discount_amount").val('');
                $('.investigation_discount').val('');
                $('#show_disc_app').hide();
                $('#submitbutton').hide();
            }else{
                $('#after_discount').val(remaining_amount.toFixed(2));
                $('#submitbutton').show();
                $('#show_disc_app').hide();
            }
        }
    };
    
    function cal_billing_cost(){
        var fees = $(".dhee").val();
        var discunt = $("#discount_amount").val();
        var total = parseFloat(fees) - parseFloat(discunt);
        $("#after_discount").val(total.toFixed(2));
    }

    // --- जावास्क्रिप्ट AJAX रिटर्न फंक्शन ---
    function executeReturnSubmission(return_type) {
        var receipt_number = $('#receipt_number').val();
        var patient_id = $('#patient_id').val();
        var targeted_tests = [];

        if (return_type === 'partial') {
            $("input[name='return_tests[]']:checked").each(function() {
                targeted_tests.push($(this).val());
            });

            if (targeted_tests.length === 0) {
                alert('कृपया Partial Return अनुरोध भेजने के लिए कम से कम एक टेस्ट पर टिक (✔) अवश्य करें!');
                return;
            }
        }

        var dialogMsg = (return_type === 'full') 
            ? "क्या आप पूरे बिल का रिफंड अनुरोध drnoida@indiaivf.in को भेजना चाहते हैं?" 
            : "क्या आप केवल चुनिंदा टेस्ट का रिफंड अनुरोध drnoida@indiaivf.in को भेजना चाहते हैं?";

        if (confirm(dialogMsg)) {
            if(typeof $('#loader_div') !== 'undefined') { $('#loader_div').show(); }
            
            $.ajax({
                url: '<?php echo base_url("billings/ajax_send_return_request"); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    receipt_number: receipt_number,
                    patient_id: patient_id,
                    return_type: return_type,
                    tests: targeted_tests
                },
                success: function(res) {
                    if(typeof $('#loader_div') !== 'undefined') { $('#loader_div').hide(); }
                    if (res.status === 'success') {
                        alert('Return Request ईमेल सफलतापूर्वक भेज दिया गया है। निर्णय होने तक यह बिल पेंडिंग रहेगा।');
                        window.location.href = '<?php echo base_url("billings/investigation_billings"); ?>';
                    } else {
                        alert('त्रुटि: ' + res.message);
                    }
                },
                error: function() {
                    if(typeof $('#loader_div') !== 'undefined') { $('#loader_div').hide(); }
                    alert('सर्वर एरर! अनुरोध भेजने में विफलता हुई, कृपया पुनः प्रयास करें।');
                }
            });
        }
    }
</script>