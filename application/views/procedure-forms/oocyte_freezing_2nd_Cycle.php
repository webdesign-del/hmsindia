<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
       
        $select_query = "SELECT * FROM `hms_oocyte_freezing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_oocyte_freezing` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_oocyte_freezing SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"	;
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        }
        $result = run_form_query($query);        

        if($result){
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
					die();
        }else{
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
        }
    }
    $select_query = "SELECT * FROM `hms_oocyte_freezing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query); 

	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);	
	
	 // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }
?>

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="pending" name="status"> 
  <input type="hidden" value="Second Cycle" name="type"> 
    			<div class="container2 red-field form mt-5 mb-5">
    			<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE FREEZING</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="3" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="3" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>	
					<div class='table-responsive2'>
    					<table class="table table-bordered table-hover mt-2 table-sm red-field">
					        <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">OOCYTE  FREEZING SECOND CYCLE</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">Date: <input type="date" value="<?php echo isset($select_result['date1'])?$select_result['date1']:""; ?>"  name="date1"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">Time: <input type="time" value="<?php echo isset($select_result['time1'])?$select_result['time1']:""; ?>"  name="time1"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>"  maxlength="20" name="emb"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">Dr: <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  maxlength="20" name="dr"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">Witness: <input type="text" value="<?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?>"  maxlength="20" name="witness1"  ></th>
					        	</tr>
					        </thead>
							 <thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">Total No Of Oocyte Frozen: <input type="text" value="<?php echo isset($select_result['total_oocyte_frozen'])?$select_result['total_oocyte_frozen']:""; ?>"  maxlength="20" name="total_oocyte_frozen"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="background: #FFC000">NO OF STRAW</td>
									<td style="background: #FFC000">OOCYTE FROZEN IN THAT STRAW WITH CELL STAGE AND GRADING</td>
									<td style="background: #FFC000">STRAW COLOUR</td>
									<td style="background: #FFC000">VISOTUBE</td>
									<td style="background: #FFC000">GOBLET</td>
									<td style="background: #FFC000">DEWAR</td>
									<td style="background: #FFC000">MEDIA USED</td>
									<td style="background: #FFC000">STORAGE RENEWAL DATE </td>
									<td style="background: #FFC000">REMARKS</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw1'])?$select_result['no_of_straw1']:""; ?>"  min="0" name="no_of_straw1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading1'])?$select_result['oocyte_fro_grading1']:""; ?>"  name="oocyte_fro_grading1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour1'])?$select_result['straw_colour1']:""; ?>"  min="0" name="straw_colour1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube1'])?$select_result['visotube1']:""; ?>"  min="0" name="visotube1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet1'])?$select_result['goblet1']:""; ?>"  min="0" name="goblet1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar1'])?$select_result['dewar1']:""; ?>"  min="0" name="dewar1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used1'])?$select_result['media_used1']:""; ?>"  min="0" name="media_used1"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"  name="date3"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?>"  maxlength="20" name="witness3"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw2'])?$select_result['no_of_straw2']:""; ?>"  min="0" name="no_of_straw2"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading2'])?$select_result['oocyte_fro_grading2']:""; ?>"  name="oocyte_fro_grading2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour2'])?$select_result['straw_colour2']:""; ?>"  min="0" name="straw_colour2"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube2'])?$select_result['visotube2']:""; ?>"  min="0" name="visotube2"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet2'])?$select_result['goblet2']:""; ?>"  min="0" name="goblet2"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar2'])?$select_result['dewar2']:""; ?>"  min="0" name="dewar2"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used2'])?$select_result['media_used2']:""; ?>"  min="0" name="media_used2"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"  name="date4"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness4'])?$select_result['witness4']:""; ?>"  maxlength="20" name="witness4"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw3'])?$select_result['no_of_straw3']:""; ?>"  min="0" name="no_of_straw3"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading3'])?$select_result['oocyte_fro_grading3']:""; ?>"  name="oocyte_fro_grading3"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour3'])?$select_result['straw_colour3']:""; ?>"  min="0" name="straw_colour3"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube3'])?$select_result['visotube3']:""; ?>"  min="0" name="visotube3"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet3'])?$select_result['goblet3']:""; ?>"  min="0" name="goblet3"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar3'])?$select_result['dewar3']:""; ?>"  min="0" name="dewar3"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used3'])?$select_result['media_used3']:""; ?>"  min="0" name="media_used4"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date5'])?$select_result['date5']:""; ?>"  name="date5"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?>"  maxlength="20" name="witness5"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw4'])?$select_result['no_of_straw4']:""; ?>"  min="0" name="no_of_straw4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading4'])?$select_result['oocyte_fro_grading4']:""; ?>"  name="oocyte_fro_grading4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour4'])?$select_result['straw_colour4']:""; ?>"  min="0" name="straw_colour4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube4'])?$select_result['visotube4']:""; ?>"  min="0" name="visotube4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet4'])?$select_result['goblet4']:""; ?>"  min="0" name="goblet4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar4'])?$select_result['dewar4']:""; ?>"  min="0" name="dewar4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used4'])?$select_result['media_used4']:""; ?>"  min="0" name="media_used4"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date6'])?$select_result['date6']:""; ?>"  name="date6"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?>"  maxlength="20" name="witness6"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw5'])?$select_result['no_of_straw5']:""; ?>"  min="0" name="no_of_straw5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading5'])?$select_result['oocyte_fro_grading5']:""; ?>"  name="oocyte_fro_grading5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour5'])?$select_result['straw_colour5']:""; ?>"  min="0" name="straw_colour5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube5'])?$select_result['visotube5']:""; ?>"  min="0" name="visotube5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet5'])?$select_result['goblet5']:""; ?>"  min="0" name="goblet5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar5'])?$select_result['dewar5']:""; ?>"  min="0" name="dewar5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used5'])?$select_result['media_used5']:""; ?>"  min="0" name="media_used5"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date7'])?$select_result['date7']:""; ?>"  name="date7"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness7'])?$select_result['witness7']:""; ?>"  maxlength="20" name="witness7"  ></td>
							    </tr>
					        </thead>
					        <thead>
					        <tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw6'])?$select_result['no_of_straw6']:""; ?>"  min="0" name="no_of_straw6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading6'])?$select_result['oocyte_fro_grading6']:""; ?>"  name="oocyte_fro_grading6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour6'])?$select_result['straw_colour6']:""; ?>"  min="0" name="straw_colour6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube6'])?$select_result['visotube6']:""; ?>"  min="0" name="visotube6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet6'])?$select_result['goblet6']:""; ?>"  min="0" name="goblet6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar6'])?$select_result['dewar6']:""; ?>"  min="0" name="dewar6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used6'])?$select_result['media_used6']:""; ?>"  min="0" name="media_used6"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date8'])?$select_result['date8']:""; ?>"  name="date8"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness8'])?$select_result['witness8']:""; ?>"  maxlength="20" name="witness8"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw7'])?$select_result['no_of_straw7']:""; ?>"  min="0" name="no_of_straw7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading7'])?$select_result['oocyte_fro_grading7']:""; ?>"  name="oocyte_fro_grading7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour7'])?$select_result['straw_colour7']:""; ?>"  min="0" name="straw_colour7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube7'])?$select_result['visotube7']:""; ?>"  min="0" name="visotube7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet7'])?$select_result['goblet7']:""; ?>"  min="0" name="goblet7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar7'])?$select_result['dewar7']:""; ?>"  min="0" name="dewar7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used7'])?$select_result['media_used7']:""; ?>"  min="0" name="media_used7"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date9'])?$select_result['date9']:""; ?>"  name="date9"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness9'])?$select_result['witness9']:""; ?>"  maxlength="20" name="witness9"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw8'])?$select_result['no_of_straw8']:""; ?>"  min="0" name="no_of_straw8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading8'])?$select_result['oocyte_fro_grading8']:""; ?>"  name="oocyte_fro_grading8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour8'])?$select_result['straw_colour8']:""; ?>"  min="0" name="straw_colour8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube8'])?$select_result['visotube8']:""; ?>"  min="0" name="visotube8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet8'])?$select_result['goblet8']:""; ?>"  min="0" name="goblet8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar8'])?$select_result['dewar8']:""; ?>"  min="0" name="dewar8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used8'])?$select_result['media_used8']:""; ?>"  min="0" name="media_used8"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date10'])?$select_result['date10']:""; ?>"  name="date10"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness10'])?$select_result['witness10']:""; ?>"  maxlength="20" name="witness10"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw9'])?$select_result['no_of_straw9']:""; ?>"  min="0" name="no_of_straw9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading9'])?$select_result['oocyte_fro_grading9']:""; ?>"  name="oocyte_fro_grading9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour9'])?$select_result['straw_colour9']:""; ?>"  min="0" name="straw_colour9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube9'])?$select_result['visotube9']:""; ?>"  min="0" name="visotube9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet9'])?$select_result['goblet9']:""; ?>"  min="0" name="goblet9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar9'])?$select_result['dewar9']:""; ?>"  min="0" name="dewar9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used9'])?$select_result['media_used9']:""; ?>"  min="0" name="media_used9"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date11'])?$select_result['date11']:""; ?>"  name="date11"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness11'])?$select_result['witness11']:""; ?>"  maxlength="20" name="witness11"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw10'])?$select_result['no_of_straw10']:""; ?>"  min="0" name="no_of_straw10"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['oocyte_fro_grading10'])?$select_result['oocyte_fro_grading10']:""; ?>"  name="oocyte_fro_grading10"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_colour10'])?$select_result['straw_colour10']:""; ?>"  min="0" name="straw_colour10"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['visotube10'])?$select_result['visotube10']:""; ?>"  min="0" name="visotube10"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['goblet10'])?$select_result['goblet10']:""; ?>"  min="0" name="goblet10"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['dewar10'])?$select_result['dewar10']:""; ?>"  min="0" name="dewar10"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_used10'])?$select_result['media_used10']:""; ?>"  min="0" name="media_used10"></td>
							        <td style="background: #FFC000"><input type="date" value="<?php echo isset($select_result['date12'])?$select_result['date12']:""; ?>"  name="date12"></td>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['witness12'])?$select_result['witness12']:""; ?>"  maxlength="20" name="witness12"  ></td>
							    </tr>
					        </thead>
					      
					    </table>
					</div>
					<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
				</div>
			</form>
			
			
	<!-- pRINT bUTTON -->		
			
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE FREEZING</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="3" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="3" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>


