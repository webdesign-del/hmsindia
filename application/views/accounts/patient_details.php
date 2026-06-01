<?php
   //$id = $_GET['id'];
   $all_method =&get_instance();
   $patient_data = get_patient_detail($data['patient_id']);
   $currency = '';
   // if($patient_data['nationality'] == 'indian'){
   // 	$currency = 'Rs.';
   // }else {
   // 	$currency = 'USD';
   // }
   $appoitmented_date = $_GET['appoitmented_date'];
   
   ?>
<style>
   table, th, td {
   border: 1px solid black;
   border-collapse: collapse;
   }
   th, td {
   padding: 5px;
   text-align: left;
   }
   .img_sec{max-width:100px;}
</style>
<div class="col-sm-12 col-xs-12  panel panel-piluku ml-5">
   <button class="btn btn-primary" onclick="window.history.go(-1)">Back</button>
   <div  style="text-align:center;">
      <form method="get" action="<?= base_url('/');?>patients/discharge_summary">
         <input type="hidden" name="patient_id" value="<?php echo $data['patient_id']; ?>">
         <input type="hidden" name="appoitmented_date" value="<?php echo $appoitmented_date; ?>">
         <Select name="discharge" style="display: inline; width:300px;" >
            <option value="0"> Select Details </option>
            
            <?php if($_SESSION['logged_doctor']['username']){
               if ($form_data) {
                    foreach ($form_data as $forms) {
                        ?> 
            <option value="<?= $forms['id'] ?>"> <?= str_replace('_', ' ', $forms['name']); ?> </option>
            <?php  } }} elseif($_SESSION['logged_embryologist']['username']){ 
               if ($form_data_embrology) {
                    foreach ($form_data_embrology as $forms) {
               ?>
            <option value="<?= $forms['id'] ?>"> <?= str_replace('_', ' ', $forms['name']); ?> </option>
            <?php  } }} else 
               if ($form_data_embrology) {
                    foreach ($formdata as $forms) {
               ?>
            <option value="<?= $forms['id'] ?>"> <?= str_replace('_', ' ', $forms['name']); ?> </option>
            <?php }} ?>
         </select >
         <input type="submit" value="submit">
      </form>
   </div>
   <input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>
   <input type='button' id='btn' value='Send to Patient' class="btn btn-primary pull-right" onclick='sendonwhatsapp("<?php echo $patient_data['wife_phone']; ?>");'>
   <div class="panel-body profile-edit">
      <h3 class="ak">Patient Details </h3>
      <p id="whatsappmessg"></p>
      <hr />
      <table style="width:100%">
         <tr>
            <th>UHID: </th>
            <td>
               <?php 	$sql = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$data['wife_phone']."' and paitent_type='new_patient'";
                  $select_result = run_select_query($sql);
                  $sql2 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result['appoitment_for']."'";
                  $select_result2 = run_select_query($sql2);
                  echo $select_result2['center_code']."/".$select_result['uhid'];
                  ?>
            </td>
         </tr>
         <tr>
            <th>IIC ID:</th>
            <td><?php echo $data['patient_id'];?></td>
         </tr>
         <tr>
            <th>Wife Name:</th>
            <td><?php echo $data['wife_name'];?></td>
         </tr>
         <tr>
            <th>Wife phone:</th>
            <td><?php echo sting_masking($data['wife_phone']);?></td>
         </tr>
         <tr>
            <th>Wife email:</th>
            <td><?php echo sting_masking($data['wife_email']);?></td>
         </tr>
         <tr>
            <th>Wife pan number:</th>
            <td><?php echo $data['wife_pan_number'];?></td>
         </tr>
         <tr>
            <th>Wife pan card:</th>
            <td><a href="<?php echo $data['wife_pan_card'];?>"  target="_blank" target="_blank"><img src="<?php echo $data['wife_pan_card'];?>" class="img_sec" /></a></td>
         </tr>
         <tr>
            <th>Wife adhaar number/ Passport No:</th>
            <td><?php echo  sting_masking($data['wife_adhar_number'])?></td>
         </tr>
         <tr>
            <th>Wife adhaar card / Passport:</th>
            <td><a href="<?php echo $data['wife_adhar_card'];?>"  target="_blank" target="_blank"><img src="<?php echo $data['wife_adhar_card'];?>" class="img_sec" /></a></td>
         </tr>
         <tr>
            <th>Wife photo:</th>
            <td><a href="<?php echo $data['wife_photo'];?>"  target="_blank" target="_blank"><img src="<?php echo $data['wife_photo'];?>" class="img_sec" /></a></td>
         </tr>
         <tr>
            <th colspan="2">* Father in case of unmarried / Divorced female</th>
         </tr>
         <tr>
            <th>Wife age:</th>
            <td><span style="border:1px solid #000; padding:3px;"><?php echo $data['wife_age'];?></span> Yrs </td>
         </tr>
         <tr>
            <th>Wife address:</th>
            <td><?php echo $data['wife_address'];?></td>
         </tr>
         <tr>
            <th>Husband / Father name:</th>
            <td><?php echo $data['husband_name'];?></td>
         </tr>
         <tr>
            <th>Husband / Father phone:</th>
            <td><?php echo sting_masking($data['husband_phone']);?></td>
         </tr>
         <tr>
            <th>Husband / Father email:</th>
            <td><?php echo sting_masking($data['husband_email']);?></td>
         </tr>
         <tr>
            <th>Husband / Father pan number:</th>
            <td><?php echo $data['husband_pan_number'];?></td>
         </tr>
         <tr>
            <th>Husband / Father pan card:</th>
            <td><a href="<?php echo $data['husband_pan_card'];?>" target="_blank"><img src="<?php echo $data['husband_pan_card'];?>" class="img_sec" /></a></td>
         </tr>
         <tr>
            <th>Husband / Father adhaar number / Passport No:</th>
            <td><?php echo  sting_masking($data['husband_adhar_number'])?></td>
         </tr>
         <tr>
            <th>Husband / Father adhaar card / Passport:</th>
            <td><a href="<?php echo $data['husband_adhar_card'];?>" target="_blank"><img src="<?php echo $data['husband_adhar_card'];?>" class="img_sec" /></a></td>
         </tr>
         <tr>
            <th>Husband / Father age:</th>
            <td><span style="border:1px solid #000; padding:3px;"><?php echo $data['husband_age'];?></span> Yrs</td>
         </tr>
         <tr>
            <th>Husband / Father address:</th>
            <td><?php echo $data['husband_address'];?></td>
         </tr>
         <tr>
            <th>Husband / Father photo:</th>
            <td><a href="<?php echo $data['husband_photo'];?>" target="_blank"><img src="<?php echo $data['husband_photo'];?>" class="img_sec" /></a></td>
         </tr>
         <tr>
            <th>Patient source:</th>
            <td><?php echo $data['patient_source'];?></td>
         </tr>
         <?php if($data['patient_source'] == 'reference'){?>
         <tr>
            <th>Patient Name:</th>
            <td><?php echo $data['reference_from'];?></td>
         </tr>
         <?php } ?>
      </table>
   </div>
   <div class="table-responsive">
      <?php echo patient_medical_info($data['patient_id']); ?>
   </div>
   <div class="table-responsive">
      <?php echo patient_follow_ups($data['patient_id']); ?>
   </div>
   <div class="table-responsive">
      <div class="col-sm-12 col-xs-12" style="padding: 0; margin-top: 20px;">
         <h3>Investigation Reports</h3>
         <br/>
      </div>
      <table class="table table-striped table-bordered table-hover dataList" id="">
         <thead>
            <tr>
               <th>Investigation</th>
               <th>Gender</th>
               <th>Reports</th>
            </tr>
         </thead>
         <tbody>
            <?php if(!empty($investigation_reports)){ ?>
            <?php foreach($investigation_reports as $ky => $vl){ ?>
            <tr class="odd gradeX">
               <td><?php echo get_investigation_name($vl['investigation_id']); ?></td>
               <td><?php echo ucwords($vl['gender']); ?></td>
               <td><a href="<?php echo $vl['report']; ?>"  target="_blank">Download Report</a></td>
            </tr>
            <?php } ?>
            <?php } ?>
         </tbody>
      </table>
   </div>
   <hr/>
   <div class="table-responsive">
      <div class="col-sm-12 col-xs-12" style="padding: 0; margin-top: 20px;">
         <h3>Procedure Reports</h3>
         <br/>
      </div>
      <table class="table table-striped table-bordered table-hover dataList" id="">
         <thead>
            <tr>
               <th>Procedure</th>
               <th>Reports</th>
            </tr>
         </thead>
         <tbody>
            <?php if(!empty($procedure_reports)){ $procedure_data = unserialize($procedure_reports['data']);?>
            <?php foreach($procedure_data['patient_procedures'] as $ky => $val){ //var_dump($procedure_reports);die; ?>
            <tr class="odd gradeX">
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php echo get_procedure_data($val['sub_procedure']); ?></td>
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">
                  <a href="javascript:void(0);" procedure_id="<?php echo $val['sub_procedure']; ?>" class="check_record btn btn-large">Check record</a>
                  <div style="display:none;" class="check_form_div" id="check_form_<?php echo $val['sub_procedure']; ?>">
                     <?php $form_list = get_prodecure_forms($val['sub_procedure']); //var_dump($form_list);die; 
                        $html = "";
                        if(!empty($form_list)){
                          foreach($form_list as $key => $vl){$form_details = get_prodecure_form($vl['form_id']);
                            $html .= "<a target='_blank' href='".base_url('check_procedure_form/'.$vl['form_id'].'/'.$procedure_reports['ID'].'/'.$procedure_reports['appointment_id'].'/'.$val['sub_procedure'])."'>".$form_details['form_name']."</a> | ";
                          }
                        }
                        if(empty($html)){
                          $html = "<p class='error'>No data found!</p>   ";
                        }
                        $html = substr($html, 0, -3);
                        echo $html;
                        ?>
                  </div>
               </td>
            </tr>
            <?php } ?>
            <?php } ?>
         </tbody>
      </table>
   </div>
   <div class="table-responsive">
      <div class="col-sm-12 col-xs-12" style="padding: 0; margin-top: 20px;">
         <h3>Prescription/Discharge Summary</h3>
         <br/>
      </div>
      <table class="table table-striped table-bordered table-hover dataList" id="">
         <thead>
            <tr>
               <th>IIC ID</th>
               <th>Patient Name</th>
               </th>
               <th>Prescription/Discharge Summary</th>
               <th>Updated At</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php if(!empty($patient_discharge)){ ?>
            <?php foreach($patient_discharge as $ky => $val){
               $patient_infor = get_patient_detail($val['patient_id']);
               $employee_infor = employee_detail_number($val['updated_by']);
               // var_dump($val);die;
               ?>
            <tr class="odd gradeX">
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php echo $val['patient_id']; ?></td>
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php echo $patient_infor['wife_name']; ?></td>
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php echo str_replace("_", " ", $val['form_name']); ?></td>
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php echo $val['updated_at']; ?></td>
               <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><a href="<?php echo base_url(); ?>patient-discharge/<?php echo $val['id']; ?>/<?php echo $val['patient_id']; ?>/<?php echo $val['form_id']; ?>?appoitmented_date=<?php echo $val['appoitmented_date']; ?>">Details</a></td>
            </tr>
            <?php } ?>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>
<div class="panel-body profile-edit" id="print_this_section" style="display:none">
   <h3>Patient Details</h3>
   <hr />
   <table style="width:100%">
      <tr style="border:1px solid #000; width:100%; display:none;" id="medinfologo_tr">
         <td style="border:1px solid #000; width:100%;text-align:center;" colspan="3">
            <img src="<?php echo base_url('assets/images/indiaivf-logo.png'); ?>" class="img-responsive" style="width:200px" />
         </td>
      </tr>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">UHID:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">
            <?php 	$sql = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$data['wife_phone']."' and paitent_type='new_patient'";
               $select_result = run_select_query($sql);
               $sql2 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result['appoitment_for']."'";
               $select_result2 = run_select_query($sql2);
               echo $select_result2['center_code']."/".$select_result['uhid'];
               ?>
         </td>
      </tr>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">IIC ID:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['patient_id'];?></td>
      </tr>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife Name:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['wife_name'];?></td>
      </tr>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife phone:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo sting_masking($data['wife_phone']);?></td>
      </tr>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife email:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo sting_masking($data['wife_email']);?></td>
      </tr>
      <?php if(!empty($data['wife_pan_number'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife pan number:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['wife_pan_number'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['wife_pan_card'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife pan card:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><a href="<?php echo $data['wife_pan_card'];?>"  target="_blank" target="_blank"><img src="<?php echo $data['wife_pan_card'];?>" width="150" class="img_sec" /></a></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['wife_adhar_number'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife adhaar number:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['wife_adhar_number'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['wife_adhar_card'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife adhaar card:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><a href="<?php echo $data['wife_adhar_card'];?>"  target="_blank" target="_blank"><img src="<?php echo $data['wife_adhar_card'];?>" width="150" class="img_sec" /></a></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['wife_age'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife age:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><span style="border:1px solid #000; padding:3px;"><?php echo $data['wife_age'];?></span> Yrs </td>
      </tr>
      <?php }?>
      <?php if(!empty($data['wife_address'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife address:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['wife_address'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['wife_photo'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Wife photo:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><a href="<?php echo $data['wife_photo'];?>"  target="_blank" target="_blank"><img src="<?php echo $data['wife_photo'];?>" width="150" class="img_sec" /></a></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_name'])){?>
      <tr>
         <th colspan="2" style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">* Father in case of unmarried / Divorced female</th>
      </tr>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father name:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['husband_name'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_phone'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father phone:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo sting_masking($data['husband_phone']);?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_email'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father email:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo sting_masking($data['husband_email']);?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_pan_number'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father pan number:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['husband_pan_number'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_pan_card'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father pan card:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><a href="<?php echo $data['husband_pan_card'];?>" target="_blank"><img src="<?php echo $data['husband_pan_card'];?>" width="150" class="img_sec" /></a></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_adhar_number'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father adhaar number:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['husband_adhar_number'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_adhar_card'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father adhaar card:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><a href="<?php echo $data['husband_adhar_card'];?>" target="_blank"><img src="<?php echo $data['husband_adhar_card'];?>" width="150" class="img_sec" /></a></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_age'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father age:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><span style="border:1px solid #000; padding:3px;"><?php echo $data['husband_age'];?></span> Yrs </td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_address'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father address:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['husband_address'];?></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['husband_photo'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Husband / Father photo:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><a href="<?php echo $data['husband_photo'];?>" target="_blank"><img src="<?php echo $data['husband_photo'];?>" width="150" class="img_sec" /></a></td>
      </tr>
      <?php }?>
      <?php if(!empty($data['patient_source'])){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Patient source:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['patient_source'];?></td>
      </tr>
      <?php }?>
      <?php if($data['patient_source'] == 'reference'){?>
      <tr>
         <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Patient Name:</th>
         <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $data['reference_from'];?></td>
      </tr>
      <?php } ?>
   </table>
   <?php if(print_patient_medical_info($data['patient_id']) !== "reportnotfound"){?>
   <div class="table-responsive">
      <?php echo print_patient_medical_info($data['patient_id']); ?>
   </div>
   <?php } ?>
</div>
</div>
<script>
   function printDiv() 
   {
     $('.hide_print').hide();
     $('#print_this_section').css('display', 'block');
     $('tr#medinfologo_tr').css('display', 'table-row');
     var divToPrint=document.getElementById('print_this_section');
     var newWin=window.open('','Print-Window');
     newWin.document.open();
     newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
     newWin.document.close();
   //   setTimeout(function(){newWin.close();},10);
   //   window.location.reload();
   }
   
   $(document).on('click',"a.followprint_btn",function(e) {
       $('tr#followlogo_tr').css('display', 'table-row');
       var divToPrint=document.getElementById($(this).data('printid'));
       var newWin=window.open('','Print-Window');
       newWin.document.open();
       newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
       newWin.document.close();
         //  setTimeout(function(){newWin.close();},1000);
      //  window.location.reload();
   });
   
   
   $(document).on('click',".sendfollowwhatsapp",function(e) {
       var printing_id = $(this).attr('printid');
       
       var data = {'patient_id':<?php echo $data['patient_id']; ?>, 'html': $("#"+printing_id).html()};
       $('span.'+printing_id).hide();
   	$.ajax({
   		url: '<?php echo base_url('accounts/prephtmltopdf')?>',
   		data: data,
   		dataType: 'json',
   		method:'post',
   		success: function(data)
   		{
   		    if(data.status == 1){
                   $('span.'+printing_id).empty().append('Prescription has been sent to patient!');
   		    }else{
   		        $('span.'+printing_id).empty().append('Oops, something went wrong!');
   		    }
   		    $('span.'+printing_id).show();
   		} 
   	});
   });
   
   function sendonwhatsapp() 
   {
       var data = {'patient_id':<?php echo $data['patient_id']; ?>, 'html': $("#print_this_section").html()};
       $('#whatsappmessg').hide();
   	$.ajax({
   		url: '<?php echo base_url('accounts/prephtmltopdf')?>',
   		data: data,
   		dataType: 'json',
   		method:'post',
   		success: function(data)
   		{
   		    if(data.status == 1){
                   $('#whatsappmessg').empty().append('Prescription has been sent to patient!');
   		    }else{
   		        $('#whatsappmessg').empty().append('Oops, something went wrong!');
   		    }
   		    $('#whatsappmessg').show();
   		} 
   	});
   }
   
   $(document).on('click',".check_record",function(e) {
   	$('.check_form_div').hide();
   	var procedure_id = $(this).attr('procedure_id');
   	$('#check_form_'+procedure_id).show();
   });
</script>