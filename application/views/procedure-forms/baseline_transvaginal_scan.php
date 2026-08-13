<?php

if(isset($_POST['submit'])){
	unset($_POST['submit']);
	
	$_POST['upload1'] = '';
	if(!empty($_FILES['upload1']['tmp_name'])){
		$dest_path = $this->config->item('upload_path');
		$destination = $dest_path.'procedure-forms-uploads/';
		$NewImageName = rand(4,10000)."-".$_FILES['upload1']['name'];
		$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
		move_uploaded_file($_FILES['upload1']['tmp_name'], $destination.$NewImageName);
		$_POST['upload1'] = $transaction_img;
	}
	$_POST['upload2'] = '';
	if(!empty($_FILES['upload2']['tmp_name'])){
		$dest_path = $this->config->item('upload_path');
		$destination = $dest_path.'procedure-forms-uploads/';
		$NewImageName = rand(4,10000)."-".$_FILES['upload2']['name'];
		$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
		move_uploaded_file($_FILES['upload2']['tmp_name'], $destination.$NewImageName);
		$_POST['upload2'] = $transaction_img;
	}
	
	$select_query = "SELECT * FROM `baseline_transvaginal_scan` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	if(empty($select_result)){
		// mysql query to insert data
		$query = "INSERT INTO `baseline_transvaginal_scan` SET ";
		$sqlArr = array();
		foreach( $_POST as $key=> $value )
		{
		  $sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$query .= implode(',' , $sqlArr);
	}else{
		// mysql query to update data
		$query = "UPDATE baseline_transvaginal_scan SET ";
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
	$select_query = "SELECT * FROM `baseline_transvaginal_scan` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">BASELINE TRANSVAGINAL SCAN</h3></td>
   </tr>
</table>
     				  <table width="100%" class="vb45rt">
<tr style="background: #b3b9b7;">
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>
			<div class="row mb-2">
				<div class="col-sm-12">
				    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
    			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
    			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
    			            ){?>
    			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
    			    <?php } ?>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<div class="col-md-12">
					<!-- general form elements -->
					<div class="card card-primary">
						<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
						    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
							<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
							<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
							<input type="hidden" value="pending" name="status"> 
			
							<table id="example1" class="table-bordered" style="width: 100%; color: red;">
								<tr style="padding: 5px;">
									<th style="color: red;">DAY OF CYCLE</th>
									<td style="padding: 0;"><input type="number" name="day_of_cycle" min="0"  class="form-control" value="<?php echo isset($select_result['day_of_cycle'])?$select_result['day_of_cycle']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">UTERINE MORPHOLOGY</th>
									<td style="padding: 0;">
										<textarea name="uterine_morphology" maxlength="150"  class="form-control"><?php echo isset($select_result['uterine_morphology'])?$select_result['uterine_morphology']:""; ?></textarea>
									</td>
								</tr>
								<tr>
									<th style="color: red;">ENDOMETRIAL THICKNESS (Cms)</th>
									<td style="padding: 0;"><input type="number" name="endometrial_thickness" min="0"  class="form-control" value="<?php echo isset($select_result['endometrial_thickness'])?$select_result['endometrial_thickness']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">OVARY  :</th>
									<td style="padding: 0;">
										<table style="width: 100%;">
											<tr>
												<td>LEFT</td>
												<td style="padding: 0;"><input type="text" maxlength="25" name="ovary_left"  class="form-control" value="<?php echo isset($select_result['ovary_left'])?$select_result['ovary_left']:""; ?>"  ></td>
												<td>RIGHT</td>
												<td style="padding: 0;"><input type="text" maxlength="25" name="ovary_right"  class="form-control" value="<?php echo isset($select_result['ovary_right'])?$select_result['ovary_right']:""; ?>"  ></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th style="color: red;">AFC</th>
									<td style="padding: 0;">
										<table style="width: 100%;">
											<tr>
												<td>LEFT</td>
												<td style="padding: 0;"><input type="text" maxlength="25" name="afc_left"  class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
												<td>RIGHT</td>
												<td style="padding: 0;"><input type="text" maxlength="25" name="afc_right"  class="form-control" value="<?php echo isset($select_result['afc_right'])?$select_result['afc_right']:""; ?>"  ></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th style="color: red;">ACCESSIBILITY</th>
									<td style="padding: 0;"><input type="text" maxlength="50" name="accessibility"  class="form-control" value="<?php echo isset($select_result['accessibility'])?$select_result['accessibility']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">MORPHOLOGY</th>
									<td style="padding: 0;"><input type="text" maxlength="50" name="morphology"  class="form-control" value="<?php echo isset($select_result['morphology'])?$select_result['morphology']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">MOBILITY</th>
									<td style="padding: 0;"><input type="text" maxlength="50" name="mobility"  class="form-control" value="<?php echo isset($select_result['mobility'])?$select_result['mobility']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">ADDITIONAL PATHOLOGIES</th>
									<td style="padding: 0;"><input type="text" maxlength="50" name="additional_pathologies"  class="form-control" value="<?php echo isset($select_result['additional_pathologies'])?$select_result['additional_pathologies']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">POLYPS</th>
									<td style="padding: 0;"><input type="text" maxlength="50" name="polyps"  class="form-control" value="<?php echo isset($select_result['polyps'])?$select_result['polyps']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">FIBROIDS/ADENOMYOSIS</th>
									<td style="padding: 0;"><input type="text" maxlength="50" name="fibroids"  class="form-control" value="<?php echo isset($select_result['fibroids'])?$select_result['fibroids']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">HYDROSALPINGES</th>
									<td style="padding: 0;"><input type="text" maxlength="25" name="hydrosalpinges"  class="form-control" value="<?php echo isset($select_result['hydrosalpinges'])?$select_result['hydrosalpinges']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;">DATE</th>
									<td style="padding: 0;"><input type="date" name="date"  class="form-control" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"  ></td>
								</tr>
								<tr>
									<th style="color: red;"></th>
									<td style="padding: 0;">
										<table style="width: 100%;">
											<tr>
												<td>Upload pic1</td>
												<td style="padding: 0;"><input type="file" name="upload1"  class="form-control" >
												<a target="_blank" href="<?php echo !empty($select_result['upload1'])?$select_result['upload1']:"javascript:void(0)"; ?>">Download</a>
											</td>
												<td>Upload pic2</td>
												<td style="padding: 0;"><input type="file" name="upload2"  class="form-control" >
												<a target="_blank" href="<?php echo !empty($select_result['upload2'])?$select_result['upload2']:"javascript:void(0)"; ?>">Download</a>
											</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table class="table-bordered" style="color: red;">
								<tr>
									<td>PRECONCEPTION & PRENATAL SEX DETERMINATION , DISCLOSURE AND SELECTION OF FETUS IS NOT DONE. IT IS PROHIBITED AND PUNISHABLE UNDER LAW . SEEKING AND ASKING FOR IT IS PUNISHABLE UNDER LAW</td>
								</tr>
							</table>
							<div class="card-footer">
								<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
								<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
							</div>
						</form>
					</div>
					<!-- /.card -->
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>






<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 






<!--         baseline_transvaginal_scan                   -->




<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
<tr>
                <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
			 <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h2>BASELINE TRANSVAGINAL SCAN</h2></center></td>
					
</tr>
<tr>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">UHID :</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"><?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></td>
</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">IIC ID:</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $patient_id; ?></td>
</tr>
		<tr>
			<td colspan="4" style="border:1px solid #cdcdcd;">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
    			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
    			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
    			            ){?>
    			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
    			    <?php } ?>
			</td>
		</tr>
	</table>








	<!-- Main content -->
 
