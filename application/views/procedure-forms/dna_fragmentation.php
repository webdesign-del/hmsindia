
<?php 
if(isset($_POST['submit'])){
  unset($_POST['submit']);
  
  $select_query = "SELECT * FROM `dna_fragmentation` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
  $select_result = run_select_query($select_query); 
  if(empty($select_result)){
      // mysql query to insert data
      $query = "INSERT INTO `dna_fragmentation` SET ";
      $sqlArr = array();
      foreach( $_POST as $key=> $value )
      {
        $sqlArr[] = " $key = '".addslashes($value)."'";
      }		
      $query .= implode(',' , $sqlArr);
  }else{
      // mysql query to update data
      $query = "UPDATE dna_fragmentation SET ";
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
$select_query = "SELECT * FROM `dna_fragmentation` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
$select_result = run_select_query($select_query);
?>

      <div class="form_page">
        <form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
          <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
          <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
          <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
          
          <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">  
          <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
          <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
          <input type="hidden" value="pending" name="status"> 
          <div class="row">
            <div class="col-md-12 col-xs-12">
            <div class="col-md-6 col-xs-12"><h4>Sperm DNA Fragmentation Test Report</h4></div>
            <div class="col-md-6 col-xs-12">
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
        			</div>
            </div>

          <div class="col-md-6 col-xs-12">
             <div class="p_info">PATIENT INFORMATION</div>
            <div class="form-group">
              <label>PATIENT NAME</label> <input type="text" value="<?php echo isset($select_result['pname'])?$select_result['pname']:""; ?>" name="pname" placeholder="Name Here*">
            </div>
            <div class="form-group">
              <label>WIFE NAME</label> <input type="text" value="<?php echo isset($select_result['wname'])?$select_result['wname']:""; ?>" name="wname" placeholder="Name Here*">
            </div>
            <div class="form-group">  
              <label>REF. BY DR:</label> <input type="text" value="<?php echo isset($select_result['refdoc'])?$select_result['refdoc']:""; ?>" name="refdoc" placeholder="Doctor Name*">
            </div>  
            </div>
          
          <div class="col-md-6 col-xs-12">
            <div class="form-group">
              <label>AGE</label> <input type="text" value="<?php echo isset($select_result['age'])?$select_result['age']:""; ?>" name="age" placeholder="Age Here">
            </div>
            <div class="form-group">
              <label>ID NO</label> <input type="text" value="<?php echo isset($select_result['idno'])?$select_result['idno']:""; ?>" name="idno" placeholder="ID Number">
            </div>
            <div class="form-group">
              <label>REPORT RELEASE</label> <input value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>" type="text" name="date">
            </div>
          </div>
        </div>

        <div class="p_info">CHARACTERSTICS</div>
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <div class="form-group">
              <label>COLLECTION TIME</label> <input value="<?php echo isset($select_result['ctime'])?$select_result['ctime']:""; ?>" type="text" name="ctime" placeholder="Time">
            </div>
            <div class="form-group">
              <label>COLLECTION TYPE</label> <input value="<?php echo isset($select_result['ctype'])?$select_result['ctype']:""; ?>" type="text" name="ctype" placeholder="Discribe Type">
            </div>
            <div class="form-group">
              <label>SAMPLE</label> <input type="text" value="<?php echo isset($select_result['sample'])?$select_result['sample']:""; ?>" name="sample" placeholder="FRESH-RAW">
            </div>
            <div class="form-group">
              <label>SPERM COUNT</label> <input type="text" value="<?php echo isset($select_result['scont'])?$select_result['scont']:""; ?>" name="scont" placeholder="29M/ML">
            </div>
            <div class="form-group">
              <label>SPERM MOTILITY</label> <input type="text" value="<?php echo isset($select_result['smotility'])?$select_result['smotility']:""; ?>" name="smotility" placeholder="19%">
            </div>
            <div class="form-group">
              <label>ROUND CELLS</label> <input type="text" value="<?php echo isset($select_result['rcell'])?$select_result['rcell']:""; ?>" name="rcell" placeholder="1-2/HPF">
            </div>
            <div class="form-group">
              <label>NORMAL FORMS</label> <input type="text" value="<?php echo isset($select_result['nforms'])?$select_result['nforms']:""; ?>" name="nforms" placeholder="3%">
            </div>
          </div>

          <div class="col-md-6 col-xs-12">
            <div class="form-group">
              <label>EXAMINATION TIME</label> <input type="text" value="<?php echo isset($select_result['etime'])?$select_result['etime']:""; ?>" name="etime" placeholder="09:15AM">
            </div>
            <div class="form-group">
              <label>PLACE OF COLLECTION</label> <input type="text" value="<?php echo isset($select_result['pcollection'])?$select_result['pcollection']:""; ?>" name="pcollection" placeholder="HOSPITAL">
            </div>
            <div class="form-group">
              <label>ABSTINENCE</label> <input type="text" value="<?php echo isset($select_result['abstinence'])?$select_result['abstinence']:""; ?>" name="abstinence" placeholder="3 DAYS">
            </div>
            <div class="form-group">
              <label>SEMEN VOLUME</label> <input type="text" value="<?php echo isset($select_result['svolume'])?$select_result['svolume']:""; ?>" name="svolume" placeholder="2.6ML">
            </div>
            <div class="form-group">
              <label>APPEARANCE</label> <input type="text" value="<?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?>" name="appearance" placeholder="NORMAL">
            </div>
            <div class="form-group">
              <label>LIQUEFACTION</label> <input type="text" value="<?php echo isset($select_result['liquefaction'])?$select_result['liquefaction']:""; ?>" name="liquefaction" placeholder="25 MINS">
            </div>
            <div class="form-group">
              <label>VISCOCITY</label> <input type="text" value="<?php echo isset($select_result['viscocity'])?$select_result['viscocity']:""; ?>" name="viscocity" placeholder="(-)">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group dfi_in">
              <label>DNA FRAGMENTATION INDEX (DFI):</label> <input type="text" value="<?php echo isset($select_result['dfi'])?$select_result['dfi']:""; ?>" name="dfi" placeholder="16%">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <div class="t_head"><span></span> <span>PERCENTAGE</span></div>
            <div class="form-group">
              <label>NOT FRAGMENTED</label> <input type="text" value="<?php echo isset($select_result['nfragmented'])?$select_result['nfragmented']:""; ?>" name="nfragmented" placeholder="84%">
            </div>
            <div class="form-group">
              <label>FRAGMENTED</label> <input type="text" value="<?php echo isset($select_result['fragmented'])?$select_result['fragmented']:""; ?>" name="fragmented" placeholder="16%">
            </div>
          </div>
          <div class="col-md-6 col-xs-12">
            <div class="t_head"><span>BASIC CLASSIFICATION</span> <span>PERCENTAGE</span></div>
            <div class="form-group">
              <label>BIG HALO</label> <input type="text" value="<?php echo isset($select_result['bhalo'])?$select_result['bhalo']:""; ?>" name="bhalo" placeholder="53%">
            </div>
            <div class="form-group">
              <label>MEDIUM HALO</label> <input type="text" value="<?php echo isset($select_result['mhelo'])?$select_result['mhelo']:""; ?>" name="mhelo" placeholder="31%">
            </div>
            <div class="form-group">
              <label>SMALL HALO</label> <input type="text" value="<?php echo isset($select_result['shelo'])?$select_result['shelo']:""; ?>" name="shelo" placeholder="11%">
            </div>
            <div class="form-group">
              <label>WITHOUT HALO</label> <input type="text" value="<?php echo isset($select_result['whelo'])?$select_result['whelo']:""; ?>" name="whelo" placeholder="5%">
            </div>
          </div>
        </div>
         <div class="row">
           <div class="col-md-6 col-xs-12">
             <div class="image"><img style="max-width: 100%;" src="<?php echo base_url(); ?>assets/images/image1.jpg" alt=""></div>
           </div>
           <div class="col-md-6 col-xs-12">
             <div class="image"><img style="max-width: 100%;" src="<?php echo base_url(); ?>assets/images/image2.jpg" alt=""></div>
           </div>
        </div> 

        <div class="row com_row">
          <div class="col-md-12">
            <div class="form-group">
              <label>COMMENTS:</label> <textarea name="dna_comment" placeholder="Comment Here..."><?php echo isset($select_result['dna_comment'])?$select_result['dna_comment']:""; ?></textarea>
            </div>
          </div>
        </div>

        <div class="row rep_raw">
          <div class="col-md-12 col-xs-12">
            <h3>Interpretation of Result:</h3>
            <table>
              <tr>
                <td>0-15% Fragmentation</td>
                <td>Higher Fertility Potential</td>
              </tr>
              <tr>
                <td>(No halo + Small Halo)</td>
                <td></td>
              </tr>
              <tr>
                <td>>15%-30%</td>
                <td>Good to Fair Fertility Potential</td>
              </tr>
              <tr>
                <td>>30%</td>
                <td>Low to Poor Fertility Potential</td>
              </tr>
            </table>
          </div>
        </div>

        <div class="row report">
          <div class="col-md-12">
            <div class="form-group">
              <label>REPORTED BY:</label> <input type="text" value="<?php echo isset($select_result['rb'])?$select_result['rb']:""; ?>" name="rb" placeholder="Name Here">
            </div>
            <div class="form-group">
              <label>VERIFIED BY:</label> <input type="text" value="<?php echo isset($select_result['vb'])?$select_result['vb']:""; ?>" name="vb" placeholder="Name Here">
            </div>

            <div class="form-group btn_grp">
            <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
            </div>
          </div>
        </div>

        </form>
      </div>

















<!--           Print Button              -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 





<!--           dna_fragmentation               -->




        
		<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<th colspan="2" style="border:1px solid #cdcdcd;"> Sperm DNA Fragmentation Test Report</th>
			<th colspan="2" style="border:1px solid #cdcdcd;">
			   <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
			</th>
		</tr>
	</table>
		
		
		
             
			
			 
			 <table style="width:100%; border:1px solid #cdcdcd;">
			 
		
				
				 <tr> <th  colspan="2" style="border:1px solid #cdcdcd;"> <div class="p_info">PATIENT INFORMATION</div> </th> </tr>
			 
              <tr>
                <td  style="border:1px solid #cdcdcd;">PATIENT NAME:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pname'])?$select_result['pname']:""; ?></td>
              </tr>
			 
			
             <tr>
                <td  style="border:1px solid #cdcdcd;">WIFE NAME:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['wname'])?$select_result['wname']:""; ?></td>
              </tr>
			  
			  
			     <tr>
                <td  style="border:1px solid #cdcdcd;">REF. BY DR:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['refdoc'])?$select_result['refdoc']:""; ?></td>
              </tr>
			  
			  <tr>
                <td  style="border:1px solid #cdcdcd;">AGE</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['age'])?$select_result['age']:""; ?></td>
              </tr>
			  
			  
			  <tr>
                <td  style="border:1px solid #cdcdcd;">ID NO: </td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['idno'])?$select_result['idno']:""; ?></td>
              </tr>
			  
		<tr>
         <td  style="border:1px solid #cdcdcd;">REPORT RELEASE</td>
        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
        </tr>


			
			 
			 <tr> <th colspan="2" style="border:1px solid #cdcdcd;"> <div class="p_info">CHARACTERSTICS</div> </th> </tr>
             
       
