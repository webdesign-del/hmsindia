<?php 
function checklogin(){
  $return = array();
  if(isset($_SESSION['logged_administrator'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_administrator']['role']);
	return $return;
  }else if(isset($_SESSION['logged_accountant'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_accountant']['role']);
	return $return;
  }else if(isset($_SESSION['logged_stock_manager'])){
	$return = array('status' => true, 'role'=>$_SESSION['logged_stock_manager']['role']);
	return $return;
  }else if(isset($_SESSION['logged_billing_manager'])){
    $return = array('status' => true, 'role'=>isset($_SESSION['logged_billing_manager']['role']) ? $_SESSION['logged_billing_manager']['role'] : 'billing_manager');
	return $return;
  }else if(isset($_SESSION['logged_telecaller'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_telecaller']['role']);
	return $return;
  }else if(isset($_SESSION['logged_central_stock_manager'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_central_stock_manager']['role']);
	return $return;
  }else if(isset($_SESSION['logged_doctor'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_doctor']['role']);
	return $return;
  }else if(isset($_SESSION['logged_investigation_manager'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_investigation_manager']['role']);
	return $return;
  }else if(isset($_SESSION['logged_counselor'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_counselor']['role']);
	return $return;
  }else if(isset($_SESSION['logged_liason'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_liason']['role']);
	return $return;
  }else if(isset($_SESSION['logged_mrd'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_mrd']['role']);
	return $return;
  }else if(isset($_SESSION['logged_embryologist'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_embryologist']['role']);
	return $return;
  }else if(isset($_SESSION['logged_viewer'])){
    $return = array('status' => true, 'role'=>$_SESSION['logged_viewer']['role']);
    log_message('info', 'Found logged_viewer session');
	return $return;
  }else{
	$return = array('status' => false, 'role'=>'');
	return $return;
  }
}

function whatsappregister($phone, $patient_info=""){
    
   /* $field_arr = array();
    $field_arr = array('patient_phone' => "+91".$phone, 'doctor_phone' => '+918888888888', "patient_info" => $patient_info, "send_welcome_message" => true);
    // var_dump($field_arr);die;
    
    $data = "\n".date("dd-mm-yy H:i:s")."-----Request--Param--------".json_encode($field_arr)."\n";
    $fp = fopen('app_data.txt', 'a');
    fwrite($fp, $data);
    fclose($fp);
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://crm.notbot.in/whatsapp/waba/v1/messages',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $field_arr,
       CURLOPT_HTTPHEADER => array(
       'API-KEY: 64c26b76b66e84ad6cb97951',
       'Content-Type: application/json'
    ),
    )); */
	
		// Sample PHP variables
$language = "en";
//$templateName = "patient_registration";
$templateName = "patient_registration_v2";
$from = "+919971934495";
$authorizationKey = "key_FRwBDvK22S";

// Create the data array
$data = [
    "messages" => [
        [
            "content" => [
                "language" => $language,
                "templateName" => $templateName
            ],
            "from" => $from,
            "to" => "+91".$phone
        ]
    ]
];

// Convert the data array to JSON
$jsonData = json_encode($data);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://public.doubletick.io/whatsapp/message/template',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $jsonData,
  CURLOPT_HTTPHEADER => array(
    "Authorization: $authorizationKey",
    'accept: application/json',
    'content-type: application/json'
  ),
));  
    
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response);
    
    $data = "\n".date("d-m-y H:i:s")."-----Response--------".json_encode($result)."\n";
    $fp = fopen('app_data.txt', 'a');
    fwrite($fp, $data);
    fclose($fp);
    
    if(isset($result->success)){
        return array("status" => 1, "message" => "registered");
    }else{
        return array("status" => 0, "message" => isset($result->message)?$result->message:"Something went wrong!");
    }
}

function generateVoucherCode($length = 12) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $voucherCode = '';
    $max = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $voucherCode .= $characters[rand(0, $max)];
    }

    return $voucherCode;
}

function whatsappfileprep($phone, $file, $name="prepfile", $element_name="prescription_sent"){
   
   /* $curl = curl_init();
    $fileprep = array('gs_element_name' => $element_name,
      'patient_phone' => "91".$phone,
      'file'=> new CURLFILE($file),
      'template_params' => 'PATIENT_NAME');

    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://curelinktech.in/integrations/send_message_template/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $fileprep,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Token 1633f8b885e236b4cf8800b3aaf547de6efbfe44'
      ),
    )); */
	
	// Sample PHP variables
//$patient_details = get_patient_detail($patient_id);
//$patient_details['wife_name']
$language = "en";
$filename = "Billing";
//$templateName = "prescription_sent";
$from = "+919971934495";
$authorizationKey = "key_FRwBDvK22S";

$publicFileUrl = "https://indiaivf.website/assets/whatsapp-pdf/" . basename($file);

// Create the data array
$data = [
    "messages" => [
        [
            "content" => [
                "language" => $language,
                "templateData" => [
                    "header" => [
                        "type" => "DOCUMENT",
                        "mediaUrl" => $publicFileUrl,
                        "filename" => $filename
                    ],
                    "body" => [
                        "placeholders" => [$name]
                    ]
                ],
                "templateName" => $element_name
            ],
            "from" => $from,
            "to" => "+91".$phone
        ]
    ]
];

// Convert the data array to JSON
$jsonData = json_encode($data);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://public.doubletick.io/whatsapp/message/template',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $jsonData,
  CURLOPT_HTTPHEADER => array(
    "Authorization: $authorizationKey",
    'accept: application/json',
    'content-type: application/json'
  ),
));

    
    $response = curl_exec($curl);
	
	//print_r($response);
    
    curl_close($curl);
    $result = json_decode($response);
    
    if(isset($result->success)){
        return array("status" => 1, "message" => "sent");
    }else{
        return array("status" => 0, "message" => isset($result->message)?$result->message:"Something went wrong!");
    }
}

function whatsappappointment($phone, $wife_name="", $center_name="", $apptime="", $app_slot ="", $location="", $element_name="appointment_confirmation"){
    // Validate required parameters
    if(empty($phone) || empty($wife_name) || empty($center_name) || empty($apptime) || empty($app_slot)) {
        error_log("WhatsApp appointment missing required parameters - Phone: $phone, Name: $wife_name, Center: $center_name, Time: $apptime, Slot: $app_slot");
        return array("status" => 0, "message" => "Missing required parameters");
    }
    
    $curl = curl_init();
   /* $fileprep = array('gs_element_name' => $element_name, 'patient_phone' => "+91".$phone, 'template_params' => $params);

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://curelinktech.in/integrations/send_message_template/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $fileprep,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Token 1633f8b885e236b4cf8800b3aaf547de6efbfe44'
      ),
    )); */
	
	//$template_params = (explode(" ",$params));
	//print_r($template_params);
	
	
	
$language = "en";
$type = "LOCATION";
$latitude = "28.535517";
$longitude = "77.391029";
//$templateName = "appointment_confirmation";
$templateName = "appointment_confirmation_v6";
//$templateName = "appointment_confirmation_v3";
$from = "+919971934495";
$authorizationKey = "key_FRwBDvK22S";	
	
// Create the data array

$data = [
    "messages" => [
        [
            "content" => [
                "language" => $language,
                "templateData" => [
                    "header" => [
                        "type" => $type,
                        "latitude" => $latitude,
                        "longitude" => $longitude
                    ],
                    "body" => [
                       "placeholders" => [$wife_name, $center_name, $apptime, $app_slot, $location]
					   //"placeholders" => $template_params
					
					]
                ],
                "templateName" => $templateName
            ],
            "from" => $from,
            "to" => (substr($phone, 0, 1) === '+' ? $phone : "+91".$phone)
        ]
    ]
];

// Log the exact data structure being sent
error_log("WhatsApp API Data Structure: " . json_encode($data, JSON_PRETTY_PRINT));
// Convert the data array to JSON
$jsonData = json_encode($data);

// Log the data being sent for debugging
error_log("WhatsApp API Request - Phone: " . (substr($phone, 0, 1) === '+' ? $phone : "+91".$phone) . ", Template: " . $templateName . ", Data: " . $jsonData);
error_log("WhatsApp API URL: https://public.doubletick.io/whatsapp/message/template");
error_log("WhatsApp API Key: " . $authorizationKey);
error_log("WhatsApp From Number: " . $from);

// Try alternative endpoints if main one fails
$api_endpoints = [
    'https://public.doubletick.io/whatsapp/message/template',
    'http://public.doubletick.io/whatsapp/message/template',
    'https://api.doubletick.io/whatsapp/message/template'
];

    $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://public.doubletick.io/whatsapp/message/template',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $jsonData,
  CURLOPT_HTTPHEADER => array(
    'Authorization: key_FRwBDvK22S',
    'accept: application/json',
    'content-type: application/json'
  ),
  // Fix SSL certificate issues
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_CAINFO => null,
  // Additional SSL options for problematic servers
  CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
)); 
    
    $response = curl_exec($curl);
    
    // Check for curl errors
    if(curl_errno($curl)) {
        $curl_error = curl_error($curl);
        error_log("WhatsApp API cURL Error: " . $curl_error . " for phone: " . $phone);
        curl_close($curl);
        return array("status" => 0, "message" => "cURL Error: " . $curl_error);
    }
    
    // Log the response for debugging
    error_log("WhatsApp API Response: " . $response);
    
    curl_close($curl);
    $result = json_decode($response);
	
	//print_r($result);
	//exit();
    if(isset($result->success)){
        error_log("WhatsApp API Success: " . json_encode($result));
        return array("status" => 1, "message" => "sent");
    }else{
        $error_message = isset($result->message) ? $result->message : "Something went wrong!";
        error_log("WhatsApp API Error: " . $error_message . " for phone: " . $phone);
        error_log("WhatsApp API Full Response: " . json_encode($result));
        return array("status" => 0, "message" => $error_message);
    }
	
	
}



function get_patient_detail_by_phone($phone)
{
	$ci= &get_instance();
    $ci->load->database();
    $sql = "SELECT * FROM hms_patients WHERE wife_phone  = '".$phone."'";
    $q   = $ci->db->query($sql);
    $result = $q->result_array();    
    if(count($result) > 0)
    {
        return $result[0];
    }
    return $result;
}

function center_detail($center)

{
	$ci= &get_instance();

    $ci->load->database();
    $sql = "SELECT * FROM hms_centers WHERE center_name  = '".$center."'";
   // echo $sql; die;
      $q   = $ci->db->query($sql);
    $result = $q->result_array(); 
    
   
    if(count($result) > 0)

    {

		    return $result[0];    	

    }

    return $result;

}

/*function check_patient_procedure_billing($patient_id){

	$ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



    $sql = "Select * from ".$db_prefix."doctor_consultation where  patient_id='".$patient_id."' and procedure_billed='1' limit 1";

	$query = $ci->db->query($sql);

	$result = $query->result_array();

    if(!empty($result)){;

        return $result[0];

    }else{

        return $result;

    }

}*/
/*
function get_procedure_package_id($package_id) {
	$ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];
    
	$query = "SELECT procedure_id FROM * from ".$db_prefix."procedure_package WHERE package_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
*/

function check_patient_procedure_billing($patient_id){

	$ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



    $sql = "Select * from ".$db_prefix."patient_procedure where  patient_id='".$patient_id."' and status='approved' limit 1";

	$query = $ci->db->query($sql);

	$result = $query->result_array();

    if(!empty($result)){;

        return $result[0];

    }else{

        return $result;

    }

}



function get_header_template($role){
	if($role == "administrator"){
		$return = array('header' => "templates/administrator_header", 'footer'=>"templates/administrator_footer", 'dashboard' => 'dashboard/administrator_dashboard');
		return $return;
	}else if($role == "accountant"){
		$return = array('header' => "templates/accountant_header", 'footer'=>"templates/accountant_footer", 'dashboard' => 'dashboard/accountant_dashboard');
		return $return;
	}else if($role == "stock_manager"){
		$return = array('header' => "templates/stock_manager_header", 'footer'=>"templates/stock_manager_footer", 'dashboard' => 'dashboard/stock_manager_dashboard');
		return $return;
	}else if($role == "billing_manager"){
		$return = array('header' => "templates/billing_manager_header", 'footer'=>"templates/billing_manager_footer", 'dashboard' => 'dashboard/billing_manager_dashboard');
		return $return;
	}else if($role == "telecaller"){
		$return = array('header' => "templates/telecaller_header", 'footer'=>"templates/telecaller_footer", 'dashboard' => 'dashboard/telecaller_dashboard');
		return $return;
	}else if($role == "central_stock_manager"){
		$return = array('header' => "templates/central_stock_manager_header", 'footer'=>"templates/central_stock_manager_footer", 'dashboard' => 'dashboard/central_stock_manager_dashboard');
		return $return;
	}else if($role == "doctor"){
        if(isset($_SESSION['logged_doctor']['junior_doctor']) && $_SESSION['logged_doctor']['junior_doctor'] == 1){
            $return = array('header' => "templates/junior_doctor_header", 'footer'=>"templates/junior_doctor_footer", 'dashboard' => 'dashboard/junior_doctor_dashboard');
		}elseif(isset($_SESSION['logged_doctor']['psychological']) && $_SESSION['logged_doctor']['psychological'] == 1){
			$return = array('header' => "templates/psychological_header", 'footer'=>"templates/psychological_footer", 'dashboard' => 'dashboard/psychological_dashboard');
        }else{
            $return = array('header' => "templates/doctor_header", 'footer'=>"templates/doctor_footer", 'dashboard' => 'dashboard/doctor_dashboard');
        }
		return $return;
	}else if($role == "investigator_manager"){
		$return = array('header' => "templates/investigator_manager_header", 'footer'=>"templates/investigator_manager_footer", 'dashboard' => 'dashboard/investigator_manager_dashboard');
		return $return;
	}else if($role == "embryologist"){
		$return = array('header' => "templates/embryologist_header", 'footer'=>"templates/embryologist_footer", 'dashboard' => 'dashboard/embryologist_dashboard');
		return $return;
	}else if($role == "counselor"){
		$return = array('header' => "templates/counselor_header", 'footer'=>"templates/counselor_footer", 'dashboard' => 'dashboard/counselor_dashboard');
		return $return;
	}else if($role == "liason"){
		$return = array('header' => "templates/liason_header", 'footer'=>"templates/liason_footer", 'dashboard' => 'dashboard/liason_dashboard');
		return $return;
	}else if($role == "mrd"){
		$return = array('header' => "templates/mrd_header", 'footer'=>"templates/mrd_footer", 'dashboard' => 'dashboard/mrd_dashboard');
		return $return;	
	}else if($role == "viewer"){
		$return = array('header' => "templates/viewer_header", 'footer'=>"templates/viewer_footer", 'dashboard' => 'dashboard/viewer_dashboard');
		return $return;
	}
}

function send_mail($to, $subject, $mail_msg, $templates="")
{
	$all_method = &get_instance();
	
	// Get email configuration with fallbacks
	$mail_host = $all_method->config->config['mail_host'] ?? 'localhost';
	$mail_username = $all_method->config->config['mail_username'] ?? '';
	$mail_password = $all_method->config->config['mail_password'] ?? '';
	$mail_from_emailid = $all_method->config->config['mail_from_emailid'] ?? 'noreply@localhost';
	$mail_from_name = $all_method->config->config['mail_from_name'] ?? 'System';
	$mail_port = $all_method->config->config['mail_port'] ?? 587;
	$mail_encryption = $all_method->config->config['mail_encryption'] ?? 'tls';
	$mail_debug = $all_method->config->config['mail_debug'] ?? false;

	// Validate required configuration
	if (empty($mail_host) || empty($mail_username) || empty($mail_password)) {
		log_message('error', 'Email configuration incomplete: missing host, username, or password');
		return false;
	}

	$explode_to = explode('|', $to);
	$dic = dirname(__DIR__);

	require_once($dic.'/smtpmailer/class.phpmailer.php');		

	$mail = new PHPMailer();

	// Enable SMTP debugging if configured
	if ($mail_debug) {
		$mail->SMTPDebug = 2; // Enable verbose debug output
	}

	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->Host = $mail_host;  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Port = $mail_port; // set the SMTP port
	$mail->Username = $mail_username;  // SMTP username
	$mail->Password = $mail_password; // SMTP password		
	$mail->From = $mail_from_emailid;
	$mail->FromName = $mail_from_name;

	// Set encryption if specified
	if (!empty($mail_encryption)) {
		$mail->SMTPSecure = $mail_encryption;
	}

	// Add recipients and track them
	$valid_recipients = array();
	foreach($explode_to as $key => $val){
		if (!empty(trim($val))) {
			$mail->AddAddress(trim($val));
			$valid_recipients[] = trim($val);
		}
	}

	// Check if we have any valid recipients
	if (empty($valid_recipients)) {
		log_message('error', 'No valid email addresses provided for sending email');
		return false;
	}

	$mail->IsHTML(true);  // set email format to HTML

	// Add attachments if specified
	if(!empty($templates)){
		$templates = explode(',', $templates);
		foreach($templates as $template){
			$template_path = $dic."/email-templates/".$template.".pdf";
			if (file_exists($template_path)) {
				$mail->AddAttachment($template_path);
			} else {
				log_message('warning', 'Email template not found: ' . $template_path);
			}
		}
	}

	$mail->Subject = $subject;
	$mail->Body = $mail_msg;		

	// Try to send the email
	try {
		if(!$mail->Send()) {
			log_message('error', 'Mailer Error: ' . $mail->ErrorInfo);
			return false;
		} else {
			log_message('info', 'Email sent successfully to: ' . implode(', ', $valid_recipients));
			return true;
		}
	} catch (Exception $e) {
		log_message('error', 'Email sending exception: ' . $e->getMessage());
		return false;
	}
}



function send_sms($phone, $message){

    $message = urlencode($message);

    $api_url = "http://bulksms.smsroot.com/app/smsapi/index.php?key=35B90CA7AE70A8&campaign=0&routeid=13&type=text&contacts=$phone&senderid=LIFIVF&msg=$message";

    $ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $api_url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);

	curl_close($ch);

	return true;

}





function dateformat($originalDate){

	$newDate = date("d-m-Y H:i", strtotime($originalDate));

	return $newDate;

}



function sting_masking($number, $maskingCharacter = 'X') {
    return substr_replace($number, str_repeat("X", 5), 2, 4);
}



/** dashboard widgets */

function ivf_opd(){
        $ci= &get_instance();
        $ci->load->database();
        $db_prefix = $ci->config->config['db_prefix'];
        $consultation_result = array();
        $consultation_sql = "SELECT count(*) as consultations FROM ".$db_prefix."consultation where status!='disapproved'";
        $consultation_q = $ci->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(count($consultation_result) > 0){
            $consultation_result = $consultation_result[0]['consultations'];
        }
        return $consultation_result;
}



function ivf_procedures(){
    $ci= &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    $consultation_result = array();
    $consultation_sql = "SELECT count(*) as procedures FROM ".$db_prefix."patient_procedure where status!='disapproved'";
    $consultation_q = $ci->db->query($consultation_sql);
    $consultation_result = $consultation_q->result_array();
    if(count($consultation_result) > 0){
        $consultation_result = $consultation_result[0]['procedures'];
    }
    return $consultation_result;
}



    function ivf_patients(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $patient_result = array();

        $total_patients = 0;

        $patient_sql = "SELECT count(*) as patients FROM ".$db_prefix."patients";

        $patient_q = $ci->db->query($patient_sql);

        $patient_result = $patient_q->result_array();

        if(count($patient_result) > 0){

            $total_patients = round($patient_result[0]['patients'], 2);

        }

        return $total_patients;

    }



    function product_vendor_cost($product_id, $brand_number, $vendor_number){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



		$item_price = 0;

		$sql_condition = '';

		$sql = "Select price, units from ".$db_prefix."product_vendors where product_id='$product_id' and brand_number='$brand_number' and vendor_number='$vendor_number' ORDER by ID DESC limit 1";

        $q = $ci->db->query($sql);

        $result = $q->result_array();

        if (!empty($result)){

            $result = $result[0];

            $vendor_price = $result['price'];

            $vendor_units = $result['units'];

            if($vendor_price == 0 && $vendor_units == 0){
                return $item_price;
            }
            $item_price = $vendor_price / $vendor_units;
            return $item_price;

        }else{

            return $item_price;

        }

	}



    function ivf_labs(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $consultation_result = array();

        $consultation_sql = "SELECT count(*) as investigations FROM ".$db_prefix."patient_investigations where status!='disapproved'";

        $consultation_q = $ci->db->query($consultation_sql);

        $consultation_result = $consultation_q->result_array();

        if(count($consultation_result) > 0){

            $consultation_result = $consultation_result[0]['investigations'];

        }

        return $consultation_result;

    }



    function proportion_oocytes_recovered(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT follicle_number1,follicle_number2,follicle_number3,follicle_number4,follicle_number5,follicle_number6,follicle_number7,follicle_number8,follicle_number9,follicle_number10,follicle_number11,follicle_number12,follicle_number13,follicle_number14,follicle_number15,follicle_number16 FROM `ovulation_induction_protocol`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT oocytes_retreived_no_1,oocytes_retreived_no_2,oocytes_retreived_no_3,oocytes_retreived_no_4,oocytes_retreived_no_5,oocytes_retreived_no_6,oocytes_retreived_no_7,oocytes_retreived_no_8,oocytes_retreived_no_9,oocytes_retreived_no_10,oocytes_retreived_no_11,oocytes_retreived_no_12,oocytes_retreived_no_13,oocytes_retreived_no_14,oocytes_retreived_no_15,oocytes_retreived_no_16,oocytes_retreived_no_17,oocytes_retreived_no_18,oocytes_retreived_no_19,oocytes_retreived_no_20,oocytes_retreived_no_21,oocytes_retreived_no_22,oocytes_retreived_no_23,oocytes_retreived_no_24,oocytes_retreived_no_25 FROM `opu`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) { 

                $m2_sum += array_sum($value);

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += array_sum($value);

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent, 2)."%";

    }



    function proportion_mii_oocytes(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT m2_1,m2_2,m2_3,m2_4,m2_5,m2_6,m2_7,m2_8,m2_9,m2_10,m2_11,m2_12,m2_13,m2_14,m2_15,m2_16,m2_17,m2_18,m2_19,m2_20,m2_21,m2_22,m2_23,m2_24,m2_25 FROM `opu`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT oocytes_retreived_no_1,oocytes_retreived_no_2,oocytes_retreived_no_3,oocytes_retreived_no_4,oocytes_retreived_no_5,oocytes_retreived_no_6,oocytes_retreived_no_7,oocytes_retreived_no_8,oocytes_retreived_no_9,oocytes_retreived_no_10,oocytes_retreived_no_11,oocytes_retreived_no_12,oocytes_retreived_no_13,oocytes_retreived_no_14,oocytes_retreived_no_15,oocytes_retreived_no_16,oocytes_retreived_no_17,oocytes_retreived_no_18,oocytes_retreived_no_19,oocytes_retreived_no_20,oocytes_retreived_no_21,oocytes_retreived_no_22,oocytes_retreived_no_23,oocytes_retreived_no_24,oocytes_retreived_no_25 FROM `opu`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) { 

                $m2_sum += array_sum($value);

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += array_sum($value);

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent, 2)."%";

    }



    function sperm_motility_post(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT progressively_post_wash_,progressively_post_wash_1,progressively_post_wash_2,progressively_post_wash_3,progressively_post_wash_4,progressively_post_wash_5,progressively_post_wash_6,progressively_post_wash_7,progressively_post_wash_8,progressively_post_wash_9,progressively_post_wash_10,progressively_post_wash_11,progressively_post_wash_12,progressively_post_wash_13,progressively_post_wash_14,progressively_post_wash_15,progressively_post_wash_16,progressively_post_wash_17,progressively_post_wash_18,progressively_post_wash_19,progressively_post_wash_20 FROM `sperm_preparation`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT sperm_counted_post_wash_,sperm_counted_post_wash_1,sperm_counted_post_wash_2,sperm_counted_post_wash_3,sperm_counted_post_wash_4,sperm_counted_post_wash_5,sperm_counted_post_wash_6,sperm_counted_post_wash_7,sperm_counted_post_wash_8,sperm_counted_post_wash_9,sperm_counted_post_wash_10,sperm_counted_post_wash_11,sperm_counted_post_wash_12,sperm_counted_post_wash_13,sperm_counted_post_wash_14,sperm_counted_post_wash_15,sperm_counted_post_wash_16,sperm_counted_post_wash_17,sperm_counted_post_wash_18,sperm_counted_post_wash_19,sperm_counted_post_wash_20 FROM `sperm_preparation`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        if(!empty($m2_result) && !empty($oocytes_result)){           

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) { 

                $m2_sum += array_sum($value);

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += array_sum($value);

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function ivf_polyspermy_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT oocyte_2pn FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT ivf_inseminated FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['oocyte_2pn'])?$value['oocyte_2pn']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['ivf_inseminated'])?$value['ivf_inseminated']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function ivf_pn_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT no_1_pn_oocyte FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT ivf_inseminated FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['no_1_pn_oocyte'])?$value['no_1_pn_oocyte']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['ivf_inseminated'])?$value['ivf_inseminated']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function icsi_pn_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT no_1_pn_oocyte FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT icsi_oocytes_injected FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['no_1_pn_oocyte'])?$value['no_1_pn_oocyte']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['icsi_oocytes_injected'])?$value['icsi_oocytes_injected']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function good_blastocyst_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT blastocysts_day5 FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT oocyte_2pn_pb FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['blastocysts_day5'])?$value['blastocysts_day5']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['oocyte_2pn_pb'])?$value['oocyte_2pn_pb']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum/$oocytes_sum);

            }

        }

        return round($percent,2);

    }



    function icsi_damage_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT icsi_degenerated FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT icsi_injected FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['icsi_degenerated'])?$value['icsi_degenerated']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['icsi_injected'])?$value['icsi_injected']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function icsi_normal_fertilization_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT oocyte_2pn_pb FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT icsi_oocytes_injected FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['oocyte_2pn_pb'])?$value['oocyte_2pn_pb']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['icsi_oocytes_injected'])?$value['icsi_oocytes_injected']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function ivf_normal_fertilization_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT oocyte_2pn_pb FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT ivf_inseminated FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['oocyte_2pn_pb'])?$value['oocyte_2pn_pb']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['ivf_inseminated'])?$value['ivf_inseminated']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function cleavage_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT cleaved_embryos FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT pb2,pb2_1,pb2_3,pb2_4,pb2_5,pb2_6,pb2_7,pb2_8,pb2_9,pb2_10,pb2_11,pb2_12,pb2_13,pb2_14,pb2_15,pb2_16,pb2_17,pb2_18,pb2_19,pb2_20 FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

               // $m2_sum += !empty($value['cleaved_embryos'])?$value['cleaved_embryos']:0;
				
				$m2_sum += !empty($value['cleaved_embryos']) ? floatval($value['cleaved_embryos']) : 0;


            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += array_sum($value);

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function day_two_embryo_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT cell_embryos_day2 FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT oocyte_2pn FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['cell_embryos_day2'])? floatval($value['cell_embryos_day2']):0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['oocyte_2pn'])?$value['oocyte_2pn']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function day_three_embryo_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT cell_embryos_day3 FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT oocyte_2pn FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['cell_embryos_day3'])?$value['cell_embryos_day3']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['oocyte_2pn'])?$value['oocyte_2pn']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function blastocyst_development_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT blastocysts_day5 FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT oocyte_2pn FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['blastocysts_day5'])?$value['blastocysts_day5']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['oocyte_2pn'])?$value['oocyte_2pn']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function successful_biopsy_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT pgt_a_biopsy_total,pgt_m_biopsy_total,pgt_sr_biopsy_total  FROM `pgt_embryo`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT pgt_a_biopsy_perform,pgt_m_biopsy_perform,pgt_sr_biopsy_perform FROM `pgt_embryo`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += array_sum($value);

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += array_sum($value);

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function blastocyst_cryosurvival_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT embryos_intact  FROM `embryo_record`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT warmed_blasts FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['embryos_intact'])?$value['embryos_intact']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['warmed_blasts'])?$value['warmed_blasts']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }

    

    function cleavage_implantation_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT cardiac_activity_no  FROM `post_embryo_transfer`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT pgt_a_5,pgt_a1_5,pgt_a2_5,pgt_a3_5,pgt_a4_5,pgt_a5_5,pgt_a65,pgt_a75,pgt_a85,pgt_a95,pgt_a105,pgt_a115,pgt_a125,pgt_a135,pgt_a145,pgt_a155,pgt_a165,pgt_a175,pgt_a185,pgt_a195,pgt_a205,pgt_a215,pgt_a225,pgt_a235,pgt_a245,pgt_a255,pgt_m_5,pgt_m1_5,pgt_m2_5,pgt_m3_5,pgt_m4_5,pgt_m5_5,pgt_m65,pgt_m75,pgt_m85,pgt_m95,pgt_m105,pgt_m115,pgt_m125,pgt_m135,pgt_m145,pgt_m155,pgt_m165,pgt_m175,pgt_m185,pgt_m195,pgt_m205,pgt_m215,pgt_m225,pgt_m235,pgt_m245,pgt_m255,pgt_sr_5,pgt_sr1_5,pgt_sr2_5,pgt_sr3_5,pgt_sr4_5,pgt_sr5_5,pgt_sr65,pgt_sr75,pgt_sr85,pgt_sr95,pgt_sr105,pgt_sr115,pgt_sr125,pgt_sr135,pgt_sr145,pgt_sr155,pgt_sr165,pgt_sr175,pgt_sr185,pgt_sr195,pgt_sr205,pgt_sr215,pgt_sr225,pgt_sr235,pgt_sr245,pgt_sr255 FROM `pgt_embryo`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['cardiac_activity_no'])?$value['cardiac_activity_no']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += array_sum($value);

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function blastocyst_implantation_rate(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];



        $percent = 0;

        $m2_sql = "SELECT cardiac_activity_no  FROM `post_embryo_transfer`";

        $m2_query = $ci->db->query($m2_sql);

        $m2_result = $m2_query->result_array();



        $oocytes_sql = "SELECT warmed_blasts FROM `embryo_record`";

        $oocytes_query = $ci->db->query($oocytes_sql);

        $oocytes_result = $oocytes_query->result_array();

        

        if(!empty($m2_result) && !empty($oocytes_result)){

            $m2_sum = $oocytes_sum = 0;

            foreach ($m2_result as $key => $value) {

                $m2_sum += !empty($value['cardiac_activity_no'])?$value['cardiac_activity_no']:0;

            }

            foreach ($oocytes_result as $key => $value) {

                $oocytes_sum += !empty($value['warmed_blasts'])?$value['warmed_blasts']:0;

            }

            if($m2_sum > 0 && $oocytes_sum > 0){

                $percent = ($m2_sum*100)/$oocytes_sum;

            }

        }

        return round($percent,2);

    }



    function total_appointment(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as appointments FROM ".$db_prefix."appointments";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['appointments'];

        }

        return $count;

    }

    

    function total_consultation(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as appointments FROM ".$db_prefix."appointments where billed='1'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['appointments'];

        }

        return $count;

    }



    function patient_in_queue(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as appointments FROM ".$db_prefix."appointments where billed='0' and (status!='cancelled' OR status!='no_show')";

        

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['appointments'];

        }

        return $count;

    }



    function appointment_cancelled(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as appointments FROM ".$db_prefix."appointments where status='cancelled'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['appointments'];

        }

        return $count;

    }



    function no_show_patient(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as appointments FROM ".$db_prefix."appointments where status='no_show'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['appointments'];

        }

        return $count;

    }



    function rescheduled_patient(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as appointments FROM ".$db_prefix."appointments where status='rescheduled'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['appointments'];

        }

        return $count;

    }



    function investigation_advised(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as investations FROM ".$db_prefix."doctor_consultation where investation_suggestion='1'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['investations'];

        }

        return $count;

    }



    function investigation_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as investations FROM ".$db_prefix."doctor_consultation where investigation_billed='1'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['investations'];

        }

        return $count;

    }



    function total_procedure(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as procedures FROM ".$db_prefix."patient_procedure where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['procedures'];

        }

        return $count;

    }



    function total_andrology_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as procedures FROM andrology where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['procedures'];

        }

        return $count;

    }



    function semen_preparation_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as procedures FROM sperm_preparation where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['procedures'];

        }

        return $count;

    }

    

    function embryo_record_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as procedures FROM embryo_record where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['procedures'];

        }

        return $count;

    }



    function pgt_biopsy_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as procedures FROM pgt_embryo where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['procedures'];

        }

        return $count;

    }



    function hysterpscopy_diagnostic(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as laparoscopy_hysteroscopy FROM laparoscopy_hysteroscopy where cervix!='' OR vagina!='' OR pv!='' OR uterocervical_length!='' OR ectocervical_canal!='' OR endometrial_cavity!='' OR bilateral_ostia!=''";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['laparoscopy_hysteroscopy'];

        }

        return $count;

    }



    function laparoscopy_diagnostic(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as laparoscopy_hysteroscopy FROM laparoscopy_hysteroscopy where uterus_visualized!='' OR bilateral_tubes!='' OR bilateral_ovaries!='' OR uterus!='' OR tubes!='' OR ovaries!='' OR pod!=''";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['laparoscopy_hysteroscopy'];

        }

        return $count;

    }



    function hysterpscopy_operative(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as laparoscopy_hysteroscopy FROM laparoscopy_hysteroscopy where uterine_cavity2!='' OR ostia2!='' OR tb2!='' OR hpe2!='' OR operative_any_other_finding2!=''";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['laparoscopy_hysteroscopy'];

        }

        return $count;

    }



    function laparoscopy_operative(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as laparoscopy_hysteroscopy FROM laparoscopy_hysteroscopy where operative_uterus!='' OR operative_tubes!='' OR operative_ovaries!='' OR operative_pod!='' OR operative_liver!='' OR operative_Chromopertubation!='' OR operative_any_other_findings!=''";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['laparoscopy_hysteroscopy'];

        }

        return $count;

    }



    function ovarian_cyst_aspiration(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as ovarian_cyst_aspiration FROM ovarian_cyst_aspiration where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['ovarian_cyst_aspiration'];

        }

        return $count;

    }



    function ovarian_prp(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as ovarian_prp FROM ovarian_prp where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['ovarian_prp'];

        }

        return $count;

    }



    function tese_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as 	tese FROM hms_tese where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['tese'];

        }

        return $count;

    }



    function opu_done(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as opu FROM opu where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['opu'];

        }

        return $count;

    }

    

    function baseline_tvs(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as baseline_transvaginal_scan FROM baseline_transvaginal_scan where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['baseline_transvaginal_scan'];

        }

        return $count;

    }

    

    function pap_smear(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as pap_smear FROM pap_smear where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['pap_smear'];

        }

        return $count;

    }

    

    function endometrial_biopsy(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as endometrial_biopsy FROM endometrial_biopsy where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['endometrial_biopsy'];

        }

        return $count;

    }

    

    function endometrial_scratching(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as endometrial_scratching FROM endometrial_scratching where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['endometrial_scratching'];

        }

        return $count;

    }

    

    function ovulation_induction(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as ovulation_induction_protocol FROM ovulation_induction_protocol where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['ovulation_induction_protocol'];

        }

        return $count;

    }

    

    function natural_cyle(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as natural_cycle_protocol FROM natural_cycle_protocol where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['natural_cycle_protocol'];

        }

        return $count;

    }

    

    function uterine_prp(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as uterine_prp FROM uterine_prp where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['uterine_prp'];

        }

        return $count;

    }



    function mock_et(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as mock_embryo_transfer FROM mock_embryo_transfer where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['mock_embryo_transfer'];

        }

        return $count;

    }



    function testicular_prp(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as testicular_prp FROM testicular_prp where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['testicular_prp'];

        }

        return $count;

    }

    

    function last_updated_user($updated_type, $updated_by){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        if($updated_type == "doctor"){

            $updated_table = "doctors";

        }else{

            $updated_table = "employees";

        }

        

        $result = array();

        $count = "";

        $sql = "SELECT name FROM ".$db_prefix.$updated_table." where username='$updated_by'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            if(isset($result[0]['name']) && !empty($result[0]['name'])){

                $name = $result[0]['name'];

                $count = $updated_type." (".$name.")";

            }

        }

        return $count;

    }



    function fnac_testes(){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $result = array();

        $count = 0;

        $sql = "SELECT count(*) as fnac FROM hms_fnac where status!='disapproved'";

        $query = $ci->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0){

            $count = $result[0]['fnac'];

        }

        return $count;

    }

    



