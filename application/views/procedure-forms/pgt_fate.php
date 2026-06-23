<?php

    // php code to Insert data into mysql database from input text

    if(isset($_POST['submit'])){

        unset($_POST['submit']);

        $select_query = "SELECT * FROM `hms_pgt_fate` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

        $select_result = run_select_query($select_query); 

        if(empty($select_result)){

            // mysql query to insert data

            $query = "INSERT INTO `hms_pgt_fate` SET ";

            $sqlArr = array();

            foreach( $_POST as $key=> $value )

            {

              $sqlArr[] = " $key = '".addslashes($value)."'";

            }		

            $query .= implode(',' , $sqlArr);
        }else{

            // mysql query to update data

            $query = "UPDATE hms_pgt_fate SET ";

            foreach( $_POST as $key=> $value )

            {

              $sqlArr[] = " $key = '".$value."'"	;

            }

            $query .= implode(',' , $sqlArr);

            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

        }

        // echo $query;die;

        $result = run_form_query($query);        

        // echo $result;die;

       if($result){

          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));

					die();

        }else{

          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));

					die();

        }

    }

    $select_query = "SELECT * FROM `hms_pgt_fate` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

    $select_result = run_select_query($select_query);  

    // var_dump($select_result);die;
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

<div class="container red-field form mt-5 mb-5">

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PGT FATE DETAILS</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="2" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
</table>	
    <div class='table-responsive'>

        <table class="table table-bordered table-hover mt-2 table-sm">

            <colgroup>

                <col span="4" style="background:peachpuff;">

                <col span="4" style="background:lightgreen;">

                <col span="4" style="background:gold;">

            </colgroup>

            <thead>

                <tr>

                    <th colspan="4"><center>PGT-A</center></th>

                    <th colspan="4">PGT-M</th>

                    <th colspan="4">PGT-SR</th>

                </tr>

                <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2">DATE</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_date'])) {$value = $select_result['pgt_'.$a[$i].'_date']; }

                        echo '<th colspan="2"><input type="date" class="form-control" value="'.$value.'" name="pgt_'.$a[$i].'_date"></td></th>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2">TIME</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_time'])) {$value = $select_result['pgt_'.$a[$i].'_time']; 

                            

                            

                        }

                        echo '<th colspan="2"><input type="time" class="form-control" value="'.$value.'" name="pgt_'.$a[$i].'_time"></td></th>';

                    }

                    ?>

                    

                    

                </tr>

                <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2">EMBRYOLOGIST</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_embryologist'])) {$value = $select_result['pgt_'.$a[$i].'_embryologist']; }

                        echo '<th colspan="2"><input type="text" class="form-control" value="'.$value.'" maxlength="20" name="pgt_'.$a[$i].'_embryologist"></td></th>';

                    }

                    ?>

                </tr>

                <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2">WITNESS</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_witness'])) {$value = $select_result['pgt_'.$a[$i].'_witness']; }

                        echo '<th colspan="2"><input type="text" class="form-control" value="'.$value.'" maxlength="20" name="pgt_'.$a[$i].'_witness"></td></th>';

                    }

                    ?>

                </tr>

                    <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2">DOCTOR</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_doctor'])) {$value = $select_result['pgt_'.$a[$i].'_doctor']; }

                        echo '<th colspan="2"><input type="text" class="form-control" value="'.$value.'" maxlength="20" name="pgt_'.$a[$i].'_doctor"></td></th>';

                    }

                    ?>

                </tr
                
                <tr>
                 <th colspan="4" style="color:red;">EMBRYOS SENT FOR PGT-A</th>
                 <th colspan="4" style="color:red;">EMBRYOS SENT FOR PGT-M</th>
                 <th colspan="4" style="color:red;">EMBRYOS SENT FOR PGT-SR</th>
                </tr>
                
                    <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_embryo_sent'])) {$value = $select_result['pgt_'.$a[$i].'_embryo_sent']; }

                        echo '<th colspan="4"><input type="number" min="0" max="20" value="'.$value.'" class="form-control" name="pgt_'.$a[$i].'_embryo_sent"></td></th>';

                    }

                    ?>

                    

                    

                </tr>
                   
                <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="color: green;">Total biopsy with DNA detected</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_biopsy_total'])) {$value = $select_result['pgt_'.$a[$i].'_biopsy_total']; }

                        echo '<th colspan="2"><input type="number" min="0" max="20" value="'.$value.'" class="form-control" name="pgt_'.$a[$i].'_biopsy_total"></td></th>';

                    }

                    ?>      

                </tr>

            </thead>

            <thead>

                <tr>
                    <th class="red-field">Embryo on which DNA detected</th>

                    <th class="red-field">Result of biopsy</th>

                    <th class="red-field">No of embryo transferred</th>

                    <th class="red-field">Fate</th>
                    
                    <th class="red-field">Embryo on which DNA detected</th>

                    <th class="red-field">Result of biopsy</th>

                    <th class="red-field">No of embryo transferred</th>

                    <th class="red-field">Fate</th>
                    
                    <th class="red-field">Embryo on which DNA detected</th>

                    <th class="red-field">Result of biopsy</th>

                    <th class="red-field">No of embryo transferred</th>

                    <th class="red-field">Fate</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a_'.$i])) {$value = $select_result['pgt_a_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m_'.$i])) {$value = $select_result['pgt_m_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr_'.$i])) {$value = $select_result['pgt_sr_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr_'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a1_'.$i])) {$value = $select_result['pgt_a1_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a1_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m1_'.$i])) {$value = $select_result['pgt_m1_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m1_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr1_'.$i])) {$value = $select_result['pgt_sr1_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr1_'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a2_'.$i])) {$value = $select_result['pgt_a2_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a2_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m2_'.$i])) {$value = $select_result['pgt_m2_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m2_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr2_'.$i])) {$value = $select_result['pgt_sr2_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr2_'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a3_'.$i])) {$value = $select_result['pgt_a3_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a3_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m3_'.$i])) {$value = $select_result['pgt_m3_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m3_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr3_'.$i])) {$value = $select_result['pgt_sr3_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr3_'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a4_'.$i])) {$value = $select_result['pgt_a4_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a4_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m4_'.$i])) {$value = $select_result['pgt_m4_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m4_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr4_'.$i])) {$value = $select_result['pgt_sr4_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr4_'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a5_'.$i])) {$value = $select_result['pgt_a5_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a5_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m5_'.$i])) {$value = $select_result['pgt_m5_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m5_'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr5_'.$i])) {$value = $select_result['pgt_sr5_'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr5_'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a6'.$i])) {$value = $select_result['pgt_a6'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a6'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m6'.$i])) {$value = $select_result['pgt_m6'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m6'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr6'.$i])) {$value = $select_result['pgt_sr6'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr6'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a7'.$i])) {$value = $select_result['pgt_a7'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a7'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m7'.$i])) {$value = $select_result['pgt_m7'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m7'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr7'.$i])) {$value = $select_result['pgt_sr7'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr7'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a8'.$i])) {$value = $select_result['pgt_a8'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a8'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m8'.$i])) {$value = $select_result['pgt_m8'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m8'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr8'.$i])) {$value = $select_result['pgt_sr8'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr8'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a9'.$i])) {$value = $select_result['pgt_a9'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a9'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m9'.$i])) {$value = $select_result['pgt_m9'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m9'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr9'.$i])) {$value = $select_result['pgt_sr9'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr9'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a10'.$i])) {$value = $select_result['pgt_a10'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a10'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m10'.$i])) {$value = $select_result['pgt_m10'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m10'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr10'.$i])) {$value = $select_result['pgt_sr10'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr10'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a11'.$i])) {$value = $select_result['pgt_a11'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a11'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m11'.$i])) {$value = $select_result['pgt_m11'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m11'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr11'.$i])) {$value = $select_result['pgt_sr11'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr11'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a12'.$i])) {$value = $select_result['pgt_a12'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a12'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m12'.$i])) {$value = $select_result['pgt_m12'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m12'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr12'.$i])) {$value = $select_result['pgt_sr12'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr12'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a13'.$i])) {$value = $select_result['pgt_a13'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a13'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m13'.$i])) {$value = $select_result['pgt_m13'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m13'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr13'.$i])) {$value = $select_result['pgt_sr13'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr13'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a14'.$i])) {$value = $select_result['pgt_a14'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a14'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m14'.$i])) {$value = $select_result['pgt_m14'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m14'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr14'.$i])) {$value = $select_result['pgt_sr14'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr14'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a15'.$i])) {$value = $select_result['pgt_a15'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a15'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m15'.$i])) {$value = $select_result['pgt_m15'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m15'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr15'.$i])) {$value = $select_result['pgt_sr15'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr15'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a16'.$i])) {$value = $select_result['pgt_a16'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a16'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m16'.$i])) {$value = $select_result['pgt_m16'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m16'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr16'.$i])) {$value = $select_result['pgt_sr16'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr16'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a17'.$i])) {$value = $select_result['pgt_a17'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a17'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m17'.$i])) {$value = $select_result['pgt_m17'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m17'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr17'.$i])) {$value = $select_result['pgt_sr17'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr17'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a18'.$i])) {$value = $select_result['pgt_a18'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a18'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m18'.$i])) {$value = $select_result['pgt_m18'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m18'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr18'.$i])) {$value = $select_result['pgt_sr18'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr18'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a19'.$i])) {$value = $select_result['pgt_a19'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a19'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m19'.$i])) {$value = $select_result['pgt_m19'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m19'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr19'.$i])) {$value = $select_result['pgt_sr19'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr19'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a20'.$i])) {$value = $select_result['pgt_a20'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a20'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m20'.$i])) {$value = $select_result['pgt_m20'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m20'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr20'.$i])) {$value = $select_result['pgt_sr20'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr20'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a21'.$i])) {$value = $select_result['pgt_a21'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a21'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m21'.$i])) {$value = $select_result['pgt_m21'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m21'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr21'.$i])) {$value = $select_result['pgt_sr21'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr21'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a22'.$i])) {$value = $select_result['pgt_a22'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a22'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m22'.$i])) {$value = $select_result['pgt_m22'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m22'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr22'.$i])) {$value = $select_result['pgt_sr22'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr22'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a23'.$i])) {$value = $select_result['pgt_a23'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a23'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m23'.$i])) {$value = $select_result['pgt_m23'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m23'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr23'.$i])) {$value = $select_result['pgt_sr23'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr23'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a24'.$i])) {$value = $select_result['pgt_a24'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a24'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m24'.$i])) {$value = $select_result['pgt_m24'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m24'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr24'.$i])) {$value = $select_result['pgt_sr24'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr24'.$i.'"></td>';

                        }

                    ?>

                </tr>

                <tr>

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a25'.$i])) {$value = $select_result['pgt_a25'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_a25'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m25'.$i])) {$value = $select_result['pgt_m25'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_m25'.$i.'"></td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr25'.$i])) {$value = $select_result['pgt_sr25'.$i]; }

                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="pgt_sr25'.$i.'"></td>';

                        }

                    ?>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- <p class="mt-2"><span>UPLOAD PHOTO</span> <input type="file" id="file" multiple change="onFileChange"/></th></p> -->

