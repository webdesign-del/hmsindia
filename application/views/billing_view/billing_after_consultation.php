<?php  
   $all_method =&get_instance();
   $done_wallet_result = array();
   $consultation_sql=0;
   $consultation_sql = "SELECT * FROM hms_patient_procedure WHERE billing_at='".$_SESSION['logged_billing_manager']['center']."' ORDER BY po_id DESC LIMIT 1 ";
   $select_result = run_select_query($consultation_sql); 
   
   $centers_sql = "SELECT * FROM `hms_centers` WHERE center_number=".$_SESSION['logged_billing_manager']['center']."";
   $centers_result = run_select_query($centers_sql);
   
   $date=date_create("2024-07-25");
   if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
       $financial_year = (date_format($date,"y")) . '-' . (date_format($date,"y")+1);
   } else {//On or Before March (FY is previous year - current year)
       $financial_year = (date_format($date,"y")-1) . '-' . date_format($date,"y");
   }
   
   
    $form_action = $billing_type = "";
    //var_dump($billing_details);die;
   
    if($billing_details['investation_suggestion'] == 1 && $billing_details['investigation_billed'] == 0 && $_GET['t'] == "investigation_billing"){
     $form_action = "add_investigations";
     $billing_type = "investigation";
   // }else if($billing_details['procedure_suggestion'] == 1 && $billing_details['procedure_billed'] == 0 && $_GET['t'] == "procedure_billing"){
    }else if($billing_details['procedure_suggestion'] == 1 && $_GET['t'] == "procedure_billing"){
     $form_action = "add_procedure";
     $billing_type = "procedure";
    }else if($billing_details['package_suggestion'] == 1 && $_GET['t'] == "package_billing"){
     $form_action = "add_package";
     $billing_type = "package"; 
    }else{
       header("location:" .base_url(). "after-consultation?m=".base64_encode('oops, something went wrong!').'&t='.base64_encode('error'));
       die();
    }
    $grand_total = 0;
    $patient_data = get_patient_detail($billing_details['patient_id']);
   ?>
<?php 
   $inved_options = '<option value="" selected> - - - Select - - - -</option>';
   $sql1 = "select * from hms_investigation where status=1"; 
   $query = $this->db->query($sql1);
   $select_result1 = $query->result(); 
   foreach ($select_result1 as $res_val){
   	$inved_options .= '<option value="'.$res_val->ID.':~'.$res_val->code.':~'.$res_val->price.':~'.$res_val->ID.'">'.$res_val->investigation.'</option>';
   } 
   ?>	
<?php 
   $ci = &get_instance();
   $ci->load->database();
   $db_prefix = $ci->config->config['db_prefix'];
   $patient_sql = "Select * from ".$db_prefix."patients where  patient_id='".$billing_details['patient_id']."'";
      $patient_q = $ci->db->query($patient_sql);
      $patient_result = $patient_q->result_array();
   $patient_id = $patient_result[0]['patient_id'];
   
   $consultation_result = $registation_result = $procedure_result = $investigation_result = $medicine_result = $remaining_billing = $bill_arr = $bill_total = array();
   $procedure_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where status='cancel' and patient_id='".$billing_details['patient_id']."'";
      $procedure_q = $ci->db->query($procedure_sql);
      $procedure_result = $procedure_q->result_array();
   
   $consultation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where status='adjust' and patient_id='".$billing_details['patient_id']."'";
      $consultation_q = $ci->db->query($consultation_sql);
      $consultation_result = $consultation_q->result_array();
   
   $registation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."registation where status='adjust' and patient_id='".$billing_details['patient_id']."'";
      $registation_q = $ci->db->query($registation_sql);
      $registation_result = $registation_q->result_array();
   
   $investigation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where status='cancel' and patient_id='".$billing_details['patient_id']."'";
      $investigation_q = $ci->db->query($investigation_sql);
      $investigation_result = $investigation_q->result_array();
   
   $medicine_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where status='cancel' and patient_id='".$billing_details['patient_id']."'";
      $medicine_q = $ci->db->query($medicine_sql);
      $medicine_result = $medicine_q->result_array();
   
   $total = 0;
   
      $done_sql = "Select sum(payment_done) as payment_done from ".$db_prefix."patient_payments where patient_id='".$billing_details['patient_id']."' AND status='3'";
   $done_q = $ci->db->query($done_sql);
   $done_result = $done_q->result_array();
   
   foreach($consultation_result as $key => $val){
    $bill_arr[] = $val['payment_done'];
   }
   
   $total = 0;
   foreach($investigation_result as $key => $val){
    $bill_arr[] = $val['payment_done'];
   }
   
   $total = 0;
   foreach($registation_result as $key => $val){
    $bill_arr[] = $val['payment_done'];
   }
   
   $total = 0;
   foreach($procedure_result as $key => $val){
   $bill_arr[] = $val['payment_done'];
   }
   
   $total = 0;
   foreach($medicine_result as $key => $val){
   $bill_arr[] = $val['payment_done'];
   }
   
   foreach($done_result as $key => $val){
   $bill_arr[] = $val['payment_done'];
   }
   
      //wallete
   $consultation_wallet_result = $procedure_wallet_result = $investigation_wallet_result = $partialpayments_wallet_result = $medicine_wallet_result = $done_wallet_result = $wallet_remaining_billing = $wallet_arr = $wallet_bill_total = array();
   $procedure_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where wallet_payment > 0 and patient_id='".$billing_details['patient_id']."'";
      $procedure_wallet_q = $ci->db->query($procedure_wallet_sql);
      $procedure_wallet_result = $procedure_wallet_q->result_array();
   
   $consultation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where wallet_payment > 0 and patient_id='".$billing_details['patient_id']."'";
      $consultation_wallet_q = $ci->db->query($consultation_wallet_sql);
      $consultation_wallet_result = $consultation_wallet_q->result_array();
   
   $investigation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where wallet_payment > 0 and patient_id='".$billing_details['patient_id']."'";
      $investigation_wallet_q = $ci->db->query($investigation_wallet_sql);
      $investigation_wallet_result = $investigation_wallet_q->result_array();
   
      $partialpayments_wallet_sql = "Select refrence_number, payment_done, wallet_payment, billing_from, billing_at from ".$db_prefix."patient_payments where wallet_payment > 0 and patient_id='".$billing_details['patient_id']."'";
      $partialpayments_wallet_q = $ci->db->query($partialpayments_wallet_sql);
      $partialpayments_wallet_result = $partialpayments_wallet_q->result_array();
   
   $medicine_wallet_sql = "Select receipt_number, payment_done,wallet_payment, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where wallet_payment > 0 and patient_id='".$billing_details['patient_id']."'";
      $medicine_wallet_q = $ci->db->query($medicine_wallet_sql);
      $medicine_wallet_result = $medicine_wallet_q->result_array();
   
   foreach($consultation_wallet_result as $key => $value){
   $wallet_arr[] = $value['wallet_payment'];
   }
   
   $total = 0;
   foreach($investigation_wallet_result as $key => $value){
   $wallet_arr[] = $value['wallet_payment'];
   }
   
   $total = 0;
   foreach($procedure_wallet_result as $key => $value){
   $wallet_arr[] = $value['wallet_payment'];
   }
   
   $total = 0;
   foreach($medicine_wallet_result as $key => $value){
   $wallet_arr[] = $value['wallet_payment'];
   }
   
   $total = 0;
   foreach($partialpayments_wallet_result as $key => $value){
   $wallet_arr[] = $value['wallet_payment'];
   }
   
   foreach($done_wallet_result as $key => $value){
   $wallet_arr[] = $value['wallet_payment'];
   }
   
   $paid_total = 0;
   $paid_total = array_sum($bill_arr);
   
      $wallet_bill_total = 0;
   $wallet_bill_total = array_sum($wallet_arr);
   
   $balance = $paid_total - $wallet_bill_total;
   ?>
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