/** dashboard widgets */



function doctor_details($doctor_id){

	$ci= &get_instance();

    $ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];

    

    $result = array();

    $sql = "Select * from ".$db_prefix."doctors where ID='".$doctor_id."'";

    $q = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0){

        $result = $result[0];

        return $result;

    }else{

        return $result;

    }

}



function get_biller_details($biller_id){

    $ci= &get_instance();

    $ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];

    

    $result = array();

    $sql = "Select * from ".$db_prefix."employees where employee_number='".$biller_id."'";

    $q = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0){

        $result = $result[0];

        return $result;

    }else{

        return $result;

    }

}



function doctor_appointment($appointment_id){

	$ci= &get_instance();

    $ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];

    

    $result = array();

    $sql = "Select * from ".$db_prefix."appointments where ID='".$appointment_id."'";

    $q = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0){

        $result = $result[0];

        return $result;

    }else{

        return $result;

    }

}



function run_form_query($query){

    $ci= &get_instance();

    $ci->load->database();

    $ci->db->query($query);

    

    if($ci->db->insert_id() > 0){

        return true;

    }else if($ci->db->affected_rows() > 0)

    {

        return true;

    }else{

        return false;

    }

}



function run_select_query($query){
    $ci= &get_instance();
    $ci->load->database();
    if(empty($query) || trim($query) == ''){
        return array();
    }
    try {
        $query_result = $ci->db->query($query);
        if($query_result === FALSE){
            return array();
        }
        $result = $query_result->result_array();
        if(!empty($result)){
            return $result[0];
        }else{
            return array();
        }
    } catch (Exception $e) {
        return array();
    }

}



function get_notification(){

	$ci= &get_instance();

    $ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];



    $sql = "Select * from ".$db_prefix."stocks where quantity <= safety_stock";

    $q = $ci->db->query($sql);

    $result = $q->result_array();

	

    $html = '';

    if(count($result) > 0){

        foreach($result as $key => $val){

        $exist = check_existancy($val['item_number']);

    		if($exist == true){

            $sql = 'INSERT INTO hms_orders(catagory, product_id, product_name, order_quantity, create_date) VALUES("'.$val['company'].'", "'.$val['item_number'].'", "'.$val['item_name'].'", "'.$val['order_qty'].'", "'.date("Y-m-d H:i:s").'")'; 

            $ci->db->query($sql);

            $html .='<li>This <b>'.$val['item_number'].'</b> item number. Name <b>'.$val['item_name'].'</b> reach <b>'.$val['quantity'].'</b> quantity</li>';

            }

    	}

    }

    return $html;

}



function get_center_notification(){

	$ci= &get_instance();

    $ci->load->database(); 

	$db_prefix = $ci->config->config['db_prefix'];

	
    // Determine active session (stock manager, billing manager, or counselor)
    $active_session = null;
    foreach (array('logged_stock_manager','logged_billing_manager','logged_counselor') as $session_key) {
        if (isset($_SESSION[$session_key]) && is_array($_SESSION[$session_key])) {
            $active_session = $_SESSION[$session_key];
            break;
        }
    }

    $center_id = isset($active_session['center']) ? $active_session['center'] : 0;
    $employee_number = isset($active_session['employee_number']) ? $active_session['employee_number'] : '';

    // Build query safely based on available identifiers
    if (!empty($employee_number)) {
        $sql = "Select * from ".$db_prefix."center_stocks where quantity <= safety_stock AND employee_number='".$employee_number."'";
    } else {
        $sql = "Select * from ".$db_prefix."center_stocks where quantity <= safety_stock AND center_number='".intval($center_id)."'";
    }
    $q = $ci->db->query($sql);

    $result = $q->result_array();

    // Reuse derived identifiers; ensure defined
    $center_id = isset($center_id) ? $center_id : 0;
    $employee_number = isset($employee_number) && $employee_number !== '' ? $employee_number : '0';
	$date = date("Y-m-d H:i:s");

		

    $html = '';

	$count = 0;

    if(count($result) > 0){

        foreach($result as $key => $val){

            $exist = check_existancy($center_id, $db_prefix.'center_orders', $val['item_number'], $val['batch_number']);

            if($exist == 0){ 

                if($center_id != 0){

                    $sql = "INSERT INTO ".$db_prefix."center_orders (ID, item_number, product_id, order_quantity, create_date, update_date, center_number, employee_number, batch_number, status, d_status) VALUES('".$val['ID']."','".$val['item_number']."', '".$val['item_number']."', '".$val['order_qty']."', '".$date."', '".$date."', '".$center_id."', '".$employee_number."','".$val['batch_number']."', '0', '0')";

                    $insert = $ci->db->query($sql);

                }

            }

		}

    }

	

	$check_i = "Select item_number from ".$db_prefix."center_orders where center_number='".$center_id."' AND status='0'";

	$q_i = $ci->db->query($check_i);

	$res_i = $q_i->result_array();

	if(count($res_i) > 0){

		foreach($res_i as $ky => $vl){					 

			$html .='<li><a href="'.base_url().'orders/center_order"> Order placed for item ('.$vl['item_number'].').</a> </li>';

			$count++;

		}

	}

	$response = array();

	$response = array('count' => $count, 'html' => $html);

    return $response;

}



function check_existancy($center_id, $table, $item)

{

    $ci=& get_instance();

    $ci->load->database();

    $check = "Select item_number from ".$table." where center_number='".$center_id."' AND item_number='".$item."' AND status='0' AND replaced='0'";

    $q = $ci->db->query($check);

    $res = $q->result_array();

    if(count($res) > 0)

    {

        return 1;

    }

    else

    {

	    return 0;

    }

}



function get_admin_notification(){/*

	$ci= &get_instance();

    $ci->load->database(); 

	$db_prefix = $ci->config->config['db_prefix'];

	

    $sql = "Select * from ".$db_prefix."stocks where quantity <= safety_stock";

    $q = $ci->db->query($sql);

    $result = $q->result_array();

	

	$date = date("Y-m-d H:i:s");

		

    $html = '';

	$count = 0;

    if(count($result) > 0){

        foreach($result as $key => $val){

				$exist = check_admin_existancy($db_prefix.'orders', $val['item_number']);

				if($exist == 0){ 

					$sql = "INSERT INTO ".$db_prefix."orders (item_number, order_quantity, create_date, update_date, status) VALUES('".$val['item_number']."', '".$val['order_qty']."', '".$date."', '".$date."', '0')";

					$insert = $ci->db->query($sql);

				}

		}

    }

	

	$check_i = "Select item_number from ".$db_prefix."orders where status='0'";

	$q_i = $ci->db->query($check_i);

	$res_i = $q_i->result_array();

	if(count($res_i) > 0){

		foreach($res_i as $ky => $vl){					 

			$html .='<li><a href="'.base_url().'/orders/my_orders">Item ('.$vl['item_number'].') reached safety stock limit.</a> </li>';

			$count++;

		}

	}

	$response = array();

	$response = array('count' => $count, 'html' => $html);

    return $response;

	*/

	$response = array('count' => 0, 'html' => '');

    return $response;

}



function check_admin_existancy($table, $item)

{

    $ci=& get_instance();

    $ci->load->database();

    $check = "Select item_number from ".$table." where item_number='".$item."' AND status='0'";

    $q = $ci->db->query($check);

    $res = $q->result_array();

    if(count($res) > 0)

    {

        return 1;

    }

    else

    {

	    return 0;

    }

}



//Unique ID

function getGUID(){
    $date = date("Y-m-d H:i:s");
    $date = strtotime($date).rand(0, 9999);
    return $date;
}

//Unique ID



//Receipt Unique ID

function getReceiptGUID(){
    $Ymd = date('Ymd');
    $max = select_receipt_last();
    if (strpos($max, $Ymd) === 0) {
        ++$max;
    } else {
        $max = rand(0, 9999).$Ymd . '001';
    }
    return $max;
}

function insert_receipt_log($receipt_number){
    $ci= &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    $sql = 'INSERT INTO '.$db_prefix.'billing_receipt(receipt_number, date) VALUES("'.$receipt_number.'", "'.date("Y-m-d H:i:s").'")'; 
    $ci->db->query($sql);
    return 1;
}

function check_billing_receipt($receipt_number){
    $ci= &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    $recp_sql = "SELECT receipt_number FROM ".$db_prefix."billing_receipt where receipt_number='$receipt_number' order by ID desc limit 1"; 
    $recp_q = $ci->db->query($recp_sql);
    $recp_result = $recp_q->result_array();

    if(!empty($recp_result)){
        return rand(1,99999).$receipt_number;
    }else{
        return $receipt_number;
    }
}

function select_receipt_last(){
    $ci= &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    $receipt_number = "";
    $sql = "SELECT * FROM ".$db_prefix."billing_receipt order by ID desc limit 1"; 
    $q = $ci->db->query($sql);
    $result = $q->result_array();
    if(!empty($result)){
        $receipt_number = $result[0]['receipt_number'];

        $recp_sql = "SELECT receipt_number FROM ".$db_prefix."billing_receipt where receipt_number='$receipt_number' order by ID desc limit 1"; 
        $recp_q = $ci->db->query($recp_sql);
        $recp_result = $recp_q->result_array();
        if(!empty($recp_result)){
            $max_sql = "SELECT receipt_number FROM ".$db_prefix."billing_receipt WHERE ID = (SELECT MAX(ID) FROM ".$db_prefix."billing_receipt)";
            $max_q = $ci->db->query($max_sql);
            $max_result = $max_q->result_array();
            if(!empty($max_result)){
                return $max_result[0]['receipt_number'];
            }
        }else{
            return $receipt_number;
        }
    }
    return $receipt_number;
}

//Patient ID
function getiic(){
    $date = date("Y-m-d H:i:s");
    $maxpatient_id = maxpatient_id();
    return (strtotime($date)+$maxpatient_id);
}

function maxpatient_id(){
	$ci= &get_instance();
    $ci->load->database();
	$db_prefix = $ci->config->config['db_prefix'];
	$max = 1;
    $sql = "SELECT count(ID) as patient_id FROM ".$db_prefix."patients";
    $q   = $ci->db->query($sql);
    $result = $q->result_array();
    if(!empty($result)){
        $max = $result[0]['patient_id'];
        $max = $max + 1;
    }
    return $max;

}

//Patient ID



function get_patient_detail($id)
{
	$ci= &get_instance();
    $ci->load->database();

    $sql = "SELECT * FROM hms_patients WHERE patient_id ='$id'";
    // echo $sql."</br>";

    $q   = $ci->db->query($sql);
    $result = $q->result_array();
    if(count($result) > 0)
    {
        return $result[0];
    }
    
    return $result;
}



/***** NOTIFICATIONS ********/

function notifications($role){

	if($role == 'billing'){

		$notice = $this->billing_notification();

	}

	return $notice;

}



function billing_notification($enter){

	$ci= &get_instance();

    $ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];

	$ci->load->model('patients_model');

	

	$patient_list =  $ci->patients_model->get_patients($enter);

	$notice = '';

	

	//check patient_profiles

	if(count($patient_list) > 0)

    {

		foreach($patient_list as $val){

			$profile = patient_profile($val['patient_id']);

			if($profile == true){

				$notice .= '<li><a href="'.base_url().'patients/edit/'.$val['patient_id'].'">Complete '.$val['wife_name'].'('.$val['patient_id'].') profile.</a></li><li class="divider"></li>';				

			}

		}

	}

	return $notice;

}



/***** NOTIFICATIONS ********/



function patient_profile($patient_id)

{

	$ci= &get_instance();

    $ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];

	$complete = false;

	

    $sql = "SELECT * FROM ".$db_prefix."patients WHERE patient_id  = '$patient_id'";

    $q   = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0)

    {

		$result = $result[0];

		$points = 0;

		$nationality = $result['nationality'];

		

		$not_field = array("reference_from", "patient_source");

		if($nationality == "indian"){

		    $not_field = array("reference_from", "patient_source", "wife_passport", "wife_passport_number", "husband_passport_number", "husband_passport","husband_pan_number", "husband_pan_card", "wife_pan_number", "wife_pan_card");

		}else{

		    $not_field = array("reference_from", "patient_source", "husband_pan_number", "husband_pan_card", "wife_pan_number", "wife_pan_card",

		    "wife_adhar_number", "wife_adhar_card", "wife_back_adhar_card", "husband_adhar_number", "husband_adhar_card", "husband_back_adhar_card");

		}

		

		foreach($result as $key => $val){

			if(!in_array($key, $not_field)){

				if($result[$key] == ''){

					$points+=1;

				}

			}

		}

		if($points > 0){;

			$complete = true;

		}

    }

    return $complete;

}



