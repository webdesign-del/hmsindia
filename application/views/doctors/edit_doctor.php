

<form class="col-sm-12 col-xs-12" method="post" action="" >

    <input type="hidden" name="action" value="update_doctor" />

    <input type="hidden" name="id" value="<?php echo $data['ID']; ?>" />

  <div class="row">

    <div class="col-sm-12 col-xs-12 panel panel-piluku">

      <div class="panel-heading">

        <h3 class="heading">Edit Doctor</h3>

      </div>

      <div class="panel-body profile-edit">

        <p>

        <div class="row">

          <div class="form-group col-sm-6 col-xs-12">

             <label for="item_name">Doctor Name (Dr.) (Required)</label>

            <input value="<?php echo $data['name']?>" placeholder="Doctor name" id="name" name="name" type="text" class="form-control validate" required>

          </div>

                    

          <div class="form-group col-sm-6 col-xs-12">            

               <label for="item_name">Fees (Required)</label>

               <input value="<?php echo $data['fees']?>" placeholder="Fees" id="fees" name="fees" type="text" class="form-control validate" required>

          </div>
		</div>
		
		<div class="row">

          <div class="form-group col-sm-6 col-xs-12">

            <label for="item_name">Doctor email (Required)</label>

            <input value="<?php echo $data['email']?>" placeholder="Doctor email" id="email" name="email" type="text" class="form-control validate" required>
		  </div>
		  
		  <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Is Primary Doctor?</label>
            <input value="1" style="left: 0;position: relative;opacity: 1;" <?php if($data['is_primary'] == 1){echo "checked='checked'"; }?> id="is_primary" name="is_primary" type="checkbox" class="form-control validate">
		  </div>
        </div>

        

        <div class="row">

	      

          <div class="form-group col-sm-6 col-xs-12">

                <label for="item_name">USD fees (Required)</label>

                <input value="<?php echo $data['usd_fees']?>" placeholder="Fees" id="usd_fees" name="usd_fees" type="text" class="form-control validate" required>

            </div>

             

           <div class="form-group col-sm-6 col-xs-12 role">

            <label for="statuss">Doctor Center (Required)</label>

            <select name="center_id" required>

            	<option value="">Select Center</option>

				<?php foreach($centers as $ky => $vl){

					  $selected="";

					  if($data['center_id'] == $vl['center_number']){$selected="selected='selected'";}

					  ?>

              	  <option value="<?php echo $vl['center_number']?>" <?php echo $selected; ?>><?php echo $vl['center_name']?></option>

                <?php } ?>

            </select>

        </div>

        </div>

        

        <div class="row">

          <div class="form-group col-sm-6 col-xs-12">

            <label for="item_name">Username (Required)</label>

			<h4><?php echo $data['username']?></h4>

          </div>

          

          	<div class="form-group col-sm-6 col-xs-12">

                <label for="item_name">Password (Optional)</label>

                <input value="" placeholder="" id="password" name="password" type="text" class="form-control validate">

            </div>

        </div>
		
		
		 <div class="row">

          <div class="form-group col-sm-12 col-xs-12">

            <label for="item_name">Doctor Degree Details  (Required)</label>

			<input value="<?php echo $data['doctor_details']?>" placeholder="Fees" id="doctor_details" name="doctor_details" type="text" class="form-control validate" required>


          </div>

        
        </div>

		<!-- यहाँ से डॉक्टर परमिशन का डायनामिक एडिट कोड शुरू होता है -->
