
<form class="col-sm-12 col-xs-12" method="post" action="" >
    <input type="hidden" name="action" value="update_center" />
    <input type="hidden" name="employee_number" value="<?php echo $data['employee_number']; ?>" />
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku">
      <div class="panel-heading">
        <h3 class="heading">Update Center</h3>
      </div>
      <div class="panel-body profile-edit">
        <p>
       
        <div class="clearfix"></div>      
         
          <div class="form-group col-sm-6 col-xs-12 role">
    <label for="statuss">Center (Required)</label>
    <select name="center_id" required class="form-control">
      <option value="">Select Center</option>
      <?php 
      if(!empty($centers)){
          foreach($centers as $ky => $vl){
              $selected="";
              if($data['center_id'] == $vl['center_number']){$selected="selected='selected'";}
              ?>
              <option value="<?php echo $vl['center_number']?>" <?php echo $selected; ?>><?php echo $vl['center_name']?></option>
          <?php } 
      } else { ?>
          <option value="" disabled>You don't have permission to switch to any center</option>
      <?php } ?>
    </select>
</div>
        </div>
        

      </div>
      <div class="clearfix"></div>
      <div class="form-group col-sm-12 col-xs-12">
        <input type="submit" id="submitbutton" class="btn btn-large" value="Submit" />
      </div>
      </p>
    </div>
  </div>
</form>