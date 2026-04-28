<!DOCTYPE html>
<html>
<head>
    <title>Add Multi-Type Coupon</title>
  
</head>
<body>
<div class="container">
    <h2>Create New Coupon</h2>
    <form method="post" action="<?php echo base_url('admin/coupon/add'); ?>">
        <div class="form-group">
            <label>Coupon Code:</label>
            <input type="text" name="coupon_code" class="form-control" placeholder="e.g. HEALTH50" required>
        </div>
        
        <div class="form-group">
            <label>Service Type (Multi-Type):</label>
            <select name="service_type" class="form-control">
                <option value="medicine">Medicine (Pharmacy)</option>
                <option value="consultant">Consultant (OPD)</option>
                <option value="investigation">Investigation (Lab)</option>
                <option value="registration">Registration Fee</option>
                <option value="all">Global (All Services)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Discount Type:</label>
            <select name="discount_type" class="form-control">
                <option value="fixed">Fixed Amount (₹)</option>
                <option value="percentage">Percentage (%)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Discount Value:</label>
            <input type="number" name="discount_value" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Minimum Bill Amount:</label>
            <input type="number" name="min_amount" class="form-control" value="0">
        </div>

        <div class="form-group">
            <label>Expiry Date:</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save Coupon</button>
    </form>
</div>
</body>
</html>