<div class="row">
  <div class="form-group col-sm-12 col-xs-12">
    <label style="font-weight: bold; color: #333; display: block; margin-bottom: 10px;">
      Allowed Centers for Doctor Switching (Permission Panel)
    </label>
    
    <div class="panel panel-default" style="border: 1px solid #ddd; padding: 15px; border-radius: 4px; background: #fbfbfb;">
      <div class="row">
        <?php 
        // डेटाबेस से सेव की गई कॉमा-सेपरेटेड स्ट्रिंग को एरे में बदलें
        $saved_centers = !empty($data['allowed_centers']) ? explode(',', $data['allowed_centers']) : array();
        
        if(!empty($centers)) { 
          foreach($centers as $ky => $vl) { 
            // चेक करें कि क्या यह सेंटर डॉक्टर की अलाउड लिस्ट में पहले से मौजूद है
            $isChecked = in_array($vl['center_number'], $saved_centers) ? 'checked="checked"' : '';
            ?>
            <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
              <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="allowed_centers[]" value="<?php echo $vl['center_number']; ?>" <?php echo $isChecked; ?> style="margin: 0; width: 18px; height: 18px;"> 
                <span><?php echo $vl['center_name']; ?></span>
              </label>
            </div>
          <?php } 
        } else { ?>
          <div class="col-xs-12 text-danger">No centers available.</div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- डॉक्टर परमिशन का डायनामिक एडिट कोड समाप्त -->

                

        <div class="row">

         	<h4 style="margin-bottom:20px;">Doctor Hours (Required)</h4>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Monday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['monday_morning_start_time']; ?>" id="monday_morning_start_time" autocomplete="off" name="monday_morning_start_time" type="text" class="form-control monday_morning_off validate timepicker monday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['monday_morning_end_time']; ?>" id="monday_morning_end_time" autocomplete="off" name="monday_morning_end_time" type="text" class="form-control monday_morning_off validate timepicker monday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['monday_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="monday_morning_off" id="monday_morning_off" class="form-control validate monday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['monday_evening_start_time']; ?>" id="monday_evening_start_time" autocomplete="off" name="monday_evening_start_time" type="text" class="monday_evening_off form-control validate timepicker monday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['monday_evening_end_time']; ?>" id="monday_evening_end_time" autocomplete="off" name="monday_evening_end_time" type="text" class="monday_evening_off form-control validate timepicker monday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['monday_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="monday_evening_off" id="monday_evening_off" class="form-control validate monday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['monday_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="monday_off" id="monday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Tuesday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['tuesday_morning_start_time']; ?>" id="tuesday_morning_start_time" autocomplete="off" name="tuesday_morning_start_time" type="text" class="tuesdy_morning_off form-control validate timepicker tuesday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['tuesday_morning_end_time']; ?>" id="tuesday_morning_end_time" autocomplete="off" name="tuesday_morning_end_time" type="text" class="tuesdy_morning_off form-control validate timepicker tuesday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['tuesdy_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="tuesdy_morning_off" id="tuesdy_morning_off" class="form-control validate tuesday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['tuesday_evening_start_time']; ?>" id="tuesday_evening_start_time" autocomplete="off" name="tuesday_evening_start_time" type="text" class="tuesdy_evening_off form-control validate timepicker tuesday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['tuesday_evening_end_time']; ?>" id="tuesday_evening_end_time" autocomplete="off" name="tuesday_evening_end_time" type="text" class="tuesdy_evening_off form-control validate timepicker tuesday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['tuesdy_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="tuesdy_evening_off" id="tuesdy_evening_off" class="form-control validate tuesday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['tuesday_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="tuesday_off" id="tuesday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Wednesday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['wednesday_morning_start_time']; ?>" id="wednesday_morning_start_time" autocomplete="off" name="wednesday_morning_start_time" type="text" class="wednesday_morning_off form-control validate timepicker wednesday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['wednesday_morning_end_time']; ?>" id="wednesday_morning_end_time" autocomplete="off" name="wednesday_morning_end_time" type="text" class="wednesday_morning_off form-control validate timepicker wednesday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['wednesday_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1" type="checkbox" name="wednesday_morning_off" id="wednesday_morning_off" class="form-control validate wednesday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['wednesday_evening_start_time']; ?>" id="wednesday_evening_start_time" autocomplete="off" name="wednesday_evening_start_time" type="text" class="wednesday_evening_off form-control validate timepicker wednesday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['wednesday_evening_end_time']; ?>" id="wednesday_evening_end_time" autocomplete="off" name="wednesday_evening_end_time" type="text" class="wednesday_evening_off form-control validate timepicker wednesday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['wednesday_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="wednesday_evening_off" id="wednesday_evening_off" class="form-control validate wednesday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['wednesday_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="wednesday_off" id="wednesday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Thursday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['thursday_morning_start_time']; ?>" id="thursday_morning_start_time" autocomplete="off" name="thursday_morning_start_time" type="text" class="thursday_morning_off form-control validate timepicker thursday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['thursday_morning_end_time']; ?>" id="thursday_morning_end_time" autocomplete="off" name="thursday_morning_end_time" type="text" class="thursday_morning_off form-control validate timepicker thursday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['thursday_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="thursday_morning_off" id="thursday_morning_off" class="form-control validate thursday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['thursday_evening_start_time']; ?>" id="thursday_evening_start_time" autocomplete="off" name="thursday_evening_start_time" type="text" class="thursday_evening_off form-control validate timepicker thursday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['thursday_evening_end_time']; ?>" id="thursday_evening_end_time" autocomplete="off" name="thursday_evening_end_time" type="text" class="thursday_evening_off form-control validate timepicker thursday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['thursday_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="thursday_evening_off" id="thursday_evening_off" class="form-control validate thursday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['thursday_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="thursday_off" id="thursday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Friday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['friday_morning_start_time']; ?>" id="friday_morning_start_time" autocomplete="off" name="friday_morning_start_time" type="text" class="friday_morning_off form-control validate timepicker friday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['friday_morning_end_time']; ?>" id="friday_morning_end_time" autocomplete="off" name="friday_morning_end_time" type="text" class="friday_morning_off form-control validate timepicker friday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['friday_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="friday_morning_off" id="friday_morning_off" class="form-control validate friday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['friday_evening_start_time']; ?>" id="friday_evening_start_time" autocomplete="off" name="friday_evening_start_time" type="text" class="friday_evening_off form-control validate timepicker friday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['friday_evening_end_time']; ?>" id="friday_evening_end_time" autocomplete="off" name="friday_evening_end_time" type="text" class="friday_evening_off form-control validate timepicker friday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['friday_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="friday_evening_off" id="friday_evening_off" class="form-control validate friday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['friday_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="friday_off" id="friday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Saturday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['saturday_morning_start_time']; ?>" id="saturday_morning_start_time" autocomplete="off" name="saturday_morning_start_time" type="text" class="saturday_morning_off form-control validate timepicker saturday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['saturday_morning_end_time']; ?>" id="saturday_morning_end_time" autocomplete="off" name="saturday_morning_end_time" type="text" class="saturday_morning_off form-control validate timepicker saturday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['saturday_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="saturday_morning_off" id="saturday_morning_off" class="form-control validate saturday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['saturday_evening_start_time']; ?>" id="saturday_evening_start_time" autocomplete="off" name="saturday_evening_start_time" type="text" class="saturday_evening_off form-control validate timepicker saturday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['saturday_evening_end_time']; ?>" id="saturday_evening_end_time" autocomplete="off" name="saturday_evening_end_time" type="text" class="saturday_evening_off form-control validate timepicker saturday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['saturday_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="saturday_evening_off" id="saturday_evening_off" class="form-control validate saturday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['saturday_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="saturday_off" id="saturday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

            <div class="row">

                <div class="form-group col-sm-2 col-xs-12">

                    <h4>Sunday : </h4>

                </div>

                <div class="form-group col-sm-10 col-xs-12">

                        <div class="col-sm-4 col-xs-12">

                        	<label style="text-align:center; width:100%;font-weight:600;">First Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['sunday_morning_start_time']; ?>" id="sunday_morning_start_time" autocomplete="off" name="sunday_morning_start_time" type="text" class="sunday_morning_off form-control validate timepicker sunday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['sunday_morning_end_time']; ?>" id="sunday_morning_end_time" autocomplete="off" name="sunday_morning_end_time" type="text" class="sunday_morning_off form-control validate timepicker sunday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['sunday_morning_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="sunday_morning_off" id="sunday_morning_off" class="form-control validate sunday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="<?php echo $data['sunday_evening_start_time']; ?>" id="sunday_evening_start_time" autocomplete="off" name="sunday_evening_start_time" type="text" class="sunday_evening_off form-control validate timepicker sunday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="<?php echo $data['sunday_evening_end_time']; ?>" id="sunday_evening_end_time" autocomplete="off" name="sunday_evening_end_time" type="text" class="sunday_evening_off form-control validate timepicker sunday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['sunday_evening_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="sunday_evening_off" id="sunday_evening_off" class="form-control validate sunday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" <?php if($data['sunday_off'] == '1'){echo 'checked="checked"';} ?> value="1"  type="checkbox" name="sunday_off" id="sunday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

           </div>

           

        <div class="row">

            	<div class="col-sm-6 col-xs-12">

		            <label>Doctor on holiday? </label>

                    <input style="left: 0;position: relative;opacity: 1;" value="1" id="on_holiday" <?php if($data['on_holiday'] == '1'){echo 'checked="checked"';} ?>  type="checkbox" class="form-control" name="on_holiday" />

                    <div class="col-sm-12 col-xs-12" id="on_holiday_dates" style="display:none;">

                    <label>Holiday dates</label>

                	<input type="text" class="on_holiday_daterange" autocomplete="off" id="on_holiday_daterange" name="on_holiday_daterange" value="" />

                    </div>

                </div>

                

            <div class="form-group col-sm-6 col-xs-12">

            <label for="statuss">Status (Required)</label>

            <br/>

            <input type="radio" name="status" value="1" <?php if($data['status'] == 1){echo 'checked="checked"';} ?> class="statuss"> Active 

            <input type="radio" name="status" value="0" <?php if($data['status'] == 0){echo 'checked="checked"';} ?> class="statuss"> Inactive 

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



<script>

$( function() {

	$( "#datepicker" ).datepicker();

} );

$('.timepicker').timepicker({

    timeFormat: 'H:mm',

    interval: 30,

    minTime: '6',

    maxTime: '23:59',

    startTime: '10:00',

    dynamic: true,

    dropdown: true,

    scrollbar: true

});



//Monday

<?php if($data['monday_off'] == '1'){ ?>

	monday_off();

<?php } ?>

$("#monday_off").change(function() {

	monday_off();

});

function monday_off(){

	if($("#monday_off").is(':checked')) {

		$("input.monday_off[type='text']").val('');

		$('#monday_morning_off').prop("checked",true);

		$('#monday_evening_off').prop("checked",true);

		$("input.monday_off[type='text']").prop('disabled', true);

		$("input.monday_off[type='text']").prop('readonly', true);

		$("input.monday_off[type='text']").prop('required',false);

	}else{

		$('#monday_morning_off').prop("checked",true);

		$('#monday_evening_off').prop("checked",true);

		$("input.monday_off[type='text']").prop('required',true);

		$("input.monday_off[type='text']").prop('disabled', false);

		$("input.monday_off[type='text']").prop('readonly', false);

	}

}



//Tuesday

<?php if($data['tuesday_off'] == '1'){ ?>

tuesday_off();

<?php } ?>

$("#tuesday_off").change(function() {

	tuesday_off();

});

function tuesday_off(){

	if($("#tuesday_off").is(':checked')) {

		$("input.tuesday_off[type='text']").val('');

		$('#tuesdy_morning_off').prop("checked",true);

		$('#tuesdy_evening_off').prop("checked",true);

		$("input.tuesday_off[type='text']").prop('disabled', true);

		$("input.tuesday_off[type='text']").prop('readonly', true);

		$("input.tuesday_off[type='text']").prop('required',false);

	}else{

		$('#tuesdy_morning_off').prop("checked",false);

		$('#tuesdy_evening_off').prop("checked",false);

		$("input.tuesday_off[type='text']").prop('required',true);

		$("input.tuesday_off[type='text']").prop('disabled', false);

		$("input.tuesday_off[type='text']").prop('readonly', false);

	}

}

//Wednesday

<?php if($data['wednesday_off'] == '1'){ ?>

wednesday_off();

<?php } ?>

$("#wednesday_off").change(function() {

	wednesday_off();

});

function wednesday_off(){

	if($("#wednesday_off").is(':checked')) {

		$("input.wednesday_off[type='text']").val('');

		$('#wednesday_morning_off').prop("checked",true);

		$('#wednesday_evening_off').prop("checked",true);

		$("input.wednesday_off[type='text']").prop('disabled', true);

		$("input.wednesday_off[type='text']").prop('readonly', true);

		$("input.wednesday_off[type='text']").prop('required',false);

	}else{

		$('#wednesday_morning_off').prop("checked",false);

		$('#wednesday_evening_off').prop("checked",false);

		$("input.wednesday_off[type='text']").prop('required',true);

		$("input.wednesday_off[type='text']").prop('disabled', false);

		$("input.wednesday_off[type='text']").prop('readonly', false);

	}

}

//thursday

<?php if($data['thursday_off'] == '1'){ ?>

thursday_off();

<?php } ?>

$("#thursday_off").change(function() {

	thursday_off();

});

function thursday_off(){

	if($("#thursday_off").is(':checked')) {

		$("input.thursday_off[type='text']").val('');

		$('#thursday_morning_off').prop("checked",true);

		$('#thursday_evening_off').prop("checked",true);

		$("input.thursday_off[type='text']").prop('disabled', true);

		$("input.thursday_off[type='text']").prop('readonly', true);

		$("input.thursday_off[type='text']").prop('required',false);

	}else{

		$('#thursday_morning_off').prop("checked",false);

		$('#thursday_evening_off').prop("checked",false);

		$("input.thursday_off[type='text']").prop('required',true);

		$("input.thursday_off[type='text']").prop('disabled', false);

		$("input.thursday_off[type='text']").prop('readonly', false);

	}

}



//Friday

<?php if($data['friday_off'] == '1'){ ?>

friday_off();

<?php } ?>

$("#friday_off").change(function() {

	friday_off();

});

function friday_off(){

	if($("#friday_off").is(':checked')) {

		$("input.friday_off[type='text']").val('');

		$('#friday_morning_off').prop("checked",true);

		$('#friday_evening_off').prop("checked",true);

		$("input.friday_off[type='text']").prop('disabled', true);

		$("input.friday_off[type='text']").prop('readonly', true);

		$("input.friday_off[type='text']").prop('required',false);

	}else{

		$('#friday_morning_off').prop("checked",false);

		$('#friday_evening_off').prop("checked",false);

		$("input.friday_off[type='text']").prop('required',true);

		$("input.friday_off[type='text']").prop('disabled', false);

		$("input.friday_off[type='text']").prop('readonly', false);

	}

}



//Saturday

<?php if($data['saturday_off'] == '1'){ ?>

saturday_off();

<?php } ?>

$("#saturday_off").change(function() {

	saturday_off();

});

function saturday_off(){

	if($("#saturday_off").is(':checked')) {

		$("input.saturday_off[type='text']").val('');

		$('#saturday_morning_off').prop("checked",true);

		$('#saturday_evening_off').prop("checked",true);

		$("input.saturday_off[type='text']").prop('disabled', true);

		$("input.saturday_off[type='text']").prop('readonly', true);

		$("input.saturday_off[type='text']").prop('required',false);

	}else{

		$('#saturday_morning_off').prop("checked",false);

		$('#saturday_evening_off').prop("checked",false);

		$("input.saturday_off[type='text']").prop('required',true);

		$("input.saturday_off[type='text']").prop('disabled', false);

		$("input.saturday_off[type='text']").prop('readonly', false);

	}

}

//Sunday

<?php if($data['sunday_off'] == '1'){ ?>

sunday_off();

<?php } ?>

$("#sunday_off").change(function() {

	sunday_off();

});

function sunday_off(){

	if($("#sunday_off").is(':checked')) {

		$("input.sunday_off[type='text']").val('');

		$('#sunday_morning_off').prop("checked",true);

		$('#sunday_evening_off').prop("checked",true);

		$("input.sunday_off[type='text']").prop('disabled', true);

		$("input.sunday_off[type='text']").prop('readonly', true);

		$("input.sunday_off[type='text']").prop('required',false);

	}else{

		$('#sunday_morning_off').prop("checked",false);

		$('#sunday_evening_off').prop("checked",false);

		$("input.sunday_off[type='text']").prop('required',true);

		$("input.sunday_off[type='text']").prop('disabled', false);

		$("input.sunday_off[type='text']").prop('readonly', false);

	}

}

<!-- Timly OFF--->

//Monday Monrning

<?php if($data['monday_morning_off'] == '1'){ ?>

monday_morning_off();

<?php } ?>

$("#monday_morning_off").change(function() {

	monday_morning_off();

});

function monday_morning_off(){

	if($("#monday_morning_off").is(':checked')) {

		$("input.monday_morning_off[type='text']").val('');

		$("input.monday_morning_off[type='text']").prop('disabled', true);

		$("input.monday_morning_off[type='text']").prop('readonly', true);

		$("input.monday_morning_off[type='text']").prop('required',false);

	}else{

		$("input.monday_morning_off[type='text']").prop('required',true);

		$("input.monday_morning_off[type='text']").prop('disabled', false);

		$("input.monday_morning_off[type='text']").prop('readonly', false);

	}

}



//Monday Evening

<?php if($data['monday_evening_off'] == '1'){ ?>

monday_evening_off();

<?php } ?>

$("#monday_evening_off").change(function() {

	monday_evening_off();

});

function monday_evening_off(){

	if($("#monday_evening_off").is(':checked')) {

		$("input.monday_evening_off[type='text']").val('');

		$("input.monday_evening_off[type='text']").prop('disabled', true);

		$("input.monday_evening_off[type='text']").prop('readonly', true);

		$("input.monday_evening_off[type='text']").prop('required',false);

	}else{

		$("input.monday_evening_off[type='text']").prop('required',true);

		$("input.monday_evening_off[type='text']").prop('disabled', false);

		$("input.monday_evening_off[type='text']").prop('readonly', false);

	}

}



//Tuesday Morning

<?php if($data['tuesdy_morning_off'] == '1'){ ?>

tuesdy_morning_off();

<?php } ?>

$("#tuesdy_morning_off").change(function() {

	tuesdy_morning_off();

});

function tuesdy_morning_off(){

	if($("#tuesdy_morning_off").is(':checked')) {

		$("input.tuesdy_morning_off[type='text']").val('');

		$("input.tuesdy_morning_off[type='text']").prop('disabled', true);

		$("input.tuesdy_morning_off[type='text']").prop('readonly', true);

		$("input.tuesdy_morning_off[type='text']").prop('required',false);

	}else{

		$("input.tuesdy_morning_off[type='text']").prop('required',true);

		$("input.tuesdy_morning_off[type='text']").prop('disabled', false);

		$("input.tuesdy_morning_off[type='text']").prop('readonly', false);

	}

}



//Tuesday Evening

<?php if($data['tuesdy_evening_off'] == '1'){ ?>

tuesdy_evening_off();

<?php } ?>

$("#tuesdy_evening_off").change(function() {

	tuesdy_evening_off();

});

function tuesdy_evening_off(){

	if($("#tuesdy_evening_off").is(':checked')) {

		$("input.tuesdy_evening_off[type='text']").val('');

		$("input.tuesdy_evening_off[type='text']").prop('disabled', true);

		$("input.tuesdy_evening_off[type='text']").prop('readonly', true);

		$("input.tuesdy_evening_off[type='text']").prop('required',false);

	}else{

		$("input.tuesdy_evening_off[type='text']").prop('required',true);

		$("input.tuesdy_evening_off[type='text']").prop('disabled', false);

		$("input.tuesdy_evening_off[type='text']").prop('readonly', false);

	}

}

//Wednesday Morning

<?php if($data['wednesday_morning_off'] == '1'){ ?>

wednesday_morning_off();

<?php } ?>

$("#wednesday_morning_off").change(function() {

	wednesday_morning_off();

});

function wednesday_morning_off(){

	if($("#wednesday_morning_off").is(':checked')) {

		$("input.wednesday_morning_off[type='text']").val('');

		$("input.wednesday_morning_off[type='text']").prop('disabled', true);

		$("input.wednesday_morning_off[type='text']").prop('readonly', true);

		$("input.wednesday_morning_off[type='text']").prop('required',false);

	}else{

		$("input.wednesday_morning_off[type='text']").prop('required',true);

		$("input.wednesday_morning_off[type='text']").prop('disabled', false);

		$("input.wednesday_morning_off[type='text']").prop('readonly', false);

	}

}



//Wednesday Evening

<?php if($data['wednesday_evening_off'] == '1'){ ?>

wednesday_evening_off();

<?php } ?>

$("#wednesday_evening_off").change(function() {

	wednesday_evening_off();

});

function wednesday_evening_off(){

	if($("#wednesday_evening_off").is(':checked')) {

		$("input.wednesday_evening_off[type='text']").val('');

		$("input.wednesday_evening_off[type='text']").prop('disabled', true);

		$("input.wednesday_evening_off[type='text']").prop('readonly', true);

		$("input.wednesday_evening_off[type='text']").prop('required',false);

	}else{

		$("input.wednesday_evening_off[type='text']").prop('required',true);

		$("input.wednesday_evening_off[type='text']").prop('disabled', false);

		$("input.wednesday_evening_off[type='text']").prop('readonly', false);

	}

}

//thursday morning

<?php if($data['thursday_morning_off'] == '1'){ ?>

thursday_morning_off();

<?php } ?>

$("#thursday_morning_off").change(function() {

	thursday_morning_off();

});

function thursday_morning_off(){

	if($("#thursday_morning_off").is(':checked')) {

		$("input.thursday_morning_off[type='text']").val('');

		$("input.thursday_morning_off[type='text']").prop('disabled', true);

		$("input.thursday_morning_off[type='text']").prop('readonly', true);

		$("input.thursday_morning_off[type='text']").prop('required',false);

	}else{

		$("input.thursday_morning_off[type='text']").prop('required',true);

		$("input.thursday_morning_off[type='text']").prop('disabled', false);

		$("input.thursday_morning_off[type='text']").prop('readonly', false);

	}

}



//thursday evening

<?php if($data['thursday_evening_off'] == '1'){ ?>

thursday_evening_off();

<?php } ?>

$("#thursday_evening_off").change(function() {

	thursday_evening_off();

});

function thursday_evening_off(){

	if($("#thursday_evening_off").is(':checked')) {

		$("input.thursday_evening_off[type='text']").val('');

		$("input.thursday_evening_off[type='text']").prop('disabled', true);

		$("input.thursday_evening_off[type='text']").prop('readonly', true);

		$("input.thursday_evening_off[type='text']").prop('required',false);

	}else{

		$("input.thursday_evening_off[type='text']").prop('required',true);

		$("input.thursday_evening_off[type='text']").prop('disabled', false);

		$("input.thursday_evening_off[type='text']").prop('readonly', false);

	}

}



//Friday morning

<?php if($data['friday_morning_off'] == '1'){ ?>

friday_morning_off();

<?php } ?>

$("#friday_morning_off").change(function() {

	friday_morning_off();

});

function friday_morning_off(){

	if($("#friday_morning_off").is(':checked')) {

		$("input.friday_morning_off[type='text']").val('');

		$("input.friday_morning_off[type='text']").prop('disabled', true);

		$("input.friday_morning_off[type='text']").prop('readonly', true);

		$("input.friday_morning_off[type='text']").prop('required',false);

	}else{

		$("input.friday_morning_off[type='text']").prop('required',true);

		$("input.friday_morning_off[type='text']").prop('disabled', false);

		$("input.friday_morning_off[type='text']").prop('readonly', false);

	}

}

//Friday evening

<?php if($data['friday_evening_off'] == '1'){ ?>

friday_evening_off();

<?php } ?>

$("#friday_evening_off").change(function() {

	friday_evening_off();

});

function friday_evening_off(){

	if($("#friday_evening_off").is(':checked')) {

		$("input.friday_evening_off[type='text']").val('');

		$("input.friday_evening_off[type='text']").prop('disabled', true);

		$("input.friday_evening_off[type='text']").prop('readonly', true);

		$("input.friday_evening_off[type='text']").prop('required',false);

	}else{

		$("input.friday_evening_off[type='text']").prop('required',true);

		$("input.friday_evening_off[type='text']").prop('disabled', false);

		$("input.friday_evening_off[type='text']").prop('readonly', false);

	}

}





//Saturday morning

<?php if($data['saturday_morning_off'] == '1'){ ?>

saturday_morning_off();

<?php } ?>

$("#saturday_morning_off").change(function() {

	saturday_morning_off();

});

function saturday_morning_off(){

	if($("#saturday_morning_off").is(':checked')) {

		$("input.saturday_morning_off[type='text']").val('');

		$("input.saturday_morning_off[type='text']").prop('disabled', true);

		$("input.saturday_morning_off[type='text']").prop('readonly', true);

		$("input.saturday_morning_off[type='text']").prop('required',false);

	}else{

		$("input.saturday_morning_off[type='text']").prop('required',true);

		$("input.saturday_morning_off[type='text']").prop('disabled', false);

		$("input.saturday_morning_off[type='text']").prop('readonly', false);

	}

}

//Saturday evening

<?php if($data['saturday_evening_off'] == '1'){ ?>

saturday_evening_off();

<?php } ?>

$("#saturday_evening_off").change(function() {

	saturday_evening_off();

});

function saturday_evening_off(){

	if($("#saturday_evening_off").is(':checked')) {

		$("input.saturday_evening_off[type='text']").val('');

		$("input.saturday_evening_off[type='text']").prop('disabled', true);

		$("input.saturday_evening_off[type='text']").prop('readonly', true);

		$("input.saturday_evening_off[type='text']").prop('required',false);

	}else{

		$("input.saturday_evening_off[type='text']").prop('required',true);

		$("input.saturday_evening_off[type='text']").prop('disabled', false);

		$("input.saturday_evening_off[type='text']").prop('readonly', false);

	}

}

//Sunday morning

<?php if($data['sunday_morning_off'] == '1'){ ?>

sunday_morning_off();

<?php } ?>

$("#sunday_morning_off").change(function() {

	sunday_morning_off();

});

function sunday_morning_off(){

	if($("#sunday_morning_off").is(':checked')) {

		$("input.sunday_morning_off[type='text']").val('');

		$("input.sunday_morning_off[type='text']").prop('disabled', true);

		$("input.sunday_morning_off[type='text']").prop('readonly', true);

		$("input.sunday_morning_off[type='text']").prop('required',false);

	}else{

		$("input.sunday_morning_off[type='text']").prop('required',true);

		$("input.sunday_morning_off[type='text']").prop('disabled', false);

		$("input.sunday_morning_off[type='text']").prop('readonly', false);

	}

}

//Sunday evening

<?php if($data['sunday_evening_off'] == '1'){ ?>

sunday_evening_off();

<?php } ?>

$("#sunday_evening_off").change(function() {

	sunday_evening_off();

});

function sunday_evening_off(){

	if($("#sunday_evening_off").is(':checked')) {

		$("input.sunday_evening_off[type='text']").val('');

		$("input.sunday_evening_off[type='text']").prop('disabled', true);

		$("input.sunday_evening_off[type='text']").prop('readonly', true);

		$("input.sunday_evening_off[type='text']").prop('required',false);

	}else{

		$("input.sunday_evening_off[type='text']").prop('required',true);

		$("input.sunday_evening_off[type='text']").prop('disabled', false);

		$("input.sunday_evening_off[type='text']").prop('readonly', false);

	}

}

<!--- Timly OFF --->



check_on_holiday();

$("#on_holiday").change(function() {

	check_on_holiday();

});

function check_on_holiday(){

	$('#on_holiday_dates').hide();

	$('#on_holiday_daterange').val('');

	$("input#on_holiday_daterange").prop('required',false);

	if($("#on_holiday").is(':checked')) {

		$("input#on_holiday_daterange").prop('required',true);

        $('#on_holiday_dates').show();

    }

}



<?php $on_holiday_daterange = ""; if($data['on_holiday'] == 1){ $on_holiday_daterange = $data['on_holiday_daterange']; } ?>

$(function() {

	  $('input#on_holiday_daterange').daterangepicker({

		opens: 'top',

		autoUpdateInput: false,

	  }, function(start, end, label) {

			//console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

			var data = {start:start.format('YYYY-MM-DD'),end:end.format('YYYY-MM-DD')};

	  });

});

$('input#on_holiday_daterange').val('<?php echo $on_holiday_daterange; ?>');

$('input#on_holiday_daterange').on('apply.daterangepicker', function(ev, picker) {

  $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

});

$('input#on_holiday_daterange').on('cancel.daterangepicker', function(ev, picker) {

	$(this).val('');

	$(this).data('daterangepicker').setStartDate(moment());

	$(this).data('daterangepicker').setEndDate(moment());

});

</script>

<style>
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999px;
    opacity: 1!important;
}
</style> 