<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data">
   <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
   <input type="hidden" name="appointment_id" value="<?php echo $billing_details['appointment_id']; ?>" />
   <input type="hidden" name="consultation_done" value="<?php echo $billing_details['ID']; ?>" />
   <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $billing_details['patient_id']; ?>" />
   <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
   <input type="hidden" id="billing_type" value="<?php echo $billing_type; ?>" />
   <input type="hidden" name="biller_id" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" />
   <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku" id="consultation_details">
         <div class="panel-heading">
            <h3 class="heading">Billing Details</h3>
            <p style="margin-top:20px;color:red;">Wallets Amount : <a href="<?php echo base_url(); ?>patients/edit/<?php echo $billing_details['patient_id']; ?>"><?php echo $balance; ?></a></p>
         </div>
         <div class="panel-body profile-edit">
            <p id="msg_area" class="delete"></p>
            <p>
            <div class="row">
               <?php if($billing_type == "investigation") { ?>
               <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="item_name">Paramedic Name (Required)</label>
                  <input value="" placeholder="Paramedic Name" id="paramedic_name" name="paramedic_name" type="text" class="form-control " required>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Receipt number (Required)</label>
                  <input value="<?php echo getReceiptGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number" name="receipt_number" type="text" class="form-control " required>
               </div>
               <?php } ?>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Date(Required)</label>
                  <input value="<?php ini_set('date.timezone', 'Asia/Kolkata'); echo date('Y-m-d H:i:s'); ?>" placeholder="Date" readonly="readonly" id="on_date" name="on_date" type="text" class="form-control " required>
               </div>
            </div>
            <?php if($billing_type == "investigation") { ?>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="item_name">IP UHID</label>
                  <input value="" placeholder="IP UHID" id="donor_patient_id" name="donor_patient_id" type="text" class="form-control " required>
               </div>
            </div>
            <?php } ?>
            <?php if($form_action == "add_investigations"){?>
            <div class="row">
               <?php 
                  if($billing_details['investation_suggestion'] == 1 && $billing_details['investigation_billed'] == 0){ ?>
               <input type="hidden" name="investation_suggestion" value="<?php echo $billing_details['investation_suggestion']; ?>" />
               <input type="button" class="add-investigations-row btn btn-large" value="Add Investigations">
               <input type="button" class="delete-investigations-row btn btn-large pull-right" value="Delete Selected Investigations">
               <h4>Investigations</h4>
               <?php  
                  //if (!empty($_SESSION['logged_billing_manager']['center']) && $_SESSION['logged_billing_manager']['center'] == "16133769691598") {
                //  $allowedCenters = array('16133769691598', '1581157290', '17519672203755');
                //  if (!empty($_SESSION['logged_billing_manager']['center']) && in_array($_SESSION['logged_billing_manager']['center'], $allowedCenters)) {
                  	?>
                  <table id="investigation_main_table">
                     <thead>
                        <tr>
                           <th></th>
                           <th>Name</th>
                           <th>IIC Code</th>
                           <th>Medgenome Code</th>
                           <th>Price</th>
                           <th>Discount</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody id="consumables_table_body">
                        <?php 
                           $invest_total = 0; if(!empty($billing_details['male_minvestigation_suggestion_list'])){ 
                           $male_minvestigation_suggestion_list = unserialize($billing_details['male_minvestigation_suggestion_list']);
                           $male_ivt_count = 1;
                                 foreach($male_minvestigation_suggestion_list as $key => $val){
                                    $investigation_details = $all_method->get_master_investigation_details($val);
                                    foreach ($investigation_details as $investigation_details) {
                                      // if (!empty($investigation_details['center_id']) && $investigation_details['center_id'] == "16133769691598") {
                              ?>
                        <tr class="male_ivt_tr" id="male_invstg_<?php echo $male_ivt_count; ?>">
                           <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                           <td>
                              <?php echo $investigation_details['investigation']; ?>
                              <input value="<?php echo $investigation_details['inv_id']; ?>" invest="<?php echo $investigation_details['investigation']; ?>" readonly="readonly" id="male_investigation_name_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_name_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td>
                              <?php echo $investigation_details['master_code']; ?>
                           </td>
                           <td>
                              <?php echo $investigation_details['code']; ?>
                              <input value="<?php echo $investigation_details['code']; ?>" readonly="readonly" id="male_investigation_code_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_code_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td>
                              <?php $invest_price = 0; $invest_price = $investigation_details['price']; echo 'Rs.'.$invest_price; ?>
                              <input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="male_price_field_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_price_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td><input value="0" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="male_investigation_discount_<?php echo $male_ivt_count; ?>" class="investigation_discount required_value" name="male_investigation_discount_<?php echo $male_ivt_count; ?>" type="text" class="form-control " required></td>
                           <td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                        <?php $grand_total += $invest_price; $invest_total += $invest_price; $male_ivt_count++;}} }/* }*/  ?>
                        <?php  if(!empty($billing_details['female_minvestigation_suggestion_list'])){ 
                           $female_minvestigation_suggestion_list = unserialize($billing_details['female_minvestigation_suggestion_list']);
                                 $female_ivt_count = 1;
                                 foreach($female_minvestigation_suggestion_list as $key => $val){ 
                                 
                                 
                                 $investigation_details = $all_method->get_master_investigation_details($val);
                                 
                              //var_dump($investigation_details);
                              
                              
                           foreach ($investigation_details as $investigation_details) {
                           
                           
                           //echo "ID: " . $detail['inv_id'] . ", Name: " . $detail['investigation'] . "<br>";  
                                 
                                 
                                 if(!empty($investigation_details)){ 
                                 //if (!empty($investigation_details['center_id']) && $investigation_details['center_id'] == "16133769691598") {
                                  //  $allowedCenterIds = array('16133769691598', '1581157290', '17519672203755');
                           
                                 // Check if the center_id exists and is in the allowed list
                                // if (!empty($investigation_details['center_id']) && in_array($investigation_details['center_id'], $allowedCenterIds)) {
                                 ?>
                        <tr class="consumables_row_1 female_ivt_tr " id="fmale_invstg_<?php echo $female_ivt_count; ?>" trcount="<?php echo $female_ivt_count; ?>">
                           <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                           <td><?php echo $investigation_details['investigation']; ?>
                              <input value="<?php echo $investigation_details['inv_id']; ?>" invest="<?php echo $investigation_details['investigation']; ?>" readonly="readonly" id="female_investigation_name_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_name_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td>
                              <?php echo $investigation_details['master_code']; ?>
                           </td>
                           <td><?php echo $investigation_details['code']; ?>
                              <input value="<?php echo $investigation_details['code']; ?>" readonly="readonly" id="female_investigation_code_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_code_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td>
                              <?php $invest_price = 0; $invest_price = $investigation_details['price']; echo 'Rs.'.$invest_price; ?>
                              <input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="female_price_field_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_price_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td><input value="0" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="female_investigation_discount_<?php echo $female_ivt_count; ?>" class="investigation_discount required_value" name="female_investigation_discount_<?php echo $female_ivt_count; ?>" type="text" class="form-control " required></td>
                           <td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                        <?php $grand_total += $invest_price; $invest_total += $invest_price; $female_ivt_count++; } }} } /*}*/ ?>
                     </tbody>
                  </table>
               <?php /*} else{*/ ?>
             <!--  <table id="investigation_main_table">
                  <thead>
                     <tr>
                        <th></th>
                        <th>Name</th>
                        <th>IIC Code</th>
                        <th>Lifecell Code</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody id="consumables_table_body">
                     <?php $invest_total = 0; if(!empty($billing_details['male_minvestigation_suggestion_list'])){ $male_minvestigation_suggestion_list = unserialize($billing_details['male_minvestigation_suggestion_list']);
                        $male_ivt_count = 1;
                        foreach($male_minvestigation_suggestion_list as $key => $val){
                        $investigation_details = $all_method->get_master_investigation_details($val);
                        foreach ($investigation_details as $investigation_details) {
                        // if (!empty($investigation_details['center_id']) && $investigation_details['center_id'] != "16133769691598") {
                        $excludedCenters = array('16133769691598', '1581157290', '17519672203755');
                        
                        // Check if center_id exists and is NOT in the excluded list
                        if (!empty($investigation_details['center_id']) && !in_array($investigation_details['center_id'], $excludedCenters)) {
                              ?>
                     <tr class="male_ivt_tr" id="male_invstg_<?php echo $male_ivt_count; ?>">
                        <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                        <td>
                           <?php echo $investigation_details['investigation']; ?>
                           <input value="<?php echo $investigation_details['inv_id']; ?>" invest="<?php echo $investigation_details['investigation']; ?>" readonly="readonly" id="male_investigation_name_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_name_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                        </td>
                        <td>
                           <?php echo $investigation_details['master_code']; ?>
                        </td>
                        <td>
                           <?php echo $investigation_details['code']; ?>
                           <input value="<?php echo $investigation_details['code']; ?>" readonly="readonly" id="male_investigation_code_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_code_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                        </td>
                        <td>
                           <?php $invest_price = 0; $invest_price = $investigation_details['price']; echo 'Rs.'.$invest_price; ?>
                           <input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="male_price_field_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_price_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                        </td>
                        <td><input value="0" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="male_investigation_discount_<?php echo $male_ivt_count; ?>" class="investigation_discount required_value" name="male_investigation_discount_<?php echo $male_ivt_count; ?>" type="text" class="form-control " required></td>
                        <td><input type="checkbox" class="statuss" name="record"></td>
                     </tr>
                     <?php $grand_total += $invest_price; $invest_total += $invest_price; $male_ivt_count++;}}} } ?>
                     <?php if(!empty($billing_details['female_minvestigation_suggestion_list'])){ $female_minvestigation_suggestion_list = unserialize($billing_details['female_minvestigation_suggestion_list']);
                        $female_ivt_count = 1;
                        foreach($female_minvestigation_suggestion_list as $key => $val){
                        $investigation_details = $all_method->get_master_investigation_details($val);
                        foreach ($investigation_details as $investigation_details) {
                        if(!empty($investigation_details)){ 
                        //if (!empty($investigation_details['center_id']) && $investigation_details['center_id'] != "16133769691598") {
                        $excludedCenters = array('16133769691598', '1581157290', '17519672203755');
                        
                        // Check if center_id exists and is NOT in the excluded list
                        if (!empty($investigation_details['center_id']) && !in_array($investigation_details['center_id'], $excludedCenters)) {
                        ?>
                     <tr class="consumables_row_1 female_ivt_tr " id="fmale_invstg_<?php echo $female_ivt_count; ?>" trcount="<?php echo $female_ivt_count; ?>">
                        <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                        <td><?php echo $investigation_details['investigation']; ?>
                           <input value="<?php echo $investigation_details['inv_id']; ?>" invest="<?php echo $investigation_details['investigation']; ?>" readonly="readonly" id="female_investigation_name_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_name_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                        </td>
                        <td>
                           <?php echo $investigation_details['master_code']; ?>
                        </td>
                        <td><?php echo $investigation_details['code']; ?>
                           <input value="<?php echo $investigation_details['code']; ?>" readonly="readonly" id="female_investigation_code_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_code_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                        </td>
                        <td>
                           <?php $invest_price = 0; $invest_price = $investigation_details['price']; echo 'Rs.'.$invest_price; ?>
                           <input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="female_price_field_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_price_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                        </td>
                        <td><input value="0" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="female_investigation_discount_<?php echo $female_ivt_count; ?>" class="investigation_discount required_value" name="female_investigation_discount_<?php echo $female_ivt_count; ?>" type="text" class="form-control " required></td>
                        <td><input type="checkbox" class="statuss" name="record"></td>
                     </tr>
                     <?php $grand_total += $invest_price; $invest_total += $invest_price; $female_ivt_count++; }}} } } ?>
                  </tbody>
               </table>-->
               <?php //} ?>
               <table>
                  <tr>
                     <td colspan='3'>
                        <strong>SUB TOTAL :-</strong>
                     </td>
                     <td>
                        <input value="<?php echo $female_ivt_count-1; ?>"  id="row_count" type="hidden" name="row_count"/>
                        <strong id="investigation_total"><?php echo $invest_total; ?></strong>
                        <input value="<?php echo $invest_total; ?>" readonly="readonly" id="investigation_sub_total" class="form-control required_value" type="hidden" required>
                        <input value="<?php echo $invest_total; ?>" readonly="readonly" id="actual_investigation_sub_total" class="form-control required_value" type="hidden" required>
                     </td>
                  </tr>
               </table>
               <?php } ?>
            </div>
            <?php } ?>
            <?php if($form_action == "add_procedure"){ ?>
            <div class="row">
               <?php //var_dump($billing_details);die;
                  if($billing_details['procedure_suggestion'] == 1 ){ $parent_procedure_details = $all_method->get_procedure_details($billing_details['procedure_suggestion_list']); 
                  //if($billing_details['procedure_suggestion'] == 1 && $billing_details['procedure_billed'] == 0){ $parent_procedure_details = $all_method->get_procedure_details($billing_details['procedure_suggestion_list']);
                  ?>
               <input type="hidden" name="procedure_suggestion" value="<?php echo $billing_details['procedure_suggestion']; ?>" />
               <input type="button" class="delete-investigations-row btn btn-large pull-right" value="Delete Selected Procedure">
               <h4>Procedure</h4>
               <table id="procedure_table">
                  <thead>
                     <tr>
                        <th>Procedure</th>
                        <th>HUB</th>
                        <th>SPOKE</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Gst Amount</th>
                        <th>Paid Price</th>
                        <th>Mode</th>
                        <th>Receipt</th>
                        <th>Upload Receipt</th>
                        <th>Delete</th>
                     </tr>
                  </thead>
                 <tbody>
                    <?php 	
                       // Build a map of procedure_ID => discount from $billing_details['procedure'] if available
                       $procedure_discount_map = array();
                       if (!empty($billing_details['procedure'])) {
                         $procedureDataFromBilling = @unserialize($billing_details['procedure']);
                         if ($procedureDataFromBilling !== false && !empty($procedureDataFromBilling['consumables']) && is_array($procedureDataFromBilling['consumables'])) {
                           foreach ($procedureDataFromBilling['consumables'] as $consumableItem) {
                             if (!empty($consumableItem['procedure_ID'])) {
                               $procedure_discount_map[$consumableItem['procedure_ID']] = isset($consumableItem['discount']) ? $consumableItem['discount'] : '';
                             }
                           }
                         }
                       }
                    //   if ($_SESSION['logged_billing_manager']['center'] == "16249589462327" || $_SESSION['logged_billing_manager']['center'] == "16133769691598"  || $_SESSION['logged_billing_manager']['center'] == "16267558222750") {
                              $sub_procedure_counter = 1;
                           if(!empty($billing_details['sub_procedure_suggestion_list'])){
                        $sub_procedure_suggestion_list = unserialize($billing_details['sub_procedure_suggestion_list']);
                        foreach($sub_procedure_suggestion_list as $key => $val){
                          $sub_procedure_details = $all_method->get_procedure_details($val); 
                    //        if (!empty($sub_procedure_details['code']) && in_array($sub_procedure_details['code'], ["IP599","IP602","IP42", "IP43", "IP44", "IP45", "IP46","IP274", "IP201", "IP202", "IP203", "IP204", "IP205", "IP206", "IP21", "IP207", "IP208", "IP209", "IP60", "IP43", "IP55", "IP56", "IP57", "IP58", "IP89", "IP91", "IPD4", "IP210", "IP211", "IP212", "IP213", "IP214", "IP215", "IP38", "IP06", "IP15", "IP07", "IP216", "IP217", "IP01", "IP02", "IP03", "IP12", "IP18", "IP17", "IP14", "IP96", "IP65", "IP63", "IP05", "IP04", "IP08", "IP09", "IP37", "IP11", "IP59", "IP16", "IP34", "IP39", "IP20", "IP94", "IP95", "IP97", "IP90", "IP98", "IP99", "IP72", "IP73", "IP93", "IP40", "IP41", "IP48", "IP66", "IP68", "IP69", "IP47", "IP74", "IPD1", "IPD2", "IP67", "IP102", "IP103", "IP104", "IP105", "IP106", "IP107", "IP108", "IP109", "IP110", "IP111", "IP112", "IP113", "IP114", "IP115", "IP116", "IP117", "IP118", "IP119", "IP120", "IP121", "IP122", "IP123", "IP124", "IP125", "IP126", "IP127", "IP128", "IP129", "IP130", "IP131", "IP132", "IP133", "IP134", "IP135", "IP136", "IP137", "IP138", "IP139", "IP140", "IP141", "IP142", "IP143", "IP144", "IP145", "IP146", "IP147", "IP148", "IP149", "IP150", "IP151", "IP152", "IP153", "IP154", "IP155", "IP156", "IP157", "IP158", "IP159", "IP160", "IP161", "IP162", "IP163", "IP164", "IP165", "IP166", "IP167", "IP168", "IP169", "IP170", "IP171", "IP172", "IP173", "IP174", "IP175", "IP176", "IP177", "IP178", "IP179", "IP180", "IP181", "IP182", "IP183", "IP184", "IP185", "IP186", "IP187", "IP188", "IP189", "IP190", "IP191", "IP192", "IP193", "IP194", "IP195", "IP196", "IP197", "IP198", "IP199", "IP200", "IP218", "IP219", "IP220",
                     //   "IP221", "IP226", "IP228", "IP230", "IP232", "IP234", "IP236", "IP238", "IP240", "IP242", "IP19", "IP62", "IP54", "IP64", "IP13", "IP22", "IP23", "IP70", "IP36", "IP71", "IP75", "IP76", "IP100", "IP78", "IP79", "IP80", "IP81", "IP82", "IP83", "IP84", "IP85", "IP86", "IP87", "IP88", "IP92", "IP222", "IP223", "IP224", "IP225", "IP227", "IP229", "IP231", "IP233", "IP235", "IP237", "IP239", "IP241", "IP245","INT78","INT65","INT13","INT02","INT11","INT54","INT42","INT64","INT20","INT229","INT230","IP101","IP250","IP251","IPD2INT","IPD1INT","INT228","INT227","IP243","INT03","INT220","IPD5","INT17","IP244","IP29","INT222","INT48","INT63","IP01D","IP252","IP253","IP286","INT225","INT226","IP24","INT40","INT246","INT07","INT19","IP286","IP292","INT53","IP299","IP300","IP302","IP301","IP303","IP27","IP26","IP298","INT292","IP447","IP246","INT47","IP458","IP357","INT31","IP32","IP453","IP451","IP459","IP288","IP289","INT67","IP31","IP254","INT298","INT458"])) {
                        ?>
                     <tr>
                        <td><?php echo $sub_procedure_details['procedure_name']; ?>
                           <input value="<?php echo $val; ?>" procedure="<?php echo $sub_procedure_details['procedure_name']; ?>" readonly="readonly" id="sub_procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                            <input value="<?php echo $sub_procedure_details['procedure_name']; ?>" procedure="<?php echo $sub_procedure_details['procedure_name']; ?>" readonly="readonly" id="sub_procedure_name_<?php echo $sub_procedure_counter;?>" class="required_value" name="procedure_name_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                       
                        </td>
                        <?php
                           $login_user_center_id = $_SESSION['logged_billing_manager']['center'];
                           $center_exit = $all_method->check_procedure_exit_in_center($sub_procedure_details['code'],$login_user_center_id);
                           if($center_exit) {
                              $hub_name = $all_method->get_center_name($login_user_center_id);
                              $spoke_name =$hub_name;
                              $hub_center_id=$login_user_center_id;
                              $spoke_center_id=$login_user_center_id;
                           }else{
                              $hub_id_of_this_procedure = $all_method->get_hub_center_id_from_spoke($login_user_center_id);
                              $hub_name = $all_method->get_center_name($hub_id_of_this_procedure);
                              $spoke_name =$all_method->get_center_name($login_user_center_id);
                              $hub_center_id=$hub_id_of_this_procedure;
                              $spoke_center_id=$login_user_center_id;
                           }
                        ?>
                        <td>
                           <?php echo $hub_name; ?>
                           <input type="hidden" value="<?php echo $hub_center_id; ?>" name="billing_from" />
                        </td>
                        <td>
                           <?php 
                           echo !empty($spoke_name) ? $spoke_name : $hub_name; 
                           ?>
                           <input type="hidden" value="<?php echo !empty($spoke_name) ? $spoke_center_id : $hub_center_id; ?>"   name="billing_at" 
                           />
                        </td>
                        <td><?php echo $sub_procedure_details['code']; ?>
                           <input value="<?php echo $sub_procedure_details['code']; ?>" readonly="readonly" id="sub_procedures_code_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_code_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['category']; ?>" readonly="readonly" id="sub_procedures_category_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_category_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['procedure']; ?>" readonly="readonly" id="procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['broad_procedure']; ?>" readonly="readonly" id="broad_procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="broad_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['broad_procedure_count']; ?>" readonly="readonly" id="broad_procedure_count_<?php echo $sub_procedure_counter;?>" class="required_value" name="broad_procedure_count_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                       
                        </td>
                        <td><?php $sub_price = 0;
                           $sub_price = $sub_procedure_details['price']; echo 'Rs.'.$sub_price; ?>
                           <input value="<?php echo $sub_price; ?>" readonly="readonly" id="sub_procedures_price_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_price_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                        </td>
                        <!-- <td><input value="" placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>" ></td> -->
                        <td><input value="<?php echo isset($procedure_discount_map[$val]) ? $procedure_discount_map[$val] : ''; ?>"   placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>" ></td>
                        <td><input value="0" placeholder="GST" id="" class="sub_procedures_discount required_value" name="" type="text" class="form-control " required sub_procedures_price="" ></td>
                        <td><input value="" placeholder="Paid Price" id="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" class="sub_procedures_paid_price required_value" name="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required ></td>
                        <td>
                           <select name="payment_method_<?php echo $sub_procedure_counter;?>" id="payment_method_<?php echo $sub_procedure_counter;?>" style="display: block;" required>
                              <option value="" selected>Select</option>
                              <?php if($patient_data['nationality'] == 'indian'){?>
                              <option value="neft" mode="NEFT">NEFT</option>
                              <option value="rtgs" mode="RTGS">RTGS</option>
                              <option value="card" mode="Card">Card</option>
                              <option value="insurance" mode="Insurance">Insurance</option>
                              <?php }else{ ?>
                              <option value="international_card" mode="International Card">International Card</option>
                              <option value="card" mode="Card">Card</option>
                              <?php } ?>
                              <option value="cash" mode="Cash">Cash</option>
                              <option value="cheque" mode="Cheque">Cheque</option>
                              <option value="upi" mode="UPI">UPI</option>
                              <option value="wallet" mode="Wallet">Wallet</option>
                              <option value="Finance" mode="Finance">Finance</option>
                           </select>
                        </td>
                        <td><input value="<?php date_default_timezone_set("America/New_York");$receipt_number = date("YmdHis") . substr(microtime(), 2, 6);echo $receipt_number; ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number_<?php echo $sub_procedure_counter;?>" name="receipt_number_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required></td>
                        <td>
                           <input type="file" 
                                 name="receipt_image_<?php echo $sub_procedure_counter;?>" 
                                 id="receipt_image_<?php echo $sub_procedure_counter;?>" 
                                 class="form-control"
                                 >
                        </td>
                        <td><input type="checkbox" class="statuss" name="record"></td>
                     </tr>
                     <?php  $grand_total += $sub_price; $sub_procedure_counter++;}}//}}else{ ?>
                     <?php 	
                   /*     //var_dump($parent_procedure_details); die;	
                        $sub_procedure_counter = 1;
                        if(!empty($billing_details['sub_procedure_suggestion_list'])){
                        $sub_procedure_suggestion_list = unserialize($billing_details['sub_procedure_suggestion_list']);
                        foreach($sub_procedure_suggestion_list as $key => $val){
                          $sub_procedure_details = $all_method->get_procedure_details($val); //var_dump($val);die;
                        
                            if (!empty($sub_procedure_details['code']) && in_array($sub_procedure_details['code'], ["IP599","IP602","IP55", "IP56", "IP57","IP16" ,"IP58", "IP89", "IP91", "IP68", "IP69", "IPD1", "IPD2", "IP67", "IP175", "IP176", "IP177", "IP178", "IP221", "IP222", "IP223", "IP224", "IP225", "IP227", "IP229", "IP231", "IP233", "IP235", "IP237", "IP239", "IP241", "IP62", "IP64", "IP13", "IP22", "IP23", "IP70", "IP36", "IP245", "IP218", "IP219", "INT222","IP101","IP250","IP251","IPD2INT","IPD1INT","INT228","INT227","INT220","IPD5","IP01D","IP286","IP450","IP452","IP289","INT67","IP451","IP02","IP218","IP45","IP11","IP459","IP14","IP298","IP04","IP64","IP601"])) {
                        ?>
                     <tr>
                     <td><?php echo $sub_procedure_details['procedure_name']; ?>
                        <input value="<?php echo $val; ?>" procedure="<?php echo $sub_procedure_details['procedure_name']; ?>" readonly="readonly" id="sub_procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                     </td>
                        <?php
                           $login_user_center_id = $_SESSION['logged_billing_manager']['center'];
                           $center_exit = $all_method->check_procedure_exit_in_center($sub_procedure_details['code'],$login_user_center_id);
                           if($center_exit) {
                              $hub_name = $all_method->get_center_name($login_user_center_id);
                              $spoke_name =$hub_name;
                              $hub_center_id=$login_user_center_id;
                              $spoke_center_id=$login_user_center_id;
                           }else{
                              $hub_id_of_this_procedure = $all_method->get_hub_center_id_from_spoke($login_user_center_id);
                              $hub_name = $all_method->get_center_name($hub_id_of_this_procedure);
                              $spoke_name =$all_method->get_center_name($login_user_center_id);
                              $hub_center_id=$hub_id_of_this_procedure;
                              $spoke_center_id=$login_user_center_id;
                           }
                        ?>
                         <td>
                           <?php echo $hub_name; ?>
                           <input type="hidden" value="<?php echo $hub_center_id; ?>" name="billing_from" />
                        </td>
                        <td>
                           <?php 
                           echo !empty($spoke_name) ? $spoke_name : $hub_name; 
                           ?>
                           <input type="hidden" 
                                 value="<?php echo !empty($spoke_name) ? $spoke_center_id : $hub_center_id; ?>" 
                                 name="billing_at" 
                           />
                        </td>
                        <td><?php echo $sub_procedure_details['code']; ?>
                           <input value="<?php echo $sub_procedure_details['code']; ?>" readonly="readonly" id="sub_procedures_code_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_code_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                        </td>
                        <td><?php $sub_price = 0;
                           $sub_price = $sub_procedure_details['price']; echo 'Rs.'.$sub_price; ?>
                           <input value="<?php echo $sub_price; ?>" readonly="readonly" id="sub_procedures_price_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_price_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                        </td>
                        <!-- <td><input value="" placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>" ></td> -->
                        <td><input value="<?php echo isset($procedure_discount_map[$val]) ? $procedure_discount_map[$val] : ''; ?>" placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>"  ></td>
                        <td><input value="0" placeholder="GST" id="" class="sub_procedures_discount required_value" name="" type="text" class="form-control " required sub_procedures_price="" ></td>
                        <td><input value="" placeholder="Paid Price" id="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" class="sub_procedures_paid_price required_value" name="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required ></td>
                        <td>
                           <select name="payment_method_<?php echo $sub_procedure_counter;?>" id="payment_method_<?php echo $sub_procedure_counter;?>" style="display: block;" required>
                              <option value="" selected>Select</option>
                              <?php if($patient_data['nationality'] == 'indian'){?>
                              <option value="neft" mode="NEFT">NEFT</option>
                              <option value="rtgs" mode="RTGS">RTGS</option>
                              <option value="card" mode="Card">Card</option>
                              <option value="insurance" mode="Insurance">Insurance</option>
                              <?php }else{ ?>
                              <option value="international_card" mode="International Card">International Card</option>
                              <option value="card" mode="Card">Card</option>
                              <?php } ?>
                              <option value="cash" mode="Cash">Cash</option>
                              <option value="cheque" mode="Cheque">Cheque</option>
                              <option value="upi" mode="UPI">UPI</option>
                              <option value="wallet" mode="Wallet">Wallet</option>
                              <option value="Finance" mode="Finance">Finance</option>
                           </select>
                        </td>
                        <td><input value="<?php echo getReceiptGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number_<?php echo $sub_procedure_counter;?>" name="receipt_number_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required></td>
                        <td><input type="file" name="receipt_image_<?php echo $sub_procedure_counter;?>" id="receipt_image_<?php echo $sub_procedure_counter;?>" class="form-control " required></td>
                        <td><input type="checkbox" class="statuss" name="record"></td>
                     </tr>
                     <?php  $grand_total += $sub_price; $sub_procedure_counter++;}}}} */ ?>
                  </tbody>
               </table>
               <?php } ?>
            </div>
            <?php } ?>
            <?php if($form_action == "add_package"){ ?>
            <div class="row">
               <?php 
                  if($billing_details['package_suggestion'] == 1 ){ $parent_procedure_details = $all_method->get_procedure_details($billing_details['package_suggestion_list']); 
                  ?>
               <input type="hidden" name="package_suggestion" value="<?php echo $billing_details['package_suggestion']; ?>" />
               <input type="button" class="delete-investigations-row btn btn-large pull-right" value="Delete Selected Package">
               <h4>Package</h4>
               <table id="package_table">
                  <thead>
                     <tr>
                        <th>Procedure</th>
                        <th>HUB</th>
                        <th>SPOKE</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Gst Amount</th>
                        <th>Paid Price</th>
                        <th>Mode</th>
                        <th>Receipt</th>
                        <th>Delete</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 	
                        $sub_procedure_counter = 1;
                        
                        // Unserialize package suggestion list once
                        if (!empty($billing_details['package_suggestion_list'])) {
                            $package_suggestion_list = unserialize($billing_details['package_suggestion_list']);
                        
                            foreach ($package_suggestion_list as $key => $val) {
                                // Convert comma-separated values into an array
                                $procedure_ids = explode(',', $val);
                        
                                foreach ($procedure_ids as $procedure_id) {
                                    // Fetch procedure details
                                    $sub_procedure_details = $all_method->get_procedure_details($procedure_id);
                        
                                    if (!$sub_procedure_details) {
                                        continue; // Skip if no details found
                                    }
                                    ?>
                     <tr><!-- -->
                        <td><?php echo $sub_procedure_details['procedure_name']; ?>
                           <input value="<?php echo $procedure_id; ?>" procedure="<?php echo $sub_procedure_details['procedure_name']; ?>" readonly="readonly" id="sub_procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['procedure_name']; ?>" procedure="<?php echo $sub_procedure_details['procedure_name']; ?>" readonly="readonly" id="sub_procedure_name_<?php echo $sub_procedure_counter;?>" class="required_value" name="procedure_name_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                       
                        </td>
                         <?php
                           $login_user_center_id = $_SESSION['logged_billing_manager']['center'];
                           $center_exit = $all_method->check_procedure_exit_in_center($sub_procedure_details['code'],$login_user_center_id);
                           if($center_exit) {
                              $hub_name = $all_method->get_center_name($login_user_center_id);
                              $spoke_name =$hub_name;
                              $hub_center_id=$login_user_center_id;
                              $spoke_center_id=$login_user_center_id;
                           }else{
                              $hub_id_of_this_procedure = $all_method->get_hub_center_id_from_spoke($login_user_center_id);
                              $hub_name = $all_method->get_center_name($hub_id_of_this_procedure);
                              $spoke_name =$all_method->get_center_name($login_user_center_id);
                              $hub_center_id=$hub_id_of_this_procedure;
                              $spoke_center_id=$login_user_center_id;
                           }
                        ?>
                        <td>
                           <?php echo $hub_name; ?>
                           <input type="hidden" value="<?php echo $hub_center_id; ?>" name="billing_from" />
                        </td>
                        <td>
                           <?php 
                           echo !empty($spoke_name) ? $spoke_name : $hub_name; 
                           ?>
                           <input type="hidden" 
                                 value="<?php echo !empty($spoke_name) ? $spoke_center_id : $hub_center_id; ?>" 
                                 name="billing_at"/>
                        </td>
                        <td><?php echo $sub_procedure_details['code']; ?>
                           <input value="<?php echo $sub_procedure_details['code']; ?>" readonly="readonly" id="sub_procedures_code_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_code_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['procedure_name']; ?>"  readonly="readonly" id="sub_procedure_name_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedure_name_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['category']; ?>" readonly="readonly" id="sub_procedures_category_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_category_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['procedure']; ?>" readonly="readonly" id="procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['broad_procedure']; ?>" readonly="readonly" id="broad_procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="broad_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['broad_procedure_count']; ?>" readonly="readonly" id="broad_procedure_count_<?php echo $sub_procedure_counter;?>" class="required_value" name="broad_procedure_count_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                       
                        </td>
                        <td>
                           <?php 
                              $sub_price = $sub_procedure_details['price']; 
                              echo 'Rs. ' . $sub_price; 
                              ?>
                           <input value="<?php echo $sub_price; ?>" readonly="readonly" id="sub_procedures_price_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_price_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                        </td>
                        <!-- <td><input value="0" placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>" ></td> -->

                        <td><input value="<?php echo isset($procedure_discount_map[$procedure_id]) ? $procedure_discount_map[$procedure_id] : '0'; ?>" placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>" ></td>
                        <td><input value="0" placeholder="GST" id="sub_procedures_gst_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_gst_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required></td>
                        <td><input value="0" placeholder="Paid Price" id="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" class="sub_procedures_paid_price required_value" name="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required ></td>
                        <td>
                           <select name="payment_method_<?php echo $sub_procedure_counter;?>" id="payment_method_<?php echo $sub_procedure_counter;?>" style="display: block;" required>
                              <option value="" selected>Select</option>
                              <?php if($patient_data['nationality'] == 'indian'){?>
                              <option value="neft" mode="NEFT">NEFT</option>
                              <option value="rtgs" mode="RTGS">RTGS</option>
                              <option value="card" mode="Card">Card</option>
                              <option value="insurance" mode="Insurance">Insurance</option>
                              <?php }else{ ?>
                              <option value="international_card" mode="International Card">International Card</option>
                              <option value="card" mode="Card">Card</option>
                              <?php } ?>
                              <option value="cash" mode="Cash">Cash</option>
                              <option value="cheque" mode="Cheque">Cheque</option>
                              <option value="upi" mode="UPI">UPI</option>
                              <option value="wallet" mode="Wallet">Wallet</option>
                              <option value="Finance" mode="Finance">Finance</option>
                           </select>
                        </td>
                        <td><input value="<?php echo getReceiptGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number_<?php echo $sub_procedure_counter;?>" name="receipt_number_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required></td>
                        <td><input type="checkbox" class="statuss" name="record"></td>
                     </tr>
                     <?php  
                        $grand_total += $sub_price;
                        $sub_procedure_counter++;
                        }
                        }
                        } 
                        ?>
                  </tbody>
               </table>
               <?php } ?>
            </div>
            <?php } ?>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Payment in(Required)</label><br/>
                  <input value="rs_payment" name="payment_in" style="position: relative;left: 0;opacity: 1;" class="payment_in" type="radio"> Rupees
                  <?php if($patient_data['nationality'] == 'non-indian'){ ?>      
                  <input value="us_payment" style="position: relative;left: 0;opacity: 1;" class="payment_in" name="payment_in" type="radio"> USD
                  <?php } ?>
               </div>
            </div>
            <div class="row" id="grand_total_section" style="display:none;">
               <?php if($billing_type == "investigation") { ?>
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12 role">
                     <label for="statuss">Payment mode (Required)</label>
                     <select name="payment_method" id="payment_method" required>
                        <option value="">Select</option>
                        <?php if($patient_data['nationality'] == 'indian'){?>
                        <option value="neft" mode="NEFT">NEFT</option>
                        <option value="rtgs" mode="RTGS">RTGS</option>
                        <option value="card" mode="Card">Card</option>
                        <option value="insurance" mode="Insurance">Insurance</option>
                        <?php }else{ ?>
                        <option value="international_card" mode="International Card">International Card</option>
                        <option value="card" mode="Card">Card</option>
                        <?php } ?>
                        <option value="cash" mode="Cash">Cash</option>
                        <option value="cheque" mode="Cheque">Cheque</option>
                        <option value="upi" mode="UPI">UPI</option>
                        <option value="wallet" mode="Wallet">Wallet</option>
                        <option value="Finance" mode="Finance">Finance</option>
                     </select>
                  </div>
                  <div class="form-group col-sm-6 col-xs-12" id="subvention_box" style="display:none;">
                     <label for="item_name">Subvention charges (Required)</label>
                     <input value="" placeholder="Subvention charges" id="subvention_charges" name="subvention_charges" type="text" class="form-control validate">
                  </div>
               </div>
               <?php } ?>
               <?php if($patient_data['nationality'] == 'non-indian'){ ?>      
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Grand Total (USD) (Required)</label>
                     <input value="<?php echo round($grand_total/$converstion_rate, 2); ?>" name="usd_totalpackage" placeholder="grand total" readonly="readonly" class="usd_dhee required_value" id="usd_fees" type="hidden" class="form-control " required>
                     <input value="<?php echo round($grand_total/$converstion_rate, 2); ?>" placeholder="grand total" readonly="readonly" name="usd_fees" id="usd_after_discount" type="text" class="form-control required_value" required>
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Discount (USD) (Required)3</label>
                     <input value="0" readonly="readonly" name="us_discount" id="us_discount" type="text" class="form-control required_value" required>
                  </div>
               </div>
               <?php } ?>
               <div class="row">
                  <?php if($billing_type == "investigation") { ?>  
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Grand Total (Rupee) (Required)</label>
                     <input value="<?php echo $grand_total; ?>" name="rs_fees" placeholder="grand total" readonly="readonly" class="required_value" id="rs_after_discount" type="hidden" class="rs_dhee form-control required_value" required>
                     <input value="<?php echo $grand_total; ?>" placeholder="grand total" readonly="readonly" name="rs_totalpackage" id="rs_totalpackage" type="text" class="form-control" required>
                  </div>
                  <?php } ?>
                  <div class="form-group col-sm-6 col-xs-12">
                     <?php if($billing_type == "investigation") { ?>  
                     <label for="item_name">Discount (Rupee) (Required)</label>
                     <input value="0" readonly="readonly" name="rs_discount" id="rs_discount" type="text" class="form-control required_value" required>
                     <?php } ?> <?php 
                        $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$billing_details['wife_phone']."' and paitent_type='new_patient'"; 
                             $query = $this->db->query($sql1);
                                          $select_result1 = $query->result(); 
                        foreach ($select_result1 as $res_val){
                        ?>
                     <input value="<?php echo $res_val->appoitment_for;?>" placeholder="origins" readonly="readonly" name="origins" id="origins" type="hidden" class="form-control">
                     <?php } ?>
                  </div>
               </div>
               <?php if($billing_type == "investigation") { ?>
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Discount amount</label>
                     <input value="0" placeholder="Discount amount" id="discount_amount" readonly="readonly" name="discount_amount" type="text" class="form-control required_value" required>
                     <input value="<?php echo $_SESSION['logged_billing_manager']['allow_discount_rs'] ;?>" id="allow_discount" type="hidden" class="form-control " required>
                     <p id="show_disc_app" style="display:none;">Given discount is more than allowed, <a href="javascript:void(0);" accountant="<?php echo $_SESSION['logged_billing_manager']['username'];?>" id="get_discount_approval">click here</a> for admin approval.</p>
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Payment received (Required)</label>
                     <input value="" placeholder="Payment received" id="payment_done" step="any" name="payment_done" type="number" class="form-control required_value" required>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Remaining amount (Required)</label>
                     <input value="" placeholder="Remaining amount" readonly="readonly" id="remaining_amount" name="remaining_amount" type="text" class="form-control required_value" required>
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Cash Payment</label>
                     <input value="" placeholder="Cash Payment" id="cash_payment" name="cash_payment" type="text" class="form-control ">
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Card Payment</label>
                     <input value="" placeholder="Card Payment" id="card_payment" name="card_payment" type="text" class="form-control ">
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">UPI Payment</label>
                     <input value="" placeholder="UPI Payment" id="upi_payment" name="upi_payment" type="text" class="form-control ">
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">NEFT Payment</label>
                     <input value="" placeholder="NEFT Payment" id="neft_payment" name="neft_payment" type="text" class="form-control ">
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Wallet Payment</label>
                     <input type="number" wallet_payment="<?php echo $balance; ?>" name="wallet_payment" id="wallet_payment" onchange="consumables_quantity_update(this)">
                  </div>
               </div>
               <?php } ?>
               <?php 	
                  //if(!empty($billing_details['sub_procedure_suggestion_list'])){
                  /*	$sub_procedure_suggestion_list = unserialize($billing_details['sub_procedure_suggestion_list']);
                  foreach($sub_procedure_suggestion_list as $key => $val){
                        $sub_procedure_details = $all_method->get_procedure_details($val); //var_dump($val);die;
                  
                          if (!empty($sub_procedure_details['code']) && in_array($sub_procedure_details['code'], ["IP17", "IP14", "IP96"])) {*/
                  ?>
               <!-- <div class="form-group col-sm-6 col-xs-12">
                  <label>Freezing Valid Date</label>
                  <input placeholder="Expiry Date" name="expiry_date" id="expiry_date" value="" type="date" class="form-control">
                  </div>
                  <div class="form-group col-sm-6 col-xs-12 role">
                    <label for="item_name">Renewal Type</label>
                    <select name="renewal_type" id="renewal_type">
                          <option value="">Select</option>
                          <option value="First Renewal">First Renewal</option> 
                          <option value="Second Renewal">Second Renewal</option> 
                          <option value="Third Renewal">Third Renewal</option> 
                          <option value="Fourth Renewal">Fourth Renewal</option> 
                          <option value="Fifth Renewal">Fifth Renewal</option> 
                          <option value="Sixth Renewal">Sixth Renewal</option> 
                          <option value="Seventh Renewal">Seventh Renewal</option> 
                          <option value="Eighth Renewal">Eighth Renewal</option> 
                          <option value="Ninth Renewal">Ninth Renewal</option> 
                          <option value="Tenth Renewal">Tenth Renewal</option> 
                    </select>
                  
                  </div-->
               <?php //}}//} ?>
               <?php if($billing_type == "investigation") { ?>  
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12" id="transaction" style="display:none;">
                     <label for="item_name">Reference no. (Optional)</label>
                     <input value="" placeholder="Reference no." id="transaction_id" name="transaction_id" type="text" class="form-control  required_value" required>
                     <label>Upload screenshot/document here</label>
                     <input type="file" class="required_value" name="transaction_img" id="transaction_img"  />
                  </div>
               </div>
               <?php } ?>
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12 role" style="display:none;">
                     <label for="statuss">Billing source (Required)</label>
                     <input type="hidden" value="16249589462327" class="required_value" name="billing_from" id="billing_from"  />
                  </div>
                  <div class="form-group col-sm-6 col-xs-12 hospital_id_section role">
                     <label for="item_name">Center Source</label>
                     <select name="hospital_id" class="required_value" id="hospital_id" required>
                        <option value="">Select</option>
                        <option value="Noida">Noida</option>
                        <option value="Gurgaon">Gurgaon</option>
                        <option value="Green Park">Green Park</option>
                        <option value="Srinagar">SRINAGAR</option>
                        <option value="Ghaziabad">Ghaziabad</option>
                        <option value="Rohini">Rohini</option>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12">
                     <label for="item_name">Billing ID (Optional)</label>
                     <input value="" placeholder="Billing ID" id="billing_id" name="billing_id" type="text" class="form-control ">
                  </div>
                  <div class="form-group col-sm-6 col-xs-12">
                     <?php if($billing_type == "package") { ?>
                     <label for="statuss">Package Form (Required)</label>
                     <input id="package_form" name="package_form" type="file" class="form-control">
                     <?php } ?>
                     <?php if($billing_type == "procedure") { ?>
                     <label for="statuss">Package Form (Required)</label>
                     <input id="package_form" name="package_form" type="file" class="form-control">
                     <?php } ?>
                  </div>
                  <div class="form-group col-sm-6 col-xs-12 role" style="display:none;">
                     <label for="statuss">Billing source (Required)</label>
                     <?php if($billing_type == "investigation") { ?>
                     <input value="<?php echo $centers_result['center_code']; ?>/D/<?php echo $financial_year; ?>/" id="series_number" name="series_number" type="hidden" class="form-control validate">
                     <?php } ?>
                     <?php if($billing_type == "procedure") { ?>
                     <input value="<?php echo $centers_result['center_code']; ?>/P/<?php echo $financial_year; ?>/" id="series_number" name="series_number" type="hidden" class="form-control validate">
                     <input type="hidden" value="<?php echo $select_result['po_id'] + 1; ?> " class="required_value" name="po_id" id="po_id"  />   								
                     <?php } ?>
                  </div>
               </div>
               <div class="clearfix"></div>
               <div class="form-group col-sm-12 col-xs-12">
                  <a class="btn btn-large" id="create_billing" href="javascript:void(0);">Create Billings</a>
               </div>
            </div>
         </div>
         </p>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12 panel panel-piluku" style="display:none;" id="consultation_preview">
         <div class="panel-heading">
            <h3 class="heading">Billing Summary</h3>
         </div>
         <div class="panel-body profile-edit">
            <p id="msg_area" class="delete"></p>
            <p>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="statuss">Paramedic (Required)</label>
                  <p id="paramedic_text"></p>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Date (Required)</label>
                  <p id=""><?php echo date("Y-m-d H:i:s"); ?></p>
               </div>
            </div>
            <div class="row investigation_preview_table">
               <table>
                  <thead>
                     <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Discount</th>
                     </tr>
                  </thead>
                  <tbody id="investigation_preview_table_body"></tbody>
               </table>
            </div>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Receipt number (Required)</label>
                  <p id="receipt_number_text"><?php date_default_timezone_set("America/New_York");$receipt_number = date("YmdHis") . substr(microtime(), 2, 6);echo $receipt_number; ?></p>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Fees (Required)</label>
                  <p id="fees_text"></p>
                  <p> Discount : <span id="discount_text"></span></p>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Payment received (Required)</label>
                  <p id="payment_done_text"></p>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Remaining amount (Required)</label>
                  <p id="remaining_amount_text"></p>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="statuss">Payment mode (Required)</label>
                  <p id="payment_method_text"></p>
               </div>
               <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="statuss">Transaction ID(Required)</label>
                  <p id="transaction_id_text"></p>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Billing ID</label>
                  <p id="billing_id_text"></p>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Hospital ID</label>
                  <p id="hospital_id_text"></p>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-sm-12 col-xs-12">
               <a class="btn btn-large" id="edit_billing" href="javascript:void(0);">Edit Billing</a>
               <input type="submit" id="submitbutton" class="btn btn-large" value="Create Billing" />
            </div>
         </div>
         </p>
      </div>
   </div>
