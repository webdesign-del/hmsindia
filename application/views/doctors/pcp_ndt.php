 <?php $all_method =&get_instance(); 

// Use CodeIgniter database connection instead of hardcoded credentials
$CI =& get_instance();
$CI->load->database();

// Check if ID is provided for deletion
if (isset($ID) && !empty($ID)) {
    // Use CodeIgniter's query builder for safe deletion
    $CI->db->where('ID', $ID);
    $result = $CI->db->delete('pcp_ndt');
    
    if ($result) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $CI->db->error()['message'];
    }
}
?>
 
    <div class="col-md-12">

	<div class="card">
	<div class="col-md-12"><h3> PCPNDT </h3></div>
	<form action="<?php echo base_url().'doctors/pcp_ndt'; ?>" method="get">
		   <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Filter by billing at</label>
                 <select class="form-control" id="center" name="center">
                	<option value=''>--Select From--</option>
					<?php $all_emplyee = $all_method->get_employee_list();
						            foreach($all_emplyee as $key => $val){ //var_dump($val);die;
                          if($center == $val['center']){
                            echo '<option value="'.$val['center'].'" selected>'.$val['center'].'</option>';
                          }else{
		                        echo '<option value="'.$val['center'].'">'.$val['center'].'</option>';
                          }
                    	  } 
					    ?>
                </select>
            </div>
			 <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Type </label>
                <select class="form-control" id="type" name="type">
				  <option value=""> --- Select --- </option>
                  <option value="IVF"> IVF </option>
                  <option value="DELIVERY"> DELIVERY </option>
                  <option value="IUI"> IUI </option>
                  <option value="TESA"> TESA </option>
                  <option value="DELIVERY"> DELIVERY </option>
                  <option value="REJUVENATION"> REJUVENATION </option>
                  <option value="PGT"> PGT </option>
				</select>
            </div>
           <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo $patient_id;?>" />
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;margin-right: 10px;">
            	<a href="<?php echo base_url().'doctors/pcp_ndt'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
			 </a>
            </div>
			<div class="col-sm-1" style="margin-top: 10px;">
            	<input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv2();'>
            </div>
			<div class="col-sm-2" style="margin-top: 10px;">
            	<input type='button' id='btn' value='Print Delivery' class="btn btn-primary pull-right" onclick='printDiv();'>
            </div>
			<div class="col-sm-1" style="margin-top: 10px;margin-right: 15px;">
            	<a href="<?php echo base_url('doctors/add_pcp_ndt'); ?>" target="_blank" class="btn btn-secondary">Add New</a>
            </div>
            <div class="col-sm-3" style="margin-top: 10px;">
            	<a href="<?php echo base_url('doctors/Pcp-Ndt'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-primary" id="export-billing">Export Billings</button>
				
               </a>
            </div>	    
            </form>
    
         <div class="clearfix"></div>
        <div class="card-content">

          <div class="table-responsive">

            <table class="table table-striped table-bordered table-hover" id="investigation_billing_list">

              <thead>
                <tr>
				  <th>ID</th>
                     <th>Name of Woman Undergoing <?php $all_emplyee = $all_method->get_test_type();
						            foreach($all_emplyee as $key => $val){ 
                                    if($test_type == $val['type']){ echo $val['type'];}
                    	             }  ?></th>
                    <th>Age</th>
					          <th>Name Of Husband With Age/Father (in case of Unmarried Divorce Female)</th>
                    <th>Complete Address </th>
					          <th>Tel No.</th>
                    <th>Parity Of Woman With Sex of Previous Child</th>
                    <th>Reason for IVF/ART</th>
                    <th>Details Of Referring Doctor</th>
                    <th>Detail Of The Doctor Patient is Further Referred for Dellvery/Management of Pregnancy</th>
                    <th>Outcome Of the Pregnancy</th>
                    <th>Any Malformation in Newborn Details</th>
					          <th>Center</th>
					          <th>Test Type</th>
					          <th>Date</th>
					          <th>Status</th>
                 
                </tr>
              </thead>

              <tbody id="investigate_result">
              <?php  foreach($investigate_result as $ky => $vl){
				            $patient_data = get_patient_detail($vl['ID']);
                            $patient_data = get_patient_detail($vl['patient_id']);
    						 $currency = '';
							 
              ?>
                <tr class="odd gradeX">
				     <td>
					 <?php if($_SESSION['logged_doctor']['username'] == "trainingdoctor@indiaivf.in" || $_SESSION['logged_doctor']['username'] == "drnoida@indiaivf.in") { ?>
					 <a href="<?php echo base_url()?>doctors/pcp_ndt_update?ID=<?php echo $vl['ID']; ?>"><?php echo $vl['patient_id']; ?></a></td>
                     <?php }else{ ?>
					 <?php echo $vl['patient_id']; ?>
					 <?php  } ?>
					 <td><?php echo $vl['wife_name'] ?></td>
					 <td><?php echo $vl['wife_age'] ?></td>
                     <td><?php echo $vl['husband_name']; ?></td>
                     <td><?php echo $vl['wife_address']; ?></td>
					 <td><?php echo $vl['wife_phone']; ?></td>
                     <td><?php echo $vl['female_pregnancy_other_p']; ?>, <?php echo $vl['female_pregnancy_other_l']; ?>, <?php echo $vl['female_pregnancy_other_a']; ?></td>
					 <td><?php echo $vl['details_management_advised']; ?></td>
					 <td><?php echo $vl['IVF_Consultant']; ?></td>
					 <td><?php echo $vl['further_referredfor_dellvery']; ?></td>
					 <td><?php echo $vl['outcome_of_pregnancy']; ?></td>
					 <td><?php echo $vl['malformation_in_newborn']; ?></td>
					 <td><?php echo $vl['center']; ?></td>
					 <td><?php echo $vl['test_type']; ?></td>
					 <td><?php echo $vl['date']; ?></td>
					 <td><a href="<?php echo base_url();?>doctors/pcp_ndt?ID=<?php echo $vl['ID']?>" class="delete"><i class="material-icons">delete</i></a></td>
				     
                </tr>
              <?php $count++;} ?>
			   <tr>
                <td colspan="10">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
		 </div>
      </div>
       <!--End Investigation Tables -->    
      <!--End Advanced Tables -->
    </div>
	<div class="row" id="print_this_section2" style="display:none;">
	  <table class="table table-striped table-bordered table-hover" id="investigation_billing_list">

              <thead>
                <tr>
				     <th style="border:1px solid;">S.No.</th>
					 <th style="border:1px solid;">IIC ID</th>
                     <th style="border:1px solid;">NAME OF WOMAN UNDERGOING <?php $all_emplyee = $all_method->get_test_type();
						            foreach($all_emplyee as $key => $val){ 
                                    if($test_type == $val['type']){ echo $val['type'];}
                    	             }  ?></th>
                     <th style="border:1px solid;">Age</th>
					 <th style="border:1px solid;">NAME OF HUSBAND WITH AGE</th>
                     <th style="border:1px solid;">COMPLETE ADDRESS</th>
					 <th style="border:1px solid;">TEL. No.</th>
                     <th style="border:1px solid;">PARITY OF WOMAN WITH SEX OF PREVIOU-SCHLD </th>
                     <th style="border:1px solid;">REASON FOR IVF/ART</th>
                     <th style="border:1px solid;">DETALIS OF REFERRING DOCTOR</th>
                     <th style="border:1px solid;">DETAIL Of THE DOCTOR PATIENT IS FURTHER REFERREDFOR DELLVERY/ MANAGEMENT OF PREGNANCY</th>
                     <th style="border:1px solid;">OUTCOME OF THE PREGNANCY</th>
                     <th style="border:1px solid;">ANY MALFORMATION IN NEWBORN DETAILS</th>
				</tr>
              </thead>

            <tbody id="investigate_result">
			
              <?php $a = 1;  foreach($investigate_result as $ky => $vl){ 
                   
			  ?>
                <tr class="odd gradeX" style="border:1px solid;">
                     <td style="border:1px solid;"><?php echo $a++; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['patient_id']; ?></td>
                     <td style="border:1px solid;"><?php echo $vl['wife_name'] ?></td>
					 <td style="border:1px solid;"><?php echo $vl['wife_age'] ?></td>
                     <td style="border:1px solid;"><?php echo $vl['husband_name']; ?></td>
                     <td style="border:1px solid;"><?php echo $vl['wife_address']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['wife_phone']; ?></td>
                     <td style="border:1px solid;"><?php echo $vl['female_pregnancy_other_p']; ?>, <?php echo $vl['female_pregnancy_other_l']; ?>, <?php echo $vl['female_pregnancy_other_a']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['details_management_advised']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['IVF_Consultant']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['further_referredfor_dellvery']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['outcome_of_pregnancy']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['malformation_in_newborn']; ?></td>
				</tr>
              <?php $count++;} ?>
			</tbody>
            </table>
	</div>
	
	<div class="row" id="print_this_section" style="display:none;">
	  <table class="table table-striped table-bordered table-hover" id="investigation_billing_list">

              <thead>
                <tr>
				     <th style="border:1px solid;">S.No.</th>
					 <th style="border:1px solid;">NAME OF WOMAN UNDERGOING <?php $all_emplyee = $all_method->get_test_type();
						            foreach($all_emplyee as $key => $val){ 
                                    if($test_type == $val['type']){ echo $val['type'];}
                    	             }  ?></th>
                     <th style="border:1px solid;">Age</th>
					 <th style="border:1px solid;">NAME OF HUSBAND WITH AGE</th>
                     <th style="border:1px solid;">COMPLETE ADDRESS</th>
					 <th style="border:1px solid;">TEL. No.</th>
                     <th style="border:1px solid;">PARITY OF WOMAN WITH SEX OF PREVIOUS CHLD </th>
                     <th style="border:1px solid;">REASON FOR IVF/ART</th>
                     <th style="border:1px solid;">DETAILS OF REFERRING DOCTOR</th>
                     <th style="border:1px solid;">DETAILS OF HOSPITAL AND DOCTOR</th>
                     <th style="border:1px solid;">OUTCOME OF THE TREATMET</th>
                     <th style="border:1px solid;">ANY MALFORMATION IN NEWBORN DETAILS</th>
				</tr>
              </thead>

            <tbody id="investigate_result">
			
              <?php $a = 1;  foreach($investigate_result as $ky => $vl){ 
                   
			  ?>
                <tr class="odd gradeX" style="border:1px solid;">
                     <td style="border:1px solid;"><?php echo $a++; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['wife_name'] ?></td>
					 <td style="border:1px solid;"><?php echo $vl['wife_age'] ?></td>
                     <td style="border:1px solid;"><?php echo $vl['husband_name']; ?></td>
                     <td style="border:1px solid;"><?php echo $vl['wife_address']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['wife_phone']; ?></td>
                     <td style="border:1px solid;"><?php echo $vl['female_pregnancy_other_p']; ?>, <?php echo $vl['female_pregnancy_other_l']; ?>, <?php echo $vl['female_pregnancy_other_a']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['details_management_advised']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['IVF_Consultant']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['further_referredfor_dellvery']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['outcome_of_pregnancy']; ?></td>
					 <td style="border:1px solid;"><?php echo $vl['malformation_in_newborn']; ?></td>
				</tr>
              <?php $count++;} ?>
			</tbody>
            </table>
	</div>
<script>
      $( function() {
        $( ".particular_date_filter" ).datepicker({
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(dateStr) {
            $('#loader_div').hide();				
            var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
            var data = {appointment_date:startDate, type:'particular_date_filter'};
          }
        });
    });
</script>
<script>
function printDiv2() 
{
  $('.hide_print').hide();
  $('input[type="submit"]').css('visibility', 'hidden');
  $('p#last_updated').css('visibility', 'hidden');
  var divToPrint=document.getElementById('print_this_section2');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>

<script>
function printDiv() 
{
  $('.hide_print').hide();
  $('input[type="submit"]').css('visibility', 'hidden');
  $('p#last_updated').css('visibility', 'hidden');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>
<style >
.custom-pagination{
  padding:8px;
}
.custom-pagination a{
  padding:10px;
  text-decoration: none;
}
.form-control{
  height: 30px!important;
  border: 1px solid #9e9e9e!important;
}
.form-control#billing_at{
  height: 40px!important;
  border: 1px solid #9e9e9e!important;
}
.border{border:1px solid #9e9e9e!important;}
</style>