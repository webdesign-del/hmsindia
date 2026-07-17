

<form class="col-sm-12 col-xs-12" method="post" action="" >

  <input type="hidden" name="action" value="add_doctor" />

  <div class="row">

    <div class="col-sm-12 col-xs-12 panel panel-piluku">

      <div class="panel-heading">

        <h3 class="heading">Add Doctor</h3>

      </div>

      <div class="panel-body profile-edit">

        <p>

        <div class="row">

          <div class="form-group col-sm-6 col-xs-12">

            <label for="item_name">Doctor Name (Dr.) (Required)</label>

            <input value="" placeholder="Doctor name" id="name" name="name" type="text" class="form-control validate" required>

          </div>

          

          	<div class="form-group col-sm-6 col-xs-12">

                <label for="item_name">Fees (Required)</label>

                <input value="" placeholder="Fees" id="fees" name="fees" type="text" class="form-control validate" required>

            </div>

		</div>        
		
		<div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Doctor email (Required)</label>
            <input value="" placeholder="Doctor email" id="email" name="email" type="text" class="form-control validate" required>
		  </div>

		  <div class="form-group col-sm-6 col-xs-12">
            <label for="item_name">Is Primary Doctor?</label>
            <input value="1" style="left: 0;position: relative;opacity: 1;" id="is_primary" name="is_primary" type="checkbox" class="form-control validate">
		  </div>
		  
        </div>        

        

        <div class="row">

        	<div class="form-group col-sm-6 col-xs-12">

                <label for="item_name">USD fees (Required)</label>

                <input value="" placeholder="Fees" id="usd_fees" name="usd_fees" type="text" class="form-control validate" required>

            </div>

            

           <div class="form-group col-sm-6 col-xs-12 role">

                <label for="statuss">Doctor Center (Required)</label>

                <select name="center_id" required>

                    <option value="">Select Center</option>

                    <?php foreach($centers as $ky => $vl){?>

                      <option value="<?php echo $vl['center_number']?>"><?php echo $vl['center_name']?></option>

                    <?php } ?>

                </select>

            </div>          

        </div>

        

        <div class="row">

          <div class="form-group col-sm-6 col-xs-12">

            <label for="item_name">Username (Required)</label>

            <input value="" placeholder="" id="username" name="username" type="text" class="form-control validate" required>

          </div>

          

          	<div class="form-group col-sm-6 col-xs-12">

                <label for="item_name">Password (Required)</label>

                <input value="" placeholder="" id="password" name="password" type="text" class="form-control validate" required>

            </div>

        </div>
		
		<div class="row">
		
			<div class="form-group col-sm-12 col-xs-12">

				<label for="item_name">Doctor Degree Details (Required)</label>

				<input value="" placeholder="" id="doctor_details" name="doctor_details" type="text" class="form-control validate" required>

			</div>
		  
		</div> 

		<!-- यहाँ से परमिशन का डायनामिक कोड शुरू होता है -->
