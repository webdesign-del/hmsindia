<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calls extends CI_Controller {


public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model('brands_model');
		$this->load->helper('myhelper');
	}	

    public function send_batch_analytics() {
        // 1. एपीआई एंडपॉइंट और आपकी गुप्त एपीआई कुंजी (API Key)
        $url = "https://anthropod.in/api/v2/call_analytics/batch/";
        $api_key = "YOUR_ACTUAL_API_KEY_HERE"; // यहाँ अपनी असली कुंजी डालें

        // 2. डेटा तैयार करें (डेटाबेस से फेच करके या डायनेमिकली बना सकते हैं)
        $post_data = array(
            "service_id" => "SALES",
            "data" => array(
                array(
                    "id" => "177721739093381",
                    "call_time" => 1739093433,
                    "customer_id" => "35052000234718259",
                    "employee_id" => "AGENT 1",
                    "call_direction" => "Outbound",
                    "call_record_link" => "https://yourwebsite.com/recordings/call1.mp3", // असली लिंक
                    "call_answer_status" => "Connected",
                    "lead_status" => "payment_pending",
                    "follwup_time" => 1739093433
                ),
                array(
                    "id" => "17772173909343381",
                    "call_time" => 1739093433,
                    "customer_id" => "3502000234718259",
                    "employee_id" => "AGENT 2",
                    "call_direction" => "Outbound",
                    "call_record_link" => "https://yourwebsite.com/recordings/call2.mp3",
                    "call_answer_status" => "Connected",
                    "lead_status" => "payment_pending",
                    "follwup_time" => 1739093433
                )
            )
        );

        // JSON में कन्वर्ट करें
        $json_payload = json_encode($post_data);

        // 3. PHP cURL के जरिए रिपेस्ट भेजें
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $api_key,
            "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        // 4. रिस्पॉन्स चेक करें
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo "API Response: " . $response;
        }
    }

    public function analytics_list() {
        // अगर डेटाबेस से कॉल डेटा लाना है, तो उसकी क्वेरी यहाँ आएगी
        $data['page_title'] = "Call Analytics List";
        
        // यह आपके व्यू (HTML) फ़ाइल को लोड करेगा
        $this->load->view('calls/analytics_list', $data);
    }
}