</form>
<script type="text/javascript">
   $(document).on('keyup',"#subvention_charges",function(e) {
     var subvention_charges = $(this).val();
     var fees = parseFloat($('#rs_fees').val());
     var discount = parseFloat($('#rs_discount').val());
     fees = (parseFloat(fees) - parseFloat(discount));
     console.log(fees+"-----------"+subvention_charges);
     if(subvention_charges != ""){
       var subvention = (parseFloat(subvention_charges) + parseFloat(fees));
       $('#rs_after_discount').val(parseFloat(subvention));
       $('#rs_totalpackage').val(parseFloat(subvention));
     }else{
       $('#rs_after_discount').val(parseFloat(fees));
       $('#rs_totalpackage').val(parseFloat(fees));
     }
   });
   
   //  $(document).on('keyup',".sub_procedures_discount",function(e) {
       
   // });
   
    $(document).on('keyup',".sub_procedures_discount",function(e) {
   
      $(".payment_in").prop('checked', false);
      $('#grand_total_section').hide();
   
       var given_discount = given_price = total = grand_total = 0;
       $('.sub_procedures_discount').each(function(){
           var discount = $(this).val();
           var investigation_price = $(this).attr('sub_procedures_price');
           var total = (investigation_price-discount);
           grand_total += parseFloat(total) || 0;
           given_discount += parseFloat(discount) || 0;
       });
       $('#rs_fees').val(grand_total.toFixed(2));
       $('#rs_after_discount').val(grand_total.toFixed(2));
       $('#rs_totalpackage').val(grand_total.toFixed(2));
       $('#discount_amount').val(0);
       $('#discount_amount').val(given_discount);
       var us_converstion_discount = (given_discount / <?php echo $converstion_rate; ?>);
       $('#us_discount').val(us_converstion_discount.toFixed(2));
       $('#rs_discount').val(given_discount.toFixed(2));
   
       console.log("given_discount_proce="+given_discount);
   });
   
   
   $(document).on('keyup',".investigation_discount",function(e) {
   
      $(".payment_in").prop('checked', false);
      $('#grand_total_section').hide();
   
     var given_discount = given_price = total = grand_total = total_without_discount = 0;
       $('.investigation_discount').each(function(){
           var discount = $(this).val();
           var investigation_price = $(this).attr('investigation_price');
           discount = parseFloat(investigation_price) * parseFloat(discount)/100;
           var total = (investigation_price-discount);
           grand_total += parseFloat(total) || 0;
           given_discount += parseFloat(discount) || 0;
           total_without_discount +=  parseFloat(investigation_price) || 0;
       });
       $('strong#investigation_total').empty().append(grand_total.toFixed(2));
       $('#investigation_sub_total').val(grand_total.toFixed(2));
    
        $('#rs_fees').val(grand_total.toFixed(2));
       $('#rs_after_discount').val(grand_total.toFixed(2));
        $('#rs_totalpackage').val(total_without_discount.toFixed(2));
   
     $('#investigation_sub_total').val(grand_total.toFixed(2));
   
       $('#discount_amount').val(0);
       $('#discount_amount').val(given_discount);
       var us_converstion_discount = (given_discount / <?php echo $converstion_rate; ?>);
       $('#us_discount').val(us_converstion_discount.toFixed(2));
       $('#rs_discount').val(given_discount.toFixed(2));
   
       console.log("given_discount11="+given_discount);
   });
   
   $(document).on('change',".payment_in",function(e) {
       $('#remaining_amount').val('');
       var payment_in = $(this).val();
       
       var billing_type = $('#billing_type').val();
       if(billing_type == 'investigation'){
           var medicine_sub_total = parseFloat($('#medicine_sub_total').val()) || 0;
           var actual_investigation_sub_total = parseFloat($('#investigation_sub_total').val()) || 0;
           var medicine_plus_investigation = parseFloat(medicine_sub_total) + parseFloat(actual_investigation_sub_total);
           var medicine_plus_investigation_usd = (medicine_plus_investigation/<?php echo $converstion_rate; ?>).toFixed(2);
           $('.usd_dhee').val(parseFloat(medicine_plus_investigation_usd));
           $('.rs_dhee').val(parseFloat(medicine_plus_investigation));
           $('#usd_after_discount').val(parseFloat(medicine_plus_investigation_usd));
           $('#rs_after_discount').val(parseFloat(medicine_plus_investigation));
            $('#rs_totalpackage').val(parseFloat(medicine_plus_investigation));
        
       }
       //var usd_dhee = parseFloat($('.usd_dhee').val());
       //var rs_dhee = parseFloat($('.rs_dhee').val());
    
       
       cal_discount(payment_in);
       $('#grand_total_section').show();
   });
   
   function cal_discount(payment_in){
    // alert('function cal dis');
     $('#payment_done').val('');
   $('#remaining_amount').val('');
     var fees_amount = 0; var after_cal_price = 0;
     var allowd = $('#allow_discount').val();
   
     if(payment_in == 'us_payment'){
       $("#discount_amount").val($('#us_discount').val());
       fees_amount = parseFloat($('.usd_dhee').val());
       after_cal_price = ( fees_amount * allowd / 100 ).toFixed(2);
     }else{
       $("#discount_amount").val($('#rs_discount').val());
       fees_amount = parseFloat($('#rs_after_discount').val());
   
       console.log('fees_amount======================'+fees_amount);
       after_cal_price = ( fees_amount * allowd / 100 ).toFixed(2);
     }
     var discount_amount = parseFloat($("#discount_amount").val());
   
     //alert('fees_amount='+fees_amount+'-----------allowd='+allowd+'--------discount_amount='+discount_amount+'-----after='+after_cal_price);
     var medicin_price = $("#medicine_sub_total").val();
   
   if(discount_amount > allowd){
           if(payment_in == 'us_payment'){
             $('#usd_after_discount').val(parseFloat(fees_amount));
           }else{
             $('#rs_after_discount').val(parseFloat(fees_amount));
             $('#rs_totalpackage').val(parseFloat(fees_amount) + parseFloat(discount_amount));
           }				
         $('#show_disc_app').show();
         $('#create_billing').hide();
   }else{
     		//var remaining_amount =  fees_amount - discount_amount;
         var remaining_amount =  fees_amount;
     // 		alert(remaining_amount);
   	if(remaining_amount < 1){
   		$('#payment_done').val(' ');
   		$('#rs_after_discount').val(' ');
         $('#usd_after_discount').val(' ');
   		$("#discount_amount").val(' ');
   		$('.investigation_discount').val(' ');
   	}else{
           if(payment_in == 'us_payment'){
             $('#usd_after_discount').val(remaining_amount.toFixed(2));
           }else{
             $('#rs_after_discount').val(remaining_amount.toFixed(2));
             $('#rs_totalpackage').val(parseFloat(remaining_amount.toFixed(2)) + parseFloat(discount_amount));
           }
   	}
   	$('#show_disc_app').hide();
   	$('#create_billing').show();
   }
   }
   
   $(document).on('keyup',"#payment_done",function(e) {
       var payment_in = $('.payment_in:checked').val();
       var fees = 0;
       if(payment_in == 'us_payment'){
         fees = parseFloat($('#usd_after_discount').val());
       }else{
         fees = parseFloat($('#rs_after_discount').val());
       }
       $('#remaining_amount').val(0);
       var payment_done = $(this).val();
       var remaining_amount = fees-payment_done;
       $('#remaining_amount').val(remaining_amount.toFixed(2));
   });
   
   $(document).on('change',"#payment_method",function(e) {
     <?php if($patient_data['nationality'] == 'indian'){ ?> 
       $('#subvention_charges').val("");
       $('#subvention_charges').removeClass('required_value');
       $('#subvention_box').hide();
       
     //   var fees = parseFloat($('#rs_fees').val());
     //   var discount = parseFloat($('#rs_discount').val());
     //   $('#rs_after_discount').val((parseFloat(fees) - parseFloat(discount)));
     
     <?php } ?>
   
     $('#remaining_amount').val("");
     $('#payment_done').val("");
   
     $('#transaction_id').prop('required',false);
   $('#transaction_img').prop('required',false);
     $('#transaction_id').empty();
     var method = $(this).val();
     $('#transaction_id').removeClass('required_value');
   $('#transaction_img').removeClass('required_value');
     if(method == 'cash'){
         $('#transaction_id').removeClass('required_value');
   $('#transaction_img').removeClass('required_value');
     }
   if(method == ''){
   	 $('#transaction_id').prop('required',false);
   	 $('#transaction_img').prop('required',false);
   	 $('#transaction').hide();		
   }else{
   	 $('#transaction_id').prop('required',false);
   	 $('#transaction_img').prop('required',false);
   	 $('#transaction').show();
   }
     if(method == "insurance"){
       $('#subvention_charges').addClass('required_value');
       $('#subvention_box').show();
     }
   });
   
    <?php if($_GET['t'] == "procedure_billing"){ ?>
     
     $(document).on('click',"#create_billing",function(e) {
        // Prevent duplicate submissions
        if ($(this).hasClass('disabled') || $(this).prop('disabled')) {
            e.preventDefault();
            return false;
        }
        
        // Disable button and add loading state
        $(this).addClass('disabled').prop('disabled', true);
        var originalText = $(this).text();
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        
        var value = $('.required_value').filter(function () {
           return this.value === '';
         });
       if (value.length == 0) {
         $('#msg_area').empty(); $('#doctor_id_text').empty(); $('#fees_text').empty(); $('#payment_done_text').empty(); 
         $('#remaining_amount_text').empty(); $('#payment_method_text').empty(); $('#investigation_preview_table_body').empty();
         $('#billing_id_text').empty(); $('#investigation_id_text').empty(); $('#hospital_id_text').empty(); $('#discount_text').empty();
   
         var male_countr = 1;
         var procedure_table_tr= $('#procedure_table tbody tr').length;
         console.log("procedure_table_tr="+procedure_table_tr);
         $('#procedure_table tbody tr').each(function(){
           if(male_countr <= procedure_table_tr){
               var investigationname, investigation_code, investigationprice, investigation_discount = '';
               investigationname = $('#sub_procedure_'+male_countr).attr('procedure');
               investigation_code = $('#sub_procedures_code_'+male_countr).val();
               investigationprice = $('#sub_procedures_price_'+male_countr).val();
               investigation_discount = $('#sub_procedures_discount_'+male_countr).val();
   
               $('#investigation_preview_table_body').append('<tr><td class="role">'+investigationname+'</td><td>'+investigation_code+'</td><td>'+investigationprice+'</td><td>'+investigation_discount+'</td></tr>');
               male_countr++;
           }
         });
   
         var doctor = $('#doctor_id').val();
         var payment_done = $('#payment_done').val();
         var payment_method = $('#payment_method').val();
         var paramedic_name =  $('#paramedic_name').val();
       
         var transaction_id = $('#transaction_id').val();
         var transaction_img = $('#transaction_img').val();
         if(doctor == '' || payment_done == '' || payment_method == '' || paramedic_name==''){
           $('#msg_area').append('One or more fields are empty !')
           // Re-enable button on error
           $('#create_billing').removeClass('disabled').prop('disabled', false).text(originalText);
         }else{
           var after_discount = 0;
           var payment_in = $('.payment_in:checked').val();
           if(payment_in == "us_payment"){
             after_discount = parseFloat($("#usd_after_discount").val());
           }else{
             after_discount = parseFloat($("#rs_after_discount").val());
           }
           $('#fees_text').empty().append(after_discount);
           $('#payment_done_text').empty().append($('#payment_done').val());
           $('#remaining_amount_text').empty().append($('#remaining_amount').val());
           $('#transaction_id_text').empty().append($('#transaction_id').val());
           $('#payment_method_text').empty().append($('#payment_method').find(':selected').attr('mode'));
           $('#billing_id_text').empty().append($('#billing_id').val());
           $('#discount_text').empty().append($('#discount_amount').val());
           $('#hospital_id_text').empty().append($('#hospital_id').val());
           $('#consultation_details').hide();
           $('#consultation_preview').show();
         }
   
       }else if (value.length > 0) { 
           alert('Please fill out all fields.'); 
           // Re-enable button on error
           $('#create_billing').removeClass('disabled').prop('disabled', false).text(originalText);
       }
     });
   <?php } else { ?>
   
   $(document).on('click',"#create_billing",function(e) {
        // Prevent duplicate submissions
        if ($(this).hasClass('disabled') || $(this).prop('disabled')) {
            e.preventDefault();
            return false;
        }
        
        // Disable button and add loading state
        $(this).addClass('disabled').prop('disabled', true);
        var originalText = $(this).text();
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        
       var value = $('.required_value').filter(function () {
         return this.value === '';
       });
       if (value.length == 0) {
         $('#msg_area').empty(); $('#doctor_id_text').empty(); $('#fees_text').empty(); $('#payment_done_text').empty(); 
         $('#remaining_amount_text').empty(); $('#payment_method_text').empty(); $('#investigation_preview_table_body').empty();
         $('#billing_id_text').empty(); $('#investigation_id_text').empty(); $('#hospital_id_text').empty(); $('#discount_text').empty();
   
         //Investigation
         var male_countr = 1;
         var female_countr = 1;
         var male_ivt_tr= $('#investigation_main_table tr.male_ivt_tr').length;
         var female_ivt_tr= $('#investigation_main_table tr.female_ivt_tr').length;
         
   
         console.log("male_ivt_tr="+male_ivt_tr+"---------female_ivt_tr="+female_ivt_tr);
         
         if(male_ivt_tr > 0){
           $('#investigation_main_table tr.male_ivt_tr').each(function(){
             if(male_countr <= male_ivt_tr){
                 var investigationname, investigation_code, investigationprice, investigation_discount = '';
                 investigationname = $('#male_investigation_name_'+male_countr).attr('invest');
                 investigation_code = $('#male_investigation_code_'+male_countr).val();
                 investigationprice = $('#male_price_field_'+male_countr).val();
                 investigation_discount = $('#male_investigation_discount_'+male_countr).val();
   
                 $('#investigation_preview_table_body').append('<tr><td class="role">'+investigationname+'</td><td>'+investigation_code+'</td><td>'+investigationprice+'</td><td>'+investigation_discount+'</td></tr>');
                 male_countr++;
             }
           });
         }
   
         if(female_ivt_tr > 0){
           $('#investigation_main_table tr.female_ivt_tr').each(function(){
             if(female_countr <= female_ivt_tr){
                 var investigationname, investigation_code, investigationprice, investigation_discount = '';
                 
                 investigationname = $('#female_investigation_name_'+female_countr).attr('invest');
                 investigation_code = $('#female_investigation_code_'+female_countr).val();
                 investigationprice = $('#female_price_field_'+female_countr).val();
                 investigation_discount = $('#female_investigation_discount_'+female_countr).val();
   
                 $('#investigation_preview_table_body').append('<tr><td class="role">'+investigationname+'</td><td>'+investigation_code+'</td><td>'+investigationprice+'</td><td>'+investigation_discount+'</td></tr>');
                 female_countr++;
             }
           });
         }
         var doctor = $('#doctor_id').val();
         var payment_done = $('#payment_done').val();
         var payment_method = $('#payment_method').val();
         var paramedic_name =  $('#paramedic_name').val();
       
         var transaction_id = $('#transaction_id').val();
         var transaction_img = $('#transaction_img').val();
         if(doctor == '' || payment_done == '' || payment_method == '' || paramedic_name==''){
           $('#msg_area').append('One or more fields are empty !')
           // Re-enable button on error
           $('#create_billing').removeClass('disabled').prop('disabled', false).text(originalText);
         }else{
           var after_discount = 0;
           var payment_in = $('.payment_in:checked').val();
           console.log('paytm--------'+payment_in);
           if(payment_in == "us_payment"){
             after_discount = parseFloat($("#usd_after_discount").val());
           }else{
             after_discount = parseFloat($("#rs_after_discount").val());
           }
           $('#paramedic_text').empty().append($('#paramedic_name').val());
           $('#fees_text').empty().append(after_discount.toFixed(2));
           $('#payment_done_text').empty().append($('#payment_done').val());
           $('#remaining_amount_text').empty().append($('#remaining_amount').val());
           $('#transaction_id_text').empty().append($('#transaction_id').val());
           $('#payment_method_text').empty().append($('#payment_method').find(':selected').attr('mode'));
           $('#billing_id_text').empty().append($('#billing_id').val());
           $('#discount_text').empty().append($('#discount_amount').val());
           $('#hospital_id_text').empty().append($('#hospital_id').val());
           $('#consultation_details').hide();
           $('#consultation_preview').show();
         }
   
       }else if (value.length > 0) { 
           alert('Please fill out all fields.'); 
           // Re-enable button on error
           $('#create_billing').removeClass('disabled').prop('disabled', false).text(originalText);
       }
     });
   <?php } ?>
   
     $(document).on('click',"#edit_billing",function(e) {
   $('#consultation_preview').hide();
   $('#consultation_details').show();
   });
   
   $(document).on('change',"#billing_from",function(e) {
     var billing_from = $(this).val();
     if(billing_from == 'IndiaIVF'){
       $('#hospital_id').prop('required',false);
       $('.hospital_id_section').hide();
     }else{
       $('#hospital_id').prop('required',true);
       $('.hospital_id_section').show();
     }
   });
   
   
   
   $(document).on('click',".remove_invstg_tr",function(e) {
     var trid = $(this).data('investg');
     $('tr#'+trid).remove();
     $(".payment_in").prop('checked', false);
      $('#grand_total_section').hide();
   
    var given_discount = given_price = total = grand_total = 0;
       $('.investigation_discount').each(function(){
           var discount = $(this).val();
           var investigation_price = $(this).attr('investigation_price');
           discount = parseFloat(investigation_price) * parseFloat(discount)/100;
           var total = (investigation_price-discount);
           grand_total += parseFloat(total) || 0;
           given_discount += parseFloat(discount) || 0;
       });
       
    $('strong#investigation_total').empty().append(grand_total.toFixed(2));
    
    $('#rs_fees').val(grand_total.toFixed(2));
    $('#rs_after_discount').val(grand_total.toFixed(2));
      $('#rs_totalpackage').val(grand_total.toFixed(2));
      // $('#investigation_sub_total').val(grand_total.toFixed(2));
       
    $('#discount_amount').val(0);
      // $('#discount_amount').val(given_discount);
      // var us_converstion_discount = (given_discount / <?php echo $converstion_rate; ?>);
       $('#us_discount').val(us_converstion_discount.toFixed(2));
       $('#rs_discount').val(given_discount.toFixed(2));
       console.log("given_discount="+given_discount.toFixed(2));
   });
