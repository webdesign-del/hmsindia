<?php $all_method =&get_instance(); ?>
<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data" >
    <input type="hidden" name="action" value="update_patient" />
    <input type="hidden" name="patient_id" value="<?php echo $patient_data['patient_id']; ?>" id="patient_id" />
    
    <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
        <div class="panel-heading">
          <h3 class="heading">Edit patient</h3>
          <p id="msg_area" style="margin-top:30px; display:none; padding:5px 15px;" class="error"></p>
        </div>
        <div class="panel-body profile-edit">
          <p>
       <div id="add_section"> 
        <div class="row">
		   <div class="form-group col-sm-3 col-xs-12" align="center"></div>                               	
           <div class="form-group col-sm-6 col-xs-12" align="center">
                <label for="item_name">IIC ID </label>
                <h3><?php echo $patient_data['patient_id']; ?></h3>
           </div>
		   <div class="form-group col-sm-3 col-xs-12" align="center"></div>
         </div>
  		 <h4>Update patient details</h4>
         <hr />
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12" align="center">
                <h4 for="item_name">Wife details </h4>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12" align="center">
                <h4 for="item_name">Husband details </h4>
           </div>
         </div>
        
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife Name (Required)</label>
                <input value="<?php echo $patient_data['wife_name']; ?>" id="wife_name" name="wife_name" type="text" class="form-control validate in_field" required>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband Name (Required)</label>
                <input value="<?php echo $patient_data['husband_name']; ?>" id="husband_name" name="husband_name" type="text" class="form-control validate in_field" required>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife Phone</label>
				<?php 
