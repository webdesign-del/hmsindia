<?php $all_method =&get_instance(); ?>
<style>
   input[type="checkbox"] {
   position: relative!important;
   left: 2px!important;
   opacity: 1!important;
   }
   .open > .dropdown-menu {
   width: 350px;
   max-height: 300px;
   overflow: auto;
   }
   label.checkbox {
   color: #000;
   }
   .btn-group{
   max-width: 100%;
   }
   button.multiselect.dropdown-toggle.btn.btn-default {
   width: 100%;
   overflow:hidden;
   }
</style>
<form class="col-sm-12 col-xs-12" method="post" action="" >
   <input type="hidden" name="action" value="update_procedure" />
   <input type="hidden" name="id" value="<?php echo $data['ID']; ?>" />
   <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
         <div class="panel-heading">
            <h3 class="heading">Edit Procedure</h3>
         </div>
         <div class="panel-body profile-edit">
            <p>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedure Name (Required)</label>
                  <input value="<?php echo $data['procedure_name']?>" placeholder="Procedure name" id="procedure_name" name="procedure_name" type="text" class="form-control validate" required>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedure Category  (Required)</label>
                  <select class="form-control" name="category">
                     <option value="<?php echo $data['category']?>"><?php echo $data['category']?></option>
                     <option value="Non IVF without Bed">Non IVF without Bed</option>
                     <option value="Non IVF without Bed">Non IVF with Bed</option>
                     <option value="IVF with Bed">IVF with Bed</option>
                  </select>
               </div>
               <!-- <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="statuss">Procedure Parent (Required)</label>
                  <select name="parent_id" required>
                      <option value="0">Select Procedure</option>
                      <?php foreach($procedures as $ky => $vl){
                     if($vl['ID'] != $_GET['id']){
                     $selected="";
                     if($data['parent_id'] == $vl['ID']){$selected="selected='selected'";}?>
                        <option value="<?php echo $vl['ID']?>" <?php echo $selected; ?>><?php echo $vl['procedure_name']?></option>
                      <?php } } ?>
                  </select>
                  </div> -->
            </div>
            <div class="row">
               <!-- <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Price (Required)</label>
                  <input value="<?php echo $data['price']?>" placeholder="Price" id="price" name="price" type="text" class="form-control validate" required>
                  </div> -->
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Rupee price (Required)</label>
                  <input value="<?php echo $data['price']?>" placeholder="Rupee price" id="price" name="price" type="text" class="form-control validate" required>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Code (Required)</label>
                  <input value="<?php echo $data['code']?>" placeholder="Code" id="code" name="code" type="text" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedures (Required)</label>
                  <input value="<?php echo $data['procedures']?>" placeholder="Procedures" id="procedures" name="procedures" type="text" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Broad Procedure (Required)</label>
                  <input value="<?php echo $data['broad_procedure']?>" placeholder="Broad Procedure" id="broad_procedure" name="broad_procedure" type="text" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Broad Procedure Count (Required)</label>
                  <input value="<?php echo $data['broad_procedure_count']?>" placeholder="Broad Procedure Count" id="broad_procedure_count" name="broad_procedure_count" type="number" class="form-control validate" required>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                 <label for="center_id">Center (Required) - Multiple Select</label><br/>
                 <select name="center_id[]" id="center_id" class="form-control multidselect_dropdown_2" multiple="multiple" required>
                     <?php 
                        $all_method->load->model('center_model');
                        $centers = $all_method->center_model->get_centers();
                        
                        // convert saved comma-separated IDs into array
                        $selected_center_ids = [];
                        if (!empty($data['center_id'])) {
                              $selected_center_ids = explode(',', $data['center_id']);
                        }

                        if (!empty($centers)) {
                              foreach ($centers as $center) {
                                 $selected = in_array($center['center_number'], $selected_center_ids) ? 'selected="selected"' : '';
                                 echo '<option value="'.$center['center_number'].'" '.$selected.'>'.$center['center_name'].'</option>';
                              }
                        }
                     ?>
                  </select>

               </div>
               <div class="form-group col-sm-6 col-xs-12 role">
                  <label for="item_name">Procedure Form (Required)</label> <br/>
                  <select class="form-control multidselect_dropdown_2"  multiple="multiple" name="procedure_form[]">
                  <?php
                     $selected_procedure_forms = array();
                     if (!empty($data['procedure_form'])) {
                           $selected_procedure_forms = explode(',', $data['procedure_form']);
                     }
                     
                     foreach($procedure_forms as $key => $val){
                           $selected = in_array($val['ID'], $selected_procedure_forms) ? 'selected="selected"' : '';
                           echo '<option value="'.$val['ID'].'" '.$selected.'>'.ucfirst(strtolower(str_replace("_", " ", $val['form_name']))).' ('.$val['form_for'].')</option>'; 
                     }
                     ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="statuss">Package</label>
                  <br/>
                  <input type="radio" name="parent_id" value="1" <?php if($data['parent_id'] == 1){echo 'checked="checked"';} ?> class="statuss" > Active 
                  <input type="radio" name="parent_id" value="0" <?php if($data['parent_id'] == 0){echo 'checked="checked"';} ?> class="statuss"> Inactive 
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="statuss">Procedure Status (Required)</label>
                  <br/>
                  <input type="radio" name="status" value="1" <?php if($data['status'] == 1){echo 'checked="checked"';} ?> class="statuss"> Active 
                  <input type="radio" name="status" value="0" <?php if($data['status'] == 0){echo 'checked="checked"';} ?> class="statuss"> Inactive 
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="form-group col-sm-12 col-xs-12 text-center">
            <input type="submit" id="submitbutton" class="btn btn-large" value="Submit" />
         </div>
         </p>
      </div>
   </div>
</form>
<script>
   $(function() {
   	$('.multidselect_dropdown_2').multiselect({ includeSelectAllOption: true });
   });
   $(function() {
   	$('.multidselect_dropdown_2').multiselect({ includeSelectAllOption: true });
   });            
</script>