<table id="example1" class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
<tr style="padding: 5px;">
<th style="color: red;border:1px solid #cdcdcd;">DAY OF CYCLE</th>
<td style="padding: 0; border:1px solid #cdcdcd;">

<?php echo isset($select_result['day_of_cycle'])?$select_result['day_of_cycle']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">UTERINE MORPHOLOGY</th>
									<td style="border:1px solid #cdcdcd;">
									<?php echo isset($select_result['uterine_morphology'])?$select_result['uterine_morphology']:""; ?>
									</td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">ENDOMETRIAL THICKNESS (Cms)</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness'])?$select_result['endometrial_thickness']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">OVARY:</th>
									<td style="border:1px solid #cdcdcd;">
										<table style="width: 100%; border:1px solid #cdcdcd;">
											<tr>
												<td  style="border:1px solid #cdcdcd;">LEFT</td>
												<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ovary_left'])?$select_result['ovary_left']:""; ?></td>
												<td  style="border:1px solid #cdcdcd;">RIGHT</td>
												<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ovary_right'])?$select_result['ovary_right']:""; ?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th style=" border:1px solid #cdcdcd;">AFC</th>
									<td style="border:1px solid #cdcdcd;">
										<table style="width: 100%; border:1px solid #cdcdcd;">
											<tr>
												<td  style="border:1px solid #cdcdcd;">LEFT</td>
												<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?></td>
												<td  style="border:1px solid #cdcdcd;">RIGHT</td>
												<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['afc_right'])?$select_result['afc_right']:""; ?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th style=" border:1px solid #cdcdcd;">ACCESSIBILITY</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['accessibility'])?$select_result['accessibility']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">MORPHOLOGY</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['morphology'])?$select_result['morphology']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">MOBILITY</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['mobility'])?$select_result['mobility']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">ADDITIONAL PATHOLOGIES</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['additional_pathologies'])?$select_result['additional_pathologies']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">POLYPS</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['polyps'])?$select_result['polyps']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">FIBROIDS/ADENOMYOSIS</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['fibroids'])?$select_result['fibroids']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">HYDROSALPINGES</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hydrosalpinges'])?$select_result['hydrosalpinges']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;">DATE</th>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
								</tr>
								<tr>
									<th style="border:1px solid #cdcdcd;"></th>
									<td style="border:1px solid #cdcdcd;">
										
						<?php 
		@		$upload1=$select_result['upload1'];
		@		$upload2=$select_result['upload2'];
				
					?>				
										<table style="width: 100%;border:1px solid #cdcdcd;">
											<tr>
<td  style="border:1px solid #cdcdcd;">Upload pic1</td>
<td style="border:1px solid #cdcdcd;">   

<?php if(!empty($upload1)) {?>
				 <img src="<?php echo $upload1;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>

											
</td>
											
											
	<td  style="border:1px solid #cdcdcd;">Upload pic2</td>
	<td style="border:1px solid #cdcdcd;"> 

	
		 
		<?php if(!empty($upload2)) {?>
				 <img src="<?php echo $upload2;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
												
												
	</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							
							
							<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
								<tr>
									<td  style="border:1px solid #cdcdcd;">PRECONCEPTION & PRENATAL SEX DETERMINATION , DISCLOSURE AND SELECTION OF FETUS IS NOT DONE. IT IS PROHIBITED AND PUNISHABLE UNDER LAW . SEEKING AND ASKING FOR IT IS PUNISHABLE UNDER LAW</td>
								</tr>
							</table>
							









</div>
						
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
 