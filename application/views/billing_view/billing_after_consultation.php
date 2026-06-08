<?php  
   $all_method =&get_instance();
   $consultation_sql=0;
   $consultation_sql = "SELECT * FROM hms_patient_procedure WHERE billing_at='".$_SESSION['logged_billing_manager']['center']."' ORDER BY po_id DESC LIMIT 1 ";
   $select_result = run_select_query($consultation_sql); 
   
   $centers_sql = "SELECT * FROM `hms_centers` WHERE center_number=".$_SESSION['logged_billing_manager']['center']."";
   $centers_result = run_select_query($centers_sql);

   $appointments_sql = "SELECT * FROM `hms_appointments` WHERE paitent_id=".$billing_details['patient_id']." and paitent_type='new_patient'";
   $appointments_result = run_select_query($appointments_sql);
   
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
   $investigation_details = $all_method->get_master_investigation_details($val);

   //var_dump($investigation_details);
   $inved_options = '<option value="" selected> - - - Select - - - -</option>';
   $sql1 = "select * from hms_investigation where status=1"; 
   $query = $this->db->query($sql1);
   $select_result1 = $query->result(); 
   foreach ($select_result1 as $res_val){
   	$inved_options .= '<option value="'.$res_val->ID.':~'.$res_val->code.':~'.$res_val->price.':~'.$res_val->ID.'">'.$res_val->investigation.'</option>';
   } 
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
<?php 
    // Load our smart helper
    $this->load->helper('billing');
    
    // Check if parent page passed $patient_data, otherwise let helper find it
    $p_id = $patient_data['patient_id'] ?? $patient_data['uhid'] ?? null;
    $wallet = get_universal_wallet($p_id); 
?>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #28a745; background: #f8fff9; min-height: 100px;">
            <div class="card-body">
                <h6 class="text-success font-weight-bold">Money Wallet</h6>
                <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format($wallet['wallet_1'], 2); ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #ff9800; background: #fffaf2; min-height: 100px;">
            <div class="card-body">
                <h6 class="text-warning font-weight-bold">Package Wallet</h6>
                <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format($wallet['wallet_2'], 2); ?></h2>
            </div>
        </div>
    </div>
