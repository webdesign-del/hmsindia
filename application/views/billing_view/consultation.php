<?php $all_method =&get_instance();
  $patient_id = $appointments['paitent_id'];
 if($patient_id == 0){
    $patient_id = getiic();
  }
  $sql1 = "SELECT * FROM hms_appointments WHERE uhid = ( SELECT MAX(uhid) FROM hms_appointments)"; 
    $query = $this->db->query($sql1);
        $select_result1 = $query->result(); 

  // Check if patient already had a consultation
  $check_prev_consultation = $this->db->get_where('hms_consultation', array('patient_id' => $patient_id))->num_rows();
  
  // Check if patient has any procedure billing
  $check_procedure = $this->db->get_where('hms_patient_procedure', array('patient_id' => $patient_id))->num_rows();

    // Flags for JS (Naming Match)
  $is_first_visit = ($check_prev_consultation == 0) ? 'true' : 'false';
  $procedure_exists = ($check_procedure > 0) ? 'true' : 'false';
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="page-header" style="border-bottom: 2px solid #337ab7; margin-bottom: 30px;">
        <h2 style="color: #337ab7; margin: 0; padding-bottom: 10px;">
          <i class="fa fa-stethoscope" style="margin-right: 10px;"></i>
          Consultation Billing
        </h2>
        <p class="text-muted">Create and manage consultation billing records</p>
      </div>
    </div>
  </div>
