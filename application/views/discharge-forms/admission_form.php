<?php $all_method =&get_instance();
$appoitmented_date = $_GET['appoitmented_date'];

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
		
	    if (!empty($appoitmented_date)) {
			$sql = "SELECT * FROM `admission_form` WHERE iic_id='$iic_id' AND appoitmented_date='$appoitmented_date'";
		} else {
			$sql = "SELECT * FROM `admission_form` WHERE iic_id='$iic_id'";
		}
		$select_result = run_select_query($sql);
        
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `admission_form` SET ";
            $sqlArr = array();
           
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
		}else{
            // mysql query to update data
            $query = "UPDATE admission_form SET ";
           
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"	;
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE iic_id='$iic_id' and appoitmented_date='$appoitmented_date'";
        }
        $result = run_form_query($query); 
        if($result){
         header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Admission form inserted!').'&t='.base64_encode('success'));
        	die();
        }else{
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
		  die();
        }
    }
	
	if (!empty($appoitmented_date)) {
			$sql = "SELECT * FROM `admission_form` WHERE iic_id='$iic_id' AND appoitmented_date='$appoitmented_date'";
	} else {
			$sql = "SELECT * FROM `admission_form` WHERE iic_id='$iic_id'";
	}
	$select_result = run_select_query($sql);
	
    $sql2 = "SELECT RIGHT(CAST(ipid AS CHAR), 3) FROM `admission_form` ORDER BY ID DESC LIMIT 1";
    $select_result2 = run_select_query($sql2);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$iic_id."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);

      $CI =& get_instance();
   $sql_forms = "SELECT id, form_name,name, db_name FROM `hms_discharge_forms` WHERE status = 'active' and role=''";
    $form_list = $CI->db->query($sql_forms)->result_array(); 
?>

<div class="ga-pro">
<h3>Admission Form</h3>

  <form action="" enctype='multipart/form-data' method="post">
	<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
	<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
	<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
	<input type="hidden" value="<?php echo $iic_id;?>" class="form" name="iic_id">
	<input type="hidden" value="<?php echo date('Y-m-d'); ?>" class="form" name="date_of_addmission">
	<input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">  
    <input type="hidden" value="<?php echo $select_result5['center_code']; ?>/<?php $year = date("y"); echo $year-1, $year; ?>/<?php echo $select_result2['RIGHT(CAST(ipid AS CHAR), 3)'] + 1;?>" class="form" id="ipid" name="ipid">	
	<div class="col-sm-12 col-md-4">
<label for="Center">Center</label>
<select class="form-control" id="center" name="center">
    <option value=''>--Select From--</option>
    <?php $all_centers = $all_method->get_all_centers();
	foreach($all_centers as $key => $val){ //var_dump($val);die;
    if($center == $val['center_number']){
    echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
    }else{
	echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
    }
    } 
	?>
    </select>
 </div> 
<div class="col-sm-12 col-md-2">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
</div>  
<div class="col-sm-12 col-md-2">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
 </div>

<div class="form-group col-md-3">
    <label>Select Discharge Form:</label>
    <select class="form-control" name="form_type">
        <option value="">-- Choose Form --</option>
        <?php if(!empty($form_list)): ?>
            <?php foreach($form_list as $form): ?>
                <option value="<?php echo $form['name']; ?>">
                    <?php echo $form['name']; ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">No forms found in database</option>
        <?php endif; ?>
    </select>
</div>
 </div>  
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
</tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IPID: <?php echo $select_result['ipid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" colspan="3">
<strong>Female Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td width="50%" colspan="3">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%">
<strong>Provisional Diagnosis:
 <textarea name="provisional_diagnosis" style="width:100%; height:80px!important;" > <?php echo isset($select_result['provisional_diagnosis'])?$select_result['provisional_diagnosis']:""; ?> </textarea>
</strong>
</td>
<td width="50%" colspan="3">
<strong>Name Of Procedure: 
 <textarea name="name_of_procedure" style="width:100%; height:80px!important;" > <?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="6" width="100%">
<strong>Anesthesia Needed:
<textarea name="anesthesia_needed" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['anesthesia_needed'])?$select_result['anesthesia_needed']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="6" width="100%">
<strong>Doctor Name: <input type="text" class="Admission" name="doctor_name" value="<?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?>">  </strong>
</td>
</tr>
</tbody>
</table> 
</div>  

<input type="submit" name="submit" value="submit">
</form>

<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table width="100%" class="vb45rt">
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID :<?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
<tr>
<td style="padding:10px!important;"></td>
</tr>
<tr>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>

<td style="padding:10px;margin-left:20px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr>
<td colspan="1" style="width:28%;">
<strong>Pt Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>Age / Sex : <?php echo $patient_data['wife_age']; ?> / F</strong>
</td>
</tr>
<tr>
<td>
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
</tr>
<tr>
<td colspan="1" style="width:28%;">
<strong>IIC ID : <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td>
<strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
</td>
</tr>
</table>
</div>
</div>
</div>

<style>
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
select#center {
    display: block!important;
}
.sec3 {
    border: 1px solid #000;
    padding: 5px;
}
.sec2 {
    border: 1px solid #000;
}
.sec2 p {
    margin: 0px;
    padding: 2px 10px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td {
  border: 1px solid #000;
  text-align: center;
  padding: 5px; 
}
.ga-pro h3 {
      text-align: center;
    font-size: 25px;
}
form {
    padding-left: 10px;
    margin-bottom: 4px;
}
.nb56ty {
    border: 1px solid #000;
}
.nb56ty input {
    width: 100%;
}
.vb45rt td {text-align: left; padding-left: 10px;}
</style>    