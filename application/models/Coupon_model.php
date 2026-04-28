<?php
class Coupon_model extends CI_Model {
    public function validate($code, $service_type, $bill_amount) {
        // 1. Search for the coupon
        $this->db->where('coupon_code', strtoupper($code));
        $this->db->where('status', 1);
        $this->db->where('expiry_date >=', date('Y-m-d'));
        $query = $this->db->get('hms_coupons');

        if ($query->num_rows() == 0) {
            return array('status' => 'error', 'message' => 'Invalid or Expired Coupon');
        }

        $coupon = $query->row();

        // 2. Check if it matches the service (medicine/lab etc)
        if ($coupon->service_type != 'all' && $coupon->service_type != $service_type) {
            return array('status' => 'error', 'message' => 'Not valid for ' . ucfirst($service_type));
        }

        // 3. Check Minimum Bill
        if ($bill_amount < $coupon->min_amount) {
            return array('status' => 'error', 'message' => 'Min bill must be ₹' . $coupon->min_amount);
        }

        // 4. Calculate Discount
        $discount = ($coupon->discount_type == 'fixed') 
                    ? $coupon->discount_value 
                    : ($bill_amount * $coupon->discount_value) / 100;

        return array(
            'status' => 'success',
            'discount' => $discount,
            'final_amount' => $bill_amount - $discount,
            'message' => 'Coupon Applied Successfully!'
        );
    }
    
}