<div class="row">
  <div class="form-group col-sm-12 col-xs-12">
    <label style="font-weight: bold; color: #333; display: block; margin-bottom: 10px;">
      Allowed Centers for Switching (Select Multiples for Permission)
    </label>
    
    <div class="panel panel-default" style="border: 1px solid #ddd; padding: 15px; border-radius: 4px; background: #fbfbfb;">
      <div class="row">
        <?php if(!empty($centers)) { 
          foreach($centers as $ky => $vl) { ?>
            <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 10px;">
              <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <!-- 'allowed_centers[]' एक एरे बनाएगा जिससे मल्टीपल वैल्यूज बैकएंड में जाएंगी -->
                <input type="checkbox" name="allowed_centers[]" value="<?php echo $vl['center_number']; ?>" style="margin: 0; width: 18px; height: 18px;"> 
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
<!-- परमिशन का डायनामिक कोड समाप्त -->

        

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

    	                        <input value="" id="monday_morning_start_time" autocomplete="off" name="monday_morning_start_time" type="text" class="form-control monday_morning_off validate timepicker monday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="monday_morning_end_time" autocomplete="off" name="monday_morning_end_time" type="text" class="form-control monday_morning_off validate timepicker monday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="monday_morning_off" id="monday_morning_off" class="form-control validate monday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="monday_evening_start_time" autocomplete="off" name="monday_evening_start_time" type="text" class="monday_evening_off form-control validate timepicker monday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="monday_evening_end_time" autocomplete="off" name="monday_evening_end_time" type="text" class="monday_evening_off form-control validate timepicker monday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="monday_evening_off" id="monday_evening_off" class="form-control validate monday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="monday_off" id="monday_off" class="form-control validate" />

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

    	                        <input value="" id="tuesday_morning_start_time" autocomplete="off" name="tuesday_morning_start_time" type="text" class="tuesdy_morning_off form-control validate timepicker tuesday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="tuesday_morning_end_time" autocomplete="off" name="tuesday_morning_end_time" type="text" class="tuesdy_morning_off form-control validate timepicker tuesday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="tuesdy_morning_off" id="tuesdy_morning_off" class="form-control validate tuesday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="tuesday_evening_start_time" autocomplete="off" name="tuesday_evening_start_time" type="text" class="tuesdy_evening_off form-control validate timepicker tuesday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="tuesday_evening_end_time" autocomplete="off" name="tuesday_evening_end_time" type="text" class="tuesdy_evening_off form-control validate timepicker tuesday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="tuesdy_evening_off" id="tuesdy_evening_off" class="form-control validate tuesday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="tuesday_off" id="tuesday_off" class="form-control validate" />

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

    	                        <input value="" id="wednesday_morning_start_time" autocomplete="off" name="wednesday_morning_start_time" type="text" class="wednesday_morning_off form-control validate timepicker wednesday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="wednesday_morning_end_time" autocomplete="off" name="wednesday_morning_end_time" type="text" class="wednesday_morning_off form-control validate timepicker wednesday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1" type="checkbox" name="wednesday_morning_off" id="wednesday_morning_off" class="form-control validate wednesday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="wednesday_evening_start_time" autocomplete="off" name="wednesday_evening_start_time" type="text" class="wednesday_evening_off form-control validate timepicker wednesday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="wednesday_evening_end_time" autocomplete="off" name="wednesday_evening_end_time" type="text" class="wednesday_evening_off form-control validate timepicker wednesday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="wednesday_evening_off" id="wednesday_evening_off" class="form-control validate wednesday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="wednesday_off" id="wednesday_off" class="form-control validate" />

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

    	                        <input value="" id="thursday_morning_start_time" autocomplete="off" name="thursday_morning_start_time" type="text" class="thursday_morning_off form-control validate timepicker thursday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="thursday_morning_end_time" autocomplete="off" name="thursday_morning_end_time" type="text" class="thursday_morning_off form-control validate timepicker thursday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="thursday_morning_off" id="thursday_morning_off" class="form-control validate thursday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="thursday_evening_start_time" autocomplete="off" name="thursday_evening_start_time" type="text" class="thursday_evening_off form-control validate timepicker thursday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="thursday_evening_end_time" autocomplete="off" name="thursday_evening_end_time" type="text" class="thursday_evening_off form-control validate timepicker thursday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="thursday_evening_off" id="thursday_evening_off" class="form-control validate thursday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="thursday_off" id="thursday_off" class="form-control validate" />

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

    	                        <input value="" id="friday_morning_start_time" autocomplete="off" name="friday_morning_start_time" type="text" class="friday_morning_off form-control validate timepicker friday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="friday_morning_end_time" autocomplete="off" name="friday_morning_end_time" type="text" class="friday_morning_off form-control validate timepicker friday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="friday_morning_off" id="friday_morning_off" class="form-control validate friday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="friday_evening_start_time" autocomplete="off" name="friday_evening_start_time" type="text" class="friday_evening_off form-control validate timepicker friday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="friday_evening_end_time" autocomplete="off" name="friday_evening_end_time" type="text" class="friday_evening_off form-control validate timepicker friday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="friday_evening_off" id="friday_evening_off" class="form-control validate friday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="friday_off" id="friday_off" class="form-control validate" />

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

    	                        <input value="" id="saturday_morning_start_time" autocomplete="off" name="saturday_morning_start_time" type="text" class="saturday_morning_off form-control validate timepicker saturday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="saturday_morning_end_time" autocomplete="off" name="saturday_morning_end_time" type="text" class="saturday_morning_off form-control validate timepicker saturday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="saturday_morning_off" id="saturday_morning_off" class="form-control validate saturday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="saturday_evening_start_time" autocomplete="off" name="saturday_evening_start_time" type="text" class="saturday_evening_off form-control validate timepicker saturday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="saturday_evening_end_time" autocomplete="off" name="saturday_evening_end_time" type="text" class="saturday_evening_off form-control validate timepicker saturday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="saturday_evening_off" id="saturday_evening_off" class="form-control validate saturday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="saturday_off" id="saturday_off" class="form-control validate" />

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

    	                        <input value="" id="sunday_morning_start_time" autocomplete="off" name="sunday_morning_start_time" type="text" class="sunday_morning_off form-control validate timepicker sunday_off" required>

                                

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="sunday_morning_end_time" autocomplete="off" name="sunday_morning_end_time" type="text" class="sunday_morning_off form-control validate timepicker sunday_off" required>

                            </div>

                            <label>First Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="sunday_morning_off" id="sunday_morning_off" class="form-control validate sunday_off" />

                        </div>

                       

                        <div class="col-sm-4 col-xs-12">

	                       	<label style="text-align:center; width:100%;font-weight:600;">Second Half</label><div class="clearfix"></div>

                        	<div class="col-sm-6 col-xs-12">

	                            <label>Start Time</label>

    	                        <input value="" id="sunday_evening_start_time" autocomplete="off" name="sunday_evening_start_time" type="text" class="sunday_evening_off form-control validate timepicker sunday_off" required>

                            </div>

                            <div class="col-sm-6 col-xs-12">

	                            <label>End Time</label>

    	                        <input value="" id="sunday_evening_end_time" autocomplete="off" name="sunday_evening_end_time" type="text" class="sunday_evening_off form-control validate timepicker sunday_off" required>

                            </div>

                            <label>Second Half Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="sunday_evening_off" id="sunday_evening_off" class="form-control validate sunday_off" />

                        </div>

                        <div class="col-sm-4 col-xs-12">

                            <label>Off ? </label>

		                    <input style="left: 0;position: relative;opacity: 1;" value="1"  type="checkbox" name="sunday_off" id="sunday_off" class="form-control validate" />

                        </div>

                    </div>

            </div>

            <hr/>

           </div>

          <div class="row">

            	<div class="col-sm-6 col-xs-12">

		            <label>Doctor on holiday? </label>

                    <input style="left: 0;position: relative;opacity: 1;" value="1" id="on_holiday"  type="checkbox" class="form-control" name="on_holiday" />

                    <div class="col-sm-12 col-xs-12" id="on_holiday_dates" style="display:none;">

                    <label>Holiday dates</label>

                	<input type="text" class="on_holiday_daterange" autocomplete="off" id="on_holiday_daterange" name="on_holiday_daterange" value="" />

                    </div>

                </div>

                

            <div class="form-group col-sm-6 col-xs-12">

                <label for="statuss">Status (Required)</label>

                <br/>

                <input type="radio" name="status" value="1" class="statuss" checked> Active 

                <input type="radio" name="status" value="0" class="statuss"> Inactive 

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

$("#monday_off").change(function() {

	$("input.monday_off[type='text']").val('');

	$("input.monday_off[type='text']").prop('disabled', false);

	$("input.monday_off[type='text']").prop('required',true);

	$("input.monday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.monday_off[type='text']").prop('disabled', true);

		$("input.monday_off[type='text']").prop('readonly', true);

		$("input.monday_off[type='text']").prop('required',false);

	}

});

//Tuesday

$("#tuesday_off").change(function() {

	$("input.tuesday_off[type='text']").val('');

	$("input.tuesday_off[type='text']").prop('disabled', false);

	$("input.tuesday_off[type='text']").prop('required',true);

	$("input.tuesday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.tuesday_off[type='text']").prop('disabled', true);

		$("input.tuesday_off[type='text']").prop('readonly', true);

		$("input.tuesday_off[type='text']").prop('required',false);

	}

});