// 1. Pehle check karein ki Patient ID empty toh nahi hai
if (!empty($patient_data['patient_id'])) {
    
    // Agar aapko database se fresh data nikalna hai toh:
    $p_id = $patient_data['patient_id'];
    $query = $this->db->query("SELECT wife_phone FROM hms_patient_medical_info WHERE patient_id = '$p_id'");
    $medical_info = $query->row_array();

    // 2. Counselor Login Check
    if (isset($_SESSION['logged_counselor']) && !empty($_SESSION['logged_counselor'])) { 
        ?>
        <p>
            <strong>Mobile No:</strong> 
            <?php 
                // Pehle medical_info check karein, agar wahan nahi hai toh patient_data se lein
                if (!empty($medical_info['wife_phone'])) {
                    echo $medical_info['wife_phone'];
                } elseif (!empty($patient_data['wife_phone'])) {
                   // echo $patient_data['wife_phone'];
                } else {
                    echo 'N/A';
                }
            ?>
        </p>
        <?php 
    } else {
        // Counselor login nahi hai
        echo "<p><strong>Mobile No:</strong> <em>Hidden (Counselor login required)</em></p>";
    }

} else {
    // Agar Patient ID hi nahi hai toh mobile_no variable empty rakhein
    $mobile_no = "";
} 
?>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband Phone (Required)</label>
                <input value="<?php echo $patient_data['husband_phone']; ?>" id="husband_phone" name="husband_phone" type="text" class="form-control validate in_field" required>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife Email (Required)</label>
                <input value="<?php echo $patient_data['wife_email']; ?>" id="wife_email" name="wife_email" type="text" class="form-control validate in_field" required>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband Email (Required)</label>
                <input value="<?php echo $patient_data['husband_email']; ?>" id="husband_email" name="husband_email" type="text" class="form-control validate in_field" required>
           </div>
         </div>
         
         <?php if($patient_data['nationality'] == "indian"){?>
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife Pan number (Optional)</label>
                <input value="<?php echo $patient_data['wife_pan_number']; ?>" id="wife_pan_number" name="wife_pan_number" type="text" class="form-control validate in_field">
                <div class="upload_div">
	                <label>Upload pan card</label>
    	            <input type="file" name="wife_pan_card" class="remove_required" />
                </div>
                <?php if(!empty($patient_data['wife_pan_card'])){ ?>
	                <a href="<?php echo $patient_data['wife_pan_card']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['wife_pan_card']; ?>" id="wife_pan_card_img" /></a>
                <?php } ?>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband Pan number (Optional)</label>
                <input value="<?php echo $patient_data['husband_pan_number']; ?>" id="husband_pan_number" name="husband_pan_number" type="text" class="form-control validate in_field">
                <div class="upload_div">
	                <label>Upload pan card</label>
    	            <input type="file" name="husband_pan_card" class="remove_required" />
                </div>
                 <?php if(!empty($patient_data['husband_pan_card'])){ ?>
				<a href="<?php echo $patient_data['husband_pan_card']; ?>" target="_blank"> <img class="img_show" title="click to enlarge" src="<?php echo $patient_data['husband_pan_card']; ?>" id="husband_pan_card_img" /></a>
                <?php } ?>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife Adhaar number (Required)</label>
                <input value="<?php echo $patient_data['wife_adhar_number']; ?>" required id="wife_adhar_number" name="wife_adhar_number" type="text" class="form-control validate in_field">
                <div class="upload_div">
	                <label>Upload front adhar card</label>
    	            <input type="file" name="wife_adhar_card" class="remove_required" <?php if(empty($patient_data['wife_adhar_card'])){ ?> required <?php } ?> />
                     <?php if(!empty($patient_data['wife_adhar_card'])){ ?>
                        <a href="<?php echo $patient_data['wife_adhar_card']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['wife_adhar_card']; ?>" id="wife_adhar_card_img" /></a>
                        <?php } ?>
                    <label>Upload back adhar card</label>
    	            <input type="file" name="wife_back_adhar_card" class="remove_required" <?php if(empty($patient_data['wife_back_adhar_card'])){ ?> required <?php } ?> />
                     <?php if(!empty($patient_data['wife_back_adhar_card'])){ ?>
                        <a href="<?php echo $patient_data['wife_back_adhar_card']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['wife_back_adhar_card']; ?>" id="wife_back_adhar_card" /></a>
                     <?php } ?>
                </div>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband Adhaar number (Required)</label>
                <input value="<?php echo $patient_data['husband_adhar_number']; ?>" required id="husband_adhar_number" name="husband_adhar_number" type="text" class="form-control validate in_field">
                <div class="upload_div">
            	    <label>Upload front adhar card</label>
        	        <input type="file" name="husband_adhar_card" class="remove_required" <?php if(empty($patient_data['husband_adhar_card'])){ ?> required <?php } ?> />
                    <?php if(!empty($patient_data['husband_adhar_card'])){ ?>
                        <a href="<?php echo $patient_data['husband_adhar_card']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['husband_adhar_card']; ?>" id="husband_adhar_card_img" /></a>
                    <?php } ?>
                    <label>Upload back adhar card</label>
        	        <input type="file" name="husband_back_adhar_card" class="remove_required" <?php if(empty($patient_data['husband_back_adhar_card'])){ ?> required <?php } ?> />
                 <?php if(!empty($patient_data['husband_back_adhar_card'])){ ?>
                    <a href="<?php echo $patient_data['husband_back_adhar_card']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['husband_back_adhar_card']; ?>" id="husband_back_adhar_card" /></a>
                <?php } ?>
                </div>
	          </div>
         </div>
         
        <?php }else{ ?>
            <div class="row">            
               <div class="form-group col-sm-6 col-xs-12">
                    <label for="item_name">Wife Passport Number (Required)</label>
                    <input value="<?php echo $patient_data['wife_passport_number']; ?>" required id="wife_passport_number" name="wife_passport_number" type="text" class="form-control validate in_field">
                    <div class="upload_div">
    	                <label>Upload Passport</label>
        	            <input type="file" name="wife_passport" class="remove_required" <?php if(empty($patient_data['wife_passport'])){ ?> required <?php } ?> />
                    </div>
                    <?php if(!empty($patient_data['wife_passport'])){ ?>
    	                <a href="<?php echo $patient_data['wife_passport']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['wife_passport']; ?>" id="wife_passport_img" /></a>
                    <?php } ?>
               </div>
               
               <div class="form-group col-sm-6 col-xs-12">
                    <label for="item_name">Husband Passport Number (Required)</label>
                    <input value="<?php echo $patient_data['husband_passport_number']; ?>" required id="husband_passport_number" name="husband_passport_number" type="text" class="form-control validate in_field">
                    <div class="upload_div">
    	                <label>Upload Passport</label>
        	            <input type="file" name="husband_passport" class="remove_required" <?php if(empty($patient_data['husband_passport'])){ ?> required <?php } ?> />
                    </div>
                     <?php if(!empty($patient_data['husband_passport'])){ ?>
    				<a href="<?php echo $patient_data['husband_passport']; ?>" target="_blank"> <img class="img_show" title="click to enlarge" src="<?php echo $patient_data['husband_passport']; ?>" id="husband_passport_img" /></a>
                    <?php } ?>
               </div>
            </div>
        <?php } ?>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
				<label for="item_name">Wife Photo (Required)</label><div class="clearfix"></div>
                <div class="upload_div">
    	            <input type="file" name="wife_photo" class="remove_required" <?php if(empty($patient_data['wife_photo'])){ ?> required <?php } ?> />
                </div>
                 <?php if(!empty($patient_data['wife_photo'])){ ?>
                <a href="<?php echo $patient_data['wife_photo']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['wife_photo']; ?>" id="wife_photo_img" /></a>
                <?php } ?>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
	           <label for="item_name">Husband Photo (Required)</label><div class="clearfix"></div>
                <div class="upload_div">
    	            <input type="file" name="husband_photo" class="remove_required" <?php if(empty($patient_data['husband_photo'])){ ?> required <?php } ?> />
                </div>
                 <?php if(!empty($patient_data['husband_photo'])){ ?>
              		 <a href="<?php echo $patient_data['husband_photo']; ?>" target="_blank"><img class="img_show" title="click to enlarge" src="<?php echo $patient_data['husband_photo']; ?>" id="husband_photo_img" /></a>
                <?php } ?>
           </div>
         </div>
         
          <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife age (Required)</label>
                <input value="<?php echo $patient_data['wife_age']; ?>" required id="wife_age" name="wife_age" type="text" class="form-control validate in_field">
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband age (Required)</label>
                <input value="<?php echo $patient_data['husband_age']; ?>" required id="husband_age" name="husband_age" type="text" class="form-control validate in_field">
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Wife address (Required) <span class="error">* Address as per adhaar</span></label>
                <textarea id="wife_address" name="wife_address" required class="form-control validate in_field"><?php echo $patient_data['wife_address']; ?></textarea>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Husband address (Required) <span class="error">* Address as per adhaar</span></label>
                <textarea id="husband_address" name="husband_address" required class="form-control validate in_field"><?php echo $patient_data['husband_address']; ?></textarea>
           </div>
         </div>
         <div class="clearfix"></div>
	     <div class="form-group col-sm-12 col-xs-12">
	        <input type="submit" id="submitbutton" class="btn btn-large" value="Update" />
         </div>
         </div>
         
        </p>
      </div>
    </div>