function get_employee_detail($id)

{

	$ci= &get_instance();

    $ci->load->database();

	

    $sql = "SELECT * FROM hms_employees WHERE username  = '".$id."'";

    $q   = $ci->db->query($sql);

    $result = $q->result_array();    

    if(count($result) > 0)

    {

		    return $result[0];    	

    }

    return $result;

}



function employee_detail_number($biller_id)

{

	$ci= &get_instance();

    $ci->load->database();

	

    $sql = "SELECT * FROM hms_employees WHERE employee_number  = '".$biller_id."'";

    $q   = $ci->db->query($sql);

    $result = $q->result_array();    

    if(count($result) > 0)

    {

		    return $result[0];    	

    }

    return $result;

}



function get_prodecure_forms($procedure){

    $ci= &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    

    $sql = "SELECT * FROM  ".$db_prefix."form_relationship WHERE procedure_id  = '".$procedure."'";

    $q   = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0)

    {

	    return $result;

    }

    return $result;

}

function get_procedure_package_id($procedure){

    $ci= &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $sql = "SELECT * FROM  ".$db_prefix."procedure_package WHERE procedure_id  = '".$procedure."'";

    $q   = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0)

    {

	    return $result;

    }

    return $result;

}



function get_prodecure_form($form_id){

    $ci= &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    

    $sql = "SELECT * FROM  ".$db_prefix."procedure_forms WHERE ID  = '".$form_id."'";

    $q   = $ci->db->query($sql);

    $result = $q->result_array();    

    if(count($result) > 0)

    {

	    return $result[0];

    }

    return $result;

}



function get_medicine_name($item_number)

{

	$ci= &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



	$sql = "SELECT * FROM ".$db_prefix."stocks WHERE item_number = '".$item_number."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();



	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$item_name = $value['item_name'];

		}

	}

	if(!empty($item_name))

	return $item_name;

	else

	return '-';

}



function get_investigation_name($id)

{

	$ci= &get_instance();

	$ci->load->database();



	$sql = "SELECT * FROM hms_investigation WHERE ID = '".$id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();



	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$investigationname = $value['investigation'];

		}

	}

	if(!empty($investigationname))

	return $investigationname;

	else

	return '-';

}

function get_master_investigation_name($id)

{

	$ci= &get_instance();

	$ci->load->database();



	$sql = "SELECT * FROM hms_master_investigations WHERE ID = '".$id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();



	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$investigationname = $value['investigation_name'];

		}

	}

	if(!empty($investigationname))

	return $investigationname;

	else

	return '-';

}



function get_center_name($center_id)
{
	$ci = &get_instance();
	$ci->load->database();
	$sql = "SELECT * FROM hms_centers WHERE center_number = '".$center_id."'";
	$q = $ci->db->query($sql);
	$result = $q->result_array();
	$center_name = ''; // Initialize the variable
	if(count($result) > 0)
	{
		foreach($result as $key => $value)
		{
			$center_name = $value['center_name'];
		}
	}	
	return $center_name;
}



function get_stock_data($item_id)

{

	$ci = &get_instance();

	$ci->load->database();

	$sql = "SELECT * FROM hms_stocks WHERE item_number = '".$item_id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$item_name = $value['item_name'];

		}

	}	

	return $item_name;

} 



function get_procedure_data($id)

{

    $procedure_name = "";

	$ci = &get_instance();

	$ci->load->database();

	$sql = "SELECT * FROM hms_procedures WHERE ID = '".$id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$procedure_name = $value['procedure_name'];

		}

	}

	return $procedure_name;

}


function get_package_data($id)

{

    $package_name = "";

	$ci = &get_instance();

	$ci->load->database();

	$sql = "SELECT * FROM hms_procedure_package WHERE procedure_id = '".$id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$package_name = $value['package_name'];

		}

	}

	return $package_name;

}



function check_edit_appointment($appointment_id){

	$ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



    $result = array();

    $sql = "SELECT edit_mode,final_mode,disapproval_reason FROM ".$db_prefix."doctor_consultation WHERE appointment_id = '".$appointment_id."' order by id desc limit 1";

   

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(!empty($result))

	{

		return $result[0];

    }

    return $result;

}



function patient_medical_info_data($appointment_id, $patient_id){

    $ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



    $result = array();

    $sql = "SELECT * FROM ".$db_prefix."patient_medical_info WHERE appointment_id = '".$appointment_id."' AND patient_id='".$patient_id."' order by ID desc limit 1";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(!empty($result))

	{

		return $result[0];

    }

    return $result;

}



function patient_doctor_consultation_data($appointment_id, $patient_id){

    $ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



    $result = array();

    $sql = "SELECT * FROM ".$db_prefix."doctor_consultation WHERE appointment_id = '".$appointment_id."' AND patient_id='".$patient_id."' order by ID desc limit 1";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(!empty($result))

	{

		return $result[0];

    }

    return $result;

}



function get_procedure($id)

{

	$ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



	$sql = "SELECT * FROM ".$db_prefix."patient_procedure WHERE ID = '".$id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

        return $result[0];

	}

	return $result;

}



function get_patient_by_number($phone_number)

{

	$ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



	$sql = "SELECT patient_id FROM ".$db_prefix."patients WHERE wife_phone = '".$phone_number."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

        return $result[0]['patient_id'];

	}

	return $result;

}



function get_doctor_centre($doctor_id)

{

	$ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $center_id  = 0;

	$sql = "SELECT center_id FROM ".$db_prefix."doctors WHERE ID = '".$doctor_id."'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

        $center_id =  $result[0]['center_id'];

        $primary_sql = "SELECT center_id, is_primary FROM ".$db_prefix."doctors WHERE center_id = '".$center_id."' AND is_primary='1'";

        $primary_q = $ci->db->query($primary_sql);

        $primary_result = $primary_q->result_array();

	

        if(!empty($primary_result)){

            return $primary_result[0]['center_id'];

        }else{

            return 0;

        }

	}

	return 0;

}



function procedure_form_data($form_name, $patient_id, $receipt_number){

    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



	$sql = "SELECT * FROM ".$form_name." WHERE receipt_number = '".$receipt_number."' and patient_id='".$patient_id."' order by id desc limit 1";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

        return $result[0];

	}

	return $result;

}



function get_converstion_rate()

{

	$conversion_rate = '72';

	$ci = &get_instance();

	$ci->load->database();

	$sql = "SELECT * FROM hms_options WHERE ID = '1'";

	$q = $ci->db->query($sql);

	$result = $q->result_array();

	if(count($result) > 0)

	{

		foreach($result as $key => $value)

		{

			$conversion_rate = $value['conversion_rate'];

		}

	}

	return $conversion_rate;

}



function check_billing_status($patient_id, $receipt_number, $type){

    $appointment_id = 0;
    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $result = array();



    if($type == "investigation"){

        $sql = "Select * from ".$db_prefix."patient_investigations where patient_id='".$patient_id."' and receipt_number='".$receipt_number."'";

        $qry = $ci->db->query($sql);

        $result = $qry->result_array();

        if(!empty($result) && isset($result[0]['appointment_id'])){
            $appointment_id = $result[0]['appointment_id'];
        } else {
            return "<h4 class='error'>No investigation record found</h4>";
        }   

    }else if($type == "procedure"){

        $sql = "Select * from ".$db_prefix."patient_procedure where patient_id='".$patient_id."' and receipt_number='".$receipt_number."'";

        $qry = $ci->db->query($sql);

        $result = $qry->result_array();

        if(!empty($result) && isset($result[0]['appointment_id'])){
            $appointment_id = $result[0]['appointment_id'];
        } else {
            return "<h4 class='error'>No procedure record found</h4>";
        }

    }



    $appoint_sql = "Select * from ".$db_prefix."doctor_consultation where appointment_id='".$appointment_id."'";

    $appoint_qry = $ci->db->query($appoint_sql);

    $appoint_result = $appoint_qry->result_array();

    if(empty($appoint_result) || !isset($appoint_result[0])){
        return "<h4 class='error'>No consultation record found</h4>";
    }
    
    $appoint_result = $appoint_result[0];

    //var_dump($appoint_result);die;

    $response = $pending = ""; 



    if(isset($appoint_result['medicine_suggestion']) && $appoint_result['medicine_suggestion'] == 1 && isset($appoint_result['medicine_billed']) && $appoint_result['medicine_billed'] == 0){

        $output = false;

        $response = "<h4 class='error'>Another billing pending. <a href='".base_url()."after-consultation'>Go to billing</a></h4>"; 

        return $response;

    }else{

        $output = true;

    }

    if(isset($appoint_result['investation_suggestion']) && $appoint_result['investation_suggestion'] == 1 && isset($appoint_result['investigation_billed']) && $appoint_result['investigation_billed'] == 0){

        $output = false;

        $response = "<h4 class='error'>Another billing pending. <a href='".base_url()."after-consultation'>Go to billing</a></h4>"; 

        return $response;

    }else{

        $output = true;      

    }

    if(isset($appoint_result['procedure_suggestion']) && $appoint_result['procedure_suggestion'] == 1 && isset($appoint_result['procedure_billed']) && $appoint_result['procedure_billed'] == 0){

        $output = false;

        $response = "<h4 class='error'>Another billing pending. <a href='".base_url()."after-consultation'>Go to billing</a></h4>"; 

        return $response;

    }else{

        $output = true;

    }

    return $response;

}



function check_form_data($patient_id, $receipt_number, $form_area){

    

    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $result = array();



    $sql = "Select * from ".$form_area." where patient_id='".$patient_id."' and receipt_number='".$receipt_number."' and status!='disapproved' order by id desc limit 1";

    

    $qry = $ci->db->query($sql);

    $result = $qry->result_array();

    if(!empty($result)){

        return $result;

    }else{

        return $result;

    }

    

}



function have_form($procedure_id, $type){

    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $condition = "";

    $result = array();

    $sql = "Select * from ".$db_prefix."form_relationship where  procedure_id='".$procedure_id."' ".$condition."";

    $q = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0){

        return true;

    }else{

        return false;

    }

}



function have_procedure_assign($procedure_id, $type){

    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $return = false;

    $result = array();

    $sql = "SELECT * FROM `".$db_prefix."form_relationship` RIGHT JOIN `".$db_prefix."procedure_forms` ON ".$db_prefix."form_relationship.form_id = ".$db_prefix."procedure_forms.ID WHERE ".$db_prefix."form_relationship.procedure_id = '".$procedure_id."' and ".$db_prefix."procedure_forms.form_for='".$type."'";

    

    $q = $ci->db->query($sql);

    $result = $q->result_array();

    if(count($result) > 0){

        return true;

    }else{

       return $return;

    }

}



function patient_balance($patient_id){
		$ci = &get_instance();
		$ci->load->database();
		$db_prefix = $ci->config->config['db_prefix'];
		$patient_sql = "Select * from ".$db_prefix."patients where  patient_id='".$patient_id."'";
        $patient_q = $ci->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$patient_id = $patient_result[0]['patient_id'];

		$consultation_result = $procedure_result = $procedure_result2 = $investigation_result = $medicine_result = $refund_amount_result = $remaining_billing = $bill_arr = $bill_total = array();
		$procedure_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where status NOT IN ('disapproved', 'cancel') and patient_id='".$patient_id."'";
        $procedure_q = $ci->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();

        $consultation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where status NOT IN ('disapproved', 'cancel') and patient_id='".$patient_id."'";
        $consultation_q = $ci->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();

		$investigation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where status NOT IN ('disapproved', 'cancel') and patient_id='".$patient_id."'";
        $investigation_q = $ci->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
		
		//$medicine_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where status NOT IN ('disapproved', 'cancel') and patient_id='".$patient_id."'";
        //$medicine_q = $ci->db->query($medicine_sql);
        //$medicine_result = $medicine_q->result_array();

		$total = 0;

        $done_sql = "Select sum(payment_done) as payment_done from ".$db_prefix."patient_payments where patient_id='".$patient_id."' AND status NOT IN ('2', '3')";
		$done_q = $ci->db->query($done_sql);
		$done_result = $done_q->result_array();

        $refund_amount_sql = "Select sum(consultation_fee) as consultation_fee, sum(usg_scan_charge) as usg_scan_charge, sum(consumable_charges) as consumable_charges, sum(file_registation_charge) as file_registation_charge, sum(refund_amount) as refund_amount from ".$db_prefix."refund_amount where patient_id='".$patient_id."' AND status='1'";
		$refund_amount_q = $ci->db->query($refund_amount_sql);
		$refund_amount_result = $refund_amount_q->result_array();



    	foreach($consultation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		    $bill_total[] = $val['fees']; 
		}


		$total = 0;
		foreach($investigation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		    $bill_total[] = $val['fees']; 
		}

		$total = 0;
		foreach($procedure_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		    $bill_total[] = $val['fees'];
		}

        $total = 0;
		foreach($procedure_result2 as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}
		
		//$total = 0;
		//foreach($medicine_result as $key => $val){
		//	$bill_arr[] = $val['payment_done'];
		//    $bill_total[] = $val['fees'];
		//}
		
		foreach($done_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}

        foreach($refund_amount_result as $key => $val){
			$bill_arr[] = $val['consultation_fee'];
            $bill_arr[] = $val['usg_scan_charge'];
            $bill_arr[] = $val['consumable_charges'];
            $bill_arr[] = $val['file_registation_charge'];
            $bill_arr[] = $val['refund_amount'];
		}


        $fee_total = $paid_total = 0;
		$fee_total = array_sum($bill_total);
		$paid_total = array_sum($bill_arr);
		
		$balance = ($fee_total - $paid_total);
		
		return round($balance,2);
}


function check_patient_investigation($patient_id, $receipt_number){

    $ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $investigation_data = array();



    $patient_sql = "Select * from ".$db_prefix."patient_investigations where  patient_id='".$patient_id."' and receipt_number='".$receipt_number."' limit 1";

	$patient_q = $ci->db->query($patient_sql);

    $patient_result = $patient_q->result_array();

    if(!empty($patient_result)){

        $patient_investigations = $patient_result[0]['investigations'];

        if(!empty($patient_investigations)){

            $patient_investigations = unserialize($patient_investigations);

            $investigation_list = array();

            $male_html = $female_html = "";

            $male_investd = $female_investd = array();

            if(!empty($patient_investigations['male_investigation'])){

                foreach($patient_investigations['male_investigation'] as $key => $vals){

                    $investigation_list['male_invest'][] = $vals['male_investigation_name'];

                    $investigationname = get_investigation_name($vals['male_investigation_name']);

                    $male_html .= strtolower($investigationname).", ";



                    $report = check_investigation_report($vals['male_investigation_name'], 'male', $receipt_number, $patient_id);

                    //var_dump($report);die;

                    if($report == 0){

                        $male_investd[] = $vals['male_investigation_name'];

                    }

                    if($report['status'] == "pending"){

                        $male_investd[] = $vals['male_investigation_name'];

                    }

                }

                $male_html = substr($male_html , 0, -2);

            }

            if(!empty($patient_investigations['female_investigation'])){

                foreach($patient_investigations['female_investigation'] as $key => $vals){

                    $investigation_list['female_invest'][] = $vals['female_investigation_name'];

                    $investigationname = get_investigation_name($vals['female_investigation_name']);

                    

                    $female_html .= strtolower($investigationname).", ";



                    $report = check_investigation_report($vals['female_investigation_name'], 'female', $receipt_number, $patient_id);

                    

                    if($report == 0){

                        $female_investd[] = $vals['female_investigation_name'];

                    }

                    if($report['status'] == "pending"){

                        $female_investd[] = $vals['female_investigation_name'];

                    }

                    

                }

                $female_html .= $investigationname.", ";

                $female_html = substr($female_html , 0, -2);

            }

            $response = array();

            $response = array('male_count' => count($male_investd), 'female_count' => count($female_investd));

            return $response;

        }else{

            return $patient_result;

        }

    }

}



function check_investigation_report($investigation_id, $gender, $receipt_number, $patient_id){

	$ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $patient_result = array();

    $patient_sql = "Select * from ".$db_prefix."patient_investigation_reports where  investigation_id='".$investigation_id."' and gender='".$gender."' and receipt_number='".$receipt_number."' and patient_id='".$patient_id."' limit 1";

	$patient_q = $ci->db->query($patient_sql);

	$patient_result = $patient_q->result_array();    

    if(!empty($patient_result)){;

        return $patient_result[0];

    }else{

        return 0;

    }

}



function check_procedure_billing($appointment_id){

	$ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



   // $sql = "Select * from ".$db_prefix."doctor_consultation where  appointment_id='".$appointment_id."' and procedure_billed='1' limit 1";
	$sql = "Select * from ".$db_prefix."doctor_consultation where  appointment_id='".$appointment_id."' limit 1";

	$query = $ci->db->query($sql);

	$result = $query->result_array();    

    if(!empty($result)){;

        return $result[0];

    }else{

        return $result;

    }

}



    function india_ivf_billing($primary, $table){

        $ci= &get_instance();

        $ci->load->database();

        $db_prefix = $ci->config->config['db_prefix'];

        

        $consultation_result = $response = array();

        $consultation_sql = "SELECT * FROM ".$db_prefix.$table." where ID='$primary'";

        $consultation_q = $ci->db->query($consultation_sql);

        $consultation_result = $consultation_q->result_array();

        if(count($consultation_result) > 0){

            $response = $consultation_result[0];

        }

        return $response;

    }





function medical_frequency($frequency){

    switch ($frequency) {

        case 'OD':

            $timing = 1;

            break;

        case 'BD':

            $timing = 2;

            break;  

        case 'TDS':

            $timing = 3;

            break;

        case 'QID':

            $timing = 4;

            break;

        case 'HS':

            $timing = 1;

            break;

        case 'SOS':

            $timing = 1;

            break;

        default:

            $timing = 1;

            break;

    }

    return $timing;

}

function get_centre_details($centre_id){
    $ci= &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    
    $consultation_result = $response = array();
    $consultation_sql = "SELECT * FROM ".$db_prefix."centers where center_number='$centre_id'";
    $consultation_q = $ci->db->query($consultation_sql);
    $consultation_result = $consultation_q->result_array();
    if(count($consultation_result) > 0){
        $response = $consultation_result[0];
    }
    return $response;
}

function get_doctors_relationship($junior_id){

    $ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    $response = array();

    $sql = "Select doctor_id from ".$db_prefix."doctor_relationship where junior_doctor_id='".$junior_id."'";

	$qry = $ci->db->query($sql);

    $result = $qry->result_array();

    if(!empty($result)){

        foreach($result as $key => $val){

            $response[] = $val['doctor_id'];

        }

        return $response;

    }

    return $response;

}



function check_discount_billing($patient_id, $receipt_number, $type){
    $ci = &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    $response = array();
    $table = "";
    if($type == "consultation"){
        $table = "consultation";
    }else if($type == "investigation"){
        $table = "patient_investigations";
    }else if($type == "procedure"){
        $table = "patient_procedure";
    }else{
        return $response;
    }

    $sql = "Select * from ".$db_prefix.$table." where patient_id='".$patient_id."' AND receipt_number='".$receipt_number."'";
	$qry = $ci->db->query($sql);
    $result = $qry->result_array();
    if(!empty($result)){
        return $result;
    }
    return $response;
}



//Patient Followup

function patient_follow_ups($patient_id){
    $ci = &get_instance();
	$ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];

    //$appointment_sql = "Select * from ".$db_prefix."appointments where  paitent_id='".$patient_id."' and billed='1'";
	$appointment_sql = "Select * from ".$db_prefix."appointments where  paitent_id='".$patient_id."' and billed='1' and follow_up_appointment='1'";
	$appointment_q = $ci->db->query($appointment_sql);
    $appointment_result = $appointment_q->result_array();
    $follow_html = "";
    if(!empty($appointment_result)){
        foreach($appointment_result as $key => $val){//var_dump($val);die;
            $follow_html .= follow_medical_info($patient_id, $val['ID']);
            $follow_html .= '<hr/>';
        }
    }
    return $follow_html;
}



//Follow Medical Info.