<tr>
         <td  style="border:1px solid #cdcdcd;">COLLECTION TIME</td>
        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ctime'])?$select_result['ctime']:""; ?> </td>
        </tr>
		
		
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">COLLECTION TYPE</td>
        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ctype'])?$select_result['ctype']:""; ?> </td>
        </tr>
		
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">SAMPLE</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['sample'])?$select_result['sample']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">SPERM COUNT</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['scont'])?$select_result['scont']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">SPERM MOTILITY</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['smotility'])?$select_result['smotility']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">ROUND CELLS</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['rcell'])?$select_result['rcell']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">NORMAL FORMS</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['nforms'])?$select_result['nforms']:""; ?> </td>
        </tr>

			<tr>
         <td  style="border:1px solid #cdcdcd;">EXAMINATION TIME</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['etime'])?$select_result['etime']:""; ?> </td>
        </tr>
		<tr>
         <td  style="border:1px solid #cdcdcd;">PLACE OF COLLECTION</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['pcollection'])?$select_result['pcollection']:""; ?> </td>
        </tr>
		<tr>
         <td  style="border:1px solid #cdcdcd;">ABSTINENCE</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['abstinence'])?$select_result['abstinence']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">SEMEN VOLUME</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['svolume'])?$select_result['svolume']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">APPEARANCE</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">LIQUEFACTION</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['liquefaction'])?$select_result['liquefaction']:""; ?> </td>
        </tr>
		
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">VISCOCITY</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['viscocity'])?$select_result['viscocity']:""; ?> </td>
        </tr>
		
		<tr>
         <td  style="border:1px solid #cdcdcd;">DNA FRAGMENTATION INDEX (DFI):</td>
        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['dfi'])?$select_result['dfi']:""; ?> </td>
        </tr>
		
			
			</table>

        
        
		 <table style="width:100%; border:1px solid #cdcdcd;">
			 
		
				
				 <tr> <th  colspan="2" style="border:1px solid #cdcdcd;"> <div class="p_info">PERCENTAGE</div> </th> </tr>
			 
              <tr>
                <td  style="border:1px solid #cdcdcd;">NOT FRAGMENTED:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nfragmented'])?$select_result['nfragmented']:""; ?></td>
              </tr>
			  
			  
			  
			   <tr>
                <td  style="border:1px solid #cdcdcd;"> FRAGMENTED:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['fragmented'])?$select_result['fragmented']:""; ?></td>
              </tr>
			  
			  
			  
			   <tr>
                <th colspan="2" style="border:1px solid #cdcdcd;"> BASIC CLASSIFICATION PERCENTAGE:</th>
                
              </tr>
		
		<tr>
                <td  style="border:1px solid #cdcdcd;"> BIG HALO:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['bhalo'])?$select_result['bhalo']:""; ?></td>
              </tr>
			  
			  
			  <tr>
                <td  style="border:1px solid #cdcdcd;"> MEDIUM HALO:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['mhelo'])?$select_result['mhelo']:""; ?></td>
              </tr>
			  
			   <tr>
                <td  style="border:1px solid #cdcdcd;"> SMALL HALO:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['shelo'])?$select_result['shelo']:""; ?></td>
              </tr>
		 <tr>
                <td  style="border:1px solid #cdcdcd;"> WITHOUT HALO:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['whelo'])?$select_result['whelo']:""; ?></td>
              </tr>
		
		</table>

		
		
		
         <div class="row" style="width:100%; border:1px solid #cdcdcd;">
           <div class="col-md-6 col-xs-12" style="border:1px solid #cdcdcd;">
             <div class="image"><img style="max-width: 100%;" src="<?php echo base_url(); ?>assets/images/image1.jpg" alt=""></div>
           </div>
           <div class="col-md-6 col-xs-12" style="border:1px solid #cdcdcd;">
             <div class="image"><img style="max-width: 100%;" src="<?php echo base_url(); ?>assets/images/image2.jpg" alt=""></div>
           </div>
        </div> 

        <div class="row com_row" style="width:100%; border:1px solid #cdcdcd;" >
          <div class="col-md-12">
           <div class="form-group" style="border:1px solid #cdcdcd;">
              <label>COMMENTS:</label> <?php echo isset($select_result['dna_comment'])?$select_result['dna_comment']:""; ?>
            </div>
          </div>
        </div>

        <div class="row rep_raw" style="width:100%; border:1px solid #cdcdcd;">
          <div class="col-md-12 col-xs-12">
            <h3>Interpretation of Result:</h3>
            <table style="width:100%; border:1px solid #cdcdcd;">
              <tr>
                <td  style="border:1px solid #cdcdcd;">0-15% Fragmentation</td>
                <td  style="border:1px solid #cdcdcd;">Higher Fertility Potential</td>
              </tr>
              <tr>
                <td  style="border:1px solid #cdcdcd;">(No halo + Small Halo)</td>
                <td  style="border:1px solid #cdcdcd;"></td>
              </tr>
              <tr>
                <td  style="border:1px solid #cdcdcd;">>15%-30%</td>
                <td  style="border:1px solid #cdcdcd;">Good to Fair Fertility Potential</td>
              </tr>
              <tr>
                <td  style="border:1px solid #cdcdcd;">>30%</td>
                <td  style="border:1px solid #cdcdcd;">Low to Poor Fertility Potential</td>
              </tr>
            </table>
          </div>
        </div>

     
	  
	  
	  
	   <table style="width:100%; border:1px solid #cdcdcd;">
			 
		
				
			 
              <tr>
                <td  style="border:1px solid #cdcdcd;">REPORTED BY:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['rb'])?$select_result['rb']:""; ?></td>
              </tr>
			  
			  
			  <tr>
                <td  style="border:1px solid #cdcdcd;">VERIFIED BY:</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['vb'])?$select_result['vb']:""; ?></td>
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
 