</form>

<script>
$(document).on('blur',"#husband_phone",function(e) {$('#msg_area').empty(); $('#msg_area').hide();
	var wife_phone = $('#wife_phone').val();
	var husband_phone = $(this).val();
	if(husband_phone == wife_phone){ $(this).val(''); $('#msg_area').append('Husband wife phone number cannot be same.'); $('#msg_area').show();}	
});
$(document).on('blur',"#husband_email",function(e) {$('#msg_area').empty();$('#msg_area').hide();
	var wife_email = $('#wife_email').val();
	var husband_email = $(this).val();
	if(husband_email == wife_email){ $(this).val('');  $('#msg_area').append('Husband wife email cannot be same.'); $('#msg_area').show();}
});


// Pancard validation
$('#wife_pan_number').on("change, blur", function() {   
	var txtpan = $(this).val(); 
	validate_pan(txtpan);
});

$('#husband_pan_number').on("change, blur", function() {   
	 var txtpan = $(this).val(); 
	 validate_pan(txtpan);
});

function validate_pan(txtpan){
  $('#msg_area').hide();
   $('#loader_div').show();
	$('#msg_area').empty();
	 var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;	
	 if (txtpan.length == 10 ) { 
		  if( txtpan.match(regExp) ){$('#submitbutton').show(); $('#loader_div').hide();}
		  else {
			 $('#msg_area').append('Not a valid PAN number.');
			 $('#submitbutton').show();
  			 $("html, body").animate({ scrollTop: 0 }, "slow");
 			 $('#loader_div').hide();
			$('#msg_area').show();
		     event.preventDefault(); 
		  } 
	 } 
	 else { 
 		   $('#msg_area').append('Please enter 10 digits for a valid PAN number');
		   $('#submitbutton').show();
			$("html, body").animate({ scrollTop: 0 }, "slow");
			$('#loader_div').hide();
			$('#msg_area').show();
		   event.preventDefault(); 
	 } 
}

