<?php 

function get_final_wallet_balance($patient_id) {
    $ci =& get_instance();
    $db_prefix = $ci->config->item('db_prefix');
    
    $inflow_arr = [];
    $total_out = 0; // Initialize total_out as 0

    // --- STEP 1: MONEY COMING IN (Cancelled/Adjusted/Refunds) ---
    
    $med_in = $ci->db->query("SELECT payment_done FROM {$db_prefix}patient_medicine WHERE patient_id='$patient_id' AND status='cancel'")->result_array();
    foreach($med_in as $v) { $inflow_arr[] = $v['payment_done']; }

    $med_ret = $ci->db->query("SELECT final_return_amount FROM medicine_returns WHERE patient_id='$patient_id' AND status='APPROVED'")->result_array();
    foreach($med_ret as $v) { $inflow_arr[] = $v['final_return_amount']; }

    $proc_in = $ci->db->query("SELECT payment_done FROM {$db_prefix}patient_procedure WHERE patient_id='$patient_id' AND status='cancel'")->result_array();
    foreach($proc_in as $v) { $inflow_arr[] = $v['payment_done']; }

    $inv_in = $ci->db->query("SELECT payment_done FROM {$db_prefix}patient_investigations WHERE patient_id='$patient_id' AND status='cancel'")->result_array();
    foreach($inv_in as $v) { $inflow_arr[] = $v['payment_done']; }

    $cons_in = $ci->db->query("SELECT payment_done FROM {$db_prefix}consultation WHERE patient_id='$patient_id' AND status='adjust '")->result_array();
    foreach($cons_in as $v) { $inflow_arr[] = $v['payment_done']; }

    $reg_in = $ci->db->query("SELECT payment_done FROM {$db_prefix}registation WHERE patient_id='$patient_id' AND status='adjust'")->result_array();
    foreach($reg_in as $v) { $inflow_arr[] = $v['payment_done']; }

    $done_in = $ci->db->query("SELECT SUM(payment_done) as total FROM {$db_prefix}patient_payments WHERE patient_id='$patient_id' AND status='3'")->row();
    if($done_in) { $inflow_arr[] = $done_in->total; }

    $ref_in = $ci->db->query("SELECT SUM(consultation_fee + usg_scan_charge + consumable_charges + file_registation_charge + refund_amount) as total FROM {$db_prefix}refund_amount WHERE patient_id='$patient_id'")->row();
    if($ref_in) { $inflow_arr[] = $ref_in->total; }


    // --- STEP 2: MONEY GOING OUT (Spent via Wallet) ---
    
    // 1. Standard HMS Tables
    $tables = ['patient_procedure', 'patient_medicine', 'patient_investigations', 'consultation', 'patient_payments'];
    foreach($tables as $table) {
        $spent_row = $ci->db->query("SELECT SUM(payment_done) as spent FROM {$db_prefix}{$table} WHERE patient_id='$patient_id' AND LOWER(payment_method)='wallet'")->row();
        if($spent_row) { 
            $total_out += ($spent_row->spent ?? 0); 
        }
    }

    // 2. Plain Sales Table (NO PREFIX)
    // We remove {$db_prefix} here because you said it's only 'sales'
    $sale_row = $ci->db->query("SELECT SUM(total_amount) as s FROM sales WHERE patient_id='$patient_id' AND LOWER(payment_method)='wallet' AND payment_status='PAID' AND status='CONFIRMED'")->row();

    if($sale_row) {
        $total_out += ($sale_row->s ?? 0);
    }

    // --- STEP 3: FINAL CALCULATION ---
    $total_in = array_sum($inflow_arr);
    $balance = $total_in - $total_out;

    return [
        'total_added' => $total_in,
        'total_spent' => $total_out,
        'balance'     => $balance
    ];
}