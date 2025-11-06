<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('Asia/Calcutta');



class Doctors_model extends CI_Model

{

	function login($data)
    {
		$result = array();
		$sql_condition = '';
		$sql = "Select * from " . $this->config->item('db_prefix') . "doctors WHERE username='".$data['email']."' AND status='1'";
        $q = $this->db->query($sql);
        $user_result = $q->result_array();
        if (count($user_result) > 0)
        {
			if($user_result[0]['center_id'] != 0)
			{ $sql_condition = ' and emp.center_id = center.center_number and center.status="1"'; }
			$new_sql = "SELECT emp.*, emp.ID as doctor_id 
						FROM ".$this->config->item('db_prefix')."doctors as emp
						WHERE emp.username='".$data['email']."' 
						AND emp.password ='".md5($data['password'])."' 
						AND emp.status='1' 
						";
			$new_q = $this->db->query($new_sql);
			// var_dump($new_q->result_array());
			// die;
		//    $new_sql = "Select *, emp.ID as doctor_id from ".$this->config->item('db_prefix')."doctors as emp, ".$this->config->item('db_prefix')."centers as center WHERE emp.username='".$data['email']."' AND emp.password ='".md5($data['password'])."' AND emp.status='1' ".$sql_condition."";
	 	//    $new_q = $this->db->query($new_sql);
		   $affected_rows = $new_q->result_array();		   
		   if (count($affected_rows) > 0)
	       {
				unset($_SESSION['logged_doctor']);
				$_SESSION['logged_doctor'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'doctor_id'=>$affected_rows[0]['doctor_id'], 'is_primary'=>$affected_rows[0]['is_primary'], 'role' => 'doctor', 'junior_doctor'=>$affected_rows[0]['junior_doctor'], 'psychological'=>$affected_rows[0]['psychological'],'center'=>$affected_rows[0]['center_id']);
				$result = array('status' => 1);
            	return $result;
			}else{
				$result = array('status' => 0);
            	return $result;		
			}
        }
        else
        {
			$result = array('status' => 0);
            return $result;		

        }

	}
	public function get_consultation_medicines($center_id, $department = 'billing')
    {
        try {
            // --- FIX: Add this line ---
            $this->db->distinct();

            $this->db->select('
                m.medicine_name as item_name, 
                m.medicine_code as item_number,
                m.id as medicine_id
            '); // --- FIX: Removed DISTINCT from here ---
            $this->db->from('center_stocks ccs');
            $this->db->join('hms_centers c', 'ccs.center_id = c.ID');
            $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id');
            // --- FIX: Corrected typo $this. to $this-> ---
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            // Apply original conditions based on new schema
            $this->db->where('ccs.status', 'ACTIVE'); // Replaces status='1'
            // --- FIX: Corrected typo $this. to $this-> ---
            $this->db->where('m.status', 'active');
            $this->db->where('mb.batch_status', 'ACTIVE');
            // $this->db->where('mb.expiry_date >', date('Y-m-d')); // Only show non-expired
            $this->db->where('ccs.available_quantity >', 0); // Only show if stock exists
            $this->db->where('ccs.center_id',$center_id); // Replaces center_number
	        if ($department) {
                $this->db->where('ccs.department', $department);
            }
            $this->db->order_by('m.medicine_name', 'ASC');
            return $this->db->get()->result_array();

        } catch (Exception $e) {
            log_message('error', 'get_consultation_medicines: ' . $e->getMessage());
            return [];
        }
    }

	// public function get_consultation_medicines($center_code, $department = 'billing')
    // {
    //         $this->db->select('
    //             DISTINCT m.medicine_name as item_name, 
    //             m.medicine_code as item_number,
    //             m.id as medicine_id
    //         ');
    //         $this->db->from('center_stocks ccs');
    //         $this->db->join('hms_centers c', 'ccs.center_id = c.ID');
    //         $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id');
    //         $this->db->join('medicines m', 'mb.medicine_id = m.id');
    //         // Apply original conditions based on new schema
    //         $this->db->where('ccs.status', 'ACTIVE'); // Replaces status='1'
    //         $this->db->where('m.status', 'active');
    //         $this->db->where('mb.batch_status', 'ACTIVE');
    //         $this->db->where('mb.expiry_date >', date('Y-m-d')); // Only show non-expired
    //         $this->db->where('ccs.available_quantity >', 0); // Only show if stock exists
    //         $this->db->where('c.center_code', $center_code); // Replaces center_number
    //         if ($department) {
    //             $this->db->where('ccs.department', $department);
    //         }
    //         $this->db->group_by('m.medicine_name, m.medicine_code, m.id');
    //         $this->db->order_by('m.medicine_name', 'ASC');
	// 		var_dump($this->db->get()->result_array());
    //         return $this->db->get()->result_array();
    // }
	public function consultation_medicine()
    {
	    $this->load->model('stock_model_new');
        $center_code = $this->session->userdata('logged_doctor')['center'] ?? null;
		$center_id =$this->stock_model_new->get_center_id($center_code);
        if (!$center_id) {
            return [];
        }
        return $this->get_consultation_medicines($center_id, 'billing');
    }
	public function consultation_medicine_ipd()
    {
	    $this->load->model('stock_model_new');
        $center_code = $this->session->userdata('logged_doctor')['center'] ?? null;
		$center_id =$this->stock_model_new->get_center_id($center_code);
        if (!$center_id) {
            return [];
        }
        return $this->get_consultation_medicines($center_id, 'Hormonal');
    }
	// public function consultation_medicine_ipd(){
	// 	$result = array();
	// 	$sql_condition = '';
	// 	$sql ="Select DISTINCT item_name, item_number from ".$this->config->item('db_prefix')."center_stocks where status='1' and center_number='".$_SESSION['logged_doctor']['center']."' and department='Hormonal' GROUP BY item_name, item_number";
	// 	$q = $this->db->query($sql);
    //     $result = $q->result_array();
    //     if (!empty($result))
    //     {
    //         return $result;
    //     }
    //     else
    //     {
    //         return $result;
    //     }
	// }

	// function consultation_medicine(){
	// 	$result = array();
	// 	$sql_condition = '';
	//   	$sql ="Select DISTINCT item_name, item_number from ".$this->config->item('db_prefix')."center_stocks where status='1' and center_number='".$_SESSION['logged_doctor']['center']."' and department='billing' GROUP BY item_name, item_number";
	// 	$q = $this->db->query($sql);
    //     $result = $q->result_array();
    //     if (!empty($result))
    //     {
    //         return $result;
    //     }
    //     else
    //     {
    //         return $result;
    //     }
	// }


	

	

	function get_doctor_consultations(){

		$result = array();

		$sql_condition = '';

	  	$sql ="Select * from ".$this->config->item('db_prefix')."doctor_consultation where final_mode='1' ORDER by consultation_date DESC";

		$q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        else

        {

            return $result;

        }

	}

	function doctor_data_by_username($doctor_username){
		$this->db->select('*');
		$this->db->where('username', $doctor_username);
		$this->db->from($this->config->item('db_prefix').'doctors');
		$query = $this->db->get();
		return $query->row();
	}

	function wl_doctor_appointment_lists($doctor_id, $appointment_date){
		$center = get_doctor_centre($doctor_id);
		$sql_condition = "appoitmented_doctor='$doctor_id'";
		
		$today = date('Y-m-d');
		$date_condition = " >= '".$today."'";
	
		if(!empty($appointment_date)){
		    $date_condition = " = '".$appointment_date."'";
		}
        
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $sql_condition and status!='consultation_done' and status!='no_show' and status!='cancelled' and appoitmented_date ".$date_condition." order by appoitmented_date ASC limit 10";
		
		$q = $this->db->query($sql);
	    $result = $q->result_array();
	    if (!empty($result))
	    {
			return $result;
        }else{
			return $result;
		}
	}
	

	function disapprove_consultation_done($id, $reason){

		$sql = "UPDATE `" . $this->config->item('db_prefix') . "doctor_consultation` SET `final_mode`='0', `disapproval_reason` = '$reason' where ID='$id'";

		$this->db->query($sql);

        return $this->db->affected_rows();

	}



	function get_doctor_appointment($appointment_id){

		$result = array();

		$sql_condition = '';

		$sql = "Select appoitmented_date, appoitmented_slot from ".$this->config->item('db_prefix')."appointments where ID='".$appointment_id."'";

	

        $q = $this->db->query($sql);

        $result = $q->result_array();

		$appointment_date = "";

        if (!empty($result))

        {

			$appointment_date  = $result[0]['appoitmented_date']." ".$result[0]['appoitmented_slot'];

            return $appointment_date;

        }

        else

        {

            return $appointment_date;

        }

	}

	

	function consultation_done($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "doctor_consultation` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$sql .= implode(',' , $sqlArr);
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;

	}
	
	/*function package_done($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "package` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$sql .= implode(',' , $sqlArr);
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}*/

	



/*	function patient_medical_info($data){

		 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_medical_info` SET ";

		$sqlArr = array();

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".addslashes($value)."'";			

		}		

		$sql .= implode(',' , $sqlArr);

		$res =  $this->db->query($sql);

		if ($res)

		{

			return $this->db->insert_id();

		}

		else

			return 0;

	}
*/

function patient_medical_info($data) {
    foreach ($data as $key => $value) {
        if (is_array($value) || is_object($value)) {
            $data[$key] = json_encode($value);
        }
    }

    $this->db->insert($this->config->item('db_prefix') . 'patient_medical_info', $data);
    return $this->db->insert_id();
}





	function get_doctors()
	{
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctors ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;

        }
        else
        {
            return $result;
        }
	}



