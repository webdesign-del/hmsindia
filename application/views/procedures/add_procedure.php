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
   <input type="hidden" name="action" value="add_procedure" />
   <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
         <div class="panel-heading">
            <h3 class="heading">Add Procedure</h3>
         </div>
         <div class="panel-body profile-edit">
            <p>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedure Name (Required)</label>
                  <input value="" placeholder="Procedure name" id="procedure_name" name="procedure_name" type="text" class="form-control validate" required>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedure Category  (Required)</label>
                  <select class="form-control" name="category" id="category">
                     <option value="">--- Select ---</option>
                     <option value="Non IVF without Bed">Non IVF without Bed</option>
                     <option value="Non IVF without Bed">Non IVF with Bed</option>
                     <option value="IVF with Bed">IVF with Bed</option>
                  </select>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Rupee price (Required)</label>
                  <input value="" placeholder="Rupee price" id="price" name="price" type="text" class="form-control validate" required>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Code (Required)</label>
                  <input value="" placeholder="Code" id="code" name="code" type="text" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedures (Required)</label>
                  <input value="" placeholder="Procedures" id="procedures" name="procedures" type="text" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Broad Procedure (Required)</label>
                  <input value="" placeholder="Broad Procedure" id="broad_procedure" name="broad_procedure" type="text" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Broad Procedure Count (Required)</label>
                  <input value="" placeholder="Broad Procedure Count" id="broad_procedure_count" name="broad_procedure_count" type="number" class="form-control validate" required>
               </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Club Procedure Name</label>
                  <input value="" placeholder="Club Procedure Name" id="package_id" name="package_id" type="text" class="form-control validate" required>
               </div>
               <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Procedure Type  (Required)</label>
                  <select class="form-control" name="code_type" id="code_type">
                     <option value="">--- Select ---</option>
                     <option value="india">Indian</option>
                     <option value="non-india">Non Indian</option>
                  </select>
               </div>
              
            </div>
            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                  <label for="center_id">Center (Required) - Multiple Select</label><br/>
                  <select name="center_id[]" id="center_id" class="form-control multidselect_dropdown_2" multiple="multiple" required>
                     <?php 
                        $all_method =&get_instance();
                        $all_method->load->model('center_model');
                        $centers = $all_method->center_model->get_centers();
                        if(!empty($centers)){
                            foreach($centers as $center){
                                echo '<option value="'.$center['center_number'].'">'.$center['center_name'].'</option>';
                            }
                        }
                        ?>
                  </select>
               </div>
               <div class="form-group col-sm-3 col-xs-6 role">
                  <label for="item_name">Procedure Form (Required)</label> <br/>
                  <select class="form-control multidselect_dropdown_2"  multiple="multiple" name="procedure_form[]">
                  <?php
                      foreach($procedure_forms as $key => $val){
                          echo '<option value="'.$val['ID'].'">'.ucfirst(strtolower(str_replace("_", " ", $val['form_name']))).' ('.$val['form_for'].')</option>'; 
                      } 
                      ?>
                  </select>
                </div>
                <div class="form-group col-sm-3 col-xs-6 role">
                  <label for="item_name">Dischage Form (Required)</label> <br/>
                  <select class="form-control multidselect_dropdown_2"  multiple="multiple" name="discharge_form[]">
                  <?php
                      foreach($discharge_forms as $key => $val){
                          echo '<option value="'.$val['id'].'">'.ucfirst(strtolower(str_replace("_", " ", $val['form_name']))).' ('.$val['form_for'].')</option>'; 
                      } 
                      ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="statuss">Package</label>
                  <br/>
                  <input type="radio" name="parent_id" value="1" class="statuss" checked> Active 
                  <input type="radio" name="parent_id" value="0" class="statuss"> Inactive 
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                    <label for="statuss">Procedure Status (Required)</label>
                    <br/>
                    <input type="radio" name="status" value="1" class="statuss" checked> Active 
                    <input type="radio" name="status" value="0" class="statuss"> Inactive 
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
</script>