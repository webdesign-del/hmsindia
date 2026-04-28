<div class="input-group mb-3">
    <input type="text" id="coupon_inp" class="form-control" placeholder="Enter Promo Code">
    <div class="input-group-append">
        <button id="apply_coupon_btn" class="btn btn-outline-primary" type="button">Apply</button>
    </div>
</div>
<small id="coupon_response"></small>

<script>
$('#apply_coupon_btn').click(function(){
    var code = $('#coupon_inp').val();
    var current_total = $('#total_bill_field').val(); // Ensure this ID matches your total field
    
    $.ajax({
        url: "<?php echo base_url('coupon/apply'); ?>",
        type: "POST",
        data: {
            code: code,
            amount: current_total,
            type: 'medicine' // Change this to 'investigation' or 'consultant' based on the page
        },
        dataType: "JSON",
        success: function(res) {
            if(res.status == 'success') {
                $('#coupon_response').html(res.msg).css('color', 'green');
                $('#discount_field').val(res.discount);
                $('#final_total_field').val(res.new_total);
            } else {
                $('#coupon_response').html(res.msg).css('color', 'red');
            }
        }
    });
});
</script>