<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->

<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">

</table>

</form>


    <!-- print-->   
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">


<tbody id="male_medicine_suggestion_table">

<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PGT FATE DETAILS</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="2" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>	


</tbody>


</table>


<table class="table table-bordered table-hover mt-2 table-sm    ">

            <colgroup>

                <col span="4" >

                <col span="4" >

                <col span="4" >

            </colgroup>

            <thead>

                <tr>

                    <th colspan="4" style="border:1px solid #cdcdcd;"><center>PGT-A</center></th>

                    <th colspan="4" style="border:1px solid #cdcdcd;">PGT-M</th>
                    
                    <th colspan="4" style="border:1px solid #cdcdcd;">PGT-SR</th>

                </tr>

                  <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">DATE</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_date'])) {$value = $select_result['pgt_'.$a[$i].'_date']; }

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';

                        }

                    ?>

                </tr>

                 <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">TIME</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_time'])) {$value = $select_result['pgt_'.$a[$i].'_time']; 
 

                        }

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';

                    }

                    ?>

                </tr>

                 <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">EMBRYOLOGIST</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_embryologist'])) {$value = $select_result['pgt_'.$a[$i].'_embryologist']; }

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';

                    }

                    ?>

                </tr>

                 <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">WITNESS</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_witness'])) {$value = $select_result['pgt_'.$a[$i].'_witness']; }

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';

                    }

                    ?>

                </tr>

                 <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">DOCTOR</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_doctor'])) {$value = $select_result['pgt_'.$a[$i].'_doctor']; }

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';

                    }

                    ?>

                </tr>
                
                 <tr>

                        <th colspan="4" style="border:1px solid #cdcdcd;" >EMBRYOS SENT FOR PGT-A</th>
                        
                        <th colspan="4" style="border:1px solid #cdcdcd;" >EMBRYOS SENT FOR PGT-M</th>
                        
                        <th colspan="4" style="border:1px solid #cdcdcd;" >EMBRYOS SENT FOR PGT-SR</th>
                    
                </tr>

                 <tr style="height: 20px;">

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_embryo_sent'])) {$value = $select_result['pgt_'.$a[$i].'_embryo_sent']; }

                        echo '<th colspan="4" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';
                    }
                    ?>

                </tr>

                <tr>

                    <?php 

                    $a = array("a","m","sr");

                    for($i=0;$i<count($a);$i++){

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;" >Total biopsy with DNA detected</th>';

                        $value=""; if(isset($select_result['pgt_'.$a[$i].'_biopsy_total'])) {$value = $select_result['pgt_'.$a[$i].'_biopsy_total']; }

                        echo '<th colspan="2" style="border:1px solid #cdcdcd;">'.$value.'</td></th>';

                    }

                    ?>      

                </tr>


