<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `intrauterine` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `intrauterine` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE intrauterine SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"    ;
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
    $select_query = "SELECT * FROM `intrauterine` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  
    
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);
?>

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
    
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">

    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="pending" name="status"> 
    <div class="container1 red-field form mt-5 mb-5">
        <ul class="d-flex mb-1 mt-2 list-unstyled">
        <div class="table-responsive">
             <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">IUI</h3></td>
   </tr>
</table>
     			  <table width="100%" class="vb45rt">
<tbody>
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
            
        <table class="table table-bordered table-hover mt-2 table-sm red-field tableMg">
            <thead>
                <tr>
                    <td colspan="2">
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
        			</td>
                </tr>
            </thead>
            <table class="table table-bordered table-hover mt-2 table-sm tableMg">
                <thead>
                    <tr>
                        <th style="color: red;"><h2>SELF CYCLE  (S)</h2></th>
                    </tr>
                </thead>
            </table>
                <ul class="d-flex mb-1 mt-2 list-unstyled">
                <div class = "table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                        <th style="color: red;"><strong>Partners name</strong></th>
                        <th><input type="text" value="<?php echo isset($select_result['partners_name2'])?$select_result['partners_name2']:""; ?>" maxlength="20" class="form" name="partners_name2"></th>
                       </tr>
                    </thead>
                  <thead>
                    <tr>
                      <th style="color: red;"><strong>ID</strong></th>
                      <th><input type="text" value="<?php echo isset($select_result['form_id2'])?$select_result['form_id2']:""; ?>" maxlength="20" class="form" name="form_id2"></th>
                   </tr>
                  </thead>
            </table>
            </div>
        </ul>
      
      
                 
            </div>
        </ul>
        <div class = "table-responsive">
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
                <tr>
                    <th>NURSE <input type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>" maxlength="20" name="nurse"></th>
                    <th>DOCTOR <input type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>" maxlength="20" name="doctor"></th>
                    <th>Embryologist <input type="text" value="<?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?>" maxlength="20" name="embryologist"></th>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
                <tr>
                    <th><ul class = "list-unstyled">
                            <li class="d-flex mb-1 mt-2 "><span>Physical Examination</span></li> 
                            <li class="d-flex mb-1 ml-4"><span class= "mr-5 ">Resp</span> <input type="text" value="<?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?>" class="form" name="Resp2"></li> 
                            <li class="d-flex mb-1 ml-4"><span class= "mr-5 ml-1 ">CVS</span> <input type="text" value="<?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?>" class="form" name="CVS"></li> 
                            <li class="d-flex mb-1 ml-4"><span class= "mr-5 ml-1 ">CNS</span> <input type="text" value="<?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?>" class="form" name="CNS"></li>
                            <li class="d-flex mb-1"><span class= "mr-4 ">Abdominal</span> <input type="text" value="<?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>" class="form" name="abdominal"></li>
                            <li class="d-flex mb-1 ml-2"><span class= "mr-5 ">Others</span> <input type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>" class="form" name="others"></li>
                        </ul>
                    </th>
                    <th>
                        <p>
                            Written informed consent taken. All vitals checked under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by normal saline  and draped.A baseline transabdominal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with normal saline .An IUI catheter with 0.5 ml of prepared sperm sample  put in the uterine cavity .The speculum is taken out . No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down
                        </p>
                        
                        
                         <textarea name="other_comments" style="width:100%; height:50px;" > <?php echo isset($select_result['other_comments'])?$select_result['other_comments']:""; ?> </textarea>
                        
                        
                        
                        <!-- <p>Written informed consent taken . All vitals checked under normal range.  15 ml of venous blood was drawn from  Arthrex  Double syringe system ,  centrifuged by ACP centrifuge system for 10 min.  
                            Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with betadine.An IUI catheter with 0.5 ml of prepared PRP(supernatant pellet ) put in the uterine cavity .The speculum is taken out .
                            No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down </p> -->
                    </th>
                </tr>
            </thead>
            <thead>
               <!-- <tr>
                    <th><p class="d-flex p-2"><span>SPERM DETAIL</span><input type="text" class="form" name="sperm_detail" value="<?php echo isset($select_result['sperm_detail'])?$select_result['sperm_detail']:""; ?>"></p></th>
                    <th><a href="#">SEMEN PREPERATION CHART</a></th>
                </tr>-->
                
                <tr>
                    <th><p class="d-flex p-2"><span>Comments</span> </th>
                    <td>  <textarea name="comments" style="width:100%; height:70px;" > <?php echo isset($select_result['comments'])?$select_result['comments']:""; ?> </textarea> </td>
                </tr>
                
                
            </thead>
        </table>
        <table class="table table-bordered table-hover  mb-2 table-sm red-field tableMg">
         
            <thead>
                <tr>
                    <th colspan="2"><p>Doctors signature <input name="doctor_signature" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>" type="text"></p></th>
                </tr>
            </thead>  
        </table>
        </div>
                    <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
                    <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</form>