<?php 
    $this->load->helper('billing');
    $p_id = $patient_id; 
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
  <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add_consultation" />
    <input type="hidden" name="appointment_id" value="<?php echo $appointments['ID']; ?>" />
    <input type="hidden" id="available_wallet_balance" value="<?php echo isset($wallet['wallet_1']) ? $wallet['wallet_1'] : 0; ?>" />
    <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
    <input type="hidden" id="billing_type" value="consultation" />
    <input type="hidden" id="patient_id" name="patient_id" value="<?php echo $patient_id;?>" />
    <input type="hidden" name="biller_id" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" />
    
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-primary" id="consultation_details">
          <div class="panel-heading" style="background: linear-gradient(135deg, #337ab7, #2e6da4); border: none;">
            <h3 class="panel-title" style="color: white; font-weight: 600;">
              <i class="fa fa-file-text-o" style="margin-right: 8px;"></i>
              Consultation Details
            </h3>
          </div>
          <div class="panel-body" style="padding: 30px; background-color: #fafafa;">
            <div id="msg_area" class="alert alert-danger" style="display: none;"></div>

            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-user" style="margin-right: 8px; color: #3498db;"></i>
                  Patient Information
                </h4>
              </div>
            </div>

            <div class="row">            
              <div class="form-group col-sm-6 col-xs-12">
                <label for="iic" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-id-card-o" style="margin-right: 5px; color: #3498db;"></i>
                  IIC ID <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-key"></i>
                  </span>
                  <input value="<?php echo $patient_id; ?>" placeholder="IIC ID" readonly="readonly" id="iic" type="text" disabled class="form-control" style="background-color: #f8f9fa;" required>
                </div>
              </div>
              
              <div class="form-group col-sm-6 col-xs-12">
                <label for="uhid" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-hospital-o" style="margin-right: 5px; color: #3498db;"></i>
                  UHID
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-hashtag"></i>
                     <?php 
                    if ($appointments['paitent_type'] == 'new_patient' ){
                      foreach ($select_result1 as $res_val){
                   ?>
                      <input value="<?php echo $res_val->uhid+1; ?>" id="uhid" name="uhid" type="text" class="form-control">
                   <?php } } ?>
                  </span>
                </div>
              </div>
            </div>

            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-user-md" style="margin-right: 8px; color: #3498db;"></i>
                  Consultation Information
                </h4>
              </div>
            </div>

            <div class="row">            
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-stethoscope" style="margin-right: 5px; color: #3498db;"></i>
                  Doctor <span class="text-danger">*</span>
                </label>
                <div class="well well-sm" style="background-color: #e8f4fd; border: 1px solid #b3d9ff; margin-top: 5px;">
                  <i class="fa fa-user-md" style="margin-right: 8px; color: #2c3e50;"></i>
                  <strong>Dr. <?php echo $all_method->doctor_name($appointments['appoitmented_doctor']); ?></strong>
                </div>
                <input id="doctor_name" type="hidden" value="Dr. <?php echo $all_method->doctor_name($appointments['appoitmented_doctor']); ?>" >
              </div>
             
              <div class="form-group col-sm-6 col-xs-12">
                <label for="on_date" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-calendar" style="margin-right: 5px; color: #3498db;"></i>
                  Date <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-clock-o"></i>
                  </span>
                  <input value="<?php echo date("Y-m-d H:i:s"); ?>" placeholder="Date" readonly="readonly" id="on_date" name="on_date" type="text" class="form-control" style="background-color: #f8f9fa;" required>
                </div>
              </div>
            </div>
         
            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-money" style="margin-right: 8px; color: #3498db;"></i>
                  Billing Information
                </h4>
              </div>
            </div>

            <div class="row">            
              <div class="form-group col-sm-6 col-xs-12">
                <label for="receipt_number" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-receipt" style="margin-right: 5px; color: #3498db;"></i>
                  Receipt Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-barcode"></i>
                  </span>
                  <input value="<?php echo getReceiptGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number" name="receipt_number" type="text" class="form-control" style="background-color: #f8f9fa;" required>
                </div>
              </div>
              
              <div class="form-group col-sm-6 col-xs-12">
                <label for="after_discount" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-rupee" style="margin-right: 5px; color: #3498db;"></i>
                  Consultation Fees <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-calculator"></i>
                  </span>
                  <input value="<?php echo $all_method->doctor_fees($appointments['appoitmented_doctor'], 'indian'); ?>" name="totalpackage" placeholder="Consultation fees" readonly="readonly" class="dhee" id="fees" type="hidden" required>
                  <input value="<?php echo $all_method->doctor_fees($appointments['appoitmented_doctor'], 'indian'); ?>" placeholder="Consultation fees" readonly="readonly" id="after_discount" name="fees" type="text" class="form-control" style="background-color: #f8f9fa;" required>
                </div>
              </div>
            </div>
     
            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-percent" style="margin-right: 8px; color: #3498db;"></i>
                  Payment Discount
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label for="payment_discount" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-tags" style="margin-right: 5px; color: #3498db;"></i>
                  Payment Discount <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-percent"></i>
                  </span>
                  <select id="payment_discount" class="form-control" required>
                    <option value="">Select Discount Type</option>
                    <option value="free">Free</option>
                    <option value="discount">Discount</option>
                    <option value="no discount">No Discount</option>
                  </select>
                </div>
              </div>

              <div class="form-group col-sm-6 col-xs-12" id="free_reason_box" style="display:none;">
                <label for="reason_of_visit" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-info-circle" style="margin-right: 5px; color: #3498db;"></i>
                  Free Reason <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-list"></i>
                  </span>
                  <select id="reason_of_visit" name="reason_of_visit" class="form-control">
                    <option value="">Select Reason</option>
                    <option value="First Visit">First Visit</option>
                    <option value="For TVS (Under Package)">For TVS (Under Package)</option>
                    <option value="Under Package">Under Package</option>
                    <option value="BHCG Counselling">BHCG Counselling</option>
                    <option value="Medicine Purchase">Medicine Purchase</option>
                    <option value="Diagnostic Test">Diagnostic Test</option>
                    <option value="Camp">Camp</option>
                  </select>
                </div>
              </div>
            </div>        
        
            <div class="row" id="discount_avail" style="display:none;">
              <div class="form-group col-sm-6 col-xs-12">
                <label for="discount_amount" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-minus-circle" style="margin-right: 5px; color: #3498db;"></i>
                  Discount Amount <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-rupee"></i>
                  </span>
                  <input value="0" placeholder="Enter discount amount" id="discount_amount" name="discount_amount" type="text" class="form-control">
                </div>
                <input value="<?php echo $_SESSION['logged_billing_manager']['allow_discount_rs']; ?>" id="allow_discount" type="hidden" required>
                <div id="show_disc_app" class="alert alert-warning" style="display:none; margin-top: 10px;">
                  <i class="fa fa-exclamation-triangle"></i>
                  Given discount is more than allowed, 
                  <a href="javascript:void(0);" accountant="<?php echo $_SESSION['logged_billing_manager']['username'];?>" id="get_discount_approval" class="alert-link">
                    click here
                  </a> 
                  for admin approval.
                </div> 
              </div>
              
              <div class="form-group col-sm-6 col-xs-12">
                <div id="center_share_div">
                  <label for="reason_of_discount" class="control-label" style="font-weight: 600; color: #34495e;">
                    <i class="fa fa-comment" style="margin-right: 5px; color: #3498db;"></i>
                    Reason of Discount <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                      <i class="fa fa-edit"></i>
                    </span>
                    <input value="" placeholder="Enter reason for discount" id="reason_of_discount" name="reason_of_discount" type="text" class="form-control">
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-credit-card" style="margin-right: 8px; color: #3498db;"></i>
                  Payment Method
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label for="payment_method" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-payment" style="margin-right: 5px; color: #3498db;"></i>
                  Payment Mode <span class="text-muted">(Optional for free)</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-credit-card"></i>
                  </span>
                  <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="">Select Payment Method</option>
                    <?php if($appointments['nationality'] == 'indian'){?>
                      <option value="neft" mode="NEFT">NEFT</option>
                      <option value="rtgs" mode="RTGS">RTGS</option>
                      <option value="card" mode="Card">Card</option>
                      <option value="upi" mode="UPI">UPI</option>
                      <option value="wallet" mode="Wallet">Wallet</option>
                    <?php }else{ ?>
                      <option value="international_card" mode="International Card">International Card</option>
                    <?php } ?>
                      <option value="cash" mode="Cash">Cash</option>
                      <option value="cheque" mode="Cheque">Cheque</option>                    
                  </select>
                </div>
              </div>
              
              <div class="form-group col-sm-6 col-xs-12" id="subvention_box" style="display:none;">
                <label for="subvention_charges" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-plus-circle" style="margin-right: 5px; color: #3498db;"></i>
                  Subvention Charges <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-rupee"></i>
                  </span>
                  <input value="" placeholder="Enter subvention charges" id="subvention_charges" name="subvention_charges" type="text" class="form-control">
                </div>
              </div>
            </div>

            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-calculator" style="margin-right: 8px; color: #3498db;"></i>
                  Payment Amount
                </h4>
              </div>
            </div>

            <div class="row">            
              <div class="form-group col-sm-6 col-xs-12">
                <label for="payment_done" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-money" style="margin-right: 5px; color: #3498db;"></i>
                  Payment Received <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-rupee"></i>
                  </span>
                  <input value="" placeholder="Enter payment received" id="payment_done" step="any" name="payment_done" type="number" class="form-control" required>
                </div>
              </div>
              
              <div class="form-group col-sm-6 col-xs-12">
                <label for="remaining_amount" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-balance-scale" style="margin-right: 5px; color: #3498db;"></i>
                  Remaining Amount <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-calculator"></i>
                  </span>
                  <input value="" placeholder="Remaining amount" readonly="readonly" id="remaining_amount" name="remaining_amount" type="text" class="form-control" style="background-color: #f8f9fa;" required>
                </div>
              </div>
            </div>

            <div class="row" style="margin-bottom: 25px;">
              <div class="col-sm-12">
                <h4 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 8px; margin-bottom: 20px;">
                  <i class="fa fa-info" style="margin-right: 8px; color: #3498db;"></i>
                  Additional Information
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label for="billing_id" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-barcode" style="margin-right: 5px; color: #3498db;"></i>
                  Billing ID <span class="text-muted">(Optional)</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-tag"></i>
                  </span>
                  <input value="" placeholder="Enter billing ID" id="billing_id" name="billing_id" type="text" class="form-control">
                </div>
                <?php 
                  $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$appointments['wife_phone']."' and paitent_type='new_patient'"; 
                  $query = $this->db->query($sql1);
                  $select_result1 = $query->result(); 
                  foreach ($select_result1 as $res_val){
                ?>
                  <input value="<?php echo $res_val->appoitment_for;?>" placeholder="origins" readonly="readonly" name="origins" id="origins" type="hidden" class="form-control">
                <?php } ?>
              </div>
              
              <div class="form-group col-sm-6 col-xs-12">
                <label for="consultation_id" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-stethoscope" style="margin-right: 5px; color: #3498db;"></i>
                  Consultation ID
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-list"></i>
                  </span>
                  <select id="consultation_id" name="consultation_id" class="form-control">
                    <option value="">Select Consultation ID</option>
                    <?php echo $all_method->get_code('consultation');?>
                  </select>
                </div>
              </div>
            </div>
         
            <div class="row" style="margin-bottom: 30px;">
              <div class="form-group col-sm-6 col-xs-12">
                <label for="billing_from" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-building" style="margin-right: 5px; color: #3498db;"></i>
                  Billing Source <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-hospital-o"></i>
                  </span>
                  <select name="billing_from" id="billing_from" class="form-control" required>
                    <option value="">Select Billing Source</option>
                    <?php if(isset($_SESSION['logged_billing_manager'])){ 
                            $center = $all_method->get_center(); 
                            if($_SESSION['logged_billing_manager']['center_type'] == "associated"){ ?>
                      <option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group col-sm-6 col-xs-12 hospital_id_section" style="display:none;">
                <label for="hospital_id" class="control-label" style="font-weight: 600; color: #34495e;">
                  <i class="fa fa-hospital" style="margin-right: 5px; color: #3498db;"></i>
                  Hospital ID
                </label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                    <i class="fa fa-id-badge"></i>
                  </span>
                  <input value="" id="hospital_id" name="hospital_id" type="text" class="form-control" placeholder="Enter hospital ID">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12 text-center">
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary btn-lg" id="create_billing" style="padding: 12px 30px; font-weight: 600; border-radius: 6px;">
                    <i class="fa fa-plus-circle" style="margin-right: 8px;"></i>
                    Create Billing
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
    
    <div class="col-sm-12" style="display:none;" id="consultation_preview">
      <div class="panel panel-success">
        <div class="panel-heading" style="background: linear-gradient(135deg, #27ae60, #229954); border: none;">
          <h3 class="panel-title" style="color: white; font-weight: 600;">
            <i class="fa fa-check-circle" style="margin-right: 8px;"></i>
            Billing Summary
          </h3>
          <div class="pull-right">
            <button type='button' id='btn' class="btn btn-warning btn-sm" onclick='printDiv();' style="margin-top: -5px;">
              <i class="fa fa-print" style="margin-right: 5px;"></i>
              Print
            </button>
          </div>
        </div>
        <div class="panel-body" style="padding: 30px; background-color: #f8f9fa;">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="print_this_section" style="margin-bottom: 0;">
              <thead style="background-color: #34495e; color: white;">
                <tr>
                  <th colspan="2" style="text-align: center; font-size: 16px; padding: 15px;">
                    <i class="fa fa-file-text-o" style="margin-right: 8px;"></i>
                    Billing Summary
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="width: 40%; font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-user-md" style="margin-right: 8px; color: #3498db;"></i>
                    Doctor:
                  </td>
                  <td id="doctor_id_text" style="font-weight: 500;"></td>
                </tr>
                <tr>
                  <td style="font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-calendar" style="margin-right: 8px; color: #3498db;"></i>
                    Date:
                  </td>
                  <td id="on_date_text" style="font-weight: 500;"></td>
                </tr>
                <tr>
                  <td style="font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-id-card-o" style="margin-right: 8px; color: #3498db;"></i>
                    IIC ID:
                  </td>
                  <td id="iic_id_text" style="font-weight: 500;"></td>
                </tr>
                <tr>
                  <td style="font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-receipt" style="margin-right: 8px; color: #3498db;"></i>
                    Receipt Number:
                  </td>
                  <td id="receipt_number_text" style="font-weight: 500;"></td>
                </tr>
                <tr>
                  <td style="font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-rupee" style="margin-right: 8px; color: #3498db;"></i>
                    Fees:
                  </td>
                  <td id="fees_text" style="font-weight: 500;"></td>
                </tr>
                <tr>
                  <td style="font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-money" style="margin-right: 8px; color: #3498db;"></i>
                    Payment Received:
                  </td>
                  <td id="payment_done_text" style="font-weight: 500;"></td>
                </tr>  
                <tr>
                  <td style="font-weight: 600; background-color: #ecf0f1;">
                    <i class="fa fa-balance-scale" style="margin-right: 8px; color: #3498db;"></i>
                    Remaining Amount:
                  </td>
                  <td id="remaining_amount_text" style="font-weight: 500;"></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row" id="transaction" style="display:none; margin-top: 20px;">
            <div class="col-sm-12">
              <h5 style="color: #2c3e50; border-bottom: 1px solid #bdc3c7; padding-bottom: 5px; margin-bottom: 15px;">
                <i class="fa fa-credit-card" style="margin-right: 8px; color: #3498db;"></i>
                Transaction Details
              </h5>
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="transaction_id" class="control-label" style="font-weight: 600; color: #34495e;">
                <i class="fa fa-hashtag" style="margin-right: 5px; color: #3498db;"></i>
                Reference Number <span class="text-muted">(Optional)</span>
              </label>
              <div class="input-group">
                <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                  <i class="fa fa-barcode"></i>
                </span>
                <input value="" placeholder="Enter reference number" id="transaction_id" name="transaction_id" type="text" class="form-control">
              </div>
            </div>
            <div class="form-group col-sm-6 col-xs-12">
              <label for="transaction_img" class="control-label" style="font-weight: 600; color: #34495e;">
                <i class="fa fa-upload" style="margin-right: 5px; color: #3498db;"></i>
                Upload Document
              </label>
              <div class="input-group">
                <span class="input-group-addon" style="background-color: #ecf0f1; border-color: #bdc3c7;">
                  <i class="fa fa-file"></i>
                </span>
                <input type="file" name="transaction_img" id="transaction_img" class="form-control" accept="image/*,.pdf">
              </div>
              <small class="help-block text-muted">Upload screenshot or document (JPG, PNG, PDF)</small>
            </div>
          </div>
          
          <div class="row" style="margin-top: 30px;">
            <div class="col-sm-12 text-center">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-warning btn-lg" id="edit_billing" style="padding: 12px 25px; font-weight: 600; border-radius: 6px; margin-right: 10px;">
                  <i class="fa fa-edit" style="margin-right: 8px;"></i>
                  Edit Billing
                </button>
                <button type="submit" id="submitbutton" class="btn btn-success btn-lg" style="padding: 12px 25px; font-weight: 600; border-radius: 6px;">
                  <i class="fa fa-check" style="margin-right: 8px;"></i>
                  Confirm Billing
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
</div>

<script type="text/javascript">
  $(document).on('keyup',"#subvention_charges",function(e) {
    var subvention_charges = $(this).val();
    var fees = parseFloat($('#fees').val());
    var discount = parseFloat($('#discount_amount').val());
    if(isNaN(discount)){ discount = 0;}
    if(isNaN(fees)){ fees = 0;}
    fees = (parseFloat(fees) - parseFloat(discount));
    if(subvention_charges != ""){
      var subvention = (parseFloat(subvention_charges) + parseFloat(fees));
      $('#after_discount').val(parseFloat(subvention));
    }else{
      $('#after_discount').val(parseFloat(fees));
    }
  });

  $(document).on('change',"#payment_discount",function(e) {
    $('#payment_method').prop('selectedIndex',0);
    $('#subvention_charges').val("");
    $("#subvention_charges").prop('required', false);
    $("#subvention_box").hide();

    $('#free_reason').val("");
    $("#free_reason").prop('required', false);
    $("#free_reason_box").hide();
    $("#payment_method").prop('required',true);
    $("input#after_discount").val($("input#fees").val());
    $("input#payment_done").val('');
    $("input#remaining_amount").val('');
    $("input#discount_amount").val('');
    $("input#reason_of_discount").val('');
    $("input#discount").prop('required',false);
    $("input#reason_of_discount").prop('required',false);
    $('#discount_avail').hide();
    if($(this).val() == 'discount'){
      $("input#discount").prop('required',true);
      $("input#reason_of_discount").prop('required',true);
      $('#discount_avail').show();
    }else if($(this).val() == 'free'){
      $("#free_reason").prop('required', true);
      $("#free_reason_box").show();
      $("#payment_method").prop('required',false);
      $("#transaction_id").prop('required',false);
      $("#after_discount").val(0);
      $('#payment_done').val(0);
      $('#remaining_amount').val(0);
    }
  });

  $(document).on('keyup',"#discount_amount",function(e) {
    var subvention_charges = 0;
    if($("#payment_method").val() == "insurance"){
      subvention_charges = parseFloat($("#subvention_charges").val());
      if(subvention_charges == ""){  subvention_charges = 0; }
    }

    $('#payment_done').val('');
    $('#remaining_amount').val('');
    var fees = parseFloat($('#fees').val());
    var new_fees = (fees + subvention_charges);
    var allowd = parseFloat($('#allow_discount').val());
    var discount_amount = parseFloat($(this).val());
    var after_cal_price = ( new_fees * allowd / 100 ).toFixed(2);
    discount_amount = (discount_amount)?discount_amount:0;
    if(discount_amount == ''){ $(this).val(""); discount_amount = 0;}
   
    if(discount_amount > after_cal_price){
        $('#fees').val('');
        $('#fees').val(parseFloat(fees));
        $('#after_discount').val(parseFloat(new_fees));
        $('#create_billing').hide();
        $('#show_disc_app').show();       
    }else{
      if(parseFloat(discount_amount) <= parseFloat(after_cal_price)){
          var listPrice = parseFloat(new_fees);
          var discount  = parseFloat(discount_amount);
          var remaining_amount = listPrice - discount;
          if(remaining_amount < 1){
            $('#payment_done').val('');
            $('#fees').val('');
            $(this).val('');
            $('#fees').val(parseFloat(fees));
            $('#after_discount').val(parseFloat(fees));
          }else{
            $('#after_discount').val(parseFloat(remaining_amount));
          }
          $('#show_disc_app').hide();
          $('#create_billing').show();
      }
      else{
          $('#fees').val(parseFloat(fees));
          $('#after_discount').val(parseFloat(fees));
          $('#create_billing').hide();
          $('#show_disc_app').show();       
      }
    }
  });
  
  $(document).on('change',"#payment_method",function(e) {
        if($('#payment_discount').val() != "free"){
          var fees = parseFloat($('#fees').val());
          var discount_amount = parseFloat($('#discount_amount').val());
          discount_amount = (discount_amount)?discount_amount:0;
          fees = (fees - discount_amount);
          $('#after_discount').val(parseFloat(fees));
        }

        $('#payment_done').val(" ");
        $('#remaining_amount').val(" ");
        $('#show_disc_app').hide();

        $('#transaction_id').prop('required',false);
        $('#transaction_img').prop('required',false);
        $('#subvention_charges').val("");
        $('#subvention_charges').prop('required',false);
        $('#subvention_box').hide();
        var method = $(this).val();
        if(method == ''){
          $('#transaction').hide();   
        }else{
          $('#transaction').show();
        }
        if(method == "insurance"){
          $('#subvention_charges').prop('required',true);
          $('#subvention_box').show();
        }
  });
  
  $(document).on('keyup',"#payment_done",function(e) {
    var fees = $('#after_discount').val();
    var payment_done = $(this).val();
    var remaining_amount = fees-payment_done;
    $('#remaining_amount').val(remaining_amount);
  });
  
  // Create Billing Event with Real-Time Wallet Check
  // Create Billing Event with Real-Time LIVE Wallet Check
  $(document).on('click', "#create_billing", function(e) {
    if ($(this).hasClass('disabled') || $(this).prop('disabled')) {
      e.preventDefault();
      return false;
    }
    
    var originalText = $(this).html();
    $('#msg_area').hide().empty();
            
    var payment_done = parseFloat($('#payment_done').val()) || 0;
    var payment_method = $('#payment_method').val();
    var payment_discount = $('#payment_discount').val();
    var patient_id = $('#patient_id').val();
    
    if($('#payment_done').val() == '' || payment_discount == ''){
      $('#msg_area').empty().append('One or more fields are empty !').show();
      $('html, body').animate({ scrollTop: $("#consultation_details").offset().top }, 500);
      return false;
    }

    // ==========================================
    // [संशोधित] बटन क्लिक पर ही लाइव वॉलेट बैलेंस चेक और UI रीलोड लॉजिक
    // ==========================================
    if (payment_method === 'wallet' && payment_done > 0) {
        var is_balance_ok = true;
        
        $.ajax({
            url: '<?php echo base_url("Billings/check_live_wallet_balance"); ?>', 
            type: 'POST',
            data: { patient_id: patient_id },
            async: false, // सिंक्रोनस रखेंगे ताकि निर्णय तुरंत हो सके
            success: function(response) {
                try {
                    var res = (typeof response === 'object') ? response : JSON.parse(response);
                    var live_balance = parseFloat(res.balance) || 0;

                    // 1. हिडन इनपुट वेरिएबल और टॉप कार्ड UI को तुरंत लाइव बैलेंस से अपडेट करें
                    $('#available_wallet_balance').val(live_balance);
                    var formatted_balance = '₹ ' + live_balance.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    $('.text-success.font-weight-bold').siblings('h2.display-5').html(formatted_balance);

                    // 2. वैलिडेशन चेक करें
                    if (payment_done > live_balance) {
                        $('#msg_area').empty().append('<strong>Warning:</strong> Wallet में पर्याप्त बैलेंस नहीं है! दूसरे टैब पर दवाइयों का बिल होने के कारण आपका उपलब्ध लाइव बैलेंस अब: ₹' + live_balance.toFixed(2) + ' है।').show();
                        $('html, body').animate({ scrollTop: $("#consultation_details").offset().top }, 500);
                        is_balance_ok = false;
                    }
                } catch (err) {
                    console.error("JSON Parsing Error: ", err);
                    $('#msg_area').empty().append('<strong>Error:</strong> Backend से अमान्य रिस्पॉन्स आया।').show();
                    is_balance_ok = false;
                }
            },
            error: function() {
                $('#msg_area').empty().append('<strong>Error:</strong> सर्वर से कनेक्शन टूट गया है।').show();
                is_balance_ok = false;
            }
        });

        if (!is_balance_ok) {
            return false; // प्रिव्यू स्क्रीन पर जाने से रोकें
        }
    }
    // ==========================================

    $(this).addClass('disabled').prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="margin-right: 8px;"></i>Processing...');
    
    // Clear old text fields
    $('#doctor_id_text, #fees_text, #payment_done_text, #remaining_amount_text, #payment_method_text, #transaction_id_text, #billing_id_text, #receipt_number_text, #on_date_text, #iic_id_text').empty();

    if(payment_discount == 'discount'){
      var reason_of_discount =  $("input#reason_of_discount").val();
      var discount_amount =  $("input#discount_amount").val();
        
      if(discount_amount == '' || reason_of_discount == ''){
        $('#msg_area').append('One or more fields are empty !').show();
        $('#create_billing').removeClass('disabled').prop('disabled', false).html(originalText);
      }else{
        value_into_text();  
        $('#create_billing').removeClass('disabled').prop('disabled', false).html(originalText);
      }
    }else{
      value_into_text();
      $('#create_billing').removeClass('disabled').prop('disabled', false).html(originalText);
    }
  });

// फॉर्म सबमिट होने से पहले फाइनल लाइव सुरक्षा चेक (बिना बिल रोके)
$('form').on('submit', function(e) {
    var $submit = $('#submitbutton');
    var payment_method = $('#payment_method').val();
    var payment_done = parseFloat($('#payment_done').val()) || 0;
    var patient_id = $('#patient_id').val();
    var is_valid = true;

    if (payment_method === 'wallet' && payment_done > 0) {
        e.preventDefault(); // क्षणिक ठहराव
        
        $.ajax({
            url: '<?php echo base_url("Billings/check_live_wallet_balance"); ?>', 
            type: 'POST',
            data: { patient_id: patient_id },
            async: false,
            success: function(response) {
                try {
                    var res = (typeof response === 'object') ? response : JSON.parse(response);
                    var live_balance = parseFloat(res.balance) || 0;

                    // अगर बैलेंस अभी भी पर्याप्त है, तो सबमिशन पास करें
                    if (live_balance >= payment_done) {
                        is_valid = true;
                    } else {
                        // यदि इस बीच भी बैलेंस कम हो गया
                        $('#available_wallet_balance').val(live_balance);
                        var formatted_balance = '₹ ' + live_balance.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        $('.text-success.font-weight-bold').siblings('h2.display-5').html(formatted_balance);
                        
                        $('#msg_area').empty().append('<strong>Warning:</strong> Wallet में पर्याप्त बैलेंस नहीं है! (उपलब्ध: ₹' + live_balance.toFixed(2) + ')').show();
                        
                        $('#consultation_preview').hide();
                        $('#consultation_details').show();
                        $('html, body').animate({ scrollTop: $("#consultation_details").offset().top }, 500);
                        
                        $submit.removeClass('disabled').prop('disabled', false).html('Confirm Billing');
                        is_valid = false;
                    }
                } catch (err) {
                    is_valid = false;
                }
            },
            error: function() {
                is_valid = false;
            }
        });
        
        if (!is_valid) {
            return false;
        }
    }

    // यदि सब कुछ मान्य है, तो फॉर्म सबमिट करें
    if (is_valid) {
        if ($submit.hasClass('disabled') || $submit.prop('disabled')) {
            e.preventDefault();
            return false;
        }
        var originalSubmitText = $submit.data('original-text') || 'Confirm Billing'; 
        $submit.data('original-text', originalSubmitText);
        $submit.addClass('disabled').prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="margin-right: 8px;"></i>Submitting...');
        
        // फॉर्म को नेटिवली सबमिट करने की अनुमति दें
        this.submit();
    }
});
  
  function value_into_text(){
    $('#doctor_id_text').append($('#doctor_name').val());
    $('#fees_text').append($('#after_discount').val());
    $('#payment_done_text').append($('#payment_done').val());
    $('#remaining_amount_text').append($('#remaining_amount').val());
    $('#transaction_id_text').append($('#transaction_id').val());
    $('#payment_method_text').append($('#payment_method').find(':selected').attr('mode'));      
    $('#billing_id_text').append($('#billing_id').val());
    $('#receipt_number_text').append($('#receipt_number').val());
    $('#on_date_text').append($('#on_date').val());
    $('#iic_id_text').append($('#patient_id').val());

    hideshow_discount();
    $('#consultation_details').hide();
    $('#consultation_preview').show();
  }
  
  function hideshow_discount(){
    var discount_amount = $('#discount_amount').val()
    if(discount_amount < 1){
      $('.discount_div').hide();
    }else{
      $('.discount_div').show();  
    }
  }
  
  $(document).on('click',"#edit_billing",function(e) {
      $('#consultation_preview').hide();
      $('#consultation_details').show();
  });
  
  function printDiv() {
    $('#print_this_section').css('visibility', 'visible');
    var divToPrint=document.getElementById("print_this_section");
    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
  }
</script>

<script>
    $(document).ready(function() {
        // 1. Logic for dropdown change PiHC0001
        $('#consultation_id').change(function() {
            var selectedText = $(this).find("option:selected").text();
            var selectedValue = $(this).val();
            var defaultPrice = $('#after_discount').data('default-price') || parseFloat($('#fees').val());

            if (selectedValue === "PiHC0001" || selectedText.includes("PiHC0001")) {
                var specialPrice = 20000;
                $('#fees').val(specialPrice);
                $('#after_discount').val(specialPrice);
                $('#payment_done').val(specialPrice); 
                $('#discount_amount').val(0);
                $('#remaining_amount').val(0);
            } else {
                $('#fees').val(defaultPrice);
                $('#after_discount').val(defaultPrice);
                $('#payment_done').val(''); 
                $('#remaining_amount').val('');
            }
        });

        // 2. Logic to restrict max discount to 5000
        $('#discount_amount').on('input', function() {
            var maxLimit = 5000;
            var currentVal = parseFloat($(this).val());
            if (currentVal > maxLimit) {
                alert("Maximum discount allowed is " + maxLimit);
                $(this).val(maxLimit);
            }
        });

        // First Visit & CEO Approval logical triggers
        var isFirstVisit = <?php echo $is_first_visit; ?>; 
        var procedureExists = <?php echo $procedure_exists; ?>;

        $('#reason_of_visit').on('change', function() {
            var reason = $(this).val();
            if (reason === "First Visit" && isFirstVisit === false) {
                alert("This is not a first-time consultation. Please select another reason.");
                $(this).val("");
                return false;
            }
        });

        $('#create_billing').on('click', function(e) {
            var reason = $('#reason_of_visit').val();
            var discountType = $('#payment_discount').val();
            var packageReasons = ["For TVS (Under Package)", "Under Package"];
            
            $('input[name="approve_by_status"]').remove();

            if (discountType === 'free' && packageReasons.indexOf(reason) !== -1 && procedureExists === false) {
                if (confirm("No paid procedure found. Save as PENDING for CEO approval?")) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'approve_by_status',
                        value: '2'
                    }).appendTo('form');

                    sendApprovalMail(reason);
                    value_into_text();
                } else {
                    return false;
                }
            }
        });

        function sendApprovalMail(reason) {
            $.ajax({
                url: '<?php echo base_url("Billings/request_ceo_approval"); ?>',
                type: 'POST',
                data: { 
                    patient_id: $('#patient_id').val(), 
                    reason: reason, 
                    doctor: $('#doctor_name').val()
                },
                success: function(res) {
                    console.log("Mail Status: " + res);
                }
            });
        }
    });
</script>