// Adhaar validation
$('#wife_adhar_number').keyup(function() {
  var value = $(this).val();
  value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
  $(this).val(value);
});

$('#husband_adhar_number').keyup(function() {
  var value = $(this).val();
  value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
  $(this).val(value);
});

$('#wife_adhar_number').on("change, blur", function() {   
	var txtpan = $(this).val(); 
	validate_adhaar(txtpan);
});
$('#husband_adhar_number').on("change, blur", function() {   
	 var txtpan = $(this).val(); 
	 validate_adhaar(txtpan);
});

function validate_adhaar(value){
  $('#msg_area').hide();
   $('#loader_div').show();
	$('#msg_area').empty();
	var maxLength = 14;
	if (value.length != maxLength) {
		 $('#msg_area').append('Adhaar number length should be 12.');
		 $('#submitbutton').hide();
		 $("html, body").animate({ scrollTop: 0 }, "slow");
		 $('#loader_div').hide();
		$('#msg_area').show();
	} else {$('#submitbutton').show(); $('#loader_div').hide(); $('#msg_area').show();}
}

// Phone number validation

$('#phone_number').on("change, blur, keyup", function() { 
	$('#add_section').hide();
	$('#search_patient').hide();
	$('#iic_id').val('');
	var txtpan = $(this).val(); 
	phone_validate(txtpan);
});
$('#husband_phone').on("change, blur, keyup", function() {   
	 var txtpan = $(this).val(); 
	 phone_validate(txtpan);
});

$('#iic_id').on("change, blur, keyup", function() {
	$('#add_section').hide();
	$('#search_patient').hide();
	$('#phone_number').val('');
	$('#search_patient').show();
});

function phone_validate(mobile) {
   $('#loader_div').show();
	$('#msg_area').empty();
	var pattern = /^\d{10}$/;
	if (pattern.test(mobile)) {$('#submitbutton').show(); $('#search_patient').show(); $('#loader_div').hide(); return true; }
	$('#msg_area').append('It is not valid mobile number.input 10 digits number!');
	$('#submitbutton').hide();
	$('#search_patient').hide();
	$("html, body").animate({ scrollTop: 0 }, "slow");
    $('#loader_div').hide();
	return false;
}

// Email validation
$('#wife_email').on("change, blur", function() {   
	var txtpan = $(this).val(); 
	email_validate(txtpan);
});
$('#husband_email').on("change, blur", function() {   
	 var txtpan = $(this).val(); 
	 email_validate(txtpan);
});

function email_validate(email) {
   $('#loader_div').show();
	$('#msg_area').empty();
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(email)) {
	   $('#msg_area').append('It is not valid email address!');
	   $('#submitbutton').hide();
	   $("html, body").animate({ scrollTop: 0 }, "slow");
       $('#loader_div').hide();
	}else{
       $('#submitbutton').show();
       $('#loader_div').hide();
	   return true;
	}
}

</script>