function follow_medical_info($patient_id, $appointment_id){ 

    //return "";

    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



  $patient_sql = "Select * from ".$db_prefix."doctor_consultation where ".$db_prefix."doctor_consultation.patient_id='$patient_id' and ".$db_prefix."doctor_consultation.appointment_id='$appointment_id' order by ".$db_prefix."doctor_consultation.ID ASC limit 2";

    //echo $patient_sql;die;

    $patient_q = $ci->db->query($patient_sql);

    $patient_result = $patient_q->result_array();

   //var_dump($patient_result);die;
   
   //echo $patient_result->receipt_number;die;

    if(empty($patient_result)){

        $response = "";

        return $response;

    }

    $patient_result = $patient_result[0];

    //$get_procedure_data = get_procedure_data();

    $procedure_suggestion = $patient_result['procedure_suggestion'];

	$sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);
	
	$doctor_id = $patient_result['doctor_id'];
	
	

	//var_dump($procedure_html);die;

    $procedure_html = "";

    if($procedure_suggestion == 1){

        $procedure_html = "<ul>";

        

        if(!empty($sub_procedure_suggestion_list)){

            foreach($sub_procedure_suggestion_list as $key => $vals){

                $sub_procedure_data = get_procedure_data($vals);

                $procedure_html .= "<li>".$sub_procedure_data."</li>";

            }

        }

        $procedure_html .= "</ul>";

    }
	
	$package_suggestion = $patient_result['package_suggestion'];

	$package_suggestion_list = unserialize($patient_result['package_suggestion_list']);
	
	$doctor_id = $patient_result['doctor_id'];
	
	

	//var_dump($procedure_html);die;

    $package_html = "";

    if($package_suggestion == 1){

        $package_html = "<ul>";

        

        if(!empty($package_suggestion_list)){

            foreach($package_suggestion_list as $key => $vals){

                $sub_package_data = get_package_data($vals);

                $package_html .= "<li>".$sub_package_data."</li>";

            }

        }

        $package_html .= "</ul>";

    }

    $medicine_suggestion = $patient_result['medicine_suggestion'];

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $male_medicine_html = $female_medicine_html = $male_medicine_html_ipd = $female_medicine_html_ipd = "";


    if($medicine_suggestion == 1){        
	    $male_medicine_suggestion_list = unserialize($patient_result['male_medicine_suggestion_list']);
        $female_medicine_suggestion_list = unserialize($patient_result['female_medicine_suggestion_list']);
        $male_medicine_suggestion_list_ipd=unserialize($patient_result['male_medicine_suggestion_list_ipd']);
        $female_medicine_suggestion_list_ipd=unserialize($patient_result['female_medicine_suggestion_list_ipd']); 
        if(!empty($male_medicine_suggestion_list)){
            $male_med_count =1; 
            $male_medicine_html = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                        <thead style="border:1px solid #000; padding:10px; width:100%;">
                                            <tr>
                                                <td style="border:1px solid #000; padding:10px;" colspan="8">Male</td>
                                            </tr>
                                            <tr>
                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                <th style="border:1px solid #000; padding:10px;">Days</th>
                                                <th style="border:1px solid #000; padding:10px;">Route</th>
                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>
                                            </tr>
                                            <tbody id="male_medicine_suggestion_table"  style="border:1px solid #000; padding:10px; width:100%;">';                                           
                                            foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html .= '<tr style="border:1px solid #000; width:40%;">  
                                                <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                             </tr>';
                                            $male_med_count++; }                           
            $male_medicine_html .= '</tbody> </thead> </table>';
        }
        if(!empty($female_medicine_suggestion_list)){
            $fmale_med_count = 1;
            $female_medicine_html .= '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                        <thead style="border:1px solid #000; padding:10px; width:100%;">
                                            <tr>
                                                <td style="border:1px solid #000; padding:10px;" colspan="8">Female</td>
                                            </tr>
                                             <tr>

                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>   
                                            <tbody id="male_medicine_suggestion_table" style="border:1px solid #000; padding:10px; width:100%;">';                                           
                                            foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html .= '<tr style="border:1px solid #000; width:40%;">    
                                                
                                                <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                             </tr>';
                                            $fmale_med_count++; }                               
            $female_medicine_html .= '</tbody> </thead> </table>';

        }
        if(!empty($male_medicine_suggestion_list_ipd)){
            $male_med_count_ipd =1; 
            $male_medicine_html_ipd = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
                                        <thead style="border:1px solid #000; padding:10px; width:100%;">
                                            <tr>
                                                <td style="border:1px solid #000; padding:10px;" colspan="8">Male</td>
                                            </tr>
                                            <tr>

                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>
                                            <tbody id="male_medicine_suggestion_table_ipd"  style="border:1px solid #000; padding:10px; width:100%;">';                                           
                                            foreach($male_medicine_suggestion_list_ipd['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html_ipd .= '<tr style="border:1px solid #000; width:40%;">  
                                                <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                             </tr>';
                                            $male_med_count_ipd++; }                           
            $male_medicine_html_ipd.= '</tbody> </thead> </table>';
        }
        if(!empty($female_medicine_suggestion_list_ipd)){
                $fmale_med_count_ipd = 1;
                $female_medicine_html_ipd .= '<table style="width:100%; border:1px solid #000;" id="female_medicine_table_ipd" border="1">
                                        <thead style="border:1px solid #000; padding:10px; width:100%;">
                                            <tr>
                                                <td style="border:1px solid #000; padding:10px;" colspan="8">Female</td>
                                            </tr>
                                             <tr>
                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                <th style="border:1px solid #000; padding:10px;">Days</th>
                                                <th style="border:1px solid #000; padding:10px;">Route</th>
                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>   
                                            <tbody id="male_medicine_suggestion_table_ipd" style="border:1px solid #000; padding:10px; width:100%;">';                                           
                                            foreach($female_medicine_suggestion_list_ipd['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html_ipd .= '<tr style="border:1px solid #000; width:40%;">    
                                                
                                                <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                            
                                                <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                             
                                                <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                             </tr>';
                                            $fmale_med_count_ipd++; }                               
            $female_medicine_html_ipd .= '</tbody> </thead> </table>';
        }

    }
    $investation_suggestion = $patient_result['investation_suggestion'];
    $male_investation_html = $female_investation_html = $male_minvestation_html = $female_minvestation_html = "";

    if($investation_suggestion == 1){        
        $male_investigation_suggestion_list = unserialize($patient_result['male_investigation_suggestion_list']);
        $female_investigation_suggestion_list = unserialize($patient_result['female_investigation_suggestion_list']);
		$male_minvestigation_suggestion_list = unserialize($patient_result['male_minvestigation_suggestion_list']);
        $female_minvestigation_suggestion_list = unserialize($patient_result['female_minvestigation_suggestion_list']);
        $male_investation_html = "<ul>";
        if(!empty($male_investigation_suggestion_list)){
            foreach($male_investigation_suggestion_list as $key => $vals){//var_dump($vals);die;
                $investigation_name = get_investigation_name($vals);
                $male_investation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $male_investation_html .= "</ul>";
        $female_investation_html = "<ul>";
        if(!empty($female_investigation_suggestion_list)){
            foreach($female_investigation_suggestion_list as $key => $vals){
                $investigation_name = get_investigation_name($vals);
                $female_investation_html .= "<li>".$investigation_name."</li>";

            }
        }
        $female_investation_html .= "</ul>";
		$male_minvestation_html = "<ul>";
        if(!empty($male_minvestigation_suggestion_list)){
            foreach($male_minvestigation_suggestion_list as $key => $vals){
                $investigation_name = get_master_investigation_name($vals);
                $male_minvestation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $male_minvestation_html .= "</ul>";
        $female_minvestation_html = "<ul>";
        if(!empty($female_minvestigation_suggestion_list)){
            foreach($female_minvestigation_suggestion_list as $key => $vals){
                $investigation_name = get_master_investigation_name($vals);
                $female_minvestation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $female_minvestation_html .= "</ul>";
    }
    $patient_details = get_patient_detail($patient_id);
    $doctor_center=get_doctor_centre($doctor_id);
    $center_details= get_center_detail($doctor_center);
    $center_logo= $center_details['upload_photo_1'];
    if(!empty($center_logo)){
    $logo= $center_details['upload_photo_1']; 
    }
    else {
    $logo= base_url()."assets/images/IndiaIVFClinic_logo.png";
    }
    $info_html = "";
    $rand_tr = rand(100, 999999999);
    $info_html = '<hr/>
    <table id="example1" class="table" border="1" style="width:100%;">
    <tbody>
        <tr style="border:1px solid #000; width:100%;">
            <td style="border:1px solid #000; width:100%;" colspan="2"><p class="follow_txt followup_print_'.$rand_tr.'_'.$patient_id.'"></p></td>
            <td><a href="javascript:void(0);" class="followprint_btn btn btn-primary" data-printid="followup_print_'.$rand_tr.'_'.$patient_id.'">Print</a></td>
            <td><input type="button" value="Send to Patient" class="btn btn-primary pull-right sendfollowwhatsapp" printid="followup_print_'.$rand_tr.'_'.$patient_id.'"></td>
        </tr>
    </tbody>
    </table>
    
    <div id="followup_print_'.$rand_tr.'_'.$patient_id.'">
    <table class="table" border="1" style="width:border:1px solid #000; width:100%;">
    <tbody>
        <tr style="border:1px solid #000; width:100%; display:none;" id="followlogo_tr">
            <td style="border:1px solid #000; width:100%;text-align:center;" colspan="3">
            
   
          
                <img src='.$logo.' class="img-responsive" style="width:200px" />
            </td>
        </tr>
        <tr style="border:1px solid #000; width:100%;">
            <td style="border:1px solid #000; width:100%;" colspan="3"><h4>Follow up assessment on '.$patient_result['consultation_date'].'</h4></td>
        </tr>
        '.patient_next_followup($patient_id).'

        <tr style="border:1px solid #000; width:100%;">
            <th style="border:1px solid #000; width:20%;">IIC ID: '.$patient_id.'</th>
            <th style="border:1px solid #000; width:20%;">Female</th>
            <th style="border:1px solid #000; width:20%;">Male</th>
        </tr>
        <tr style="border:1px solid #000; width:100%;">
            <th style="border:1px solid #000; width:20%;">Name</th>
            <th style="border:1px solid #000; width:20%;">'.$patient_details['wife_name'].'</th>
            <th style="border:1px solid #000; width:20%;">'.$patient_details['husband_name'].'</th>
        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:40%;">PRESENTING COMPLAINTS</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_findings'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_findings'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:40%;">MANAGEMENT ADVISED</th>

            <td style="border:1px solid #000; width:20%;">

            '.$procedure_html.'

            </td>

        </tr>
		<tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:40%;">PACKAGE ADVISED</th>

            <td style="border:1px solid #000; width:20%;">

            '.$package_html.'

            </td>

        </tr>
		<tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:40%;">Inform on day one of menstrual cycle</th>


        </tr>
        </tbody>
    </table>

        <table style="width:100%; border:1px solid #000;" border="1">
        
        <tr style="border:1px solid #000; width:100%;" colspan="2">
            <td style="border:0px solid #000; padding:10px;">Medicines Opd</td>
        </tr>
    
        <tr style="border:1px solid #000; width:100%;">
            <td colspan="2">'.$female_medicine_html.'</td>
            <td>'.$male_medicine_html.'</td>
        </tr>
        <tr style="border:1px solid #000; width:100%;" colspan="2">
            <td style="border:0px solid #000; padding:10px;">Medicines Ipd</td>
        </tr>
          <tr style="border:1px solid #000; width:100%;">
            <td colspan="2">'.$female_medicine_html_ipd.'</td>
            <td>'.$male_medicine_html_ipd.'</td>
        </tr>
        
        <tr style="border:1px solid #000; width:100%;">
            <td style="border:0px solid #000; padding:10px;">Investigations</td>
        </tr>
        <tr style="border:1px solid #000; width:100%;" colspan="3">
            <td style="border:1px solid #000; padding:10px"></td>
            <td style="border:1px solid #000; padding:10px;">Female</td>
            <td style="border:1px solid #000; padding:10px;">Male</td>
        </tr>
            
        <tr style="border:1px solid #000; padding:10px; width:100%;">
            <td style="border:1px solid #000; width:20%;"></td>

            <td style="border:1px solid #000; width:20%;">'.$female_investation_html.'</td>

            <td style="border:1px solid #000; width:20%;">'.$male_investation_html.'</td>

        </tr>
		<tr style="border:1px solid #000; width:100%;">
            <td style="border:0px solid #000; padding:10px;">IIC Investigations</td>
        </tr>
        <tr style="border:1px solid #000; width:100%;" colspan="3">
            <td style="border:1px solid #000; padding:10px"></td>
            <td style="border:1px solid #000; padding:10px;">Female</td>
            <td style="border:1px solid #000; padding:10px;">Male</td>
        </tr>
            
        <tr style="border:1px solid #000; padding:10px; width:100%;">
            <td style="border:1px solid #000; width:20%;"></td>

            <td style="border:1px solid #000; width:20%;">'.$female_minvestation_html.'</td>

            <td style="border:1px solid #000; width:20%;">'.$male_minvestation_html.'</td>

        </tr>
    </tbody>
    </table></div>';

    return $info_html;

}




function check_patient_medical_info($patient_id){

    //return "";

	$ci = &get_instance();

	$ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];



    $patient_sql = "Select * from ".$db_prefix."patient_medical_info RIGHT join ".$db_prefix."doctor_consultation on ".$db_prefix."doctor_consultation.patient_id=".$db_prefix."patient_medical_info.patient_id where ".$db_prefix."doctor_consultation.patient_id='$patient_id' AND ".$db_prefix."doctor_consultation.final_mode='1' order by ".$db_prefix."doctor_consultation.ID DESC limit 1";

    $patient_q = $ci->db->query($patient_sql);

    $patient_result = $patient_q->result_array();

    //var_dump($patient_result);die;

    if(empty($patient_result)){

        return 0;

    }else{

        return 1;

    }

}



//Print Patient Medical Info.

function print_follow_medical_info($patient_id){

    $ci = &get_instance();

	$ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];

    

    $appointment_sql = "Select * from ".$db_prefix."appointments where  paitent_id='".$patient_id."' and billed='1' and follow_up_appointment='1'";

	$appointment_q = $ci->db->query($appointment_sql);

    $appointment_result = $appointment_q->result_array();

    $follow_html = "";

    if(!empty($appointment_result)){

        foreach($appointment_result as $key => $val){//var_dump($val);die;
            $follow_html .= print_follow_medical_info_html($patient_id, $val['ID']);
        }

    }

    return $follow_html;

}

function print_follow_medical_info_html($patient_id, $appointment_id){ 

    //return "";

    $ci = &get_instance();

    $ci->load->database();

    $db_prefix = $ci->config->config['db_prefix'];



    $patient_sql = "Select * from ".$db_prefix."doctor_consultation where ".$db_prefix."doctor_consultation.patient_id='$patient_id' and ".$db_prefix."doctor_consultation.appointment_id='$appointment_id' order by ".$db_prefix."doctor_consultation.ID DESC limit 1";

    //echo $patient_sql;die;

    $patient_q = $ci->db->query($patient_sql);

    $patient_result = $patient_q->result_array();

    //var_dump($patient_result);die;

    if(empty($patient_result)){

        $response = "";

        return $response;

    }

    $patient_result = $patient_result[0];

    //$get_procedure_data = get_procedure_data();

    $procedure_suggestion = $patient_result['procedure_suggestion'];

	$sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $procedure_html = "";

    if($procedure_suggestion == 1){

        $procedure_html = "<ul>";

        

        if(!empty($sub_procedure_suggestion_list)){

            foreach($sub_procedure_suggestion_list as $key => $vals){

                $sub_procedure_data = get_procedure_data($vals);

                $procedure_html .= "<li>".$sub_procedure_data."</li>";

            }

        }

        $procedure_html .= "</ul>";

    }
	
	//var_dump($procedure_html);die;
	
	  //$get_procedure_data = get_procedure_data();

    $package_suggestion = $patient_result['package_suggestion'];

	$package_suggestion_list = unserialize($patient_result['package_suggestion_list']);

	//var_dump($package_suggestion_list);die;

    $package_html = "";

    if($package_suggestion == 1){

        $package_html = "<ul>";

        

        if(!empty($package_suggestion_list)){

            foreach($package_suggestion_list as $key => $vals){

                $sub_package_data = get_package_data($vals);

                $package_html .= "<li>".$sub_package_data."</li>";

            }

        }

        $package_html .= "</ul>";

    }
	

    $medicine_suggestion = $patient_result['medicine_suggestion'];
    $male_medicine_html = $female_medicine_html = $male_medicine_html_ipd = $female_medicine_html_ipd = "";
    if($medicine_suggestion == 1){        
	    $male_medicine_suggestion_list = unserialize($patient_result['male_medicine_suggestion_list']);
        $female_medicine_suggestion_list = unserialize($patient_result['female_medicine_suggestion_list']);
        $male_medicine_suggestion_list_ipd=unserialize($patient_result['male_medicine_suggestion_list_ipd']);
        $female_medicine_suggestion_list_ipd=unserialize($patient_result['female_medicine_suggestion_list_ipd']); 
        if(!empty($male_medicine_suggestion_list)){
            $male_medicine_html = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                
                                        <thead>

                                            <tbody id="male_medicine_suggestion_table">';                                           

                                            $male_med_count = 1;
                                            foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                
                                                $male_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$male_med_count.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                        </tr>';
                                            $male_med_count++;
                                            }                                   

            $male_medicine_html .= '</tbody> </thead> </table>';
        }
        if(!empty($female_medicine_suggestion_list)){
            $female_medicine_html .= '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                        <thead>
                                            <tbody id="male_medicine_suggestion_table">';                                           
                                            $fmale_med_count = 1;
                                            foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                
                                                $female_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$fmale_med_count.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                        </tr>';
                                            $fmale_med_count++;    
                                            }                                                            

            $female_medicine_html .= '</tbody> </thead> </table>';

        }
        if(!empty($male_medicine_suggestion_list_ipd)){
            $male_medicine_html_ipd = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
                                        <thead>
                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            $male_med_count_ipd = 1;
                                            foreach($male_medicine_suggestion_list_ipd['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                
                                                $male_medicine_html_ipd .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$male_med_count_ipd.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                        </tr>';
                                            $male_med_count_ipd++;
                                            }                                   
            $male_medicine_html_ipd .= '</tbody> </thead> </table>';
        }
         if(!empty($female_medicine_suggestion_list_ipd)){
            $female_medicine_html_ipd .= '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                        <thead>
                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            $fmale_med_count_ipd = 1;
                                            foreach($female_medicine_suggestion_list_ipd['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html_ipd .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$fmale_med_count_ipd.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                        </tr>';
                                            $fmale_med_count_ipd++;    
                                            }                                                            
            $female_medicine_html_ipd .= '</tbody> </thead> </table>';
        }
    }

    

    $investation_suggestion = $patient_result['investation_suggestion'];

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $male_investation_html = $female_investation_html = $male_minvestation_html = $female_minvestation_html = "";

    if($investation_suggestion == 1){        

        $male_investigation_suggestion_list = unserialize($patient_result['male_investigation_suggestion_list']);

        $female_investigation_suggestion_list = unserialize($patient_result['female_investigation_suggestion_list']);

		$male_minvestigation_suggestion_list = unserialize($patient_result['male_minvestigation_suggestion_list']);

        $female_minvestigation_suggestion_list = unserialize($patient_result['female_minvestigation_suggestion_list']);

        $male_investation_html = "<ul>";

        

        if(!empty($male_investigation_suggestion_list)){

            foreach($male_investigation_suggestion_list as $key => $vals){//var_dump($vals);die;

                $investigation_name = get_investigation_name($vals);

                $male_investation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $male_investation_html .= "</ul>";



        $female_investation_html = "<ul>";

        

        if(!empty($female_investigation_suggestion_list)){

            foreach($female_investigation_suggestion_list as $key => $vals){

                $investigation_name = get_investigation_name($vals);

                $female_investation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $female_investation_html .= "</ul>";
		
		 $male_minvestation_html = "<ul>";

        

        if(!empty($male_minvestigation_suggestion_list)){

            foreach($male_minvestigation_suggestion_list as $key => $vals){//var_dump($vals);die;

                $investigation_name = get_master_investigation_name($vals);

                $male_minvestation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $male_minvestation_html .= "</ul>";



        $female_minvestation_html = "<ul>";

        

        if(!empty($female_minvestigation_suggestion_list)){

            foreach($female_minvestigation_suggestion_list as $key => $vals){

                $investigation_name = get_master_investigation_name($vals);

                $female_minvestation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $female_minvestation_html .= "</ul>";
		
		

    }

    

    $patient_details = get_patient_detail($patient_id);

    //var_dump($patient_details);die;

    $info_html = "";

    $info_html = '<h4>Follow up assessment on '.$patient_result['consultation_date'].'</h4> <table id="example1" class="table"  border="1" style="width:100%; border:1px solid #000;">

    <tbody>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;"></th>

            <th style="border:1px solid #000; width:20%;">Female</th>

            <th style="border:1px solid #000; width:20%;">Male</th>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:40%;">Presenting<br>Complaints</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_findings'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_findings'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th>Management<br>Advised</th>

            <td style="border:1px solid #000; width:20%;">

            '.$procedure_html.'

            </td>

        </tr>
		 <tr style="border:1px solid #000; width:100%;">

            <th>Package<br>Advised</th>

            <td style="border:1px solid #000; width:20%;">

            '.$package_html.'

            </td>

        </tr>

        <tr>
            <th>Medicines Opd</th>
            <td>'.$female_medicine_html.'</td>
            <td>'.$male_medicine_html.'</td>
        </tr>
        <tr>
            <th>Medicines Ipd</th>
            <td>'.$female_medicine_html_ipd.'</td>
            <td>'.$male_medicine_html_ipd.'</td>
        </tr>
        <tr>
            <th>Investigations</th>
            <td>'.$female_investation_html.'</td>
            <td>'.$male_investation_html.'</td>
        </tr>
		<tr>

            <th>IIC Investigations</th>

            <td>'.$female_minvestation_html.'</td>

            <td>'.$male_minvestation_html.'</td>

        </tr>

    </tbody>

    </table>';

    return $info_html;

}


function check_suggested($consultation_id, $column_one, $column_two){
     $ci = &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];
    $result = array();
    $sql = "Select $column_one, $column_two from ".$db_prefix."doctor_consultation where ID='".$consultation_id."'";
    $qry = $ci->db->query($sql);
    $result = $qry->result_array();
    
    if(!empty($result)){
        $result = $result[0];
        if($result[$column_one] == 0){
            return 1;
        }
        if($result[$column_one] == 1 && $result[$column_two] == 0){
            return 0;
        }
        if($result[$column_one] == 1 && $result[$column_two] == 1){
            return 1;
        }
    }else{
        return $result;
    }
}


function print_pdf_patient_medical_info($patient_id){

    //return "";
    
    $ci = &get_instance();
    
    $ci->load->database();
    
    $db_prefix = $ci->config->config['db_prefix'];   
    
    $patient_sql = "Select * from ".$db_prefix."patient_medical_info RIGHT join ".$db_prefix."doctor_consultation on ".$db_prefix."doctor_consultation.patient_id=".$db_prefix."patient_medical_info.patient_id where ".$db_prefix."doctor_consultation.patient_id='$patient_id' AND ".$db_prefix."doctor_consultation.final_mode='1' order by ".$db_prefix."doctor_consultation.ID DESC limit 1";
    // echo $patient_sql;die;
    
    $patient_q = $ci->db->query($patient_sql);
    
    $patient_result = $patient_q->result_array();
    
    //var_dump($patient_result);die;
    
    if(empty($patient_result)){
    
        $response = "reportnotfound";
    
        return $response;
    
    }
    
    $patient_result = $patient_result[0];
    
    //$get_procedure_data = get_procedure_data();
	
	  
    
    $procedure_suggestion = $patient_result['procedure_suggestion'];
    
    $sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);
    
 /*   echo '<pre>';
var_dump($procedure_html);
echo '</pre>';
die();*/
    
    $procedure_html = "";
    
    if($procedure_suggestion == 1){
    
        $procedure_html = "<ul>";
    
        
    
        if(!empty($sub_procedure_suggestion_list)){
    
            foreach($sub_procedure_suggestion_list as $key => $vals){
    
                $sub_procedure_data = get_procedure_data($vals);
    
                $procedure_html .= "<li>".$sub_procedure_data."</li>";
    
            }
    
        }
    
        $procedure_html .= "</ul>";
    
    }
	
	 $package_suggestion = $patient_result['package_suggestion'];
    
    $package_suggestion_list = unserialize($patient_result['package_suggestion_list']);
    
    $package_html = "";
    
    if($package_suggestion == 1){
    
        $package_html = "<ul>";
    
        
    
        if(!empty($package_suggestion_list)){
    
            foreach($package_suggestion_list as $key => $vals){
    
                $sub_package_data = get_package_data($vals);
    
                $package_html .= "<li>".$sub_package_data."</li>";
    
            }
    
        }
    
        $package_html .= "</ul>";
    
    }
    
    $medicine_suggestion = $patient_result['medicine_suggestion'];
    
    //var_dump($parent_proce$procedure_suggestiondure_data);die;
    
    $male_medicine_html = $female_medicine_html = $male_medicine_html_ipd = $female_medicine_html_ipd = "";
    
    if($medicine_suggestion == 1){        
        $male_medicine_suggestion_list = unserialize($patient_result['male_medicine_suggestion_list']);
        $female_medicine_suggestion_list = unserialize($patient_result['female_medicine_suggestion_list']);
        $male_medicine_suggestion_list_ipd=unserialize($patient_result['male_medicine_suggestion_list_ipd']);
        $female_medicine_suggestion_list_ipd=unserialize($patient_result['female_medicine_suggestion_list_ipd']); 
        if(!empty($male_medicine_suggestion_list)){
            $male_medicine_html = '<table style="width:40%; border:1px solid #000;" id="male_medicine_table" border="1">
                                        <thead>
                                            <tbody id="male_medicine_suggestion_table">';                                           
                                            $male_med_count = 1;
                                            foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                    $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$male_med_count.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                        </tr>';
                                            $male_med_count++;
                                            }                                      
    
            $male_medicine_html .= '</tbody> </thead> </table>';    
        }
        if(!empty($female_medicine_suggestion_list)){
    
            $female_medicine_html .= '<table style="width:40%; border:1px solid #000;" id="male_medicine_table" border="1">
    
                                        <thead>    
                                            <tbody id="male_medicine_suggestion_table">';                                           
                                            $fmale_med_count = 1;
                                            foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$fmale_med_count.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                        </tr>';
                                            $fmale_med_count++;    
                                            }                                          
    
            $female_medicine_html .= '</tbody> </thead> </table>';
    
        }
        if(!empty($male_medicine_suggestion_list_ipd)){
            $male_medicine_html_ipd = '<table style="width:40%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
                                        <thead>
                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            $male_med_count_ipd = 1;
                                            foreach($male_medicine_suggestion_list_ipd['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                    $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html_ipd .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$male_med_count_ipd.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                        </tr>';
                                            $male_med_count_ipd++;
                                            }                                      
            $male_medicine_html_ipd .= '</tbody> </thead> </table>';    
        }
        if(!empty($female_medicine_suggestion_list_ipd)){
    
            $female_medicine_html_ipd .= '<table style="width:40%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
    
                                        <thead>    
                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            $fmale_med_count_ipd = 1;
                                            foreach($female_medicine_suggestion_list_ipd['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html_ipd .='<tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Medicine '.$fmale_med_count_ipd.':-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                        </tr>
                                                                        <tr style="border:1px solid #000; width:40%;">    
                                                                           <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                           <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                        </tr>';
                                            $fmale_med_count_ipd++;    
                                            }                                          
    
            $female_medicine_html_ipd .= '</tbody> </thead> </table>';
    
        }
    
    }
    
    
    
    $investation_suggestion = $patient_result['investation_suggestion'];
    
    //var_dump($parent_proce$procedure_suggestiondure_data);die;
    
    $male_investation_html = $female_investation_html = $male_minvestation_html = $female_minvestation_html = "";
    
    if($investation_suggestion == 1){        
    
        $male_investigation_suggestion_list = unserialize($patient_result['male_investigation_suggestion_list']);
    
        $female_investigation_suggestion_list = unserialize($patient_result['female_investigation_suggestion_list']);
    
		$male_minvestigation_suggestion_list = unserialize($patient_result['male_minvestigation_suggestion_list']);
    
        $female_minvestigation_suggestion_list = unserialize($patient_result['female_minvestigation_suggestion_list']);
    
        $male_investation_html = "<ul>";
    
        
    
        if(!empty($male_investigation_suggestion_list)){
    
            foreach($male_investigation_suggestion_list as $key => $vals){//var_dump($vals);die;
    
                $investigation_name = get_investigation_name($vals);
    
                $male_investation_html .= "<li>".$investigation_name."</li>";
    
            }
    
        }
    
        $male_investation_html .= "</ul>";
    
    
    
        $female_investation_html = "<ul>";
    
        
    
        if(!empty($female_investigation_suggestion_list)){
    
            foreach($female_investigation_suggestion_list as $key => $vals){
    
                $investigation_name = get_investigation_name($vals);
    
                $female_investation_html .= "<li>".$investigation_name."</li>";
    
            }
    
        }
    
        $female_investation_html .= "</ul>";
		
		$male_minvestation_html = "<ul>";
    
        
    
        if(!empty($male_minvestigation_suggestion_list)){
    
            foreach($male_minvestigation_suggestion_list as $key => $vals){//var_dump($vals);die;
    
                $investigation_name = get_master_investigation_name($vals);
    
                $male_minvestation_html .= "<li>".$investigation_name."</li>";
    
            }
    
        }
    
        $male_minvestation_html .= "</ul>";
    
    
    
        $female_minvestation_html = "<ul>";
    
        
    
        if(!empty($female_minvestigation_suggestion_list)){
    
            foreach($female_minvestigation_suggestion_list as $key => $vals){
    
                $investigation_name = get_master_investigation_name($vals);
    
                $female_minvestation_html .= "<li>".$investigation_name."</li>";
    
            }
    
        }
    
        $female_minvestation_html .= "</ul>";
    
    }
    
    
    
    $info_html = "";
    
    $info_html = '<h4>Initial assessment report</h4> <table id="example1" class="table" border="1" style="width:100%;">
    
    <thead>
    
        <th></th>
    
        <th>Female</th>
    
        <th>Male</th>
    
    </thead>
    
    <tbody>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <th style="border:1px solid #000; width:20%;">OCCUPATION 1</th>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_occupation'].'</td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_occupation'].'</td>
    
        </tr>
        <tr style="border:1px solid #000; width:40%;">
    
            <th style="border:1px solid #000; width:20%;">WT/HT/BMI</th>
    
            <td style="border:1px solid #000; width:40%;"> <span>Weight: '.$patient_result['female_wt_ht_bmi'].'</span> <span style="margin-left:50px;">Height: '.$patient_result['female_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['female_bmi'].'</span></td>
    
            <td style="border:1px solid #000; width:40%;"> <span>Weight: '.$patient_result['male_wt_ht_bmi'].'</span> <span style="margin-left:50px;">Height: '.$patient_result['male_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['male_bmi'].'</span></td>
    
        </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>
    
    H/O PREVIOUS<br> PREGNANCIES<br>
    
    (IN PREVIOUS<br> RELATIONSHIPS,<br>MARRIAGES ALSO )
    
    </th>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Total pregnancies</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_total_pregnancies'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of live births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_live_birth'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of spontaneous abortions in first trimester</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_spontaneous_abortions'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of termination of pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_termination_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of still births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_still_births'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No. of ectopic pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_ectopic_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>History of any abnormality in child</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_abnormality_child'].'</td>
    
        </tr>
		
		<tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"> <span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">P - '.$patient_result['female_pregnancy_other_p'].' </span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">L - '.$patient_result['female_pregnancy_other_l'].'</span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">A - '.$patient_result['female_pregnancy_other_a'].'</span></td>

            <td style="border:1px solid #000; width:40%;"></td>

        </tr>

    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Others</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_pregnancy_other'].'</td>
    
        </tr>
    
    </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
    <!-- <h1 style="margin-top:50px;">Tick the right option</h1> -->
    
    <table width="100%">
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Total pregnancies</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_total_pregnancies'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of live births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_live_birth'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of spontaneous abortions in first trimester</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_spontaneous_abortions'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of termination of pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_termination_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of still births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_still_births'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No. of ectopic pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_ectopic_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>History of any abnorfemality in child</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_abnormality_child'].'</td>
    
        </tr>
		
		<tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">P - '.$patient_result['male_pregnancy_other_p'].' </span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">L - '.$patient_result['male_pregnancy_other_l'].'</span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">A - '.$patient_result['male_pregnancy_other_a'].'</span></td>

            <td style="border:1px solid #000; width:40%;"></td>

        </tr>

    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Others</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_pregnancy_other'].'</td>
    
        </tr>
    
    </table>
    
    </tr>
    
    <tr>
    
    <th>SEXUAL<br> HISTORY</th>
    
    <td style="border:1px solid #000; width:100%;">
    
        <center><p>Marital life</p></center>
    
        <table width="100%">
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Active marital life</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_active_marital'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">No.of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_sexual_partners'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Duration of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_duration_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Frequency of sexual intercourse</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_frequency_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Contraception used</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_contraception'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Others</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_sexual_other'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
        <center><p>Marital life</p></center>
    
        <table width="100%">
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Active marital life</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_active_marital'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">No.of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_sexual_partners'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Duration of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_duration_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Frequency of sexual intercourse</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_frequency_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Contraception used</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_contraception'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Erection disorder</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_erection_disorder'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Ejaculation disorder</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_ejaculation_disorder'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Others</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_sexual_other'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>TYPE OF<br> INFERTILITY</th>
    
    <td style="border:1px solid #000; width:20%;"></td>
    
    <td style="border:1px solid #000; width:20%;"></td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>PAST GYNECOLOGICAL<br>UROLOGICAL HISTORY</th>
    
    <td style="border:1px solid #000; width:40%;">
    
        <table>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">H/o D and c</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_h_o_dandc'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:20%;"></td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>MENSTRUATION <br>HISTORY</th>
    
  <td colspan="1">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_menarche_age'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_flow'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_frequencye'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_galactorrhea'].'</td>

            </tr>

        </table>

    </td>
	<td colspan="1">

        <table>
		
		 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_menarche_age'].'</td>

            </tr>
			
			 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_flow'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_frequency'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_galactorrhea'].'</td>

            </tr>

        </table>

    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>PAST <br> INVESTIGATIONS</th>
    
    <td style="border:1px solid #000; width:40%;">
    
        <p><b>SERUM AMH</b></p>
    
        <table width="40%" class="border-field">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RESULT</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_result_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_result_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_3'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_result_3'].'</td>
    
            </tr>
    
        </table>
    
        <br>
    
        <p><b>SERUM FSH</b></p>
    
        <table width="40%" class="border-field">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RESULT</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_result_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_result_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_3'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_result_3'].'</td>
    
            </tr>
    
        </table>
    
        <br>
    
        <p><b>HSG</b></p>
    
        <table width="40%" class="border-field">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RESULT</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_result_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_result_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_3'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_result_3'].'</td>
    
            </tr>
    
        </table>
    
        <br>
    
        <p><b>USG OF PELVIS</b></p>
    
        <table width="40%" class="border-field">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RESULT</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_result_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_result_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_3'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_result_3'].'</td>
    
            </tr>
    
        </table>
    
        <br>
    
        <table width="40%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Others</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_past_investigation_others'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
        <p><b>SEMEN ANALYSIS</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:40%;">
                <td style="border:1px solid #000; width:20%;">DT</td>
				<td style="border:1px solid #000; width:20%;">RESULT</td>
                <td style="border:1px solid #000; width:20%;">DT</td>
				<td style="border:1px solid #000; width:20%;">RESULT</td>
				<td style="border:1px solid #000; width:20%;">DT</td>
				<td style="border:1px solid #000; width:20%;">RESULT</td>
            </tr>

            <tr style="border:1px solid #000; width:40%;">
                
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_result_1'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_1'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_2'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_result_2'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_3'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_result_3'].'</td>
            </tr>

        </table>
    
        <br>
    
        <p><b>SERUM FSH</b></p>
    
        <table style="border:1px solid #000; width:40%;" class="border-field">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RESULT</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_result_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_result_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_3'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_result_3'].'</td>
    
            </tr>
    
        </table>
    
        <br>
    
        <p><b>SERUM TESTOSTERONE</b></p>
    
        <table width="40%" class="border-field">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RESULT</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
                <td style="border:1px solid #000; width:20%;">DT</td>
    
                <td style="border:1px solid #000; width:20%;">RE</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_result_1'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_result_2'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_3'].'</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_result_3'].'</td>
    
            </tr>
    
        </table>
    
        <br>
    
        <table width="40%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Others</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_past_investigation_others'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>PREVIOUS <br>INFERTILITY <br>TREATMENT<br> DETAILS </th>
    
    <td style="border:1px solid #000; width:20%;">
    
    <table width="100%">
    
           <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">YEARS OF TAKING INFERTILITY TREATMENT</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_infertility_treatment_years'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">OVULATION INDUCTION DRUGS HOW MANY CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_induction_drugs_cycles'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">OVULATION INDUCTION INJECTION TAKEN HOW MANY CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_induction_injection_cycles'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">TOTAL NO. OF IUI CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_iui_cycles'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">TOTAL NO . OF IVF/ICSI CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_ivf_icsi_cycles'].'</td>
    
            </tr>
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Total No. OF STIMULATED IVF CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_stimulated_ivf_cycles'].'</td>
    
            </tr>
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Total No. cycles with no evidence of fertilization</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_no_evidence_fertilization'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">NO. OF EGGS RETREIVED EACH CYCLE</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_egg_retreived'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">NO. OF FRESH CYCLE</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fresh_cycle'].'</td>
    
            </tr>    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
           <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">MEDICATIONS FOR SPERM</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['medication_for_sperm'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">NO. OF TIMES TESA DONE</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['no_of_tesa'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">TESA REPORT</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['tesa_report'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">TESE REPORT</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['tese_report'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">MICRO TESE</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['micro_tese'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">OTHERS</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['other_s'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
	<tr style="border:1px solid #000; width:100%;">
    
    <th>Others</th>
    
    <td style="border:1px solid #000; width:40%;">'.$patient_result['female_others_msg'].'</td>
	<td style="border:1px solid #000; width:40%;">'.$patient_result['male_others_msg'].'</td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>GENERAL<br> EXAMINATION</th>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Nutritional assessment :-</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_nutritional_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Psychological assessment :-</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_psychological_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Anxious</br>combative</br>depressed</br>normal</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_mood_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Pulse</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pulse'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Blood pressure</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_blood_pressure'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Temperature</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_temperature'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">CVS</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_cvs'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Chest</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_chest'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Abdomen</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_abdomen'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:80%;">Others</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_general_exam_others'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
        <td style="border:1px solid #000; width:100%;">
    
        <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Nutritional assessment :-</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['nutritional_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Psychological assessment :-</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['psychological_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Anxious</br>combative</br>depressed</br>normal</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['anxious_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Pulse</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['pulse_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Blood pressure</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['bp_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Temperature</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['temp_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">CVS-</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['cvs_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Chest</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['chest_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Abdomen</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['abdomen_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">Others</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['assessment_others'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>LOCAL<br> EXAMINATION</th>
    
    <td style="border:1px solid #000; width:100%;">
    
        <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">P/S</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_ps'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">P/V</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_pv'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">PAP SMEAR TAKEN</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_pap'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">HVS C&S TAKEN</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hvs_taken'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">ENDOMETRIAL BIOPSY HPE/TB QUANTIFERON</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_endometrial_biopsy'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">UROSURGEON FINDINGS (ATTACH PRESCRIPTION)</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['urosurgeon_findings'].' <a href="'.$patient_result['prescription'].'">Download</a></td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>MANAGEMENT<br> ADVISED</th>
    
    <td style="border:1px solid #000; width:20%;">
    
    '.$procedure_html.'
    
    </td>
    
    </tr>
	
	<tr style="border:1px solid #000; width:100%;">
    
    <th>PACKAGE<br> ADVISED</th>
    
    <td style="border:1px solid #000; width:20%;">
    
    '.$package_html.'
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>DETAILS OF <br>MANAGEMENT ADVISED</th>
    
    <td style="border:1px solid #000; width:40%;">'.$patient_result['details_management_advised'].'</td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>REASON FOR <br>ADVISED MANAGEMENT</th>
    
    <td colspan="2">
    
        <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">LOW OVARIAN RESERVE</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['low_ovarian_reserve_evidence'].' ('.$patient_result['low_ovarian_reserve_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">TUBAL FACTOR</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['tubal_factor_evidence'].' ('.$patient_result['tubal_factor_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">MALE FACTOR</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_factor_evidence'].' ('.$patient_result['male_factor_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">ENDOMETRIOSIS</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['endometriosis_evidence'].' ('.$patient_result['endometriosis_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">PCOS</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['pcos_evidence'].' ('.$patient_result['pcos_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">UNEXPLAINED INFERTILITY</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['unexplained_infertility_evidence'].' ('.$patient_result['unexplained_infertility_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Others</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['advised_reason_other_evidence'].' ('.$patient_result['advised_reason_other_evidence_date'].')</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    <th style="border:1px solid #000; padding:10px;">Medicines Opd</th>
        <td>'.$female_medicine_html.'</td>
        <td>'.$male_medicine_html.'</td>
    <th style="border:1px solid #000; padding:10px;">Medicines Ipd</th>
        <td>'.$female_medicine_html_ipd.'</td>
        <td>'.$male_medicine_html_ipd.'</td>
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">

    <th style="border:1px solid #000; padding:10px;">Investigations</th>
    
    <td style="border:1px solid #000; padding:10px;">'.$female_investation_html.'</td>
    
    <td style="border:1px solid #000; padding:10px;">'.$male_investation_html.'</td>
    
    </tr>
	
	<tr style="border:1px solid #000; width:100%;">

    <th style="border:1px solid #000; padding:10px;">IIC Investigations</th>
    
    <td style="border:1px solid #000; padding:10px;">'.$female_minvestation_html.'</td>
    
    <td style="border:1px solid #000; padding:10px;">'.$male_minvestation_html.'</td>
    
    </tr>
    
    </tbody>
    
    </table>';
    
    return $info_html;
    
}


function print_patient_medical_info($patient_id){

    $ci = &get_instance();
    $ci->load->database();
    $db_prefix = $ci->config->config['db_prefix'];   
    $patient_sql = "Select * from ".$db_prefix."patient_medical_info RIGHT join ".$db_prefix."doctor_consultation on ".$db_prefix."doctor_consultation.patient_id=".$db_prefix."patient_medical_info.patient_id where ".$db_prefix."doctor_consultation.patient_id='$patient_id' AND ".$db_prefix."doctor_consultation.final_mode='1' order by ".$db_prefix."doctor_consultation.ID ASC limit 1";
    $patient_q = $ci->db->query($patient_sql);
    $patient_result = $patient_q->result_array();
    if(empty($patient_result)){
        $response = "reportnotfound";
        return $response;
    }
    $patient_result = $patient_result[0];
    //$get_procedure_data = get_procedure_data();
    $procedure_suggestion = $patient_result['procedure_suggestion'];
    $sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);
    $procedure_html = "";
    if($procedure_suggestion == 1){
        $procedure_html = "<ul>";
        if(!empty($sub_procedure_suggestion_list)){
            foreach($sub_procedure_suggestion_list as $key => $vals){
                $sub_procedure_data = get_procedure_data($vals);
                $procedure_html .= "<li>".$sub_procedure_data."</li>";
            }
        }
        $procedure_html .= "</ul>";
    }
	$package_suggestion = $patient_result['package_suggestion'];
    $package_suggestion_list = unserialize($patient_result['package_suggestion_list']);
	 $package_html = "";
    if($package_suggestion == 1){
        $package_html = "<ul>";
        if(!empty($package_suggestion_list)){
            foreach($package_suggestion_list as $key => $vals){
                $sub_package_data = get_package_data($vals);
                $package_html .= "<li>".$sub_package_data."</li>";
            }
        }
        $package_html .= "</ul>";
    
    }
    $medicine_suggestion = $patient_result['medicine_suggestion'];
    $male_medicine_html = $female_medicine_html = $male_medicine_html_ipd = $female_medicine_html_ipd = "";
    if($medicine_suggestion == 1){        
        $male_medicine_suggestion_list = unserialize($patient_result['male_medicine_suggestion_list']);
        $female_medicine_suggestion_list = unserialize($patient_result['female_medicine_suggestion_list']);
        $male_medicine_suggestion_list_ipd=unserialize($patient_result['male_medicine_suggestion_list_ipd']);
        $female_medicine_suggestion_list_ipd=unserialize($patient_result['female_medicine_suggestion_list_ipd']); 
        if(!empty($male_medicine_suggestion_list)){
            $male_medicine_html = '<table style="width:40%; border:1px solid #000;" id="male_medicine_table" border="1">
                                        <thead>
                                            <tr>
                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                <th style="border:1px solid #000; padding:10px;">Days</th>
                                                <th style="border:1px solid #000; padding:10px;">Route</th>
                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>
                                            </tr>
                                            <tbody id="male_medicine_suggestion_table">';                                           
                                            $male_med_count = 1;
                                            foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                    $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html .='<tr style="border:1px solid #000; width:40%;">   
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                      
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                        </tr>';
                                            $male_med_count++;
                                            }                                      
    
            $male_medicine_html .= '</tbody> </thead> </table>';    
        }
        if(!empty($female_medicine_suggestion_list)){
            $female_medicine_html .= '<table style="width:40%; border:1px solid #000;" id="male_medicine_table" border="1">
    
                                        <thead>    
                                        <tr>

                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>
                                            <tbody id="male_medicine_suggestion_table">';                                           
                                            $fmale_med_count = 1;
                                            foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                           
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                        </tr>';
                                            $fmale_med_count++;    
                                            }                                          
    
            $female_medicine_html .= '</tbody> </thead> </table>';
    
        }
        if(!empty($male_medicine_suggestion_list_ipd)){
            $male_medicine_html = '<table style="width:40%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
                                        <thead>
                                            <tr>
                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                <th style="border:1px solid #000; padding:10px;">Days</th>
                                                <th style="border:1px solid #000; padding:10px;">Route</th>
                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>
                                            </tr>
                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            $male_med_count_ipd = 1;
                                            foreach($male_medicine_suggestion_list_ipd['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                    $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html_ipd .='<tr style="border:1px solid #000; width:40%;">   
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                      
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                        </tr>';
                                            $male_med_count_ipd++;
                                            }                                      
    
            $male_medicine_html_ipd .= '</tbody> </thead> </table>';    
        }
        if(!empty($female_medicine_suggestion_list_ipd)){
            $female_medicine_html_ipd .= '<table style="width:40%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
    
                                        <thead>    
                                        <tr>

                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>
                                            <tbody id="male_medicine_suggestion_table">';                                           
                                            $fmale_med_count_ipd = 1;
                                            foreach($female_medicine_suggestion_list_ipd['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html_ipd .='<tr style="border:1px solid #000; width:40%;">    
                                                                           
                                                                           <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                       
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                        
                                                                           <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                        </tr>';
                                            $fmale_med_count_ipd++;    
                                            }                                          
    
            $female_medicine_html_ipd .= '</tbody> </thead> </table>';
    
        }
    
    }
    
    
    
    $investation_suggestion = $patient_result['investation_suggestion'];
    
    //var_dump($parent_proce$procedure_suggestiondure_data);die;
    
    $male_investation_html = $female_investation_html = $male_minvestation_html = $female_minvestation_html = "";
    
    if($investation_suggestion == 1){        
        $male_investigation_suggestion_list = unserialize($patient_result['male_investigation_suggestion_list']);
        $female_investigation_suggestion_list = unserialize($patient_result['female_investigation_suggestion_list']);
		$male_minvestigation_suggestion_list = unserialize($patient_result['male_minvestigation_suggestion_list']);
        $female_minvestigation_suggestion_list = unserialize($patient_result['female_minvestigation_suggestion_list']);
        $male_investation_html = "<ul>";
        if(!empty($male_investigation_suggestion_list)){
            foreach($male_investigation_suggestion_list as $key => $vals){//var_dump($vals);die;
                $investigation_name = get_investigation_name($vals);
                $male_investation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $male_investation_html .= "</ul>";
        $female_investation_html = "<ul>";
        if(!empty($female_investigation_suggestion_list)){
            foreach($female_investigation_suggestion_list as $key => $vals){
                $investigation_name = get_investigation_name($vals);
                $female_investation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $female_investation_html .= "</ul>";
		  $male_minvestation_html = "<ul>";
        if(!empty($male_minvestigation_suggestion_list)){
            foreach($male_minvestigation_suggestion_list as $key => $vals){//var_dump($vals);die;
                $investigation_name = get_master_investigation_name($vals);
                $male_minvestation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $male_minvestation_html .= "</ul>";
        $female_minvestation_html = "<ul>";
        if(!empty($female_minvestigation_suggestion_list)){
            foreach($female_minvestigation_suggestion_list as $key => $vals){
                $investigation_name = get_master_investigation_name($vals);
                $female_minvestation_html .= "<li>".$investigation_name."</li>";
            }
        }
        $female_minvestation_html .= "</ul>";
    }
    
    
    
    $info_html = "";
    
    $info_html = '<h4>Initial assessment report</h4> <table id="example1" class="table" border="1" style="width:100%;">
    
    <thead>
    
        <th></th>
    
        <th>Female</th>
    
        <th>Male</th>
    
    </thead>
    
    <tbody>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <th style="border:1px solid #000; width:20%;">Blood Group</th>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_blood_grp'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_blood_grp'].'</td>
    
        </tr>

        <tr style="border:1px solid #000; width:40%;">
    
            <th style="border:1px solid #000; width:20%;">NATIONALITY</th>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_nationality'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_nationality'].'</td>
    
        </tr>

        <tr style="border:1px solid #000; width:40%;">
    
            <th style="border:1px solid #000; width:20%;">OCCUPATION 1</th>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_occupation'].'</td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_occupation'].'</td>
    
        </tr>

        <tr style="border:1px solid #000; width:40%;">
    
            <th style="border:1px solid #000; width:20%;">WT/HT/BMI</th>
    
            <td style="border:1px solid #000; width:40%;"><span> Weight: '.$patient_result['female_wt_ht_bmi'].'</span><span  style="margin-left:50px;"> Height: '.$patient_result['female_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['female_bmi'].'</span></td>
    
            <td style="border:1px solid #000; width:40%;"><span> Weight: '.$patient_result['male_wt_ht_bmi'].'</span><span style="margin-left:50px;"> Height: '.$patient_result['male_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['male_bmi'].'</span></td>
    
        </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>
    
    H/O PREVIOUS<br> PREGNANCIES<br>
    
    (IN PREVIOUS<br> RELATIONSHIPS,<br>MARRIAGES ALSO )
    
    </th>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Total pregnancies</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_total_pregnancies'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of live births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_live_birth'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of spontaneous abortions in first trimester</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_spontaneous_abortions'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of termination of pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_termination_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of still births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_still_births'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No. of ectopic pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_ectopic_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>History of any abnormality in child</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_abnormality_child'].'</td>
    
        </tr>
		
		<tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><span style="border: 1px solid #000;padding: 5px 10px;">P - '.$patient_result['female_pregnancy_other_p'].' </span><span style="border: 1px solid #000;padding: 5px 10px;">L - '.$patient_result['female_pregnancy_other_l'].'</span><span style="border: 1px solid #000;padding: 5px 10px;">A - '.$patient_result['female_pregnancy_other_a'].'</span></td>

            <td style="border:1px solid #000; width:40%;"></td>

        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Others</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_pregnancy_other'].'</td>
    
        </tr>
    
    </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
    <!-- <h1 style="margin-top:50px;">Tick the right option</h1> -->
    
    <table width="100%">
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Total pregnancies</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_total_pregnancies'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of live births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_live_birth'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of spontaneous abortions in first trimester</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_spontaneous_abortions'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of termination of pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_termination_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No.of still births</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_still_births'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>No. of ectopic pregnancy</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_ectopic_pregnancy'].'</td>
    
        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>History of any abnorfemality in child</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_abnormality_child'].'</td>
    
        </tr>
		
		<tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"> <span style="border: 1px solid #000;padding: 5px 10px;">P - '.$patient_result['male_pregnancy_other_p'].' </span><span style="border: 1px solid #000;padding: 5px 10px;">L - '.$patient_result['male_pregnancy_other_l'].'</span><span style="border: 1px solid #000;padding: 5px 10px;">A - '.$patient_result['male_pregnancy_other_a'].'</span></td>

            <td style="border:1px solid #000; width:40%;"></td>

        </tr>
    
        <tr style="border:1px solid #000; width:40%;">
    
            <td style="border:1px solid #000; width:60%;"><p>Others</p></td>
    
            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_pregnancy_other'].'</td>
    
        </tr>
    
    </table>
    
    </tr>
    
    <tr>
    
    <th>SEXUAL<br> HISTORY</th>
    
    <td style="border:1px solid #000; width:100%;">
    
        
    
        <table width="100%">
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;width:60%;">Marital life</td>
    
                <td style="border:1px solid #000;width:40%;">'.$patient_result['female_marital_life'].'</td>
    
            </tr>
            
            
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Active marital life</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_active_marital'].'</td>
    
            </tr>
            
            
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">No.of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_sexual_partners'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Duration of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_duration_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Frequency of sexual intercourse</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_frequency_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Contraception used</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_contraception'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Others</td>
    
                <td style="border:1px solid #000;">'.$patient_result['female_sexual_other'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
       
    
        <table width="100%">
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;width:60%;">Marital life</td>
    
                <td style="border:1px solid #000;width:40%;">'.$patient_result['male_marital_life'].'</td>
    
            </tr>
            
             <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Active marital life</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_active_marital'].'</td>
    
            </tr>
            
            
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">No.of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_sexual_partners'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Duration of sexual partners</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_duration_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Frequency of sexual intercourse</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_frequency_sexual'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Contraception used</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_contraception'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Erection disorder</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_erection_disorder'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Ejaculation disorder</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_ejaculation_disorder'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000;">
    
                <td style="border:1px solid #000;">Others</td>
    
                <td style="border:1px solid #000;">'.$patient_result['male_sexual_other'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>TYPE OF<br> INFERTILITY</th>
    
     <td style="border:1px solid #000; width:20%;"> '.$patient_result['female_intertiliy_type'].' </td>
    
    <td style="border:1px solid #000; width:20%;"> '.$patient_result['male_intertiliy_type'].' </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:40%;">
    
    <th>PAST GYNECOLOGICAL<br>UROLOGICAL HISTORY</th>
    
    <td style="border:1px solid #000; width:40%;">
    
        <table>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">H/o D and c</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_h_o_dandc'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
     <td style="border:1px solid #000; width:100%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hernia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hernia'].'</td>

            </tr>

        </table>

    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>MENSTRUATION <br>HISTORY</th>
    
    <td colspan="1">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_menarche_age'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_flow'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_frequencye'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_galactorrhea'].'</td>

            </tr>

        </table>

    </td>
	<td colspan="1">

        <table>
		
		 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_menarche_age'].'</td>

            </tr>
			
			 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_flow'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_frequency'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_galactorrhea'].'</td>

            </tr>

        </table>

    </td>
    
    </tr>




    <tr>
    <th style="color: red;">PAST /PRESENT MEDICAL HISTORY</th>
    <td>
    <table width="100%">
    <tbody><tr>
    <td style="border:1px solid #000; width:40%;">Heart attack</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['heart_attack'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_attack_text'].'</td>
    </tr>
    <tr>
    <td>Pacemaker</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['pacemaker'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['pacemaker_text'].'</td>
    </tr>
    <tr>
    <td>Other heart disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['other_heart_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['other_heart_disease_text'].'</td>
    </tr>
    <tr>
    <td>High blood pressure</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['high_blood_pressure'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['high_blood_pressure_text'].'</td>
    </tr>
    <tr>
    <td>Blood clots</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['blood_clots'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_clots_text'].'</td>
    </tr>
    <tr>
    <td>Chest pain</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['chest_pain'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['chest_pain_text'].'</td>
    </tr>
    <tr>
    <td>Stroke</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['stroke'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['stroke_text'].'</td>
    </tr>
    <tr>
    <td>Asthma</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['asthma'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['asthma_text'].'</td>
    </tr>
    <tr>
    <td>Other lung disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['lung_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['lung_disease_text'].'</td>
    </tr>
    <tr>
    <td>Difficulty breathing</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['difficulty_breathing'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['difficulty_breathing_text'].'</td>
    </tr>
    <tr>
    <td>Sleep apnea or snoring</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['snoring'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['snoring_text'].'</td>
    </tr>
    <tr>
    <td>Epilepsy or seizures</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['epilepsy'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['epilepsy_text'].'</td>
    </tr>
    <tr>
    <td>Fainting spells</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['fainting_spells'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['fainting_spells_text'].'</td>
    </tr>
    <tr>
    <td>Diabetes</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['diabetes'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['diabetes_text'].'</td>
    </tr>
    <tr>
    <td>Muscle disorders</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['muscle_disorders'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['muscle_disorders_text'].'</td>
    </tr>
    <tr>
    <td>Kidney disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['kidney_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['kidney_disease_text'].'</td>
    </tr>
    <tr>
    <td>Hepatitis</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['hepatitis'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['hepatitis_text'].'</td>
    </tr>
    <tr>
    <td>Tuberculosis</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['tuberculosis'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['tuberculosis_text'].'</td>
    </tr>
    <tr>
    <td>HIV</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['hiv'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['hiv_text'].'</td>
    </tr>
    <tr>
    <td>Heart burn/reflux</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['heart_burn'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_burn_text'].'</td>
    </tr>
    <tr>
    <td>Cancer </td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['cancer'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['cancer_text'].'</td>
    </tr>
    <tr>
    <td>Blood disorders</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['blood_disorders'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_disorders_text'].'</td>
    </tr>
    <tr>
    <td>Rheumatic disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['rheumatic_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['rheumatic_disease_text'].'</td>
    </tr>
    <tr>
    <td>Psychiatric disorder</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['psychiatric_disorder'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['psychiatric_disorder_text'].'</td>
    </tr>
    <tr>
    <td>Thyroid disorder</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['thyroid_disorder'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['thyroid_disorder_text'].'</td>
    </tr>
    <tr>
    <td>Urinary infection</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['urinary_infection'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['urinary_infection_text'].'</td>
    </tr>
    <tr>
    <td>Sexually transmitted disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['sexually_transmitted'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['sexually_transmitted_text'].'</td>
    </tr>
    <tr>
    <td>Other medical condition or impairments</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['heart_attack'].'</td>
    </tr>
    </tbody></table>
    </td>
    <td>
    <table width="100%">
    <tbody><tr>
    <td style="border:1px solid #000; width:40%;">Heart attack</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_attack'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_attack_text'].'</td>
    </tr>
    <tr>
    <td>Pacemaker</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_pacemaker'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_pacemaker_text'].'</td>
    </tr>
    <tr>
    <td>Other heart disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_heart_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_heart_disease_text'].'</td>
    </tr>
    <tr>
    <td>High blood pressure</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_high_blood_pressure'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_high_blood_pressure_text'].'</td>
    </tr>
    <tr>
    <td>Blood clots</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_clots'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_clots_text'].'</td>
    </tr>
    <tr>
    <td>Chest pain</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_chest_pain'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_chest_pain_text'].'</td>
    </tr>
    <tr>
    <td>Stroke</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_stroke'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_stroke_text'].'</td>
    </tr>
    <tr>
    <td>Asthma</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_asthma'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_asthma_text'].'</td>
    </tr>
    <tr>
    <td>Other lung disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_lung_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_lung_disease_text'].'</td>
    </tr>
    <tr>
    <td>Difficulty breathing</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_difficulty_breathing'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_difficulty_breathing_text'].'</td>
    </tr>
    <tr>
    <td>Sleep apnea or snoring</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_snoring'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_snoring_text'].'</td>
    </tr>
    <tr>
    <td>Epilepsy or seizures</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_epilepsy'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_epilepsy_text'].'</td>
    </tr>
    <tr>
    <td>Fainting spells</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fainting_spells'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fainting_spells_text'].'</td>
    </tr>
    <tr>
    <td>Diabetes</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_diabetes'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_diabetes_text'].'</td>
    </tr>
    <tr>
    <td>Muscle disorders</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_muscle_disorders'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_muscle_disorders_text'].'</td>
    </tr>
    <tr>
    <td>Kidney disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_kidney_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_kidney_disease_text'].'</td>
    </tr>
    <tr>
    <td>Hepatitis</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hepatitis'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hepatitis_text'].'</td>
    </tr>
    <tr>
    <td>Tuberculosis</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_tuberculosis'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_tuberculosis_text'].'</td>
    </tr>
    <tr>
    <td>HIV</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hiv'].'</td>
	 <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hiv_text'].'</td>
    </tr>
    <tr>
    <td>Heart burn/reflux</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_burn'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_burn_text'].'</td>
    </tr>
    <tr>
    <td>Cancer </td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_cancer'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_cancer_text'].'</td>
    </tr>
    <tr>
    <td>Blood disorders</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_disorders'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_disorders_text'].'</td>
    </tr>
    <tr>
    <td>Rheumatic disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_rheumatic_disease'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_rheumatic_disease_text'].'</td>
    </tr>
    <tr>
    <td>Psychiatric disorder</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_psychiatric_disorder'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_psychiatric_disorder_text'].'</td>
    </tr>
    <tr>
    <td>Thyroid disorder</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_thyroid_disorder'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_thyroid_disorder_text'].'</td>
    </tr>
    <tr>
    <td>Urinary infection</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection_text'].'</td>
    </tr>
    <tr>
    <td>Sexually transmitted disease</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_sexually_transmitted'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_sexually_transmitted_text'].'</td>
    </tr>
    <tr>
    <td>Other medical condition or impairments</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    </tr>


    <tr>
    <th style="color: red;">PAST ANESTHESIA AND SURGICAL PROCEDURES</th>
    <td>
    <table>
    <tbody><tr>
    <td>Laparoscopy/pelvic/abdominal operations</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['abdominal_operations'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['abdominal_operations_text'].'</td>
    </tr>
    <tr>
    <td>Other operations</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['other_operations'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['other_operations_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    <td>
    <table>
    <tbody><tr>
    <td>Laparoscopy/pelvic/abdominal operations</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_abdominal_operations'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_abdominal_operations_text'].'</td>
    </tr>
    <tr>
    <td>Other operations</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_operations'].'</td>
	 <td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_operations_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    </tr>


    <tr>
    <th style="color: red;">ALLERGY HISTORY</th>
    <td>
    <table width="100%">
    <tbody><tr>
    <td>Medications</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['medications'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['medications_text'].'</td>
    </tr>
    <tr>
    <td>environmental factors</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['environmental_factors'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['environmental_factors_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    <td>
    <table width="100%">
    <tbody><tr>
    <td>Medications</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_medications'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_medications_text'].'</td>
    </tr>
    <tr>
    <td>environmental factors</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_environmental_factors'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_environmental_factors_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    </tr>
    
    
    <tr>
    <th style="color: red;">SOCIAL &amp; DRUG INTAKE HISTORY</th>
    <td>
    <table>
    <tbody><tr>
    <td>Dentures</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['dentures'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['dentures_text'].'</td>
    </tr>
    <tr>
    <td>Loose teeth</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['loose_teeth'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['loose_teeth_text'].'</td>
    </tr>
    <tr>
    <td>Hearing aid</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['hearing_aid'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['hearing_aid_text'].'</td>
    </tr>
    <tr>
    <td>Caps on front teeth</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['caps_on_front_teeth'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['caps_on_front_teeth_text'].'</td>
    </tr>
    <tr>
    <td>Contact lenses</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['contact_lenses'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['contact_lenses_text'].'</td>
    </tr>
    <tr>
    <td>Body piercing</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['body_piercing'].'</td>
	 <td style="border:1px solid #000; width:20%;">'.$patient_result['body_piercing_text'].'</td>
    </tr>
    <tr>
    <td>H/o blood transfusion</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['blood_transfusion'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_transfusion_text'].'</td>
    </tr>
    <tr>
    <td>H/o road traffic accident/any injury</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['traffic_accident'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['traffic_accident_text'].'</td>
    </tr>
    <tr>
    <td>Smoke(past)daily</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_past'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_past_text'].'</td>
    </tr>
    <tr>
    <td>Smoke(present)daily</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_present'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_present_text'].'</td>
    </tr>
    <tr>
    <td>Drink(past)units per week</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['drink_past'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['drink_past_text'].'</td>
    </tr>
    <tr>
    <td>Drink(present)units per week</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['drink_present'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['drink_present_text'].'</td>
    </tr>
    <tr>
    <td>Hashish/cocaine /abusive drugs</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['abusive_drugs'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['abusive_drugs_text'].'</td>
    </tr>
    <tr>
    <td>Have you ever used cortisone/steroid</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['steroid'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['steroid_text'].'</td>
    </tr>
    <tr>
    <td>Medication</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['medication'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['medication_text'].'</td>
    </tr>
    <tr>
    <td>Herbal products</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['herbal_products'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['herbal_products_text'].'</td>
    </tr>
    <tr>
    <td>Eye drops</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['eye_drops'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['eye_drops_text'].'</td>
    </tr>
    <tr>
    <td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['non_prescription_drugs'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['non_prescription_drugs_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    <td>
    <table>
    <tbody><tr>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_dentures'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_dentures_text'].'</td>
    </tr>
    <tr>
    <td>Loose teeth</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_loose_teeth'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_loose_teeth_text'].'</td>
    </tr>
    <tr>
    <td>Hearing aid</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hearing_aid'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hearing_aid_text'].'</td>
    </tr>
    <tr>
    <td>Caps on front teeth</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_caps_on_front_teeth'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_caps_on_front_teeth_text'].'</td>
    </tr>
    <tr>
    <td>Contact lenses</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_contact_lenses'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_contact_lenses_text'].'</td>
    </tr>
    <tr>
    <td>Body piercing</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_body_piercing'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_body_piercing_text'].'</td>
    </tr>
    <tr>
    <td>H/o blood transfusion</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_transfusion'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_transfusion_text'].'</td>
    </tr>
    <tr>
    <td>H/o road traffic accident/any injury</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_traffic_accident'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_traffic_accident_text'].'</td>
    </tr>
    <tr>
    <td>Smoke(past)daily</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_past'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_past_text'].'</td>
    </tr>
    <tr>
    <td>Smoke(present)daily</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_present'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_present_text'].'</td>
    </tr>
    <tr>
    <td>Drink(past)units per week</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_past'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_past_text'].'</td>
    </tr>
    <tr>
    <td>Drink(present)units per week</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_present'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_present_text'].'</td>
    </tr>
    <tr>
    <td>Hashish/cocaine /abusive drugs</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_abusive_drugs'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_abusive_drugs_text'].'</td>
    </tr>
    <tr>
    <td>Have you ever used cortisone/steroid</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_steroid'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_steroid_text'].'</td>
    </tr>
    <tr>
    <td>Medication</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_medication'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_medication_text'].'</td>
    </tr>
    <tr>
    <td>Herbal products</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_herbal_products'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_herbal_products_text'].'</td>
    </tr>
    <tr>
    <td>Eye drops</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_eye_drops'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_eye_drops_text'].'</td>
    </tr>
    <tr>
    <td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['male_non_prescription_drugs'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_non_prescription_drugs_text'].'</td>
    </tr>
    </tbody></table>
    </td>
    </tr>
    
    
    <tr>
    <th style="color: red;">FAMILY HISTORY</th>
    <td>
    <table width="100%">
    <tbody><tr>
    <td>Any family member any problem <br> with anesthesia</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['member_with_anesthesia'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['member_with_anesthesia_text'].'</td>
    </tr>
    </tbody></table>
    <table>
    <tbody><tr>
    <td></td>
    <td>Maternal</td>
	<td></td>
    <td>Paternal</td>
	<td></td>
    </tr>
    <tr>
    <td>Diabetes</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_diabetes'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_diabetes_text'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_diabetes'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_diabetes_text'].'</td>
    </tr>
    <tr>
    <td>Heart/thrombo embolism</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_thrombo_embolism'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_thrombo_embolism_text'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_thrombo_embolism'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_thrombo_embolism_text'].'</td>
    </tr>
    <tr>
    <td>Endocrine/metabolic</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_metabolic'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_metabolic_text'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_metabolic'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_metabolic_text'].'</td>
    </tr>
    <tr>
    <td>Urinary tract/renal</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_urinary_tract'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_urinary_tract_text'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_urinary_tract'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_urinary_tract_text'].'</td>
    </tr>
    <tr>
    <td>Psychiatric/neurological</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_neurological'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_neurological_text'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_neurological'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_neurological_text'].'</td>
    </tr>
    <tr>
    <td>Other/malignancy</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_malignancy'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_malignancy_text'].'</td>
    <td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_malignancy'].'</td>
	<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_malignancy_text'].'</td>
	<td style="border:1px solid #000; width:40%;">'.$patient_result['male_others_msg'].'</td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>GENERAL<br> EXAMINATION</th>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Nutritional assessment :-</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_nutritional_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Psychological assessment :-</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_psychological_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Anxious</br>combative</br>depressed</br>normal</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_mood_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Pulse</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_pulse'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Blood pressure</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_blood_pressure'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Temperature</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_temperature'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">CVS</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_cvs'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Chest</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_chest'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Abdomen</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_abdomen'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:20%;">
    
                <td style="border:1px solid #000; width:60%;">Others</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['female_general_exam_others'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
        <td style="border:1px solid #000; width:100%;">
    
        <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Nutritional assessment :-</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['nutritional_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Psychological assessment :-</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['psychological_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Anxious</br>combative</br>depressed</br>normal</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['anxious_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Pulse</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['pulse_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Blood pressure</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['bp_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Temperature</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['temp_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">CVS-</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['cvs_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Chest</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['chest_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Abdomen</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['abdomen_assessment'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:50%;">Others</td>
    
                <td style="border:1px solid #000; width:50%;">'.$patient_result['assessment_others'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>LOCAL<br> EXAMINATION</th>
    
    <td style="border:1px solid #000; width:100%;">
    
        <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">P/S</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_ps'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">P/V</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_pv'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">PAP SMEAR TAKEN</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_pap'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">HVS C&S TAKEN</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hvs_taken'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">ENDOMETRIAL BIOPSY HPE/TB QUANTIFERON</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_endometrial_biopsy'].'</td>
    
            </tr>
    
        </table>
    
    </td>
    
    <td style="border:1px solid #000; width:100%;">
    
    <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">UROSURGEON FINDINGS (ATTACH PRESCRIPTION)</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['urosurgeon_findings'].' <a href="'.$patient_result['prescription'].'">Download</a></td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>MANAGEMENT<br> ADVISED</th>
    
    <td style="border:1px solid #000; width:20%;">
    
    '.$procedure_html.'
    
    </td>
    
    </tr>
	
	<tr style="border:1px solid #000; width:100%;">
    
    <th>PACKAGE<br> ADVISED</th>
    
    <td style="border:1px solid #000; width:20%;">
    
    '.$package_html.'
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>DETAILS OF <br>MANAGEMENT ADVISED</th>
    
    <td style="border:1px solid #000; width:40%;">'.$patient_result['details_management_advised'].'</td>
    
    </tr>
	
	<tr style="border:1px solid #000; width:100%;">
    
    <th>Inform on day one of menstrual cycle</th>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
    
    <th>REASON FOR <br>ADVISED MANAGEMENT</th>
    
    <td colspan="2">
    
        <table width="100%">
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">LOW OVARIAN RESERVE</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['low_ovarian_reserve_evidence'].' ('.$patient_result['low_ovarian_reserve_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">TUBAL FACTOR</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['tubal_factor_evidence'].' ('.$patient_result['tubal_factor_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">MALE FACTOR</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_factor_evidence'].' ('.$patient_result['male_factor_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">ENDOMETRIOSIS</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['endometriosis_evidence'].' ('.$patient_result['endometriosis_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">PCOS</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['pcos_evidence'].' ('.$patient_result['pcos_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">UNEXPLAINED INFERTILITY</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['unexplained_infertility_evidence'].' ('.$patient_result['unexplained_infertility_evidence_date'].')</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Others</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['advised_reason_other_evidence'].' ('.$patient_result['advised_reason_other_evidence_date'].')</td>
    
            </tr>
    
        </table>
    
    </td>
    
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;" colspan="3">Medicine Opd</td>
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;" colspan="2">Female</td>
        <td style="border:1px solid #000; padding:10px">male</td>
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
        <td colspan="2">'.$female_medicine_html.'</td>
        <td>'.$male_medicine_html.'</td>
    </tr>
        <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;" colspan="3">Medicine Ipd</td>
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;" colspan="2">Female</td>
        <td style="border:1px solid #000; padding:10px">male</td>
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
        <td colspan="2">'.$female_medicine_html_ipd.'</td>
        <td>'.$male_medicine_html_ipd.'</td>
    </tr>
	
	 <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;">Provisional Diagnosis (ICD 10 CODES)</td>
        <td style="border:1px solid #000; padding:10px">Female</td>
        <td style="border:1px solid #000; padding:10px">male</td>
    </tr>
    
    <tr>
        <th style="border:1px solid #000; padding:10px;"></th>
        <td style="border:1px solid #000; padding:10px;">'.$provisional_diagnosis_suggestion_html.'</td>
        <td style="border:1px solid #000; padding:10px;">'.$provisional_diagnosis_suggestion_html.'</td>
    </tr>
    
    <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;">Investigations</td>
        <td style="border:1px solid #000; padding:10px">Female</td>
        <td style="border:1px solid #000; padding:10px">male</td>
    </tr>
    
    <tr>
        <th style="border:1px solid #000; padding:10px;"></th>
        <td style="border:1px solid #000; padding:10px;">'.$female_investation_html.'</td>
        <td style="border:1px solid #000; padding:10px;">'.$male_investation_html.'</td>
    </tr>
    
	 <tr style="border:1px solid #000; width:100%;">
        <td style="border:1px solid #000; padding:10px;">IIC Investigations</td>
        <td style="border:1px solid #000; padding:10px">Female</td>
        <td style="border:1px solid #000; padding:10px">male</td>
    </tr>
    
    <tr>
        <th style="border:1px solid #000; padding:10px;"></th>
        <td style="border:1px solid #000; padding:10px;">'.$female_minvestation_html.'</td>
        <td style="border:1px solid #000; padding:10px;">'.$male_minvestation_html.'</td>
    </tr>
    
    </tbody>
    
    </table>';
    
    return $info_html;
    
}


//Patient Medical Info.

function patient_medical_info($patient_id){

    //return "";

	$ci = &get_instance();

	$ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];



   $patient_sql = "Select * from ".$db_prefix."patient_medical_info RIGHT join ".$db_prefix."doctor_consultation on ".$db_prefix."doctor_consultation.patient_id=".$db_prefix."patient_medical_info.patient_id where ".$db_prefix."doctor_consultation.patient_id='$patient_id' AND ".$db_prefix."doctor_consultation.final_mode='1' order by ".$db_prefix."doctor_consultation.ID ASC limit 1";

    

    $patient_q = $ci->db->query($patient_sql);

    $patient_result = $patient_q->result_array();

    //var_dump($patient_result);die;

    if(empty($patient_result)){

        $response = "<h3 class='error'>Reports not uploaded Yet!<h3>";

        return $response;

    }

    $patient_result = $patient_result[0];

	//$get_procedure_data = get_procedure_data();

	$procedure_suggestion = $patient_result['procedure_suggestion'];

	$sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $procedure_html = "";

    if($procedure_suggestion == 1){

        $procedure_html = "<ul>";

        

        if(!empty($sub_procedure_suggestion_list)){

            foreach($sub_procedure_suggestion_list as $key => $vals){

                $sub_procedure_data = get_procedure_data($vals);

                $procedure_html .= "<li>".$sub_procedure_data."</li>";

            }

        }

        $procedure_html .= "</ul>";

    }
	
	$package_suggestion = $patient_result['package_suggestion'];

	$package_suggestion_list = unserialize($patient_result['package_suggestion_list']);

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $package_html = "";

    if($package_suggestion == 1){

        $package_html = "<ul>";

        

        if(!empty($package_suggestion_list)){

            foreach($package_suggestion_list as $key => $vals){

                $sub_package_data = get_package_data($vals);

                $package_html .= "<li>".$sub_package_data."</li>";

            }

        }

        $package_html .= "</ul>";

    }

    $medicine_suggestion = $patient_result['medicine_suggestion'];

	//var_dump($medicine_suggestion = $patient_result['medicine_suggestion']);die;

    $male_medicine_html = $female_medicine_html = $male_medicine_html_ipd = $female_medicine_html_ipd = "";

    if($medicine_suggestion == 1){        

	    $male_medicine_suggestion_list = unserialize($patient_result['male_medicine_suggestion_list']);
        $female_medicine_suggestion_list = unserialize($patient_result['female_medicine_suggestion_list']);
        $male_medicine_suggestion_list_ipd=unserialize($patient_result['male_medicine_suggestion_list_ipd']);
        $female_medicine_suggestion_list_ipd=unserialize($patient_result['female_medicine_suggestion_list_ipd']); 
        if(!empty($male_medicine_suggestion_list)){

            $male_medicine_html = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">

                                        <thead>

                                            <tr>

                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>

                                            <tbody id="male_medicine_suggestion_table">';                                           

                                            foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                $male_medicine_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html .= "<tr> <td style='border:1px solid #000; padding:10px;'>".get_medicine_name($vals['male_medicine_name'])."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_dosage']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_when_start']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_days']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_route']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_frequency']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_timing']."</td><td style='border:1px solid #000; padding:10px;'>".$male_medicine_take."</td></tr>";

                                            }                                      

            $male_medicine_html .= '</tbody> </thead> </table>';

           

        }
        if(!empty($female_medicine_suggestion_list)){

            $female_medicine_html .= '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">

                                        <thead>

                                            <tr>

                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>

                                            <tbody id="male_medicine_suggestion_table">';                                           

                                            foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_medicine_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html .= "<tr>

                                                        <td style='border:1px solid #000; padding:10px;'>".get_medicine_name($vals['female_medicine_name'])."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_dosage']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_when_start']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_days']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_route']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_frequency']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_timing']."</td>
                                                        <td style='border:1px solid #000; padding:10px;'>".$female_medicine_take."</td>

                                                    </tr>";

                                            }                                          

            $female_medicine_html .= '</tbody> </thead> </table>';

        }
        if(!empty($male_medicine_suggestion_list_ipd)){
            $male_medicine_html_ipd = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
                                        <thead>
                                            <tr>
                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                <th style="border:1px solid #000; padding:10px;">Days</th>
                                                <th style="border:1px solid #000; padding:10px;">Route</th>
                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>
                                            </tr>
                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            foreach($male_medicine_suggestion_list_ipd['male_medicine_suggestion_list'] as $key => $vals){//var_dump($vals);die;
                                                $male_medicine_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                $male_medicine_html_ipd .= "<tr> <td style='border:1px solid #000; padding:10px;'>".get_medicine_name($vals['male_medicine_name'])."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_dosage']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_when_start']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_days']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_route']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_frequency']."</td><td style='border:1px solid #000; padding:10px;'>".$vals['male_medicine_timing']."</td><td style='border:1px solid #000; padding:10px;'>".$male_medicine_take."</td></tr>";

                                            }                                      
            $male_medicine_html_ipd .= '</tbody> </thead> </table>';
        }
         if(!empty($female_medicine_suggestion_list_ipd)){
            $female_medicine_html_ipd .= '<table style="width:100%; border:1px solid #000;" id="male_medicine_table_ipd" border="1">
                                        <thead>
                                            <tr>
                                                <th style="border:1px solid #000; padding:10px;">Medicine</th>

                                                <th style="border:1px solid #000; padding:10px;">Dosage</th>

                                                <th style="border:1px solid #000; padding:10px;">Start on</th>

                                                <th style="border:1px solid #000; padding:10px;">Days</th>

                                                <th style="border:1px solid #000; padding:10px;">Route</th>

                                                <th style="border:1px solid #000; padding:10px;">Frequency</th>

                                                <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                <th style="border:1px solid #000; padding:10px;">Take</th>

                                            </tr>

                                            <tbody id="male_medicine_suggestion_table_ipd">';                                           
                                            foreach($female_medicine_suggestion_list_ipd['female_medicine_suggestion_list'] as $key => $vals){
                                                $female_medicine_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                $female_medicine_html_ipd .= "<tr>
                                                        <td style='border:1px solid #000; padding:10px;'>".get_medicine_name($vals['female_medicine_name'])."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_dosage']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_when_start']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_days']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_route']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_frequency']."</td>

                                                        <td style='border:1px solid #000; padding:10px;'>".$vals['female_medicine_timing']."</td>
                                                        <td style='border:1px solid #000; padding:10px;'>".$female_medicine_take."</td>

                                                    </tr>";

                                            }                                          
            $female_medicine_html_ipd .= '</tbody> </thead> </table>';

        }
    }
    $investation_suggestion = $patient_result['investation_suggestion'];

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $male_investation_html = $female_investation_html =  $male_minvestation_html = $female_minvestation_html = "";

    if($investation_suggestion == 1){        

        $male_investigation_suggestion_list = unserialize($patient_result['male_investigation_suggestion_list']);

        $female_investigation_suggestion_list = unserialize($patient_result['female_investigation_suggestion_list']);
		
		 $male_minvestigation_suggestion_list = unserialize($patient_result['male_minvestigation_suggestion_list']);

        $female_minvestigation_suggestion_list = unserialize($patient_result['female_minvestigation_suggestion_list']);



        $male_investation_html = "<ul>";

        

        if(!empty($male_investigation_suggestion_list)){

            foreach($male_investigation_suggestion_list as $key => $vals){//var_dump($vals);die;

                $investigation_name = get_investigation_name($vals);

                $male_investation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $male_investation_html .= "</ul>";



        $female_investation_html = "<ul>";

        

        if(!empty($female_investigation_suggestion_list)){

            foreach($female_investigation_suggestion_list as $key => $vals){

                $investigation_name = get_investigation_name($vals);

                $female_investation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $female_investation_html .= "</ul>";
		
		
		   $male_minvestation_html = "<ul>";

        

        if(!empty($male_minvestigation_suggestion_list)){

            foreach($male_minvestigation_suggestion_list as $key => $vals){ 

                $investigation_name = get_master_investigation_name($vals);

                $male_minvestation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $male_minvestation_html .= "</ul>";



        $female_minvestation_html = "<ul>";

        

        if(!empty($female_minvestigation_suggestion_list)){

            foreach($female_minvestigation_suggestion_list as $key => $vals){

                $investigation_name = get_master_investigation_name($vals);

                $female_minvestation_html .= "<li>".$investigation_name."</li>";

            }

        }

        $female_minvestation_html .= "</ul>";

    }

	

	$info_html = "";

	$info_html = '<table id="example1" class="table" border="1" style="width:100%;">
    <tbody>
        <tr style="border:1px solid #000; width:100%;">
            <td style="border:1px solid #000; width:100%;" colspan="2">Initial assessment report</td>
        </tr>'.patient_next_followup($patient_id).'
    </tbody>
    </table> <table id="example1" class="table" border="1" style="width:100%;">

    <thead>

        <th></th>

        <th>Female</th>

        <th>Male</th>

    </thead>

    <tbody>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">NAME</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_name'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_name'].'</td>

        </tr>
		
		<tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">Blood Group</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_blood_grp'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_blood_grp'].'</td>

        </tr>
		
		<tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">NATIONALITY</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_nationality'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_nationality'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">OCCUPATION</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_occupation'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_occupation'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">ETHNICITY</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_ethnicity'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_ethnicity'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">WT/HT/BMI</th>

            <td style="border:1px solid #000; width:40%;"><span> Weight: '.$patient_result['female_wt_ht_bmi'].'</span><span style="margin-left:50px;"> Height: '.$patient_result['female_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['female_bmi'].'</span></td>

            <td style="border:1px solid #000; width:40%;"><span> Weight: '.$patient_result['male_wt_ht_bmi'].'</span><span style="margin-left:50px;"> Height: '.$patient_result['male_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['male_bmi'].'</span></td>

        </tr>

<tr style="border:1px solid #000; width:100%;">

<th>

    H/O PREVIOUS PREGNANCIES<br>

    (IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )

</th>

<td style="border:1px solid #000; width:100%;">

    <table width="100%">

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>Total pregnancies</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_total_pregnancies'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of live births</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_live_birth'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of spontaneous abortions in first trimester</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_spontaneous_abortions'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of termination of pregnancy</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_termination_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of still births</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_still_births'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No. of ectopic pregnancy</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_ectopic_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>History of any abnormality in child</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_abnormality_child'].'</td>

        </tr>

         <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"> <span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">P - '.$patient_result['female_pregnancy_other_p'].' </span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">L - '.$patient_result['female_pregnancy_other_l'].'</span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">A - '.$patient_result['female_pregnancy_other_a'].'</span></td>

            <td style="border:1px solid #000; width:40%;"></td>

        </tr>
        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>Others</p></td>

            <td style="border:1px solid #000; width:40%;"><p>'.$patient_result['female_pregnancy_other'].'</p></td>

        </tr>

    </table>

</td>

<td style="border:1px solid #000; width:100%;">

    <!-- <h1 style="margin-top:50px;">Tick the right option</h1> -->

    <table width="100%">

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>Total pregnancies</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_total_pregnancies'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of live births</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_live_birth'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of spontaneous abortions in first trimester</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_spontaneous_abortions'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of termination of pregnancy</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_termination_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No.of still births</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_still_births'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>No. of ectopic pregnancy</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_ectopic_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>History of any abnorfemality in child</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_abnormality_child'].'</td>

        </tr>
		<tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"> <span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">P - '.$patient_result['male_pregnancy_other_p'].' </span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">L - '.$patient_result['male_pregnancy_other_l'].'</span><span style="margin-left:20px;border: 1px solid #000;padding: 5px 10px;">A - '.$patient_result['male_pregnancy_other_a'].'</span></td>

            <td style="border:1px solid #000; width:40%;"></td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:60%;"><p>Others</p></td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_pregnancy_other'].'</td>

        </tr>

    </table>

</tr>

<tr>

    <th>SEXUAL HISTORY</th>

    <td>

    

        <table style="border:1px solid #000;width:100%;">

            <tr style="border:1px solid #000;">

                <td style="width:60%;">Marital life</td>

                <td>'.$patient_result['female_marital_life'].'</td>

            </tr>
            
            
            <tr style="border:1px solid #000;">

                <td>Active marital life</td>

                <td>'.$patient_result['female_active_marital'].'</td>

            </tr>
            

            <tr style="border:1px solid #000;">

                <td>No.of sexual partners</td>

                <td>'.$patient_result['female_sexual_partners'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td>Duration of sexual partners</td>

                <td>'.$patient_result['female_duration_sexual'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td>Frequency of sexual intercourse</td>

                <td>'.$patient_result['female_frequency_sexual'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td>Contraception used</td>

                <td>'.$patient_result['female_contraception'].'</td>

            </tr>
			
			 <tr style="border:1px solid #000;">

                <td>Erection disorder</td>

                <td>'.$patient_result['female_erection_disorder'].'</td>

            </tr>
			
			 <tr style="border:1px solid #000;">

                <td>Ejaculation disorder</td>

                <td>'.$patient_result['female_ejaculation_disorder'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td>Others</td>

                <td>'.$patient_result['female_sexual_other'].'</td>

            </tr>

        </table>

    </td>

    <td>

        <center><p>Marital life</p></center>

        <table>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;width:60%;"> Marital life</td>

                <td>'.$patient_result['male_marital_life'].'</td>

            </tr>
            
            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Active marital life</td>

                <td>'.$patient_result['male_active_marital'].'</td>

            </tr>
            

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">No.of sexual partners</td>

                <td style="border:1px solid #000;">'.$patient_result['male_sexual_partners'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Duration of sexual partners</td>

                <td style="border:1px solid #000;">'.$patient_result['male_duration_sexual'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Frequency of sexual intercourse</td>

                <td style="border:1px solid #000;">'.$patient_result['male_frequency_sexual'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Contraception used</td>

                <td style="border:1px solid #000;">'.$patient_result['male_contraception'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Erection disorder</td>

                <td style="border:1px solid #000;">'.$patient_result['male_erection_disorder'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Ejaculation disorder</td>

                <td style="border:1px solid #000;">'.$patient_result['male_ejaculation_disorder'].'</td>

            </tr>

            <tr style="border:1px solid #000;">

                <td style="border:1px solid #000;">Others</td>

                <td style="border:1px solid #000;">'.$patient_result['male_sexual_other'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>TYPE OF INFERTILITY</th>

      <td style="border:1px solid #000; width:20%;"> '.$patient_result['female_intertiliy_type'].' </td>
    
    <td style="border:1px solid #000; width:20%;"> '.$patient_result['male_intertiliy_type'].' </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PAST GYNECOLOGICAL/UROLOGICAL HISTORY</th>

    <td style="border:1px solid #000; width:100%;">

        <table>
		
		<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Dysmenorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_dysmenorrhea'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Menorrhagia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_menorrhagia'].'</td>

            </tr>
			
			

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">H/o D and c</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_h_o_dandc'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Dyspareunia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_dyspareunia'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Orchidopexies</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_orchidopexies'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Mumps/orchitis</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_mumps_orchitis'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hernia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hernia'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Varicocele</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_varicocele'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_history_other'].'</td>

            </tr>

        </table>

    </td>

  <td style="border:1px solid #000; width:100%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Dysmenorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_dysmenorrhea'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Menorrhagia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_menorrhagia'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">H/o D and c</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_h_o_dandc'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Dyspareunia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_dyspareunia'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Orchidopexies</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_orchidopexies'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Mumps/orchitis</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_mumps_orchitis'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hernia</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hernia'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Varicocele</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_varicocele'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_history_other'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>MENSTRUATION HISTORY</th>

    <td colspan="1">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_menarche_age'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_flow'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_frequencye'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_galactorrhea'].'</td>

            </tr>

        </table>

    </td>
	<td colspan="1">

        <table>
		
		 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_menarche_age'].'</td>

            </tr>
			
			 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_flow'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_frequency'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_galactorrhea'].'</td>

            </tr>

        </table>

    </td>

</tr>








<tr>
<th style="color: red;">PAST /PRESENT MEDICAL HISTORY</th>
<td>
<table width="100%">
<tbody><tr>
<td style="border:1px solid #000; width:40%;">Heart attack</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_attack'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_attack_text'].'</td>
</tr>
<tr>
<td>Pacemaker</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['pacemaker'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['pacemaker_text'].'</td>
</tr>
<tr>
<td>Other heart disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['other_heart_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['other_heart_disease_text'].'</td>
</tr>
<tr>
<td>High blood pressure</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['high_blood_pressure'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['high_blood_pressure_text'].'</td>
</tr>
<tr>
<td>Blood clots</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_clots'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_clots_text'].'</td>
</tr>
<tr>
<td>Chest pain</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['chest_pain'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['chest_pain_text'].'</td>
</tr>
<tr>
<td>Stroke</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['stroke'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['stroke_text'].'</td>
</tr>
<tr>
<td>Asthma</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['asthma'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['asthma_text'].'</td>
</tr>
<tr>
<td>Other lung disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['lung_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['lung_disease_text'].'</td>
</tr>
<tr>
<td>Difficulty breathing</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['difficulty_breathing'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['difficulty_breathing_text'].'</td>
</tr>
<tr>
<td>Sleep apnea or snoring</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['snoring'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['snoring_text'].'</td>
</tr>
<tr>
<td>Epilepsy or seizures</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['epilepsy'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['epilepsy_text'].'</td>
</tr>
<tr>
<td>Fainting spells</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['fainting_spells'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['fainting_spells_text'].'</td>
</tr>
<tr>
<td>Diabetes</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['diabetes'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['diabetes_text'].'</td>
</tr>
<tr>
<td>Muscle disorders</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['muscle_disorders'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['muscle_disorders_text'].'</td>
</tr>
<tr>
<td>Kidney disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['kidney_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['kidney_disease_text'].'</td>
</tr>
<tr>
<td>Hepatitis</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['hepatitis'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['hepatitis_text'].'</td>
</tr>
<tr>
<td>Tuberculosis</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['tuberculosis'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['tuberculosis_text'].'</td>
</tr>
<tr>
<td>HIV</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['hiv'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['hiv_text'].'</td>
</tr>
<tr>
<td>Heart burn/reflux</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_burn'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_burn_text'].'</td>
</tr>
<tr>
<td>Cancer </td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['cancer'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['cancer_text'].'</td>
</tr>
<tr>
<td>Blood disorders</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_disorders'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_disorders_text'].'</td>
</tr>
<tr>
<td>Rheumatic disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['rheumatic_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['rheumatic_disease_text'].'</td>
</tr>
<tr>
<td>Psychiatric disorder</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['psychiatric_disorder'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['psychiatric_disorder_text'].'</td>
</tr>
<tr>
<td>Thyroid disorder</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['thyroid_disorder'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['thyroid_disorder_text'].'</td>
</tr>
<tr>
<td>Urinary infection</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['urinary_infection'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['urinary_infection_text'].'</td>
</tr>
<tr>
<td>Sexually transmitted disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['sexually_transmitted'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['sexually_transmitted_text'].'</td>
</tr>
<tr>
<td>Other medical condition or impairments</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['heart_attack'].'</td>
</tr>
</tbody></table>
</td>
<td>
<table width="100%">
<tbody><tr>
<td style="border:1px solid #000; width:40%;">Heart attack</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_attack'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_attack_text'].'</td>
</tr>
<tr>
<td>Pacemaker</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_pacemaker'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_pacemaker_text'].'</td>
</tr>
<tr>
<td>Other heart disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_heart_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_heart_disease_text'].'</td>
</tr>
<tr>
<td>High blood pressure</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_high_blood_pressure'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_high_blood_pressure_text'].'</td>
</tr>
<tr>
<td>Blood clots</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_clots'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_clots_text'].'</td>
</tr>
<tr>
<td>Chest pain</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_chest_pain'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_chest_pain_text'].'</td>
</tr>
<tr>
<td>Stroke</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_stroke'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_stroke_text'].'</td>
</tr>
<tr>
<td>Asthma</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_asthma'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_lung_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_lung_disease_text'].'</td>
</tr>
<tr>
<td>Difficulty breathing</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_difficulty_breathing'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_difficulty_breathing_text'].'</td>
</tr>
<tr>
<td>Sleep apnea or snoring</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_snoring'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_snoring_text'].'</td>
</tr>
<tr>
<td>Epilepsy or seizures</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_epilepsy'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_epilepsy_text'].'</td>
</tr>
<tr>
<td>Fainting spells</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fainting_spells'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fainting_spells_text'].'</td>
</tr>
<tr>
<td>Diabetes</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_diabetes'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_diabetes_text'].'</td>
</tr>
<tr>
<td>Muscle disorders</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_muscle_disorders'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_muscle_disorders_text'].'</td>
</tr>
<tr>
<td>Kidney disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_kidney_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_kidney_disease_text'].'</td>
</tr>
<tr>
<td>Hepatitis</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hepatitis'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hepatitis_text'].'</td>
</tr>
<tr>
<td>Tuberculosis</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_tuberculosis'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_tuberculosis_text'].'</td>
</tr>
<tr>
<td>HIV</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hiv'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hiv_text'].'</td>
</tr>
<tr>
<td>Heart burn/reflux</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_burn'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_heart_burn_text'].'</td>
</tr>
<tr>
<td>Cancer </td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_cancer'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_cancer_text'].'</td>
</tr>
<tr>
<td>Blood disorders</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_disorders'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_disorders_text'].'</td>
</tr>
<tr>
<td>Rheumatic disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_rheumatic_disease'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_rheumatic_disease_text'].'</td>
</tr>
<tr>
<td>Psychiatric disorder</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_psychiatric_disorder'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_psychiatric_disorder_text'].'</td>
</tr>
<tr>
<td>Thyroid disorder</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_thyroid_disorder'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_thyroid_disorder_text'].'</td>
</tr>
<tr>
<td>Urinary infection</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection_text'].'</td>
</tr>
<tr>
<td>Sexually transmitted disease</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_sexually_transmitted'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_sexually_transmitted_text'].'</td>
</tr>
<tr>
<td>Other medical condition or impairments</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_urinary_infection_text'].'</td>
</tr>
</tbody></table>
</td>
</tr>

<tr>
<th style="color: red;">PAST ANESTHESIA AND SURGICAL PROCEDURES</th>
<td>
<table>
<tbody><tr>
<td>Laparoscopy/pelvic/abdominal operations</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['abdominal_operations'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['abdominal_operations_text'].'</td>
</tr>
<tr>
<td>Other operations</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['other_operations'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['other_operations_text'].'</td>
</tr>
</tbody></table>
</td>
<td>
<table>
<tbody><tr>
<td>Laparoscopy/pelvic/abdominal operations</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_abdominal_operations'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_abdominal_operations_text'].'</td>
</tr>
<tr>
<td>Other operations</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_operations'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_other_operations_text'].'</td>
</tr>
</tbody></table>
</td>
</tr>


<tr>
<th style="color: red;">ALLERGY HISTORY</th>
<td>
<table width="100%">
<tbody><tr>
<td>Medications</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['medications'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['medications_text'].'</td>
</tr>
<tr>
<td>environmental factors</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['environmental_factors'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['environmental_factors_text'].'</td>
</tr>
</tbody></table>
</td>
<td>
<table width="100%">
<tbody><tr>
<td>Medications</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_medications'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_medications_text'].'</td>
</tr>
<tr>
<td>environmental factors</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_environmental_factors'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_environmental_factors_text'].'</td>
</tr>
</tbody></table>
</td>
</tr>


<tr>
<th style="color: red;">SOCIAL &amp; DRUG INTAKE HISTORY</th>
<td>
<table>
<tbody><tr>
<td>Dentures</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['dentures'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['dentures_text'].'</td>
</tr>
<tr>
<td>Loose teeth</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['loose_teeth'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['loose_teeth_text'].'</td>
</tr>
<tr>
<td>Hearing aid</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['hearing_aid'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['hearing_aid_text'].'</td>
</tr>
<tr>
<td>Caps on front teeth</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['caps_on_front_teeth'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['caps_on_front_teeth_text'].'</td>
</tr>
<tr>
<td>Contact lenses</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['contact_lenses'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['contact_lenses_text'].'</td>
</tr>
<tr>
<td>Body piercing</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['body_piercing'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['body_piercing_text'].'</td>
</tr>
<tr>
<td>H/o blood transfusion</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_transfusion'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['blood_transfusion_text'].'</td>
</tr>
<tr>
<td>H/o road traffic accident/any injury</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['traffic_accident'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['traffic_accident_text'].'</td>
</tr>
<tr>
<td>Smoke(past)daily</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_past'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_past_text'].'</td>
</tr>
<tr>
<td>Smoke(present)daily</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_present'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['smoke_present_text'].'</td>
</tr>
<tr>
<td>Drink(past)units per week</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['drink_past'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['drink_past_text'].'</td>
</tr>
<tr>
<td>Drink(present)units per week</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['drink_present'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['drink_present_text'].'</td>
</tr>
<tr>
<td>Hashish/cocaine /abusive drugs</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['abusive_drugs'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['abusive_drugs_text'].'</td>
</tr>
<tr>
<td>Have you ever used cortisone/steroid</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['steroid'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['steroid_text'].'</td>
</tr>
<tr>
<td>Medication</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['medication'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['medication_text'].'</td>
</tr>
<tr>
<td>Herbal products</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['herbal_products'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['herbal_products_text'].'</td>
</tr>
<tr>
<td>Eye drops</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['eye_drops'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['eye_drops_text'].'</td>
</tr>
<tr>
<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['non_prescription_drugs'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['non_prescription_drugs_text'].'</td>
</tr>
</tbody></table>
</td>
<td>
<table>
<tbody><tr>
<td>Dentures	</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_dentures'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_dentures_text'].'</td>
</tr>
<tr>
<td>Loose teeth</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_loose_teeth'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_loose_teeth_text'].'</td>
</tr>
<tr>
<td>Hearing aid</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hearing_aid'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_hearing_aid_text'].'</td>
</tr>
<tr>
<td>Caps on front teeth</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_caps_on_front_teeth'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_caps_on_front_teeth_text'].'</td>
</tr>
<tr>
<td>Contact lenses</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_contact_lenses'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_contact_lenses_text'].'</td>
</tr>
<tr>
<td>Body piercing</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_body_piercing'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_body_piercing_text'].'</td>
</tr>
<tr>
<td>H/o blood transfusion</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_transfusion'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_blood_transfusion_text'].'</td>
</tr>
<tr>
<td>H/o road traffic accident/any injury</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_traffic_accident'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_traffic_accident_text'].'</td>
</tr>
<tr>
<td>Smoke(past)daily</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_past'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_past_text'].'</td>
</tr>
<tr>
<td>Smoke(present)daily</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_present'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_smoke_present_text'].'</td>
</tr>
<tr>
<td>Drink(past)units per week</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_past'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_past_text'].'</td>
</tr>
<tr>
<td>Drink(present)units per week</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_present'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_drink_present_text'].'</td>
</tr>
<tr>
<td>Hashish/cocaine /abusive drugs</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_abusive_drugs'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_abusive_drugs_text'].'</td>
</tr>
<tr>
<td>Have you ever used cortisone/steroid</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_steroid'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_steroid_text'].'</td>
</tr>
<tr>
<td>Medication</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_medication'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_medication_text'].'</td>
</tr>
<tr>
<td>Herbal products</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_herbal_products'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_herbal_products_text'].'</td>
</tr>
<tr>
<td>Eye drops</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_eye_drops'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_eye_drops_text'].'</td>
</tr>
<tr>
<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_non_prescription_drugs'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_non_prescription_drugs_text'].'</td>
</tr>
</tbody></table>
</td>
</tr>


<tr>
<th style="color: red;">FAMILY HISTORY</th>
<td>
<table width="100%">
<tbody><tr>
<td>Any family member any problem <br> with anesthesia</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['member_with_anesthesia'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['member_with_anesthesia_tex'].'</td>
</tr>
</tbody></table>
<table>
<tbody><tr>
<td></td>
<td>Maternal</td>
<td></td>
<td>Paternal</td>
<td></td>
</tr>
<tr>
<td>Diabetes</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_diabetes'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_diabetes_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_diabetes'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_diabetes_text'].'</td>
</tr>
<tr>
<td>Heart/thrombo embolism</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_thrombo_embolism'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_thrombo_embolism_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_thrombo_embolism'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_thrombo_embolism_text'].'</td>
</tr>
<tr>
<td>Endocrine/metabolic</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_metabolic'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_metabolic_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_metabolic'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_metabolic_text'].'</td>
</tr>
<tr>
<td>Urinary tract/renal</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_urinary_tract'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_urinary_tract_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_urinary_tract'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_urinary_tract_text'].'</td>
</tr>
<tr>
<td>Psychiatric/neurological</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_neurological'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_neurological_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_neurological'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_neurological_text'].'</td>
</tr>
<tr>
<td>Other/malignancy</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_malignancy'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_malignancy_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_malignancy'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['paternal_malignancy_text'].'</td>
</tr>
</tbody></table>
</td>
<td>
<table width="100%">
<tbody><tr>
<td>Any family member any problem <br> with anesthesia</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_member_with_anesthesia'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_member_with_anesthesia_text'].'</td>
</tr>
</tbody></table>
<table>
<tbody><tr>
<td></td>
<td>Maternal</td>
<td></td>
<td>Paternal</td>
<td></td>
</tr>
<tr>
<td>Diabetes</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_diabetes'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_diabetes_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_diabetes'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_diabetes_text'].'</td>
</tr>
<tr>
<td>Heart/thrombo embolism</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_thrombo_embolism'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_thrombo_embolism_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_thrombo_embolism'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_thrombo_embolism_text'].'</td>
</tr>
<tr>
<td>Endocrine/metabolic</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_metabolic'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['maternal_metabolic_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_metabolic'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_metabolic_text'].'</td>
</tr>
<tr>
<td>Urinary tract/renal</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_urinary_tract'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_urinary_tract_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_urinary_tract'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_urinary_tract_text'].'</td>
</tr>
<tr>
<td>Psychiatric/neurological</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_neurological'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_neurological_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_neurological'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_neurological_text'].'</td>
</tr>
<tr>
<td>Other/malignancy</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_malignancy'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_maternal_malignancy_text'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_malignancy'].'</td>
<td style="border:1px solid #000; width:20%;">'.$patient_result['male_paternal_malignancy_text'].'</td>
</tr>
</tbody></table>
</td>
</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PAST INVESTIGATIONS</th>

    <td style="border:1px solid #000; width:100%;">

        <p><b>SERUM AMH</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_1'].'</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_result_1'].'</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_3'].'</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_amh_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <p><b>SERUM FSH</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_result_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_fsh_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <p><b>HSG</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_result_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_hsg_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <p><b>USG OF PELVIS</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_result_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['female_pelvis_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_past_investigation_others'].'</td>

            </tr>

        </table>

    </td>

    <td style="border:1px solid #000; width:20%;">

        <p><b>SEMEN ANALYSIS</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT(Ng/ml)</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT(Ng/ml)</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT(Ng/ml)</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_result_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_semen_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <p><b>SERUM FSH</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_result_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_fsh_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <p><b>SERUM TESTOSTERONE</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_result_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_result_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['male_testost_dt_result_3'].'</td>

            </tr>

        </table>

        <br>

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_past_investigation_others'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PREVIOUS INFERTILITY TREATMENT DETAILS </th>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">YEARS OF TAKING INFERTILITY TREATMENT</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_infertility_treatment_years'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">OVULATION INDUCTION DRUGS HOW MANY CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_induction_drugs_cycles'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">OVULATION INDUCTION INJECTION TAKEN HOW MANY CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_induction_injection_cycles'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">TOTAL NO. OF IUI CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_iui_cycles'].'</td>
    
            </tr>
    
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">TOTAL NO . OF IVF/ICSI CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_ivf_icsi_cycles'].'</td>
    
            </tr>
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Total No. OF STIMULATED IVF CYCLES</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_stimulated_ivf_cycles'].'</td>
    
            </tr>
            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">Total No. cycles with no evidence of fertilization</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_no_evidence_fertilization'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">NO. OF EGGS RETREIVED EACH CYCLE</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_egg_retreived'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:80%;">NO. OF FRESH CYCLE</td>
    
                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_fresh_cycle'].'</td>
    
            </tr>
        </table>

    </td>

    <td style="border:1px solid #000; width:20%;">

        <table>

           <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">MEDICATIONS FOR SPERM</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['medication_for_sperm'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">NO. OF TIMES TESA DONE</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['no_of_tesa'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">TESA REPORT</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['tesa_report'].'</td>
    
            </tr>

            <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">TESE REPORT</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['tese_report'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">MICRO TESE</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['micro_tese'].'</td>
    
            </tr>
             <tr style="border:1px solid #000; width:40%;">
    
                <td style="border:1px solid #000; width:60%;">OTHERS</td>
    
                <td style="border:1px solid #000; width:40%;">'.$patient_result['other_s'].'</td>
    
            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">
    
    <th>Others</th>
    
    <td style="border:1px solid #000; width:40%;">'.$patient_result['female_others_msg'].'</td>
	<td style="border:1px solid #000; width:40%;">'.$patient_result['male_others_msg'].'</td>
    
    </tr>

<tr style="border:1px solid #000; width:100%;">

    <th>GENERAL EXAMINATION</th>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Nutritional assessment :-Obese/average built/thin/cachexic</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_nutritional_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Psychological assessment :-</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_psychological_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Anxious/combative/depressed/normal</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_mood_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Pulse</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pulse'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Blood pressure</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_blood_pressure'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Temperature</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_temperature'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">CVS</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_cvs'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Chest</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_chest'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Abdomen</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_abdomen'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_general_exam_others'].'</td>

            </tr>

        </table>

    </td>

        <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Nutritional assessment :-Obese/average built/thin/cachexic</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['nutritional_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Psychological assessment :-</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['psychological_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Anxious/combative/depressed/normal</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['anxious_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Pulse</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['pulse_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Blood pressure</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['bp_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Temperature</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['temp_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">CVS-</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['cvs_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Chest</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['chest_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Abdomen</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['abdomen_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Others</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['assessment_others'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>LOCAL EXAMINATION</th>

    <td style="border:1px solid #000; width:20%;">

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">P/S</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_ps'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">P/V</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_pv'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">PAP SMEAR TAKEN</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_exam_pap'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">HVS C&S TAKEN</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hvs_taken'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">ENDOMETRIAL BIOPSY HPE/TB QUANTIFERON</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_endometrial_biopsy'].'</td>

            </tr>

        </table>

    </td>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">UROSURGEON FINDINGS (ATTACH PRESCRIPTION)</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['urosurgeon_findings'].' <a href="'.$patient_result['prescription'].'">Download</a></td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>MANAGEMENT ADVISED</th>

	<td style="border:1px solid #000; width:20%;">

	'.$procedure_html.'

	</td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PACKAGE ADVISED</th>

	<td style="border:1px solid #000; width:20%;">

	'.$package_html.'

	</td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>DETAILS OF MANAGEMENT ADVISED</th>

    <td style="border:1px solid #000; width:70%;">'.$patient_result['details_management_advised'].'</td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>REASON FOR ADVISED MANAGEMENT</th>

    <td colspan="2">

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">LOW OVARIAN RESERVE</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['low_ovarian_reserve_evidence'].' ('.$patient_result['low_ovarian_reserve_evidence_date'].')</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">TUBAL FACTOR</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['tubal_factor_evidence'].' ('.$patient_result['tubal_factor_evidence_date'].')</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">MALE FACTOR</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_factor_evidence'].' ('.$patient_result['male_factor_evidence_date'].')</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">ENDOMETRIOSIS</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['endometriosis_evidence'].' ('.$patient_result['endometriosis_evidence_date'].')</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">PCOS</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['pcos_evidence'].' ('.$patient_result['pcos_evidence_date'].')</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">UNEXPLAINED INFERTILITY</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['unexplained_infertility_evidence'].' ('.$patient_result['unexplained_infertility_evidence_date'].')</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['advised_reason_other_evidence'].' ('.$patient_result['advised_reason_other_evidence_date'].')</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">
    <td style="border:1px solid #000; padding:10px;" colspan="3">Medicines Opd</td>
</tr>

<tr style="border:1px solid #000; width:100%;">
    <td colspan="2">'.$female_medicine_html.'</td>
    <td>'.$male_medicine_html.'</td>
</tr>
<tr style="border:1px solid #000; width:100%;">
    <td style="border:1px solid #000; padding:10px;" colspan="3">Medicines Ipd</td>
</tr>

<tr style="border:1px solid #000; width:100%;">
    <td colspan="2">'.$female_medicine_html_ipd.'</td>
    <td>'.$male_medicine_html_ipd.'</td>
</tr>
<tr style="border:1px solid #000; width:100%;">
    <td style="border:1px solid #000; padding:10px;" colspan="3">IIC Investigations</td>
</tr>

<tr style="border:1px solid #000; width:100%;">
    <td colspan="2">'.$female_minvestation_html.'</td>
    <td>'.$male_minvestation_html.'</td>
</tr>

<th style="border:1px solid #000; padding:10px;">Investigations</th>

<td style="border:1px solid #000; padding:10px;">'.$female_investation_html.'</td>

<td style="border:1px solid #000; padding:10px;">'.$male_investation_html.'</td>

</tr>

</tbody>

</table>';

return $info_html;

}




//Patient Medical Info for doctor

function patient_medical_data($patient_id){

    return "";

	$ci = &get_instance();

	$ci->load->database();

	$db_prefix = $ci->config->config['db_prefix'];

	

    // $patient_sql = "Select * from ".$db_prefix."patient_medical_info where  patient_id='".$patient_id."' order by ID desc limit 1";

    $patient_sql = "Select * from ".$db_prefix."patient_medical_info RIGHT join ".$db_prefix."doctor_consultation on ".$db_prefix."doctor_consultation.patient_id=".$db_prefix."patient_medical_info.patient_id where ".$db_prefix."doctor_consultation.patient_id='$patient_id' AND ".$db_prefix."doctor_consultation.final_mode='1' order by ".$db_prefix."doctor_consultation.ID DESC limit 1";

	$patient_q = $ci->db->query($patient_sql);

    $patient_result = $patient_q->result_array();

    if(empty($patient_result)){

        $response = "<h3 class='error'>Reports not uploaded Yet!<h3>";

        return $response;

    }

    $patient_result = $patient_result[0];

    

	//$get_procedure_data = get_procedure_data();

	$procedure_suggestion = $patient_result['procedure_suggestion'];

	$sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);

	//var_dump($parent_proce$procedure_suggestiondure_data);die;

    $procedure_html = "";

    if($procedure_suggestion == 1){

        $procedure_html = "<ul>";

        if(!empty($sub_procedure_suggestion_list)){

            foreach($sub_procedure_suggestion_list as $key => $vals){

                $sub_procedure_data = get_procedure_data($vals);

                $procedure_html .= "<li>".$sub_procedure_data."</li>";

            }

        }

        $procedure_html .= "</ul>";

    }
	
	//var_dump($procedure_html);die;
	
		$package_suggestion = $patient_result['package_suggestion'];

	$package_suggestion_list = unserialize($patient_result['package_suggestion_list']);

	//var_dump($package_html);die;

    $package_html = "";

    if($package_suggestion == 1){

        $package_html = "<ul>";

        if(!empty($package_suggestion_list)){

            foreach($package_suggestion_list as $key => $vals){

                $sub_package_data = get_package_data($vals);

                $package_html .= "<li>".$sub_package_data."</li>";

            }

        }

        $package_html .= "</ul>";

    }

	

	$info_html = "";

	$info_html = '<h4>INITIAL ASSESSMENT SHEET</h4> <h4>Medical Info</h4> <table id="example1" class="table" border="1" style="width:100%;">

    <thead>

        <th></th>

        <th>Female</th>

        <th>Male</th>

    </thead>

    <tbody>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">NAME</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_name'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_name'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">OCCUPATION</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_occupation'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_occupation'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">ETHNICITY</th>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['female_ethnicity'].'</td>

            <td style="border:1px solid #000; width:40%;">'.$patient_result['male_ethnicity'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <th style="border:1px solid #000; width:20%;">WT/HT/BMI</th>

            <td style="border:1px solid #000; width:40%;"><span> Weight: '.$patient_result['female_wt_ht_bmi'].'</span><span style="margin-left:50px;"> Height: '.$patient_result['female_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['female_bmi'].'</span></td>

            <td style="border:1px solid #000; width:40%;"><span> Weight: '.$patient_result['male_wt_ht_bmi'].'</span><span style="margin-left:50px;"> Height: '.$patient_result['male_ht'].'</span><span style="margin-left:50px;"> BMI: '.$patient_result['male_bmi'].'</span></td>

        </tr>

<tr style="border:1px solid #000; width:100%;">

<th>

    H/O PREVIOUS PREGNANCIES<br>

    (IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )

</th>

<td style="border:1px solid #000; width:100%;">

    <table width="100%">

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>Total pregnancies</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_total_pregnancies'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of live births</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_live_births'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of spontaneous abortions in first trimester</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_spontaneous_abortions'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of termination of pregnancy</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_termination_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of still births</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_no_of_still_births'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No. of ectopic pregnancy</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_ectopic_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>History of any abnormality in child</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_any_abnormality'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>Others</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['female_others'].'</td>

        </tr>

    </table>

</td>

<td style="border:1px solid #000; width:100%;">

    <!-- <h1 style="margin-top:50px;">Tick the right option</h1> -->

    <table width="100%">

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>Total pregnancies</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_total_pregnancies'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of live births</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_live_births'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of spontaneous abortions in first trimester</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_spontaneous_abortions'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of termination of pregnancy</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_termination_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No.of still births</p></td>

			<td style="border:1px solid #000; width:20%;">'.$patient_result['male_no_of_still_births'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>No. of ectopic pregnancy</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_ectopic_pregnancy'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>History of any abnorfemality in child</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_any_abnorfemality'].'</td>

        </tr>

        <tr style="border:1px solid #000; width:100%;">

            <td style="border:1px solid #000; width:80%;"><p>Others</p></td>

            <td style="border:1px solid #000; width:20%;">'.$patient_result['male_others'].'</td>

        </tr>

    </table>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>TYPE OF INFERTILITY</th>

    <td style="border:1px solid #000; width:20%;"></td>

    <td style="border:1px solid #000; width:20%;"></td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PAST GYNECOLOGICAL/UROLOGICAL HISTORY</th>

    <td style="border:1px solid #000; width:100%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">H/o D and c</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hod'].'</td>

            </tr>

        </table>

    </td>

    <td style="border:1px solid #000; width:20%;"></td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>MENSTRUATION HISTORY</th>

    <td colspan="1">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_menarche_age'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_flow'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_frequencye'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_galactorrhea'].'</td>

            </tr>

        </table>

    </td>
	<td colspan="1">

        <table>
		
		 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Age at menarche</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_menarche_age'].'</td>

            </tr>
			
			 <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Flow- heavy/average/less</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_flow'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Frequency- regular /irregular</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_frequency'].'</td>

            </tr>
			
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Days</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_days'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Hirsutism</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_hirsutism'].'</td>

            </tr>
			<tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Galactorrhea</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_galactorrhea'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PAST INVESTIGATIONS</th>

    <td style="border:1px solid #000; width:100%;">

        <p><b>SERUM AMH</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_amh_male_dt_1'].'</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['serum_amh_male_result'].'</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['serum_amh_male_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_amh_male_re_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_amh_male_dt_3'].'</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['serum_amh_male_re_2'].'</td>

            </tr>

        </table>

        <br>

        <p><b>SERUM FSH</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_male_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_male_result'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_male_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_male_re_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_male_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_male_re_2'].'</td>

            </tr>

        </table>

        <br>

        <p><b>HSG</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['hsg_male_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['hsg_male_result'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['hsg_male_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['hsg_male_re_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['hsg_male_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['hsg_male_re_2'].'</td>

            </tr>

        </table>

        <br>

        <p><b>USG OF PELVIS</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['usg_male_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['usg_male_result'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['usg_male_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['usg_male_re_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['usg_male_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['usg_male_re_2'].'</td>

            </tr>

        </table>

        <br>

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_investigation_others'].'</td>

            </tr>

        </table>

    </td>

    <td style="border:1px solid #000; width:20%;">

        <p><b>SEMEN ANALYSIS</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">
                <td style="border:1px solid #000; width:80%;">DT</td>
                <td style="border:1px solid #000; width:80%;">TC</td>
				<td style="border:1px solid #000; width:80%;">M</td>
				<td style="border:1px solid #000; width:80%;">MP</td>
				<td style="border:1px solid #000; width:80%;">DT</td>
				<td style="border:1px solid #000; width:80%;">TC</td>
				<td style="border:1px solid #000; width:80%;">M</td>
				<td style="border:1px solid #000; width:80%;">MP</td>
            </tr>

            <tr style="border:1px solid #000; width:100%;">
                <td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_dt_1'].'</td>
                <td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_tc_1'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_m_1'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_mp_1'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_dt_2'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_tc_2'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_m_2'].'</td>
				<td style="border:1px solid #000; width:20%;">'.$patient_result['semen_analysis_female_mp_2'].'</td>
            </tr>

        </table>

        <br>

        <p><b>SERUM FSH</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_female_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_female_result'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_female_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_female_re_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_female_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_fsh_female_re_2'].'</td>

            </tr>

        </table>

        <br>

        <p><b>SERUM TESTOSTERONE</b></p>

        <table width="100%" class="border-field">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RESULT</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

                <td style="border:1px solid #000; width:20%;">DT</td>

                <td style="border:1px solid #000; width:20%;">RE</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_testo_female_dt_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_testo_female_result'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_testo_female_dt_2'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_testo_female_re_1'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_testo_female_dt_3'].'</td>

				<td style="border:1px solid #000; width:20%;">'.$patient_result['serum_testo_female_re_1'].'</td>

            </tr>

        </table>

        <br>

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_invsetigation_others'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PREVIOUS INFERTILITY TREATMENT DETAILS </th>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">YEARS OF TAKING INFERTILITY TREATMENT</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_years_of_taking_infertility_treatment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">OVULATION INDUCTION DRUGS HOW MANY CYCLES</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_ovulation_induction_drug'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">TOTAL NO. OF IUI CYCLES</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_total_no_of_iui_cycles'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">TOTAL NO . OF IVF/ICSI CYCLES</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_total_no_of_ivf_cycles'].'</td>

            </tr>

        </table>

    </td>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">FNAC TESTES DONE WITH REPORT</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_fnac_testes'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>GENERAL EXAMINATION</th>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Nutritional assessment :-Obese/average built/thin/cachexic</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_obese'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Psychological assessment :-</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_psychological_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Anxious/combative/depressed/normal</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_anxious'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Pulse</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pulse'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Blood pressure</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_blood_pressure_value'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Temperature</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_temp'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">CVS</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_cvs'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Chest</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_chest'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Abdomen</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_abdomen'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_general_info_others'].'</td>

            </tr>

        </table>

    </td>

        <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Nutritional assessment :-Obese/average built/thin/cachexic</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_obese'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Psychological assessment :-</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_psychological_assessment'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Anxious/combative/depressed/normal</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_anxious'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Pulse</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_pulse'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Blood pressure</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_blood_pressure_value'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Temperature</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_temp'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">CVS-</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_cvs'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Chest</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_chest'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Abdomen</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_abdomen'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:60%;">Others</td>

                <td style="border:1px solid #000; width:40%;">'.$patient_result['male_general_info_others'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>LOCAL EXAMINATION</th>

    <td style="border:1px solid #000; width:20%;">

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">P/S</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_p_s'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">P/V</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_local_p_v'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">PAP SMEAR TAKEN</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['pap_smear_taken'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">HVS C&S TAKEN</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_hvs_c_s_taken'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">ENDOMETRIAL BIOPSY HPE/TB QUANTIFERON</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_endometrial_biopsy'].'</td>

            </tr>

        </table>

    </td>

    <td style="border:1px solid #000; width:20%;">

        <table>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">UROSURGEON FINDINGS (ATTACH PRESCRIPTION)</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['male_urosurgeon_findings'].'</td>

            </tr>

        </table>

    </td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>MANAGEMENT ADVISED</th>

	<td style="border:1px solid #000; width:20%;">

	'.$procedure_html.'

	</td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>PACKAGE ADVISED</th>

	<td style="border:1px solid #000; width:20%;">

	'.$package_html.'

	</td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>DETAILS OF MANAGEMENT ADVISED</th>

    <td style="border:1px solid #000; width:70%;">'.$patient_result['detail_management_advised'].'</td>

</tr>

<tr style="border:1px solid #000; width:100%;">

    <th>REASON FOR ADVISED MANAGEMENT</th>

    <td colspan="2">

        <table width="100%">

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">LOW OVARIAN RESERVE</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_low_ovarian'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">TUBAL FACTOR</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_tubal_factor'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">MALE FACTOR</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_factor'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">ENDOMETRIOSIS</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_endometriosis'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">PCOS</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_pcos'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">UNEXPLAINED INFERTILITY</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_unexplained_infertility'].'</td>

            </tr>

            <tr style="border:1px solid #000; width:100%;">

                <td style="border:1px solid #000; width:80%;">Others</td>

                <td style="border:1px solid #000; width:20%;">'.$patient_result['female_reason_for_am_others'].'</td>

            </tr>

        </table>

    </td>

</tr>

</tbody>

</table>';

return $info_html;

}

function patient_next_followup($patient_id){
    //return "";
	$ci = &get_instance();
	$ci->load->database();
	$db_prefix = $ci->config->config['db_prefix'];
    $patient_sql = "SELECT appoitmented_date, appoitmented_slot FROM `".$db_prefix."appointments` WHERE paitent_id='$patient_id' AND follow_up_appointment='1' AND billed='0' ORDER BY ID DESC LIMIT 1";
    $patient_q = $ci->db->query($patient_sql);
    $patient_result = $patient_q->row();
    if($patient_result){
        return '<tr style="border:1px solid #000; width:100%;"><td colspan="3">
                    <strong>Next appointment on '.$patient_result->appoitmented_date.' at '.$patient_result->appoitmented_slot.'</strong></td></tr>';
    }
    return "";
}





function get_center_detail($center)

{
	$ci= &get_instance();

    $ci->load->database();
    $sql = "SELECT * FROM hms_centers WHERE center_number  = '".$center."'";
   // echo $sql; die;
      $q   = $ci->db->query($sql);
    $result = $q->result_array(); 
    
   
    if(count($result) > 0)

    {

		    return $result[0];    	

    }

    return $result;

}

/**
 * Get the billing "At" center based on logged-in user's center classification
 * 
 * @param string $billing_at_center_id The center ID where billing is happening
 * @return array Array containing 'center_name' and 'center_id' for display
 */
function get_billing_at_center_display($billing_at_center_id) {
    $CI =& get_instance();
        if (!isset($_SESSION['logged_billing_manager']) || !isset($_SESSION['logged_billing_manager']['center'])) {
        return array(
            'center_name' => get_center_name($billing_at_center_id),
            'center_id' => $billing_at_center_id
        );
    }
    
    $logged_center_id = $_SESSION['logged_billing_manager']['center'];
    $CI->load->model('hub_spoke_model');
    $center_classification = $CI->hub_spoke_model->get_center_classification_for_billing($logged_center_id);
    if ($center_classification['classification'] == 'hub') {
        $center_sql = "SELECT center_name FROM " . $CI->config->item('db_prefix') . "centers WHERE center_number = ?";
        $center_query = $CI->db->query($center_sql, array($logged_center_id));
        $center_result = $center_query->result_array();
        if (!empty($center_result)) {
            return array(
                'center_name' => $center_result[0]['center_name'],
                'center_id' => $logged_center_id
            );
        }
    }
    if ($center_classification['classification'] == 'spoke') {
        if (!empty($center_classification['hub_center_name'])) {
            return array(
                'center_name' => $center_classification['hub_center_name'],
                'center_id' => $center_classification['hub_center_id']
            );
        }
    }
    return array(
        'center_name' => get_center_name($billing_at_center_id),
        'center_id' => $billing_at_center_id
    );
}

   

?>