//Wednesday

$("#wednesday_off").change(function() {

	$("input.wednesday_off[type='text']").val('');

	$("input.wednesday_off[type='text']").prop('disabled', false);

	$("input.wednesday_off[type='text']").prop('required',true);

	$("input.wednesday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.wednesday_off[type='text']").prop('disabled', true);

		$("input.wednesday_off[type='text']").prop('readonly', true);

		$("input.wednesday_off[type='text']").prop('required',false);

	}

});

//thursday

$("#thursday_off").change(function() {

	$("input.thursday_off[type='text']").val('');

	$("input.thursday_off[type='text']").prop('disabled', false);

	$("input.thursday_off[type='text']").prop('required',true);

	$("input.thursday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.thursday_off[type='text']").prop('disabled', true);

		$("input.thursday_off[type='text']").prop('readonly', true);

		$("input.thursday_off[type='text']").prop('required',false);

	}

});

//Friday

$("#friday_off").change(function() {

	$("input.friday_off[type='text']").val('');

	$("input.friday_off[type='text']").prop('disabled', false);

	$("input.friday_off[type='text']").prop('required',true);

	$("input.friday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.friday_off[type='text']").prop('disabled', true);

		$("input.friday_off[type='text']").prop('readonly', true);

		$("input.friday_off[type='text']").prop('required',false);

	}

});