</script>
<script type="text/javascript">
   $(document).ready(function(){
         $(".add-investigations-row").click(function(){
   	//var rows= $('#row_count').val();
   	//var rows= $('#consumables_table_body tr:last').attr('trcount');
   	var rows = $('#consumables_table_body tr').length
   	var count = parseInt(rows) + 1;
             var markup = '<tr class="female_ivt_tr" id="fmale_invstg_'+count+'" trcount="'+count+'"><td><input type="checkbox" class="active-statuss"  rel="consumables"  index="'+count+'"></td><td class="role cons_cls_'+count+'"><select name="consumables_name_'+count+'" class="cons-cls-'+count+' item_select consumables_select form-control " id="consumables_name_'+count+'" count="'+count+'"><?php echo $inved_options; ?></select></td> <td><input value="<?php echo $investigation_details['ID']; ?>" readonly="readonly" id="female_investigation_name_'+count+'" trcount="'+count+'" class="price_field required_value" name="female_investigation_name_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control"><input value="<?php echo $investigation_details['code']; ?>" readonly="readonly" id="female_investigation_code_'+count+'" trcount="'+count+'" class="price_field required_value" name="female_investigation_code_<?php echo $female_ivt_count; ?>" type="text" class="form-control"></td><td><input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="female_price_field_'+count+'" trcount="'+count+'" class="price_field required_value" name="female_investigation_price_<?php echo $female_ivt_count; ?>" type="text" class="form-control " required></td><td><input value="" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="female_investigation_discount_'+count+'" trcount="'+count+'" class="investigation_discount required_value" name="female_investigation_discount_<?php echo $female_ivt_count; ?>" type="text" class="form-control " required></td><td><input type="checkbox" class="statuss" name="record"></td></tr>';
              $("table tbody#consumables_table_body").append(markup);
   	$('#row_count').val(count)
   //	calculate_fees();
         });
     });
   
   $(document).on('change',".consumables_select",function(e) {
         //$('#msg_area').empty();
   var selected_data = $(this).val();
   var count = $(this).attr('count');
   const myArray = selected_data.split(":~");
     
     $('#female_investigation_name_'+count).val(myArray[3]);
     $('#female_investigation_name_'+count).attr("invest", myArray[0]);
   $('#female_investigation_code_'+count).val(myArray[1]);
   $('#female_price_field_'+count).val(myArray[2]);
   $('#female_investigation_discount_'+count).attr("investigation_price", myArray[2]);
   
    $('#male_investigation_name_'+count).val(myArray[3]);
     $('#male_investigation_name_'+count).attr("invest", myArray[0]);
   $('#male_investigation_code_'+count).val(myArray[1]);
   $('#male_price_field_'+count).val(myArray[2]);
   $('#male_investigation_discount_'+count).attr("investigation_price", myArray[2]);
   
     });
   
   // Find and remove selected table rows
     $(".delete-investigations-row").click(function(){
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
</script>
<script>
   $(document).on('click',".active-statuss",function(e) {
       var count = $(this).attr('index');
       var type = $(this).attr('rel');
       if($(this).is(':checked'))
       {
          // console.log(count+"---------"+type);
           if(type =="consumables"){
               $('td.role.cons_cls_'+count+' select').select2({tags: true});
               $('.cons-cls-'+count).prop("disabled", false);
               $('.cons-cls-'+count).addClass("required_value");
           }
       }else
       {
          if(type =="consumables"){
               $('.cons-cls-'+count).prop("disabled", true);
               $('.cons-cls-'+count).removeClass("required_value");
           }
       }       
   });	
   
   function consumables_quantity_update(el) {
   var wallet_payment = $(el).attr('wallet_payment');
   var balance = $(el).val();
   if (balance > 0) {
       if (parseInt(wallet_payment) < parseInt(balance)) {
           alert('Wallet Amount must be less than or equal to wallet payment');
           $(el).val("0");
           return false;
       }
   }
   }
   
   // Fix for payment_method validation error
   $(document).ready(function() {
       // Handle form submission to prevent validation errors on hidden required fields
       $('form').on('submit', function(e) {
           // Remove required attribute from hidden payment method selects
           $('select[name^="payment_method_"]').each(function() {
               if ($(this).is(':hidden') || $(this).closest('tr').is(':hidden') || $(this).closest('div').is(':hidden')) {
                   $(this).removeAttr('required');
               }
           });
           
           // Also handle selects that are in hidden table rows
           $('select[name^="payment_method_"]').each(function() {
               var $select = $(this);
               var $row = $select.closest('tr');
               if ($row.length && $row.is(':hidden')) {
                   $select.removeAttr('required');
               }
           });
       });
       
       // When showing/hiding table rows, manage required attributes
       $(document).on('change', '.statuss', function() {
           var $row = $(this).closest('tr');
           var $paymentSelect = $row.find('select[name^="payment_method_"]');
           
           if ($row.is(':visible')) {
               $paymentSelect.attr('required', 'required');
           } else {
               $paymentSelect.removeAttr('required');
           }
       });
   });
</script>