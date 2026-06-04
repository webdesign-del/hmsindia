 <?php $all_method =&get_instance(); ?><?php
if (isset($_POST['submit'])) {
    extract($_POST);
	$ID = $_GET['ID'];
   $sql1 = "update pcp_ndt set wife_name='$wife_name', wife_age='$wife_age', husband_name='$husband_name', wife_address='$wife_address', wife_phone='$wife_phone',female_pregnancy_other_p='$female_pregnancy_other_p',female_pregnancy_other_l='$female_pregnancy_other_l',female_pregnancy_other_a='$female_pregnancy_other_a', details_management_advised='$details_management_advised', IVF_Consultant='$IVF_Consultant', procedure_done='$procedure_done', outcome_of_tretment='$outcome_of_tretment', further_referredfor_dellvery='$further_referredfor_dellvery', outcome_of_pregnancy='$outcome_of_pregnancy', male='$male', female='$female', malformation_in_newborn='$malformation_in_newborn', female_issues='$female_issues', date_of_discharge='$date_of_discharge', center='$center' where ID = '$ID'  ";
    $query2 = $this->db->query($sql1);
	$num = (int) $query2;
    if ($num > 0) {
        $_SESSION['MSG'] = "Your profile has been successfully updated.!!";
    } else {
        $_SESSION['MSG'] = "Your profile has not been updated.!!";
    }
}
    $ID = $_GET['ID'];
    $sql1 = "SELECT * FROM pcp_ndt WHERE ID='$ID'";
	$query = $this->db->query($sql1);
    $select_result1 = $query->result(); 
         foreach ($select_result1 as $res_val){       					
?>
<div class="ga-pro">
<h3>PCP NDT</h3>
<form action="" enctype='multipart/form-data' method="post">
<input type="hidden" value="<?php echo $res_val->ID; ?>" class="form" name="husband_name">
<input type="hidden" value="<?php echo $res_val->wife_name; ?>" class="form" name="wife_name">

<table width="100%" class="vb45rt">
<tbody>
<tr>
<td colspan="2" width="50%">
<strong>IIC ID: <input type="text" name="patient_id" value="<?php echo $res_val->patient_id; ?>" readonly></strong>
</td>
<td colspan="2" width="50%">
<strong>Wife Name: <input type="text" name="wife_name" value="<?php echo $res_val->wife_name; ?>"> </strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">
<strong>Age: <input type="text" name="wife_age" value="<?php echo $res_val->wife_age; ?>"></strong>
</td>
<td colspan="2" width="50%">
<strong>Husband Name : <input type="text" name="husband_name" value="<?php echo $res_val->husband_name; ?>"> </strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">
<strong>Address
<textarea name="wife_address" style="width:100%; height:100px!important;" > <?php echo $res_val->wife_address; ?> </textarea>
</strong>
</td>
<td width="50%">
<strong>Wife Phone:
<textarea name="wife_phone" style="width:100%; height:100px!important;" ><?php echo $res_val->wife_phone; ?></textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">
<strong>PARITY OF WOMAN WITH SEX OF PREVIOUS CHILD<br/> 
<input type="text" name="female_pregnancy_other_p" value="<?php echo $res_val->female_pregnancy_other_p; ?>" style="width:30%; margin-right:10px!important;">
<input type="text" name="female_pregnancy_other_l" value="<?php echo $res_val->female_pregnancy_other_l; ?>" style="width:30%; margin-right:10px!important;">
<input type="text" name="female_pregnancy_other_a" value="<?php echo $res_val->female_pregnancy_other_a; ?>" style="width:30%;">
</strong>
</td>
<td colspan="2" width="50%">
<strong>Reason For IVF / ART: <input type="text" name="details_management_advised" value="<?php echo $res_val->details_management_advised; ?>"> </strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">

<strong>Detail of Referring Dr: <input type="text" name="IVF_Consultant" value="<?php echo $res_val->IVF_Consultant; ?>"> </strong>

</td>
<td width="50%">
<strong>Procedure Done
<textarea name="procedure_done" style="width:100%; height:100px!important;" > <?php echo $res_val->procedure_done; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">
<strong>Outcome of The Tretment
<textarea name="outcome_of_tretment" style="width:100%; height:100px!important;" > <?php echo $res_val->outcome_of_tretment; ?> </textarea>
</strong>
</td>
<td width="50%">
<strong>Detail of the Dr. further referred for delivery/Management of oregnancy
<textarea name="further_referredfor_dellvery" style="width:100%; height:100px!important;" > <?php echo $res_val->further_referredfor_dellvery; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">
<strong>Outcome Of the Pregnancy
<textarea name="outcome_of_pregnancy" style="width:100%; height:100px!important;"  > <?php echo $res_val->outcome_of_pregnancy; ?> </textarea>
</strong>
</td>
<td width="50%">
<strong>Any Malformation in Newborn Details 
<textarea name="malformation_in_newborn" style="width:100%; height:100px!important;"  > <?php echo $res_val->malformation_in_newborn; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%">
<strong>Male
<textarea name="male" style="width:100%; height:100px!important;"  > <?php echo $res_val->male; ?> </textarea>
</strong>
</td>
<td width="50%">
<strong>Female 
<textarea name="female" style="width:100%; height:100px!important;"  > <?php echo $res_val->female; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td width="50%" colspan="2">
<strong>Female Issues 
<textarea name="female_issues" style="width:100%; height:100px!important;"  > <?php echo $res_val->female_issues; ?> </textarea>
</strong>
</td>
<td colspan="2" width="50%">
<strong>Date Of Discharge
<input type="date" class="Discharge" name="date_of_discharge" value="<?php echo $res_val->date_of_discharge; ?>"></strong>
</td>
</tr> 
<tr>
<td width="50%" colspan="2">
            	<label>Center</label>
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
</td>
</tr>
</tbody>
</table> 
</div>  
<input type="submit" name="submit" value="submit">
</form>
</div>
 <?php } ?>
<style>
select#center {
    display: block!important;
}
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
.sec3 td {
    text-align: left;
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
.nb56ty input {
    width: 100%;
}
.vb45rt td {
	text-align: left; 
	padding-left: 10px;
}
</style>    