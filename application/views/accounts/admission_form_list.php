<?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
       <div class="card-action"><h3>Admission Form List</h3></div>
       <div class="clearfix"></div>
        <form action="<?php echo base_url().'accounts/admission_form_list'; ?>" method="get">
             
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
              <label>Patient ID / IIC ID</label>
              <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo isset($patient_id)?$patient_id:'';?>" />
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
                <button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
                <a href="<?php echo base_url().'accounts/admission_form_list'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            </form>  
        <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>IIC ID</th>
                  <th>Ipid</th>
                  <th>Doctor Name</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="admission_result">
              <?php 
              $count=1; 
              if(!empty($admission_result)){
              foreach($admission_result as $ky => $vl){
                
                $uhid_display = "";
                $w_name = "N/A";
                $w_age = "N/A";
                
                $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$vl['patient_id']."' and paitent_type='new_patient' limit 1 "; 
                $query1 = $this->db->query($sql1);
                $select_result1 = $query1->result_array(); 
                
                if(!empty($select_result1)) {
                    $res_val = $select_result1[0]; 

                    $sql_patients = "Select * from ".$this->config->item('db_prefix')."patients where patient_id='".$res_val['paitent_id']."' limit 1"; 
                    $query_patients = $this->db->query($sql_patients);
                    $select_patients = $query_patients->result_array();
                    
                    if(!empty($select_patients)) {
                        $w_name = htmlspecialchars($select_patients[0]['wife_name'], ENT_QUOTES);
                        $w_age  = htmlspecialchars($select_patients[0]['wife_age'], ENT_QUOTES);
                    }
                    
                    $sql_centers = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$res_val['appoitment_for']."' limit 1"; 
                    $query_centers = $this->db->query($sql_centers);
                    $select_centers = $query_centers->result_array();
                    
                    $center_code = "";
                    if(!empty($select_centers)) {
                        $center_code = $select_centers[0]['center_code'];
                    }
                    
                    if(!empty($res_val['uhid'])) {
                        $uhid_display = (!empty($center_code) ? $center_code . "/" : "") . $res_val['uhid'];
                    }
                }
               ?>
                <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
                  <td><a href="<?php echo base_url().'patient_details'; ?>/<?php echo $vl['patient_id']; ?>"><?php echo $vl['patient_id']; ?></a></td>
                  <td><?php echo $vl['ipid']?></td>
                  <td><?php echo $vl['doctor_name']?></td>
                  <td><?php echo $vl['updated_at']?></td>
                  <td>
                        <div style="text-align: center;">
                            <button type="button" onclick="printOldBetterLabel('<?php echo $w_name; ?>', '<?php echo $w_age; ?>', '<?php echo $uhid_display; ?>', '<?php echo $vl['patient_id']; ?>', '<?php echo $vl['ipid']; ?>')" class="btn btn-primary" style="padding: 5px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                🖨️ Print
                            </button>
                        </div>
                    </td>
                </tr>
              <?php $count++;}} else { ?>
                <tr><td colspan="6" style="text-align:center;">No Records Found</td></tr>
              <?php } ?>
              </tbody>            
            </table>
          </div>
          
          <div class="row">
              <div class="col-sm-12" style="text-align: right; margin-top: 10px;">
                  <div class="custom-pagination">
                      <?php 
                        // नोट: अगर आपके कंट्रोलर से आ रहे पैजिनेशन वेरिएबल का नाम अलग है (जैसे $links या $pagination), तो उसे यहाँ बदल लें
                        if(isset($links)) { 
                            echo $links; 
                        } elseif(isset($pagination)) {
                            echo $pagination;
                        } else {
                            // बैकअप के लिए मैन्युअल चेक (अगर CodeIgniter library लोड है)
                            echo $this->pagination->create_links(); 
                        }
                      ?>
                  </div>
              </div>
          </div>

        </div>
      </div>
     </div>
     
<div id="print_this_section" style="display:none;">
    <div class="ga-pro">
        <table width="100%" class="vb45rt" style="border-collapse: separate; border-spacing: 15px;">
            <tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
            <tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
            <tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
			<tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
			<tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
			<tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
			<tr>
                <?php for($i=0; $i<3; $i++){ ?>
                <td style="padding:10px; border:1px solid #333; border-radius: 4px; background: #fff;">
                    <table width="280px" class="vb45rt">
                        <tbody>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>Pt Name : <span class="lbl_name"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>Age / Sex : <span class="lbl_age"></span> / F</strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>UHID : <span class="lbl_uhid"></span></strong></td></tr>
                            <tr><td style="width:28%; padding: 2px 0;"><strong>IIC ID : <span class="lbl_iic"></span></strong></td></tr>
                            <tr><td style="padding: 2px 0;"><strong>IPID : <span class="lbl_ipid"></span></strong></td></tr>
                        </tbody>
                    </table> 
                </td>
                <?php } ?>
            </tr>
        </table>
    </div>
</div> 
   
<style>
/* पैजिनेशन को सुंदर और बटन जैसा लुक देने के लिए स्टाइल */
.custom-pagination { padding: 8px 0; display: inline-block; }
.custom-pagination a, .custom-pagination strong {
  padding: 8px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #337ab7;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 3px;
  margin: 0 2px;
}
.custom-pagination strong {
  color: #fff;
  background-color: #337ab7;
  border-color: #337ab7;
}
.form-control{ height: 30px!important; border: 1px solid #9e9e9e!important; }
</style>

<script type="text/javascript">
function printOldBetterLabel(name, age, uhid, iic, ipid) {
    var printContainer = document.getElementById('print_this_section');

    var nameFields = printContainer.getElementsByClassName('lbl_name');
    var ageFields = printContainer.getElementsByClassName('lbl_age');
    var uhidFields = printContainer.getElementsByClassName('lbl_uhid');
    var iicFields = printContainer.getElementsByClassName('lbl_iic');
    var ipidFields = printContainer.getElementsByClassName('lbl_ipid');

    for(var i=0; i<nameFields.length; i++) { nameFields[i].innerText = name; }
    for(var i=0; i<ageFields.length; i++) { ageFields[i].innerText = age; }
    for(var i=0; i<uhidFields.length; i++) { uhidFields[i].innerText = uhid ? uhid : 'N/A'; }
    for(var i=0; i<iicFields.length; i++) { iicFields[i].innerText = iic; }
    for(var i=0; i<ipidFields.length; i++) { ipidFields[i].innerText = ipid ? ipid : 'N/A'; }

    var printContents = printContainer.innerHTML;
    var originalContents = document.body.innerHTML;

    var printStyles = '<style>' +
                      'body { background: white; color: black; padding: 5px; font-family: "Arial", sans-serif; margin: 0; }' +
                      '.vb45rt { width: 100%; border-collapse: collapse; }' +
                      'td { font-size: 13px; line-height: 1.5; color: #000; }' +
                      'strong { font-weight: bold; }' +
                      '@page { size: auto; margin: 2mm; }' +
                      '</style>';

    document.body.innerHTML = printStyles + printContents;
    window.print();

    document.body.innerHTML = originalContents;
    location.reload();
}
</script>