	function get_junior_doctors(){

		$result = array();

		$sql_condition = '';

		$sql = "Select * from ".$this->config->item('db_prefix')."doctors where junior_doctor='1'  ORDER by ID DESC";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        else

        {

            return $result;

        }

	}

	function get_freezing(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctors where junior_doctor='1'  ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }
        else
        {
            return $result;
        }
	}

	function get_doctors_list(){

		$result = array();

		$sql_condition = '';

		$sql = "Select * from ".$this->config->item('db_prefix')."doctors where status='1' ORDER by ID DESC";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        else

        {

            return $result;

        }

	}

	

	function add_doctor($data){

		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "doctors` SET ";

		$sqlArr = array();

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".addslashes($value)."'";

		}		

		$sql .= implode(',' , $sqlArr);

		

       	$res =  $this->db->query($sql);

		if ($res)

		{

			return $this->db->insert_id();

		}

		else

			return 0;

	}



	function add_junior_doctors($data){

		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "doctors` SET ";

		$sqlArr = array();

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".addslashes($value)."'";

		}		

		$sql .= implode(',' , $sqlArr);

		

       	$res =  $this->db->query($sql);

		if ($res)

		{

			return $this->db->insert_id();

		}

		else

			return 0;

	}

	

	function get_doctor_data($item){

		$result = array();

		$sql_condition = '';

		$sql = "Select * from ".$this->config->item('db_prefix')."doctors where ID='".$item."'";

	

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0];

        }

        else

        {

            return $result;

        }

	}

	

	public function update_doctor_data($data, $item)

    {	

        $sql = "UPDATE " . config_item('db_prefix') . "doctors SET ";

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".$value."'"	;

		}

		$sql .= implode(',' , $sqlArr);

		$sql .= " WHERE ID = '".$item."'";

        $this->db->query($sql);

        return 1;

    }



	function update_doctor_relationship($junior_id, $doctor_lists)

	{

		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "doctor_relationship WHERE junior_doctor_id = '".$junior_id."'";

		$res =  $this->db->query($sql);

		

		foreach($doctor_lists as $key => $vals){

			//var_dump($junior_id);die;

			$sql = "INSERT INTO `".$this->config->item('db_prefix')."doctor_relationship` (`junior_doctor_id`, `doctor_id`) VALUES ('$junior_id', '$vals')";

			$res =  $this->db->query($sql);						

		}

		return 1;

	}

	

	public function delete_doctor_data($item){

		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "doctors WHERE ID = '".$item."'";

       	$res =  $this->db->query($sql);

		if ($res)

		{

			return 1;

		}

		else

			return 0;	

	}



	function delete_junior_doctor_data($junior_id){

		$sql = "DELETE ".$this->config->item('db_prefix')."doctors, ".$this->config->item('db_prefix')."doctor_relationship FROM ".$this->config->item('db_prefix')."doctors INNER JOIN ".$this->config->item('db_prefix')."doctor_relationship ON ".$this->config->item('db_prefix')."doctors.ID = ".$this->config->item('db_prefix')."doctor_relationship.junior_doctor_id WHERE ".$this->config->item('db_prefix')."doctor_relationship.junior_doctor_id='$junior_id'";

       	$res =  $this->db->query($sql);

		if ($res)

		{

			return 1;

		}

		else

			return 0;

	}

	

	function doctor_fees($id, $nationality){

		$condition = '';

		if($nationality == 'indian'){

			$condition = 'fees';

		}else{

			$condition = 'usd_fees';

		}

		$sql = "Select ".$condition." from ".$this->config->item('db_prefix')."doctors where ID='".$id."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0][$condition];

        }

        else

        {

            return $result;

        }

	}

	

	function center_doctors($center){

		$result = array();
		$sql = "Select ID, name from ".$this->config->item('db_prefix')."doctors where center_id='$center' and status='1' ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        return $result;
	}

	

	function doctor_appointments($doctor, $date){

		$result = $slots = array();

		$sql = "Select appoitmented_slot from ".$this->config->item('db_prefix')."appointments where appoitmented_doctor='$doctor' and appoitmented_date='$date'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        return $result;

	}

	

	function doctor_slots($doctor, $day){

		$result = $slots = array();

		$sql = "Select ".$day."_slots as slots from ".$this->config->item('db_prefix')."doctors where ID='$doctor' and ".$day."_off='0' and status='1'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

			$slots =  unserialize($result[0]['slots']);

            return $slots;

        }

        return $result;

	}

	

	function doctor_on_holiday($doctor){

		$result = 0;

		$sql = "Select on_holiday_daterange as holiday_dates from ".$this->config->item('db_prefix')."doctors where ID='$doctor' and on_holiday='1' and status='1'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['holiday_dates'];

        }

        return 0;

	}

	

	function get_center_doctors($center){

		$result = array();

		$sql = "Select ID, name from ".$this->config->item('db_prefix')."doctors where status='1'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

			return $result;

        }else{

			return $result;

		}

	}

	

	// Dashboard Start



	function procedure_form_insert($data, $form_area){

		$dsql = "DELETE FROM " .$form_area. " WHERE patient_id = '".$data['patient_id']."' and receipt_number='".$data['receipt_number']."'";

       	$dres =  $this->db->query($dsql);



		$sql = "INSERT INTO `".$form_area."` SET ";

		$sqlArr = array();

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".addslashes($value)."'";

		}		

		$sql .= implode(',' , $sqlArr);

       	$res =  $this->db->query($sql);

		if ($res)

		{

			return $this->db->insert_id();

		}

		else

			return 0;

	}



	function doctor_relationship($junior_id, $doctor_lists){



		foreach($doctor_lists as $key => $vals){

			//var_dump($junior_id);die;

			$sql = "INSERT INTO `".$this->config->item('db_prefix')."doctor_relationship` (`junior_doctor_id`, `doctor_id`) VALUES ('$junior_id', '$vals')";

			$res =  $this->db->query($sql);						

		}

		return 1;



	}



	function check_procedure_form($form_id, $patient_procedure_id, $procedure_id){

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."procedure_forms where ID='$form_id'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

			$form_area = $result[0]['form_area'];

			$prod_result = array();

			$prod_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where ID='$patient_procedure_id'";

			

			$prod_q = $this->db->query($prod_sql);

			$prod_result = $prod_q->result_array();

			if (!empty($prod_result))

			{

				$patient_id = $prod_result[0]['patient_id'];

				$receipt_number = $prod_result[0]['receipt_number'];



				$form_result = array();

				$form_sql = "Select * from ".strtolower($form_area)." where patient_id='$patient_id' and receipt_number='$receipt_number' and procedure_id='$procedure_id'";

				

				$form_q = $this->db->query($form_sql);

				$form_result = $form_q->result_array();

				if (!empty($form_result))

				{

					return $form_result;

				}else{

					return $form_result;

				}

			}else{

				return $prod_result;

			}

        }else{

			return $result;

		}

	}

	function get_doctor_count($doctor_id, $status, $start_date, $end_date, $patient_id, $patient_name){
		$center = get_doctor_centre($doctor_id);
		$sql_condition = "appoitmented_doctor='$doctor_id'";
		if($center > 0){
			$sql_condition = "appoitment_for='$center'";
		}
		if (!empty($status)){
			$sql_condition .= " and status='$status'";
		}
		if (!empty($patient_id)){
			$sql_condition .= " and paitent_id='$patient_id'";
		}
		if (!empty($patient_name)){
			$sql_condition .= " and (wife_name='$patient_name' or wife_phone = '$patient_name')";
		}
		if (!empty($start_date) && !empty($end_date)){
			$sql_condition .= " and appoitmented_date >='$start_date' and  appoitmented_date <= '$end_date'";
		}
		else if (!empty($start_date) && empty($end_date)){
			$sql_condition .= " and appoitmented_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$sql_condition .= " and appoitmented_date='$end_date'";
		}
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $sql_condition and status!='no_show' and status!='cancelled' order by appoitmented_date desc";
		$q = $this->db->query($sql);
		return $q->num_rows();
	}

	function doctor_appointment_lists_pagination($doctor_id, $limit, $page, $status, $start_date, $end_date, $patient_id, $patient_name){
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
	
		$center = get_doctor_centre($doctor_id);
		$sql_condition = "appoitmented_doctor='$doctor_id'";
		if($center > 0){
			$sql_condition = "appoitment_for='$center'";
		}
		if (!empty($status)){
			$sql_condition .= " and status='$status'";
		}
		if (!empty($patient_id)){
			$sql_condition .= " and paitent_id='$patient_id'";
		}
		if (!empty($patient_name)){
			$sql_condition .= " and (wife_name like'%$patient_name%' or wife_phone = '$patient_name')";
		}
		if (!empty($start_date) && !empty($end_date)){
			$sql_condition .= " and appoitmented_date >='$start_date' and  appoitmented_date <= '$end_date'";
		}
		else if (!empty($start_date) && empty($end_date)){
			$sql_condition .= " and appoitmented_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$sql_condition .= " and appoitmented_date='$end_date'";
		}
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $sql_condition and status not in('no_show', 'cancelled') order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
			return $result;
        }else{
			return $result;
		}
	}

	function doctor_appointment_lists($doctor_id){
		$center = get_doctor_centre($doctor_id);
		$sql_condition = "appoitmented_doctor='$doctor_id'";
		if($center > 0){
			$sql_condition = "appoitment_for='$center'";
		}

		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $sql_condition and status!='no_show' and status!='cancelled' order by appoitmented_date desc limit 10";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
			return $result;
        }else{
			return $result;
		}
	}



	function junior_doctor_appointment_lists($junior_id){

		$result = $response = array();

		$doctor_related = get_doctors_relationship($junior_id);		

		foreach($doctor_related as $key => $val){

			$sql = "Select * from ".$this->config->item('db_prefix')."appointments where appoitmented_doctor='$val' and status!='no_show' and status!='cancelled' order by appoitmented_date desc";

			$q = $this->db->query($sql);

			$result = $q->result_array();

			if(!empty($result)){

				$response[] = $result;

			}

		}

		

		return $response;

	}



	function doctor_ipd_lists($doctor_id){

		$result = array();

		// Optimized query - only select needed columns and add LIMIT for performance
		$sql = "Select ID, wife_phone, reason_of_visit, appoitmented_date, paitent_id, wife_name, husband_name 
				from ".$this->config->item('db_prefix')."appointments 
				where status!='no_show' 
				and status!='cancelled' 
				and billed='1' 
				and appoitmented_doctor='$doctor_id' 
				order by appoitmented_date desc 
				LIMIT 100";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

			return $result;

        }else{

			return $result;

		}

	}
	
	/********My IPD********/
	function ipd_data_count($wife_name, $paitent_id){
		$ipd_data = array();
		$conditions = '';
		if (!empty($wife_name)){
			$conditions .= " and wife_name='$wife_name'";
		}
		if (!empty($paitent_id)){
			$conditions .= " and paitent_id='$paitent_id'";
		}
	    $ipd_data_sql = "Select * from ".$this->config->item('db_prefix')."appointments where status!='no_show' and status!='cancelled' and billed='1' order by wife_phone and appoitmented_date desc";
	    $q = $this->db->query($ipd_data_sql);
		return $q->num_rows();
	}
	
	function ipd_data_list_patination($limit, $page, $wife_name, $paitent_id){
		$ipd_data = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name='$wife_name'";
		}
		if (!empty($paitent_id)){
			$conditions .= " and paitent_id='$paitent_id'";
		}
		$ipd_data_sql = "Select * from ".$this->config->item('db_prefix')."appointments where 1".$conditions." and status!='no_show' and status!='cancelled' and billed='1' order by wife_phone and appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$ipd_data_q = $this->db->query($ipd_data_sql);
		$ipd_data = $ipd_data_q->result_array();
		return $ipd_data;
	}
	
	/*******End My IPD********/



	function update_procedure_report_status($status, $reason, $patient_id, $receipt_number, $form_area, $data_id){

		$sql = "UPDATE `$form_area` SET `status`='$status',`disapproval_reason`='$reason' WHERE patient_id='$patient_id' AND receipt_number='$receipt_number' and id='$data_id'";

		$this->db->query($sql);

        return $this->db->affected_rows();

	}

	

	//Ajax filter

	function ajax_appointment_status_data($status){

		$condition = "";

		if(isset($_SESSION['logged_doctor'])){ 

			$doctor_id = $_SESSION['logged_doctor']['doctor_id'];

			$condition = " appoitmented_doctor='$doctor_id' and ";

		}

		

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition status='$status' ORDER by appoitmented_date DESC";

		$q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        else

        {

            return $result;

        }

	}

	

	function ajax_appointment_date_wise_data($start, $end){

		$condition = "";

		if(isset($_SESSION['logged_doctor'])){ 

			$doctor_id = $_SESSION['logged_doctor']['doctor_id'];

			$condition = " appoitmented_doctor='$doctor_id' and ";

		}

	

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition appoitmented_date between '".$start."' AND '".$end."'";

		$q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        else

        {

            return $result;

        }

	}

	

	function ajax_appointment_particular_date_data($appointment_date){

		$condition = "";

		if(isset($_SESSION['logged_doctor'])){ 

			$doctor_id = $_SESSION['logged_doctor']['doctor_id'];

			$condition = " appoitmented_doctor='$doctor_id' and ";

		}

		

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition appoitmented_date='$appointment_date' ORDER by appoitmented_date DESC";

		$q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result;

        }

        else

        {

            return $result;

        }

	}



	function check_doctor($username){

		$result = array();

		$sql = "Select count(*) as count from ".$this->config->item('db_prefix')."doctors where username='$username' order by ID desc limit 1";

		$q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['count'];

        }

        else

        {

            return $result;

        }

	}



	function check_junior_doctor($username){

		$result = array();

		$sql = "Select count(*) as count from ".$this->config->item('db_prefix')."junior_doctors where username='$username' order by ID desc limit 1";

		$q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['count'];

        }

        else

        {

            return $result;

        }

	}