//Saturday

$("#saturday_off").change(function() {

	$("input.saturday_off[type='text']").val('');

	$("input.saturday_off[type='text']").prop('disabled', false);

	$("input.saturday_off[type='text']").prop('required',true);

	$("input.saturday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.saturday_off[type='text']").prop('disabled', true);

		$("input.saturday_off[type='text']").prop('readonly', true);

		$("input.saturday_off[type='text']").prop('required',false);

	}

});

//Sunday

$("#sunday_off").change(function() {

	$("input.sunday_off[type='text']").val('');

	$("input.sunday_off[type='text']").prop('disabled', false);

	$("input.sunday_off[type='text']").prop('required',true);

	$("input.sunday_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.sunday_off[type='text']").prop('disabled', true);

		$("input.sunday_off[type='text']").prop('readonly', true);

		$("input.sunday_off[type='text']").prop('required',false);

	}

});

<!-- Timly OFF--->

//Monday Monrning

$("#monday_morning_off").change(function() {

	$("input.monday_morning_off[type='text']").val('');

	$("input.monday_morning_off[type='text']").prop('disabled', false);

	$("input.monday_morning_off[type='text']").prop('required',true);

	$("input.monday_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.monday_morning_off[type='text']").prop('disabled', true);

		$("input.monday_morning_off[type='text']").prop('readonly', true);

		$("input.monday_morning_off[type='text']").prop('required',false);

	}

});

//Monday Evening

$("#monday_evening_off").change(function() {

	$("input.monday_evening_off[type='text']").val('');

	$("input.monday_evening_off[type='text']").prop('disabled', false);

	$("input.monday_evening_off[type='text']").prop('required',true);

	$("input.monday_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.monday_evening_off[type='text']").prop('disabled', true);

		$("input.monday_evening_off[type='text']").prop('readonly', true);

		$("input.monday_evening_off[type='text']").prop('required',false);

	}

});

//Tuesday Morning

$("#tuesdy_morning_off").change(function() {

	$("input.tuesdy_morning_off[type='text']").val('');

	$("input.tuesdy_morning_off[type='text']").prop('disabled', false);

	$("input.tuesdy_morning_off[type='text']").prop('required',true);

	$("input.tuesdy_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.tuesdy_morning_off[type='text']").prop('disabled', true);

		$("input.tuesdy_morning_off[type='text']").prop('readonly', true);

		$("input.tuesdy_morning_off[type='text']").prop('required',false);

	}

});

//Tuesday Evening

$("#tuesdy_evening_off").change(function() {

	$("input.tuesdy_evening_off[type='text']").val('');

	$("input.tuesdy_evening_off[type='text']").prop('disabled', false);

	$("input.tuesdy_evening_off[type='text']").prop('required',true);

	$("input.tuesdy_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.tuesdy_evening_off[type='text']").prop('disabled', true);

		$("input.tuesdy_evening_off[type='text']").prop('readonly', true);

		$("input.tuesdy_evening_off[type='text']").prop('required',false);

	}

});

//Wednesday Morning

$("#wednesday_morning_off").change(function() {

	$("input.wednesday_morning_off[type='text']").val('');

	$("input.wednesday_morning_off[type='text']").prop('disabled', false);

	$("input.wednesday_morning_off[type='text']").prop('required',true);

	$("input.wednesday_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.wednesday_morning_off[type='text']").prop('disabled', true);

		$("input.wednesday_morning_off[type='text']").prop('readonly', true);

		$("input.wednesday_morning_off[type='text']").prop('required',false);

	}

});

//Wednesday Evening

$("#wednesday_evening_off").change(function() {

	$("input.wednesday_evening_off[type='text']").val('');

	$("input.wednesday_evening_off[type='text']").prop('disabled', false);

	$("input.wednesday_evening_off[type='text']").prop('required',true);

	$("input.wednesday_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.wednesday_evening_off[type='text']").prop('disabled', true);

		$("input.wednesday_evening_off[type='text']").prop('readonly', true);

		$("input.wednesday_evening_off[type='text']").prop('required',false);

	}

});

//thursday morning