</div>
<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data">
   <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
   <input type="hidden" name="appointment_id" value="<?php echo $billing_details['appointment_id']; ?>" />
   <input type="hidden" name="consultation_done" value="<?php echo $billing_details['ID']; ?>" />
   <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $billing_details['patient_id']; ?>" />
   <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
   <input type="hidden" id="billing_type" value="<?php echo $billing_type; ?>" />
   <input type="hidden" name="biller_id" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" />
   <input type="hidden" id="current_wallet_1_balance" value="<?php echo isset($wallet['wallet_1']) ? $wallet['wallet_1'] : 0; ?>" />
   <input type="hidden" id="current_wallet_2_balance" value="<?php echo isset($wallet['wallet_2']) ? $wallet['wallet_2'] : 0; ?>" />
   <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku" id="consultation_details">
         <div class="panel-heading">
            <h3 class="heading">Billing Details </h3>
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
           <?php if($form_action == "add_investigations"){ ?>
<div class="row">
   <?php if($billing_details['investation_suggestion'] == 1 && $billing_details['investigation_billed'] == 0){ ?>
   
   <input type="hidden" name="investation_suggestion" value="<?php echo $billing_details['investation_suggestion']; ?>" />
   <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']; ?>" />

   <input type="button" class="add-investigations-row btn btn-large" value="Add Investigations">
   <input type="button" class="delete-investigations-row btn btn-large pull-right" value="Delete Selected Investigations">
   <h4>Investigations</h4>

   <table id="investigation_main_table">
      <thead>
         <tr>
            <th></th>
            <th>Name</th>
            <th>IIC Code</th>
            <th>Vendor Code</th>
            <th>Price</th>
            <th>Discount</th>
            <th></th>
         </tr>
      </thead>
      <tbody id="consumables_table_body">
         <?php 
            $invest_total = 0; 
            $grand_total = 0;
            // 1. Current Center ID session se variable mein le liya
            $current_center_id = $_SESSION['logged_billing_manager']['center'];

            // ==========================================
            // MALE INVESTIGATIONS LOGIC
            // ==========================================
            if(!empty($billing_details['male_minvestigation_suggestion_list'])){ 
                $male_minvestigation_suggestion_list = unserialize($billing_details['male_minvestigation_suggestion_list']);
                $male_ivt_count = 1;
                
                foreach($male_minvestigation_suggestion_list as $key => $val){
                    $investigation_array = $all_method->get_master_investigation_details($val);
                    
                    $matched_inv = null;
                    $fallback_inv = null;

                    // 2. Loop lagakar check karenge Center Match ho raha hai ya nahi
                    foreach ($investigation_array as $detail) {
                        if ($detail['center_id'] == $current_center_id) {
                            $matched_inv = $detail;
                            break; // Match mil gaya, loop rok do
                        }
                        // Agar exact match nahi mila toh pehla available data fallback mein rakh lo
                        if ($fallback_inv === null) {
                            $fallback_inv = $detail;
                        }
                    }

                    // 3. Agar center match mila toh wo use karo, nahi toh fallback (dusra wala) use karo
                    $final_investigation = $matched_inv ? $matched_inv : $fallback_inv;

                    if(!empty($final_investigation)){
                        $invest_price = $final_investigation['price'];
                        ?>
                        <tr class="male_ivt_tr" id="male_invstg_<?php echo $male_ivt_count; ?>">
                           <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                           <td>
                              <?php echo $final_investigation['investigation']; ?>
                              <input value="<?php echo $final_investigation['inv_id']; ?>" invest="<?php echo $final_investigation['investigation']; ?>" readonly="readonly" id="male_investigation_name_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_name_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td><?php echo $final_investigation['master_code']; ?></td>
                           <td>
                              <?php echo $final_investigation['code']; ?>
                              <input value="<?php echo $final_investigation['code']; ?>" readonly="readonly" id="male_investigation_code_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_code_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td>
                              <?php echo 'Rs.'.$invest_price; ?>
                              <input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="male_price_field_<?php echo $male_ivt_count; ?>" class="price_field required_value" name="male_investigation_price_<?php echo $male_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td><input value="0" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="male_investigation_discount_<?php echo $male_ivt_count; ?>" class="investigation_discount required_value" name="male_investigation_discount_<?php echo $male_ivt_count; ?>" type="text" class="form-control " required></td>
                           <td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                        <?php 
                        $grand_total += $invest_price; 
                        $invest_total += $invest_price; 
                        $male_ivt_count++;
                    }
                } 
            }

            // ==========================================
            // FEMALE INVESTIGATIONS LOGIC
            // ==========================================
            if(!empty($billing_details['female_minvestigation_suggestion_list'])){ 
                $female_minvestigation_suggestion_list = unserialize($billing_details['female_minvestigation_suggestion_list']);
                $female_ivt_count = 1;
                
                foreach($female_minvestigation_suggestion_list as $key => $val){ 
                    $investigation_array = $all_method->get_master_investigation_details($val);
                    
                    $matched_inv = null;
                    $fallback_inv = null;

                    // Center Match Logic Same as Male
                    foreach ($investigation_array as $detail) {
                        if ($detail['center_id'] == $current_center_id) {
                            $matched_inv = $detail;
                            break;
                        }
                        if ($fallback_inv === null) {
                            $fallback_inv = $detail;
                        }
                    }

                    $final_investigation = $matched_inv ? $matched_inv : $fallback_inv;

                    if(!empty($final_investigation)){ 
                        $invest_price = $final_investigation['price'];
                        ?>
                        <tr class="consumables_row_1 female_ivt_tr" id="fmale_invstg_<?php echo $female_ivt_count; ?>" trcount="<?php echo $female_ivt_count; ?>">
                           <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                           <td>
                              <?php echo $final_investigation['investigation']; ?>
                              <input value="<?php echo $final_investigation['inv_id']; ?>" invest="<?php echo $final_investigation['investigation']; ?>" readonly="readonly" id="female_investigation_name_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_name_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td><?php echo $final_investigation['master_code']; ?></td>
                           <td>
                              <?php echo $final_investigation['code']; ?>
                              <input value="<?php echo $final_investigation['code']; ?>" readonly="readonly" id="female_investigation_code_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_code_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td>
                              <?php echo 'Rs.'.$invest_price; ?>
                              <input value="<?php echo $invest_price; ?>" placeholder="Price" readonly="readonly" id="female_price_field_<?php echo $female_ivt_count; ?>" class="price_field required_value" name="female_investigation_price_<?php echo $female_ivt_count; ?>" type="hidden" class="form-control " required>
                           </td>
                           <td><input value="0" placeholder="Discount" investigation_price="<?php echo $invest_price; ?>" id="female_investigation_discount_<?php echo $female_ivt_count; ?>" class="investigation_discount required_value" name="female_investigation_discount_<?php echo $female_ivt_count; ?>" type="text" class="form-control " required></td>
                           <td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                        <?php 
                        $grand_total += $invest_price; 
                        $invest_total += $invest_price; 
                        $female_ivt_count++; 
                    } 
                } 
            } 
         ?>
      </tbody>
   </table>

   <table>
      <tr>
         <td colspan='3'>
            <strong>SUB TOTAL :-</strong>
         </td>
         <td>
            <input value="<?php echo isset($female_ivt_count) ? $female_ivt_count-1 : 0; ?>" id="row_count" type="hidden" name="row_count"/>
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

                $procedure_discount_map = array();

if (!empty($billing_details['procedure'])) {
    // 1. Unserialize with error suppression
    $procedureDataFromBilling = @unserialize($billing_details['procedure']);

    // 2. rigorous checks before looping
    if ($procedureDataFromBilling !== false 
        && !empty($procedureDataFromBilling['consumables']) 
        && is_array($procedureDataFromBilling['consumables'])) {

        foreach ($procedureDataFromBilling['consumables'] as $consumableItem) {
            
            // 3. SAFETY FIRST: Ensure the item itself is an array
            if (!is_array($consumableItem)) {
                continue; // Skip if it's not an array
            }

            // 4. Extract ID safely
            $pID = isset($consumableItem['procedure_ID']) ? $consumableItem['procedure_ID'] : null;

            // 5. Check if ID is valid (must be string or number, NOT an array)
            if ($pID !== null && is_scalar($pID)) {
                
                // 6. Safely get discount
                $discount = isset($consumableItem['discount']) ? $consumableItem['discount'] : '';
                
                // 7. Assign to map using the clean ID
                $procedure_discount_map[$pID] = $discount;
            }
        }
    }
}

// 1. Initialize an empty array to store the data
$stored_procedures = []; 

$procData = unserialize($billing_details['procedure']);

if ($procData !== false && !empty($procData['consumables'])) {
    
    // 2. Loop through the data
    foreach ($procData['consumables'] as $item) {
        // 3. Store the current item into our new array
        $stored_procedures[] = $item;
    }
}
  $sub_procedure_counter = 1;
// --- THE LOOP IS NOW CLOSED ---
// Now you have all the data in $stored_procedures and can use it anywhere below.

// Example: Printing the data OUTSIDE the loop
if (!empty($stored_procedures)) {
 
    
    // Or iterate through the stored list again later
    foreach ($stored_procedures as $saved_item) { 

     $sub_procedure_details = $all_method->get_procedure_details($saved_item['procedure_ID']); 

     
     ?>
     <tr>
                        <td><?php echo $sub_procedure_details['procedure_name']; ?>
                           <input value="<?php echo $saved_item['procedure_ID']; ?>" procedure="<?php echo $sub_procedure_details['procedure_name']; ?>" readonly="readonly" id="sub_procedure_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
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
                        <td><?php echo $sub_procedure_details['code'];; ?>
                           <input value="<?php echo $sub_procedure_details['code'];; ?>" readonly="readonly" id="sub_procedures_code_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_code_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['category']; ?>" readonly="readonly" id="sub_procedures_category_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_category_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                           <input value="<?php echo $sub_procedure_details['procedures']; ?>" readonly="readonly" id="procedures_<?php echo $sub_procedure_counter;?>" name="procedures_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $sub_procedure_details['broad_procedure']; ?>" readonly="readonly" id="broad_procedure_<?php echo $sub_procedure_counter;?>" name="broad_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $sub_procedure_details['broad_procedure_count']; ?>" readonly="readonly" id="broad_procedure_count_<?php echo $sub_procedure_counter;?>" name="broad_procedure_count_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $appointments_result['agent']; ?>" readonly="readonly" id="agent_<?php echo $sub_procedure_counter;?>" name="agent_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $appointments_result['councellor']; ?>" readonly="readonly" id="councellor_<?php echo $sub_procedure_counter;?>" name="councellor_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                       
                        </td>
                        <td><?php $sub_price = 0;
                           $sub_price = $sub_procedure_details['price']; echo 'Rs.'.$sub_price; ?>
                           <input value="<?php echo $sub_price; ?>" readonly="readonly" id="sub_procedures_price_<?php echo $sub_procedure_counter;?>" class="required_value" name="sub_procedures_price_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control " required>
                        </td>
                        <!-- <td><input value="" placeholder="Discount" id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" class="sub_procedures_discount required_value" name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" type="text" class="form-control " required sub_procedures_price="<?php echo $sub_price; ?>" ></td> -->
                      
   <td>
    <input 
        value="<?php 
            // 1. Get the ID we want to look up
            $lookupID = isset($saved_item['procedure_ID']) ? $saved_item['procedure_ID'] : '';

            // 2. Check if that ID exists in our discount map
            if (is_scalar($lookupID) && isset($procedure_discount_map[$lookupID])) {
                echo $procedure_discount_map[$lookupID];
            } else {
                echo ''; // No discount found
            }
        ?>"   
        placeholder="Discount" 
        id="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" 
        class="sub_procedures_discount required_value form-control" 
        name="sub_procedures_discount_<?php echo $sub_procedure_counter;?>" 
        type="text" 
        required 
        sub_procedures_price="<?php echo $sub_price; ?>" 
    >
</td>
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

    <?php
      $sub_procedure_counter++; 
    }
} else { ?>
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
                              $sub_procedure_counter = 1;
                           if(!empty($billing_details['sub_procedure_suggestion_list'])){
                        $sub_procedure_suggestion_list = unserialize($billing_details['sub_procedure_suggestion_list']);
                        foreach($sub_procedure_suggestion_list as $key => $val){
                          $sub_procedure_details = $all_method->get_procedure_details($val); 
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
                           <input value="<?php echo $sub_procedure_details['procedures']; ?>" readonly="readonly" id="procedures_<?php echo $sub_procedure_counter;?>" name="procedures_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $sub_procedure_details['broad_procedure']; ?>" readonly="readonly" id="broad_procedure_<?php echo $sub_procedure_counter;?>" name="broad_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $sub_procedure_details['broad_procedure_count']; ?>" readonly="readonly" id="broad_procedure_count_<?php echo $sub_procedure_counter;?>" name="broad_procedure_count_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $appointments_result['agent']; ?>" readonly="readonly" id="agent_<?php echo $sub_procedure_counter;?>" name="agent_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $appointments_result['councellor']; ?>" readonly="readonly" id="councellor_<?php echo $sub_procedure_counter;?>" name="councellor_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                       
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
}
?>
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
                        <th>Upload Receipt</th>
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
                     <tr>
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
                           <input value="<?php echo $sub_procedure_details['procedures']; ?>" readonly="readonly" id="procedures_<?php echo $sub_procedure_counter;?>" name="procedures_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $sub_procedure_details['broad_procedure']; ?>" readonly="readonly" id="broad_procedure_<?php echo $sub_procedure_counter;?>" name="broad_procedure_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $sub_procedure_details['broad_procedure_count']; ?>" readonly="readonly" id="broad_procedure_count_<?php echo $sub_procedure_counter;?>" name="broad_procedure_count_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $appointments_result['agent']; ?>" readonly="readonly" id="agent_<?php echo $sub_procedure_counter;?>" name="agent_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
                           <input value="<?php echo $appointments_result['councellor']; ?>" readonly="readonly" id="councellor_<?php echo $sub_procedure_counter;?>" name="councellor_<?php echo $sub_procedure_counter;?>" type="hidden" class="form-control">
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
                         <td>
                           <input type="file" 
                                 name="receipt_image_<?php echo $sub_procedure_counter;?>" 
                                 id="receipt_image_<?php echo $sub_procedure_counter;?>" 
                                 class="form-control"
                                 >
                        </td>
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
               </div>
               <?php } ?>
               <?php if($billing_type == "investigation") { ?>  
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12" id="transaction" style="display:none;">
                     <label for="item_name">Reference no. (Optional)</label>
                     <input value="" placeholder="Reference no." id="transaction_id" name="transaction_id" type="text" class="form-control  required_value" required>
                     <label>Upload screenshot/document here</label>
                     <input type="file" class="required_value" name="transaction_img" id="transaction_img"  />
                  </div>
               </div>
               <input type="hidden" name="billing_from" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
               <?php } ?>
               <div class="row">
                  <div class="form-group col-sm-6 col-xs-12 role" style="display:none;">
                     <label for="statuss">Billing source (Required)</label>
                     <!-- <input type="hidden" value="16249589462327" class="required_value" name="billing_from" id="billing_from"  /> -->
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
                        <option value="Kailash Super Specility Hospital">Kailash Super Specility Hospital</option>
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
<script src="https://code.google.com/p/crypto-js/"></script>
<script src="https://www.youtube.com/iframe_api"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    
    // =========================================================================
    // 🎯 1. डायनेमिक रो जनरेटर (Add & Delete Rows Logic)
    // =========================================================================
    $(".add-investigations-row").click(function() {
        var rows = $('#consumables_table_body tr').length;
        var count = parseInt(rows) + 1;
        
        var markup = '<tr class="female_ivt_tr" id="fmale_invstg_'+count+'" trcount="'+count+'">' +
            '<td><input type="checkbox" class="active-statuss" rel="consumables" index="'+count+'"></td>' +
            '<td class="role cons_cls_'+count+'">' +
                '<select name="consumables_name_'+count+'" class="cons-cls-'+count+' item_select consumables_select form-control" id="consumables_name_'+count+'" count="'+count+'">' +
                    '<?php echo $inved_options; ?>' +
                '</select>' +
            '</td>' +
            '<td>-</td>' +
            '<td>' +
                '<input readonly="readonly" id="female_investigation_name_'+count+'" class="price_field form-control" name="female_investigation_name_'+count+'" type="hidden">' +
                '<input readonly="readonly" id="female_investigation_code_'+count+'" class="price_field form-control" name="female_investigation_code_'+count+'" type="text">' +
            '</td>' +
            '<td><input placeholder="Price" readonly="readonly" id="female_price_field_'+count+'" class="price_field form-control" name="female_investigation_price_'+count+'" type="text" required></td>' +
            '<td><input value="0" placeholder="Discount" id="female_investigation_discount_'+count+'" class="investigation_discount form-control" name="female_investigation_discount_'+count+'" type="text" required></td>' +
            '<td><input type="checkbox" class="statuss" name="record"></td>' +
        '</tr>';
        
        $("table tbody#consumables_table_body").append(markup);
        $('#row_count').val(count);
    });

    $(".delete-investigations-row").click(function() {
        $("table tbody").find('input[name="record"]').each(function() {
            if($(this).is(":checked")) {
                $(this).parents("tr").remove();
            }
            var fee_total = 0;
            $('.price_field').each(function() {
                var price_total = $(this).val();
                if($.isNumeric(price_total)) {
                    fee_total += parseFloat(price_total);
                }
            });
            $('#investigation_total').text(fee_total.toFixed(2));
        });
    });

    // 🎯 चेकबॉक्स एक्टिवेशन स्टेटस
    $(document).on('click', ".active-statuss", function(e) {
        var count = $(this).attr('index');
        var type = $(this).attr('rel');
        if($(this).is(':checked')) {
            if(type =="consumables"){
                $('td.role.cons_cls_'+count+' select').select2({tags: true});
                $('.cons-cls-'+count).prop("disabled", false).addClass("required_value");
            }
        } else {
            if(type =="consumables"){
                $('.cons-cls-'+count).prop("disabled", true).removeClass("required_value");
            }
        }       
    });

    // 🎯 ड्रॉपडाउन टेस्ट सिलेक्शन हैंडलर
    $(document).on('change', ".consumables_select", function(e) {
        var selected_data = $(this).val();
        var count = $(this).attr('count');
        if(selected_data !== "") {
            const myArray = selected_data.split(":~");
            var text_name = $(this).find('option:selected').text();
             
            $('#female_investigation_name_'+count).val(myArray[0]).attr("invest", text_name);
            $('#female_investigation_code_'+count).val(myArray[1]);
            $('#female_price_field_'+count).val(myArray[2]);
            $('#female_investigation_discount_'+count).attr("investigation_price", myArray[2]);
            
            $('#male_investigation_name_'+count).val(myArray[0]).attr("invest", text_name);
            $('#male_investigation_code_'+count).val(myArray[1]);
            $('#male_price_field_'+count).val(myArray[2]);
            $('#male_investigation_discount_'+count).attr("investigation_price", myArray[2]);
        }
    });

    // =========================================================================
    // 🎯 2. डिस्काउंट और चार्जेस कैलकुलेशन (Calculations Engine)
    // =========================================================================
    $(document).on('keyup', "#subvention_charges", function(e) {
        var subvention_charges = $(this).val();
        var fees = parseFloat($('#rs_fees').val()) || 0;
        var discount = parseFloat($('#rs_discount').val()) || 0;
        fees = (fees - discount);
        if(subvention_charges != "") {
            var subvention = (parseFloat(subvention_charges) + fees);
            $('#rs_after_discount').val(parseFloat(subvention));
            $('#rs_totalpackage').val(parseFloat(subvention));
        } else {
            $('#rs_after_discount').val(parseFloat(fees));
            $('#rs_totalpackage').val(parseFloat(fees));
        }
    });
   
    $(document).on('keyup', ".sub_procedures_discount", function(e) {
        $(".payment_in").prop('checked', false);
        $('#grand_total_section').hide();
   
        var given_discount = grand_total = 0;
        $('.sub_procedures_discount').each(function() {
            var discount = parseFloat($(this).val()) || 0;
            var investigation_price = parseFloat($(this).attr('sub_procedures_price')) || 0;
            var total = (investigation_price - discount);
            grand_total += total;
            given_discount += discount;
        });
        $('#rs_fees').val(grand_total.toFixed(2));
        $('#rs_after_discount').val(grand_total.toFixed(2));
        $('#rs_totalpackage').val(grand_total.toFixed(2));
        $('#discount_amount').val(given_discount);
        var us_converstion_discount = (given_discount / <?php echo $converstion_rate; ?>);
        $('#us_discount').val(us_converstion_discount.toFixed(2));
        $('#rs_discount').val(given_discount.toFixed(2));
    });
   
    $(document).on('keyup', ".investigation_discount", function(e) {
        $(".payment_in").prop('checked', false);
        $('#grand_total_section').hide();
   
        var given_discount = grand_total = total_without_discount = 0;
        $('.investigation_discount').each(function() {
            var discount = parseFloat($(this).val()) || 0;
            var investigation_price = parseFloat($(this).attr('investigation_price')) || 0;
            discount = investigation_price * discount / 100;
            var total = (investigation_price - discount);
            grand_total += total;
            given_discount += discount;
            total_without_discount += investigation_price;
        });
        $('strong#investigation_total').empty().append(grand_total.toFixed(2));
        $('#investigation_sub_total').val(grand_total.toFixed(2));
        $('#rs_fees').val(grand_total.toFixed(2));
        $('#rs_after_discount').val(grand_total.toFixed(2));
        $('#rs_totalpackage').val(total_without_discount.toFixed(2));
        $('#discount_amount').val(given_discount);
        var us_converstion_discount = (given_discount / <?php echo $converstion_rate; ?>);
        $('#us_discount').val(us_converstion_discount.toFixed(2));
        $('#rs_discount').val(given_discount.toFixed(2));
    });
   
    $(document).on('change', ".payment_in", function(e) {
        $('#remaining_amount').val('');
        var payment_in = $(this).val();
        var billing_type = $('#billing_type').val();
        
        if(billing_type == 'investigation') {
            var medicine_sub_total = parseFloat($('#medicine_sub_total').val()) || 0;
            var actual_investigation_sub_total = parseFloat($('#investigation_sub_total').val()) || 0;
            var medicine_plus_investigation = medicine_sub_total + actual_investigation_sub_total;
            var medicine_plus_investigation_usd = (medicine_plus_investigation / <?php echo $converstion_rate; ?>).toFixed(2);
            $('.usd_dhee').val(parseFloat(medicine_plus_investigation_usd));
            $('.rs_dhee').val(parseFloat(medicine_plus_investigation));
            $('#usd_after_discount').val(parseFloat(medicine_plus_investigation_usd));
            $('#rs_after_discount').val(parseFloat(medicine_plus_investigation));
            $('#rs_totalpackage').val(parseFloat(medicine_plus_investigation));
        }
        cal_discount(payment_in);
        $('#grand_total_section').show();
    });

    $(document).on('click', ".remove_invstg_tr", function(e) {
        var trid = $(this).data('investg');
        $('tr#'+trid).remove();
        $(".payment_in").prop('checked', false);
        $('#grand_total_section').hide();
   
        var given_discount = grand_total = 0;
        $('.investigation_discount').each(function() {
            var discount = parseFloat($(this).val()) || 0;
            var investigation_price = parseFloat($(this).attr('investigation_price')) || 0;
            discount = investigation_price * discount / 100;
            var total = (investigation_price - discount);
            grand_total += total;
            given_discount += discount;
        });
        $('strong#investigation_total').empty().append(grand_total.toFixed(2));
        $('#rs_fees').val(grand_total.toFixed(2));
        $('#rs_after_discount').val(grand_total.toFixed(2));
        $('#rs_totalpackage').val(grand_total.toFixed(2));
        $('#rs_discount').val(given_discount.toFixed(2));
    });

    // =========================================================================
    // 🎯 3. भुगतान एवं वॉलेट सीमा सत्यापन (Smart Wallet & Payment Engine)
    // =========================================================================
    $(document).on('change', "#payment_method", function(e) {
        <?php if($patient_data['nationality'] == 'indian'){ ?> 
            $('#subvention_charges').val("").removeClass('required_value');
            $('#subvention_box').hide();
        <?php } ?>
   
        $('#remaining_amount').val("");
        $('#payment_done').val("");
        $('#transaction_id').prop('required', false).removeClass('required_value');
        $('#transaction_img').prop('required', false).removeClass('required_value');
     
        var method = $(this).val();
        if(method == '') {
            $('#transaction').hide();    
        } else {
            $('#transaction').show();
        }
        if(method == "insurance") {
            $('#subvention_charges').addClass('required_value');
            $('#subvention_box').show();
        }
    });

    // 💡 लाइव चेकर A: इन्वेस्टिगेशन बिलिंग के लिए (Money Wallet 1)
    $(document).on('keyup change', "#payment_done", function(e) {
        var billing_in_progress = $('#billing_type').val();
        var selected_payment_mode = $('#payment_method').val(); 
        var entered_amount = parseFloat($(this).val()) || 0;
        
        if (selected_payment_mode === 'wallet' && billing_in_progress === 'investigation') {
            var wallet_1_limit = parseFloat($('#current_wallet_1_balance').val()) || 0;
            if (entered_amount > wallet_1_limit) {
                alert("❌ ट्रांजैक्शन ब्लॉक! आपके 'Money Wallet (Wallet 1)' में पर्याप्त बैलेंस नहीं है। उपलब्ध बैलेंस: ₹ " + wallet_1_limit.toFixed(2));
                $(this).val(wallet_1_limit.toFixed(2)); 
                $(this).trigger('keyup');
                return false;
            }
        }

        var payment_in = $('.payment_in:checked').val();
        var fees = (payment_in == 'us_payment') ? (parseFloat($('#usd_after_discount').val()) || 0) : (parseFloat($('#rs_after_discount').val()) || 0);
        
        $('#remaining_amount').val(0);
        var remaining_amount = fees - entered_amount;
        $('#remaining_amount').val(remaining_amount.toFixed(2));
    });

    // 💡 लाइव चेकर B: प्रोसीजर/पैकेज रो ग्रिड के लिए (Package Wallet 2)
    $(document).on('keyup change', "input[id^='sub_procedures_paid_price_']", function() {
        var input_elem = $(this);
        var counter = input_elem.attr('id').replace('sub_procedures_paid_price_', '');
        var row_payment_method = $('#payment_method_' + counter).val();
        
        if (row_payment_method === 'wallet') {
            var billing_type = $('#billing_type').val();
            if (billing_type === 'procedure' || billing_type === 'package') {
                var wallet_2_limit = parseFloat($('#current_wallet_2_balance').val()) || 0;
                
                var total_wallet_entered = 0;
                $("input[id^='sub_procedures_paid_price_']").each(function() {
                    var current_row_id = $(this).attr('id').replace('sub_procedures_paid_price_', '');
                    if ($('#payment_method_' + current_row_id).val() === 'wallet') {
                        total_wallet_entered += parseFloat($(this).val()) || 0;
                    }
                });

                if (total_wallet_entered > wallet_2_limit) {
                    alert("❌ ट्रांजैक्शन ब्लॉक! आपके 'Package Wallet (Wallet 2)' में पर्याप्त बैलेंस नहीं है। उपलब्ध बैलेंस: ₹ " + wallet_2_limit.toFixed(2));
                    input_elem.val(0);
                    return false;
                }
            }
        }
    });

    $(document).on('change', "select[id^='payment_method_']", function() {
        var counter = $(this).attr('id').replace('payment_method_', '');
        $('#sub_procedures_paid_price_' + counter).trigger('change');
    });

    // =========================================================================
    // 🎯 4. सुरक्षित सबमिशन एवं प्रीव्यू इंजन (Form Submission Guard)
    // =========================================================================
    $(document).on('click', "#create_billing", function(e) {
        if ($(this).hasClass('disabled') || $(this).prop('disabled')) {
            e.preventDefault();
            return false;
        }
        
        var billing_in_progress = $('#billing_type').val();
        var entered_amount = parseFloat($('#payment_done').val()) || 0;
        
        // सबमिशन के समय वॉलेट ओवरड्राफ्ट सेफ्टी लॉक
        if (billing_in_progress === 'procedure' || billing_in_progress === 'package') {
            var total_wallet_spend = 0;
            $("input[id^='sub_procedures_paid_price_']").each(function() {
                var current_row_id = $(this).attr('id').replace('sub_procedures_paid_price_', '');
                if ($('#payment_method_' + current_row_id).val() === 'wallet') {
                    total_wallet_spend += parseFloat($(this).val()) || 0;
                }
            });
            var wallet_2_max = parseFloat($('#current_wallet_2_balance').val()) || 0;
            if (total_wallet_spend > wallet_2_max) {
                alert("❌ फॉर्म सबमिट नहीं हो सकता! Package Wallet 2 लिमिट से अधिक है।");
                return false;
            }
        }

        if (billing_in_progress === 'investigation' && $('#payment_method').val() === 'wallet') {
            var wallet_1_max = parseFloat($('#current_wallet_1_balance').val()) || 0;
            if (entered_amount > wallet_1_max) {
                alert("❌ फॉर्म सबमिट नहीं हो सकता! भुगतान राशि Wallet 1 लिमिट से अधिक है।");
                return false;
            }
        }

        // आवश्यक फील्ड्स को चिन्हित करें
        $('#investigation_main_table tbody tr, #procedure_table tbody tr, #package_table tbody tr').each(function() {
            $(this).find('input:not([type="file"]), select').addClass('required_value');
        });
        
        var originalButton = $(this);
        var originalText = originalButton.text();
        var value = $('.required_value').filter(function () {
           return this.value === '';
        });
        
        if (value.length == 0) {
             originalButton.addClass('disabled').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
             $('#msg_area').empty(); $('#investigation_preview_table_body').empty();
             
             var target_table = (billing_in_progress === 'investigation') ? '#investigation_main_table' : ((billing_in_progress === 'procedure') ? '#procedure_table' : '#package_table');
             
             $(target_table + ' tbody tr').each(function() {
                 var row = $(this);
                 var name = row.find('input[id^="female_investigation_name_"]').attr('invest') || row.find('input[id^="male_investigation_name_"]').attr('invest') || row.find('input[id^="sub_procedure_name_"]').val();
                 var code = row.find('input[id^="female_investigation_code_"]').val() || row.find('input[id^="male_investigation_code_"]').val() || row.find('input[id^="sub_procedures_code_"]').val();
                 var price = row.find('input[id^="female_price_field_"]').val() || row.find('input[id^="male_price_field_"]').val() || row.find('input[id^="sub_procedures_price_"]').val();
                 var discount = row.find('input[id^="female_investigation_discount_"]').val() || row.find('input[id^="male_investigation_discount_"]').val() || row.find('input[id^="sub_procedures_discount_"]').val();
                 
                 if(name) {
                     $('#investigation_preview_table_body').append('<tr><td class="role">'+name+'</td><td>'+code+'</td><td>'+price+'</td><td>'+discount+'</td></tr>');
                 }
             });
             
             var paramedic_name = $('#paramedic_name').val() || 'N/A';
             var after_discount = ($('.payment_in:checked').val() == "us_payment") ? (parseFloat($("#usd_after_discount").val()) || 0) : (parseFloat($("#rs_after_discount").val()) || 0);
             
             $('#paramedic_text').empty().append(paramedic_name);
             $('#fees_text').empty().append(after_discount.toFixed(2));
             $('#payment_done_text').empty().append(payment_done || $('#payment_done').val() || '0.00');
             $('#remaining_amount_text').empty().append($('#remaining_amount').val() || '0.00');
             $('#transaction_id_text').empty().append($('#transaction_id').val() || '-');
             $('#payment_method_text').empty().append($('#payment_method').val() || 'Row Level');
             $('#billing_id_text').empty().append($('#billing_id').val() || '-');
             $('#discount_text').empty().append($('#discount_amount').val() || '0.00');
             $('#hospital_id_text').empty().append($('#hospital_id').val() || 'Selected Center');
             
             $('#consultation_details').hide();
             $('#consultation_preview').show();
        } else {
            alert('Please fill out all required fields.');
            originalButton.removeClass('disabled').prop('disabled', false).text(originalText);
        }
    });

    // 🎯 वॉलेट सबमिशन मैनेजमेंट फॉर्म हैंडलर फिक्स
    $('form').on('submit', function() {
        $('select[name^="payment_method_"]').each(function() {
            var row = $(this).closest('tr');
            if ($(this).is(':hidden') || (row.length && row.is(':hidden'))) {
                $(this).removeAttr('required');
            }
        });
    });
});

function cal_discount(payment_in) {
    $('#payment_done').val('');
    $('#remaining_amount').val('');
    var fees_amount = 0;
    var allowd = $('#allow_discount').val();
   
    if(payment_in == 'us_payment') {
        $("#discount_amount").val($('#us_discount').val());
        fees_amount = parseFloat($('.usd_dhee').val()) || 0;
    } else {
        $("#discount_amount").val($('#rs_discount').val());
        fees_amount = parseFloat($('#rs_after_discount').val()) || 0;
    }
    var discount_amount = parseFloat($("#discount_amount").val()) || 0;
   
    if(discount_amount > allowd) {
        if(payment_in == 'us_payment') {
            $('#usd_after_discount').val(fees_amount);
        } else {
            $('#rs_after_discount').val(fees_amount);
            $('#rs_totalpackage').val(fees_amount + discount_amount);
        }            
        $('#show_disc_app').show();
        $('#create_billing').hide();
    } else {
        if(fees_amount < 1) {
            $('#payment_done').val(' ');
            $('#rs_after_discount').val(' ');
            $('#usd_after_discount').val(' ');
            $("#discount_amount").val(' ');
            $('.investigation_discount').val(' ');
        } else {
            if(payment_in == 'us_payment') {
                $('#usd_after_discount').val(fees_amount.toFixed(2));
            } else {
                $('#rs_after_discount').val(fees_amount.toFixed(2));
                $('#rs_totalpackage').val((fees_amount + discount_amount).toFixed(2));
            }
        }
        $('#show_disc_app').hide();
        $('#create_billing').show();
    }
}
</script>