/**********PCP NDT*********/

function patient_pcpndt_count($center, $start_date, $end_date, $iic_id, $type, $ID){
		$investigation_result = array();
		$conditions = '';
		
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and center="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($type)){
			$conditions .= " and type='$type'";
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and date='$end_date'";
		}

	    $investigation_sql = "Select * from pcp_ndt where 1 ".$conditions."";
		//echo $investigation_sql;
		//exit();
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
	function patient_pcpndt_list_patination($limit, $page, $center, $start_date, $end_date, $iic_id, $type, $ID){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and center="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($type)){
			$conditions .= " and type='$type'";
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and date='$end_date'";
		}
	    $investigation_sql = "Select * from pcp_ndt where 1".$conditions." order by date ASC limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	
	function export_pcpndt_data($start, $end, $center, $iic_id, $test_type){

		
		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and center="'.$center.'"';
        }
		if(!empty($test_type)){
			$conditions .= ' and test_type="'.$test_type.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and date between '".$start."' AND '".$end."' ";
        }
		
	    $investigation_sql = "Select DISTINCT iic_id, wife_name, husband_name, wife_phone, wife_age, female_issues, wife_address, details_management_advised,IVF_Consultant,procedure_done,outcome_of_tretment,center,test_type, date from pcp_ndt where 1 $conditions order by date desc";
        //echo $investigation_sql;
		//exit();
		$investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				 $response[] = array(
                        'iic_id' => $val['iic_id'],
                        'wife_name' => $val['wife_name'],
						'husband_name' => $val['husband_name'],
						'wife_phone' => $val['wife_phone'],
                        'wife_age' => $val['wife_age'],
                        'female_issues' => $val['female_issues'],
                        'wife_address' => $val['wife_address'],
                        'details_management_advised' => $val['details_management_advised'],
                        'IVF_Consultant' => $val['IVF_Consultant'],
                        'procedure_done' => $val['procedure_done'],
						'outcome_of_tretment' => $val['outcome_of_tretment'],
						'test_type' => $val['test_type'],
						'date' => $val['date'],
                        'billing_type' => 'pcpndt',
                );
            }
        }    
		return $response;
    }
	
	function get_employee_list(){
		$result = array();
		$sql_condition = '';
        $sql = "Select DISTINCT center from pcp_ndt";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function get_test_type(){
		$result = array();
		$sql_condition = '';
        $sql = "Select DISTINCT type from pcp_ndt";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function consultation_provisional_diagnosis(){
		$result = array();
		$sql_condition = '';
	  	$sql ="Select * from ".$this->config->item('db_prefix')."provisional_diagnosis where status='1'";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	
	function add_pcp_ndt($data){
		$sql = "INSERT INTO `pcp_ndt` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
    function get_doctor_patient_count($start_date, $end_date, $patient_id, $center_number){
		//$app_result = array();
		$conditions = '';
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date='$end_date'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (empty($patient_id)){
			if (!empty($center_number)){
				$conditions .= " and center_number='$center_number'";
			}
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1 ".$conditions."";
		$q = $this->db->query($investigation_r_sql);
		return $q->num_rows();
	}
	
	function doctor_patient_lists_pagination($limit, $page, $start_date, $end_date, $patient_id, $center_number){
		//$app_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date='$end_date'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (empty($patient_id)){
			if (!empty($center_number)){
				$conditions .= " and center_number='$center_number'";
			}
		}
		$app_result = array();
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1".$conditions." order by consultation_date desc limit ". $limit." OFFSET ".$offset."";
		//$investigation_r_q = $this->db->query($investigation_r_sql);
		//$app_result = $investigation_r_q->result_array();
		//return $app_result;
		$q = $this->db->query($investigation_r_sql);
		$app_result = $q->result_array();
        if (!empty($app_result))
        {
            return $app_result;
        }
        else
        {
            return $app_result;
        }
	}
	
	 function patient_duration_count($patient_id, $center ,$doctor){
		$app_result = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($doctor)){
			$conditions .= " and doctor='$doctor'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."patient_medical_info where 1 ".$conditions."";
		$q = $this->db->query($investigation_r_sql);
		return $q->num_rows();
	}
	
	function patient_duration_pagination($limit, $page, $patient_id, $center, $doctor){
		$app_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($doctor)){
			$conditions .= " and doctor='$doctor'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."patient_medical_info where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$investigation_r_q = $this->db->query($investigation_r_sql);
		$app_result = $investigation_r_q->result_array();
		return $app_result;
	}
	// Dashboard Start 
	
	function insert_add_consent_book($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "consent_book` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}		
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function consent_book_count($consent_book_name, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if (!empty($consent_book_name)){
			$conditions .= " and consent_book_name='$consent_book_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."consent_book where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function consent_book_patination($limit, $page, $consent_book_name, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($consent_book_name)){
			$conditions .= " and consent_book_name='$consent_book_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."consent_book where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	function general_instructions() {
        $this->db->from('hms_general_instructions');
         $this->db->where('status', 'active');
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }    	
	}
	
	function testicular_stem_add($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "testicular_stem` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$sql .= implode(',' , $sqlArr);
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function testicular_stem_details($receipt_number){

		$result = array();

		$sql_condition = '';

		$sql = "Select * from ".$this->config->item('db_prefix')."testicular_stem where receipt_number='".$receipt_number."'";
	
        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0];

        }

        else

        {

            return $result;

        }

	}
	
	function oocyte_activation_add($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "oocyte_activation` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$sql .= implode(',' , $sqlArr);
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function consent_for_oocyte_details($receipt_number){

		$result = array();

		$sql_condition = '';

		$sql = "Select * from ".$this->config->item('db_prefix')."oocyte_activation where receipt_number='".$receipt_number."'";
	
        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0];

        }

        else

        {

            return $result;

        }

	}
		
}



// END Stock_model class

/* End of file Stock_model.php */

/* Location: ./application/models/Stock_model.php */