<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 

    <div class="container red-field form mt-5 mb-5">
        <div class="table-responsive">
         <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">IUI</h3></td>
   </tr>
</table>
		 <table width="100%" class="vb45rt">
<tbody>
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
		<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="border:1px solid #cdcdcd;">
            <thead>
		      <tr>
                   <td colspan="4" style="border:1px solid #cdcdcd;">
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated"> Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
        			</td>
                </tr>
            </thead>
            <table class="table table-bordered table-hover mt-2 table-sm tableMg" style="width:100%;border:1px solid #cdcdcd;">
                <thead>
                    <tr>
                        <th colspan="2" width="50%" style="border:1px solid #cdcdcd;"><h2>SELF CYCLE  (S)</h2></th>
                    </tr>
                </thead>
            </table>
             
               
                    <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;" >
                    <thead>
                        <tr>
                        <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>Partners name</strong></th>
                        <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['partners_name2'])?$select_result['partners_name2']:""; ?> </th>
                        </tr>
                    </thead>
                  <thead>
				    <tr>
                      <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>ID</strong></th>
                      <th colspan="1" width="25%" style="border:1px solid #cdcdcd;">  <?php echo isset($select_result['form_id2'])?$select_result['form_id2']:""; ?>  </th>
                   </tr>
                  </thead>
            </table>
          
      
           </div>
        <div class = "table-responsive">
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                <tr>
  <th style="border:1px solid #cdcdcd;"> NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?> </th>
  <th style="border:1px solid #cdcdcd;"> DOCTOR  <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?> </th>
 <th style="border:1px solid #cdcdcd;">Embryologist <?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?> </th>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
            <thead>
                <tr>
                    <th style="border:1px solid #cdcdcd;"><ol >
  <li class="d-flex mb-1 mt-2 "> <span> Physical Examination </span></li> 
 <li class="d-flex mb-1 ml-4"><span class= "mr-5 ">Resp </span> <?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?> </li> 
<li class="d-flex mb-1 ml-4"><span class= "mr-5 ml-1 "> CVS </span> <?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?> </li> 
 <li class="d-flex mb-1 ml-4"><span class= "mr-5 ml-1 ">CNS</span> <?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?></li>
 <li class="d-flex mb-1"><span class= "mr-4 ">Abdominal</span> <?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?></li>
  <li class="d-flex mb-1 ml-2"><span class= "mr-5 ">Others</span> <?php echo isset($select_result['others'])?$select_result['others']:""; ?></li>
                        </ul>
                    </th>
                    <th style="border:1px solid #cdcdcd;">
                        <p>
                            Written informed consent taken. All vitals checked under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by normal saline  and draped.A baseline transabdominal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with normal saline .An IUI catheter with 0.5 ml of prepared sperm sample  put in the uterine cavity .The speculum is taken out . No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down
                        </p>
                        <!-- <p>Written informed consent taken . All vitals checked under normal range.  15 ml of venous blood was drawn from  Arthrex  Double syringe system ,  centrifuged by ACP centrifuge system for 10 min.  
                            Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with betadine.An IUI catheter with 0.5 ml of prepared PRP(supernatant pellet ) put in the uterine cavity .The speculum is taken out .
                            No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down </p> -->
                    </th>
                </tr>
            </thead>
            <thead>
                <tr>
				<!--<th style="border:1px solid #cdcdcd;"  ><p class="d-flex p-2"><span> SPERM DETAIL </span> <?php echo isset($select_result['sperm_detail'])?$select_result['sperm_detail']:""; ?> </p></th>
                    <th  style="border:1px solid #cdcdcd;"> SEMEN PREPERATION CHART </th>
                </tr>-->
                
                 <tr >
                    <th style="border:1px solid #cdcdcd;"><p class="d-flex p-2"><span>Comments</span> </th>
                    <td style="border:1px solid #cdcdcd;"> <textarea name="comments" style="width:100%; height:70px;" > <?php echo isset($select_result['comments'])?$select_result['comments']:""; ?> </textarea> </td>
                </tr>
                
                
                
            </thead>
        </table>
      



	  <table style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                <tr>
                    <th colspan="2" style="border:1px solid #cdcdcd;"><p> Doctors signature <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </p></th>
                </tr>
            </thead>  
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