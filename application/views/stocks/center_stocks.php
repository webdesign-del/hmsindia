  <?php  $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="card">
        <div class="card-action"><h3> Stocks Lists </h3></div>
         <div class="clearfix"></div>
      <form action="<?php echo base_url().'stocks/center_stocks'; ?>" method="get">
		   <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Expiry Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Expiry End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Generic Name</label>
                <input type="text" class="form-control" id="generic_name" name="generic_name" value="<?php echo $generic_name;?>" />
            </div>
			 <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Medicine Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo $item_name;?>" />
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'stocks/center_stocks'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
			<div class="col-sm-2" style="margin-top: 10px;">
            	<a href="<?php echo base_url('stocks/Medicine-Patients'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Billings</button>
               </a>
            </div>
            </form>
        <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover stock_list" id="centre_stock_list">
              <thead>
                <tr>
                  <th>Generic Name</th>
				  <th>Brand</th>
                  <th>Item ID</th>
                  <th>Item name</th>
                  <th>Batch</th>
                  <th>Category</th>
                  <th>Quantity (units)</th>
                  <th>MRP</th>
                  <th>Expiry</th>
                  <th>Status</th>
				  <!--<th>Action</th>
				   <th></th>-->
                  <th>GST Rate</th>
                </tr>
              </thead>
              <tbody id="table_content">
              <?php $count=1; foreach($investigate_result as $vl){ ?>
                <tr class="odd gradeX">
				<td><?php echo $vl['generic_name']?></td>
                  <td><?php echo $all_method->get_brand_name($vl['brand_name']);?></td>
                  <td><a href="<?php echo base_url(); ?>stocks/cdetail/<?php echo $vl['item_number']?>"><?php echo $vl['item_number']?></a></td>
                  <td><?php echo $vl['item_name']?></td>
                  <td><?php echo $vl['batch_number']?></td>
                  <td><?php $category_name = $all_method->get_category_name($vl['category']); echo $category_name; ?></td>
                  <td><?php echo $vl['quantity']?></td>
                  <td><?php echo $vl['mrp']; ?></td>
                  <?php
// Assuming $vl['expiry'] is the expiry date in 'Y-m-d' format
$expiryDate = new DateTime($vl['expiry']);
$today = new DateTime();
$twoMonthsFromNow = (clone $today)->modify('+2 months');

$color = '';
if ($expiryDate <= $twoMonthsFromNow) {
    $color = 'style="color: red;font-weight:800"'; // Set the color to red if within 2 months
}
?>

<td <?php echo $color; ?>><?php echo $vl['expiry']; ?></td>
                  <td><?php if($vl['status'] == '1'){echo "Active";}else{echo "Inactive"; } ?></td>
				  <!--<td><a href="<?php echo base_url();?>stocks/return_medicine_item?ID=<?php echo $vl['ID']?>" class="edit">Return</a></td>
                  <td><a href="<?php echo base_url();?>stocks/center_medicine_order?item_number=<?php echo $vl['item_number']?>" class="edit">Order</a></td>
             <?php /* if($vl['status'] == '1'){ ?>
				          <td><!--<a href="<?php echo base_url();?>stocks/edit?item_number=<?php echo $vl['item_number']?>" class="edit"><i class="material-icons">edit</i></a> <a href="<?php echo base_url();?>stocks/delete?item_number=<?php echo $vl['item_number']?>" class="delete"><i class="material-icons">delete</i></a>--> 
                  <a href="<?php echo base_url();?>orders/purchase_order/<?php echo $vl['item_number']?>" class="btn btn-large">Purchase order</a>
                  <a href="<?php echo base_url();?>stocks/audit_stocks?ID=<?php echo $vl['ID']?>" class="btn btn-large">Audit Stocks</a>
                </td><?php } */ ?> -->
                  <td><?php echo $vl['gstrate']?></td>
                </tr>
              <?php $count++;} ?>
			   <tr>
                <td colspan="9">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!--End Advanced Tables -->
    </div>
  
  <script>
      $( function() {
        $( ".particular_date_filter" ).datepicker({
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(dateStr) {
            $('#loader_div').hide();				
            var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
            var data = {appointment_date:startDate, type:'particular_date_filter'};
          }
        });
    });
</script>
<style >
.custom-pagination{
  padding:8px;
}
.custom-pagination a{
  padding:10px;
  text-decoration: none;
}
.form-control{
  height: 30px!important;
  border: 1px solid #9e9e9e!important;
}
.form-control#billing_at{
  height: 40px!important;
  border: 1px solid #9e9e9e!important;
}
</style>