$("#thursday_morning_off").change(function() {

	$("input.thursday_morning_off[type='text']").val('');

	$("input.thursday_morning_off[type='text']").prop('disabled', false);

	$("input.thursday_morning_off[type='text']").prop('required',true);

	$("input.thursday_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.thursday_morning_off[type='text']").prop('disabled', true);

		$("input.thursday_morning_off[type='text']").prop('readonly', true);

		$("input.thursday_morning_off[type='text']").prop('required',false);

	}

});

//thursday evening

$("#thursday_evening_off").change(function() {

	$("input.thursday_evening_off[type='text']").val('');

	$("input.thursday_evening_off[type='text']").prop('disabled', false);

	$("input.thursday_evening_off[type='text']").prop('required',true);

	$("input.thursday_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.thursday_evening_off[type='text']").prop('disabled', true);

		$("input.thursday_evening_off[type='text']").prop('readonly', true);

		$("input.thursday_evening_off[type='text']").prop('required',false);

	}

});

//Friday morning

$("#friday_morning_off").change(function() {

	$("input.friday_morning_off[type='text']").val('');

	$("input.friday_morning_off[type='text']").prop('disabled', false);

	$("input.friday_morning_off[type='text']").prop('required',true);

	$("input.friday_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.friday_morning_off[type='text']").prop('disabled', true);

		$("input.friday_morning_off[type='text']").prop('readonly', true);

		$("input.friday_morning_off[type='text']").prop('required',false);

	}

});

//Friday evening

$("#friday_evening_off").change(function() {

	$("input.friday_evening_off[type='text']").val('');

	$("input.friday_evening_off[type='text']").prop('disabled', false);

	$("input.friday_evening_off[type='text']").prop('required',true);

	$("input.friday_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.friday_evening_off[type='text']").prop('disabled', true);

		$("input.friday_evening_off[type='text']").prop('readonly', true);

		$("input.friday_evening_off[type='text']").prop('required',false);

	}

});

//Saturday morning

$("#saturday_morning_off").change(function() {

	$("input.saturday_morning_off[type='text']").val('');

	$("input.saturday_morning_off[type='text']").prop('disabled', false);

	$("input.saturday_morning_off[type='text']").prop('required',true);

	$("input.saturday_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.saturday_morning_off[type='text']").prop('disabled', true);

		$("input.saturday_morning_off[type='text']").prop('readonly', true);

		$("input.saturday_morning_off[type='text']").prop('required',false);

	}

});

//Saturday evening

$("#saturday_evening_off").change(function() {

	$("input.saturday_evening_off[type='text']").val('');

	$("input.saturday_evening_off[type='text']").prop('disabled', false);

	$("input.saturday_evening_off[type='text']").prop('required',true);

	$("input.saturday_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.saturday_evening_off[type='text']").prop('disabled', true);

		$("input.saturday_evening_off[type='text']").prop('readonly', true);

		$("input.saturday_evening_off[type='text']").prop('required',false);

	}

});

//Sunday morning

$("#sunday_morning_off").change(function() {

	$("input.sunday_morning_off[type='text']").val('');

	$("input.sunday_morning_off[type='text']").prop('disabled', false);

	$("input.sunday_morning_off[type='text']").prop('required',true);

	$("input.sunday_morning_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.sunday_morning_off[type='text']").prop('disabled', true);

		$("input.sunday_morning_off[type='text']").prop('readonly', true);

		$("input.sunday_morning_off[type='text']").prop('required',false);

	}

});

//Sunday evening

$("#sunday_evening_off").change(function() {

	$("input.sunday_evening_off[type='text']").val('');

	$("input.sunday_evening_off[type='text']").prop('disabled', false);

	$("input.sunday_evening_off[type='text']").prop('required',true);

	$("input.sunday_evening_off[type='text']").prop('readonly', false);

	if(this.checked) {

		$("input.sunday_evening_off[type='text']").prop('disabled', true);

		$("input.sunday_evening_off[type='text']").prop('readonly', true);

		$("input.sunday_evening_off[type='text']").prop('required',false);

	}

});

<!--- Timly OFF --->



$("#on_holiday").change(function() {

    $('#on_holiday_dates').hide();

	$('#on_holiday_daterange').val('');

	$("input#on_holiday_daterange").prop('required',false);

	if(this.checked) {

		$("input#on_holiday_daterange").prop('required',true);

        $('#on_holiday_dates').show();

    }

});

$(function() {	  

	  $('input#on_holiday_daterange').daterangepicker({

		opens: 'left',

		autoUpdateInput: false,

		minDate:new Date()

	  }, function(start, end, label) {

			//console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

			var data = {start:start.format('YYYY-MM-DD'),end:end.format('YYYY-MM-DD')};

	  });

});

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