</thead>

 <thead>

                <tr>
                    
                    <th class="red-field" style="border:1px solid #cdcdcd;">Embryo on which DNA detected</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">Result of biopsy</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">No of embryo transferred</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">Fate</th>
                    
                     <th class="red-field" style="border:1px solid #cdcdcd;">Embryo on which DNA detected</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">Result of biopsy</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">No of embryo transferred</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">Fate</th>
                    
                     <th class="red-field" style="border:1px solid #cdcdcd;">Embryo on which DNA detected</th>
                    
                    <th class="red-field" style="border:1px solid #cdcdcd;">Result of biopsy</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">No of embryo transferred</th>

                    <th class="red-field" style="border:1px solid #cdcdcd;">Fate</th>
                
                </tr>

            </thead>


             <tbody>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a_'.$i])) {$value = $select_result['pgt_a_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m_'.$i])) {$value = $select_result['pgt_m_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                       for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr_'.$i])) {$value = $select_result['pgt_sr_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>
                  <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a1_'.$i])) {$value = $select_result['pgt_a1_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m1_'.$i])) {$value = $select_result['pgt_m1_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr1_'.$i])) {$value = $select_result['pgt_sr1_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a2_'.$i])) {$value = $select_result['pgt_a2_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m2_'.$i])) {$value = $select_result['pgt_m2_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                           for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr2_'.$i])) {$value = $select_result['pgt_sr2_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                 <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a3_'.$i])) {$value = $select_result['pgt_a3_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m3_'.$i])) {$value = $select_result['pgt_m3_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr3_'.$i])) {$value = $select_result['pgt_sr3_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

<tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a4_'.$i])) {$value = $select_result['pgt_a4_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m4_'.$i])) {$value = $select_result['pgt_m4_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                           for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr4_'.$i])) {$value = $select_result['pgt_sr4_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a5_'.$i])) {$value = $select_result['pgt_a5_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m5_'.$i])) {$value = $select_result['pgt_m5_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr5_'.$i])) {$value = $select_result['pgt_sr5_'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a6'.$i])) {$value = $select_result['pgt_a6'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m6'.$i])) {$value = $select_result['pgt_m6'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                      for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr6'.$i])) {$value = $select_result['pgt_sr6'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                        

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a7'.$i])) {$value = $select_result['pgt_a7'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m7'.$i])) {$value = $select_result['pgt_m7'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr7'.$i])) {$value = $select_result['pgt_sr7'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a8'.$i])) {$value = $select_result['pgt_a8'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m8'.$i])) {$value = $select_result['pgt_m8'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                           for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr8'.$i])) {$value = $select_result['pgt_sr8'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a9'.$i])) {$value = $select_result['pgt_a9'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m9'.$i])) {$value = $select_result['pgt_m9'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                        
                       for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr9'.$i])) {$value = $select_result['pgt_sr9'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a10'.$i])) {$value = $select_result['pgt_a10'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m10'.$i])) {$value = $select_result['pgt_m10'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr10'.$i])) {$value = $select_result['pgt_sr10'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a11'.$i])) {$value = $select_result['pgt_a11'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m11'.$i])) {$value = $select_result['pgt_m11'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr11'.$i])) {$value = $select_result['pgt_sr11'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a12'.$i])) {$value = $select_result['pgt_a12'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m12'.$i])) {$value = $select_result['pgt_m12'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr12'.$i])) {$value = $select_result['pgt_sr12'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a13'.$i])) {$value = $select_result['pgt_a13'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m13'.$i])) {$value = $select_result['pgt_m13'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                          for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr13'.$i])) {$value = $select_result['pgt_sr13'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a14'.$i])) {$value = $select_result['pgt_a14'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m14'.$i])) {$value = $select_result['pgt_m14'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                      for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr14'.$i])) {$value = $select_result['pgt_sr14'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a15'.$i])) {$value = $select_result['pgt_a15'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m15'.$i])) {$value = $select_result['pgt_m15'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
 
                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m15'.$i])) {$value = $select_result['pgt_sr15'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a16'.$i])) {$value = $select_result['pgt_a16'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m16'.$i])) {$value = $select_result['pgt_m16'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr16'.$i])) {$value = $select_result['pgt_sr16'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                 <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a17'.$i])) {$value = $select_result['pgt_a17'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m17'.$i])) {$value = $select_result['pgt_m17'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr17'.$i])) {$value = $select_result['pgt_sr17'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a18'.$i])) {$value = $select_result['pgt_a18'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m18'.$i])) {$value = $select_result['pgt_m18'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                       for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr18'.$i])) {$value = $select_result['pgt_sr18'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a19'.$i])) {$value = $select_result['pgt_a19'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m19'.$i])) {$value = $select_result['pgt_m19'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr19'.$i])) {$value = $select_result['pgt_sr19'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a20'.$i])) {$value = $select_result['pgt_a20'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m20'.$i])) {$value = $select_result['pgt_m20'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                          for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr20'.$i])) {$value = $select_result['pgt_sr20'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a21'.$i])) {$value = $select_result['pgt_a21'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m21'.$i])) {$value = $select_result['pgt_m21'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr21'.$i])) {$value = $select_result['pgt_sr21'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a22'.$i])) {$value = $select_result['pgt_a22'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m22'.$i])) {$value = $select_result['pgt_m22'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                         for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr22'.$i])) {$value = $select_result['pgt_sr22'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a23'.$i])) {$value = $select_result['pgt_a23'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m23'.$i])) {$value = $select_result['pgt_m23'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr23'.$i])) {$value = $select_result['pgt_sr23'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a24'.$i])) {$value = $select_result['pgt_a24'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m24'.$i])) {$value = $select_result['pgt_m24'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr24'.$i])) {$value = $select_result['pgt_sr24'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }
                    ?>

                </tr>

                <tr style="height: 20px;">

                    <?php

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_a25'.$i])) {$value = $select_result['pgt_a25'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                        for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_m25'.$i])) {$value = $select_result['pgt_m25'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                      for($i=0;$i<=3;$i++){

                        $value=""; if(isset($select_result['pgt_sr25'.$i])) {$value = $select_result['pgt_sr25'.$i]; }

                            echo '<td style="border:1px solid #cdcdcd;">'.$value.'</td>';

                        }

                    ?>

                </tr>


                 </tbody>

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