</tr>
	   </table>			
    					


	<table class="table table-bordered table-hover mt-2 table-sm red-field" style="width:100%; border:1px solid #cdcdcd;"  >
					        <thead>
					        	<tr>
					        		<th colspan="9" style="border:1px solid #cdcdcd;">OOCYTE  FREEZING</th>
					        	</tr>
					        </thead>
					        <thead>
							    <tr>
							        <th colspan="9" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
								    <th colspan="9" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time1'])?$select_result['time1']:""; ?></th>
					        	</tr>
					        </thead>
                            <thead>
					        	<tr>
					        		<th colspan="9" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="border:1px solid #cdcdcd;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="9" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?></th>
					        	</tr>
					        </thead> 
                             <thead>
					        	<tr>
					        		<td style="border:1px solid #cdcdcd;">No Of Straw</td>
									<td style="border:1px solid #cdcdcd;">Oocyte Frozen in That Straw with cell stage and grading</td>
									<td style="border:1px solid #cdcdcd;">Straw Colour</td>
									<td style="border:1px solid #cdcdcd;">Visotube</td>
									<td style="border:1px solid #cdcdcd;">Goblet</td>
									<td style="border:1px solid #cdcdcd;">Dewar</td>
									<td style="border:1px solid #cdcdcd;">Media used</td>
									<td style="border:1px solid #cdcdcd;">Storage renewal date</td>
									<td style="border:1px solid #cdcdcd;">Remarks</td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw1'])?$select_result['no_of_straw1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading1'])?$select_result['oocyte_fro_grading1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour1'])?$select_result['straw_colour1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube1'])?$select_result['visotube1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet1'])?$select_result['goblet1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar1'])?$select_result['dewar1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used1'])?$select_result['media_used1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?></td>
							    </tr>
					        </thead>
							<thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw2'])?$select_result['no_of_straw2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading2'])?$select_result['oocyte_fro_grading2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour2'])?$select_result['straw_colour2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube2'])?$select_result['visotube2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet2'])?$select_result['goblet2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar2'])?$select_result['dewar2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used2'])?$select_result['media_used2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date4'])?$select_result['date4']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness4'])?$select_result['witness4']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw3'])?$select_result['no_of_straw3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading3'])?$select_result['oocyte_fro_grading3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour3'])?$select_result['straw_colour3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube3'])?$select_result['visotube3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet3'])?$select_result['goblet3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar3'])?$select_result['dewar3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used3'])?$select_result['media_used3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date5'])?$select_result['date5']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw4'])?$select_result['no_of_straw4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading4'])?$select_result['oocyte_fro_grading4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour4'])?$select_result['straw_colour4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube4'])?$select_result['visotube4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet4'])?$select_result['goblet4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar4'])?$select_result['dewar4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used4'])?$select_result['media_used4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date6'])?$select_result['date6']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw5'])?$select_result['no_of_straw5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading5'])?$select_result['oocyte_fro_grading5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour5'])?$select_result['straw_colour5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube5'])?$select_result['visotube5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet5'])?$select_result['goblet5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar5'])?$select_result['dewar5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used5'])?$select_result['media_used5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date7'])?$select_result['date7']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness7'])?$select_result['witness7']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
					        <tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw6'])?$select_result['no_of_straw6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading6'])?$select_result['oocyte_fro_grading6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour6'])?$select_result['straw_colour6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube6'])?$select_result['visotube6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet6'])?$select_result['goblet6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar6'])?$select_result['dewar6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used6'])?$select_result['media_used6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness8'])?$select_result['witness8']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw7'])?$select_result['no_of_straw7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading7'])?$select_result['oocyte_fro_grading7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour7'])?$select_result['straw_colour7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube7'])?$select_result['visotube7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet7'])?$select_result['goblet7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar7'])?$select_result['dewar7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used7'])?$select_result['media_used7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date9'])?$select_result['date9']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness9'])?$select_result['witness9']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw8'])?$select_result['no_of_straw8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading8'])?$select_result['oocyte_fro_grading8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour8'])?$select_result['straw_colour8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube8'])?$select_result['visotube8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet8'])?$select_result['goblet8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar8'])?$select_result['dewar8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used8'])?$select_result['media_used8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date10'])?$select_result['date10']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness10'])?$select_result['witness10']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw9'])?$select_result['no_of_straw9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading9'])?$select_result['oocyte_fro_grading9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour9'])?$select_result['straw_colour9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube9'])?$select_result['visotube9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet9'])?$select_result['goblet9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar9'])?$select_result['dewar9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used9'])?$select_result['media_used9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date11'])?$select_result['date11']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness11'])?$select_result['witness11']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['no_of_straw10'])?$select_result['no_of_straw10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_fro_grading10'])?$select_result['oocyte_fro_grading10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['straw_colour10'])?$select_result['straw_colour10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['visotube10'])?$select_result['visotube10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['goblet10'])?$select_result['goblet10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar10'])?$select_result['dewar10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_used10'])?$select_result['media_used10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date12'])?$select_result['date12']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness12'])?$select_result['witness12']:""; ?></td>
							    </tr>
					        </thead>
					        </table>
							 
	
</div>
 <style type="text/css">
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999px;
    opacity: 1!important;
}
 </style>
						
<script>
$(document).ready(function(){
    
    // Step 1: Agar button kahin aur se disable ho raha hai toh usko enable karein
    $('#btn').prop('disabled', false);

    $(".ptable").click(function(){
        // Elements ko hide/show karna
        $('.searchform').hide();
        $('.printbtn').hide(); 
        $('.prtable').css('display', 'block');
      
        // Print logic
        var divToPrint = document.getElementById('printtable');
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
      
        // Timeout thoda bada rakhein taaki print dialog perfectly load ho sake
        setTimeout(function(){
            newWin.close();
        }, 500); 

        // Note: window.location.reload(); ko hta diya gaya hai taaki page achanak refresh na ho.
    });
});
</script>					
			