<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wallet_model extends CI_Model {

    public function get_wallets($patient_id) {
    // Patient ID ke base par record fetch karein
    $this->db->where('patient_id', $patient_id);
    $query = $this->db->get('hms_patient_wallets');
    
    if ($query->num_rows() > 0) {
        return $query->row_array();
    } else {
        // Agar record nahi hai toh default 0 bhejein
        return ['wallet_1_balance' => 0.00, 'wallet_2_balance' => 0.00];
    }
}

    public function move_money_w1_to_w2($patient_id, $amount, $remarks, $user_id) {
        $wallets = $this->get_wallets($patient_id);
        if ($wallets['wallet_1_balance'] < $amount) return "Insufficient Balance in Wallet A.";

        $this->db->trans_start();
        $new_w1 = $wallets['wallet_1_balance'] - $amount;
        $new_w2 = $wallets['wallet_2_balance'] + $amount;

        $this->db->where('patient_id', $patient_id)->update('hms_patient_wallets', [
            'wallet_1_balance' => $new_w1,
            'wallet_2_balance' => $new_w2
        ]);

        $this->db->insert('hms_wallet_logs', [
            'patient_id' => $patient_id,
            'amount' => $amount,
            'action_type' => 'TRANSFER_MONEY_WALLET_TO_PACKAGE_WALLET',
            'opening_w1' => $wallets['wallet_1_balance'], 'closing_w1' => $new_w1,
            'opening_w2' => $wallets['wallet_2_balance'], 'closing_w2' => $new_w2,
            'remarks' => $remarks, 'created_by' => $user_id
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

// Check karein ki yahan $screenshot parameter hai
public function deposit_w1($patient_id, $amount, $mode, $remarks, $user_id, $screenshot = "") {
    
    $wallets = $this->get_wallets($patient_id);
    $new_w1 = $wallets['wallet_1_balance'] + $amount;

    $this->db->trans_start();

    // 1. Balance Update Logic (Same as before)
    $exists = $this->db->get_where('hms_patient_wallets', ['patient_id' => $patient_id])->num_rows();
    if ($exists > 0) {
        $this->db->where('patient_id', $patient_id)->update('hms_patient_wallets', ['wallet_1_balance' => $new_w1]);
    } else {
        $this->db->insert('hms_patient_wallets', ['patient_id' => $patient_id, 'wallet_1_balance' => $new_w1]);
    }

    // 2. Log Entry - Yahan 'screenshot' column ka hona zaroori hai
    $log_data = [
        'patient_id'  => $patient_id,
        'amount'      => $amount,
        'action_type' => 'DEPOSIT_MONEY_WALLET',
        'opening_w1'  => $wallets['wallet_1_balance'],
        'closing_w1'  => $new_w1,
        'mode'        => $mode,
        'remarks'     => $remarks,
        'screenshot'  => $screenshot, // Ye value database mein jayegi
        'created_by'  => (!empty($user_id)) ? $user_id : 1,
        'created_at'  => date('Y-m-d H:i:s')
    ];

    $this->db->insert('hms_wallet_logs', $log_data);

    $this->db->trans_complete();
    return $this->db->trans_status();
}

    // Wallet_model.php ke andar add karein
    public function get_wallet_history($patient_id) {
        return $this->db->where('patient_id', $patient_id)
                        ->order_by('log_id', 'DESC')
                        ->get('hms_wallet_logs')
                        ->result_array();
    }

    public function use_wallet_money($patient_id, $amount, $procedure_name, $user_id) {
        // 1. Current balance check karein (Hum sirf Wallet B se paise katenge)
        $wallets = $this->get_wallets($patient_id);
        
        if ($wallets['wallet_2_balance'] < $amount) {
            return "Insufficient balance in Procedure Wallet (B).";
        }

        $new_w2_balance = $wallets['wallet_2_balance'] - $amount;

        $this->db->trans_start();

        // 2. Main Balance Update (Minus karna)
        $this->db->where('patient_id', $patient_id)->update('hms_patient_wallets', [
            'wallet_2_balance' => $new_w2_balance
        ]);

        // 3. Log Entry (Taki passbook mein dikhe ki paise kahan kharch huye)
        $this->db->insert('hms_wallet_logs', [
            'patient_id' => $patient_id,
            'amount' => $amount,
            'action_type' => 'USAGE_W2', // Pata chale ki kharch hua hai
            'opening_w1' => $wallets['wallet_1_balance'],
            'closing_w1' => $wallets['wallet_1_balance'],
            'opening_w2' => $wallets['wallet_2_balance'],
            'closing_w2' => $new_w2_balance,
            'remarks' => "Deducted for: " . $procedure_name,
            'created_by' => $user_id
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function request_transfer_b_to_a($patient_id, $amount, $reason, $user_id) {
        // Sirf Log create karein status='pending' ke saath
        // Is waqt balance update nahi hoga
        $data = [
            'patient_id'  => $patient_id,
            'amount'      => $amount,
            'action_type' => 'TRANSFER_B_TO_A_REQUEST',
            'status'      => 'pending',
            'remarks'     => "Approval Required: " . $reason,
            'created_by'  => $user_id,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('hms_wallet_logs', $data);
    }

    // 1. Transfer Request bhejni ki logic
    public function request_transfer_2_to_1($patient_id, $amount, $remarks, $user_id) {
        // Balance check karein ki Wallet 2 mein paise hain bhi ya nahi
        $wallets = $this->get_wallets($patient_id);
        if ($wallets['wallet_2_balance'] < $amount) {
            return "Insufficient balance in Wallet 2.";
        }

        // Sirf Log entry karein status='pending' ke sath
        $data = [
            'patient_id'  => $patient_id,
            'amount'      => $amount,
            'action_type' => 'TRANSFER_PACKAGE_WALLET_TO_MONEY_WALLET',
            'status'      => 'pending', // Ye Accountant ke pass dikhega
            'remarks'     => "Request: " . $remarks,
            'created_by'  => $user_id,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('hms_wallet_logs', $data);
    }

    // 2. Accountant jab Approve karega tab ye chalega
    public function approve_transfer_request($log_id) {
        $log = $this->db->get_where('hms_wallet_logs', ['log_id' => $log_id])->row_array();
        
        if ($log && $log['status'] == 'pending') {
            $p_id = $log['patient_id'];
            $amt  = $log['amount'];
            $wallets = $this->get_wallets($p_id);

            $this->db->trans_start();
            // Wallet 2 se minus aur Wallet 1 mein plus
            $this->db->where('patient_id', $p_id)->update('hms_patient_wallets', [
                'wallet_1_balance' => $wallets['wallet_1_balance'] + $amt,
                'wallet_2_balance' => $wallets['wallet_2_balance'] - $amt
            ]);
            // Status update
            $this->db->where('log_id', $log_id)->update('hms_wallet_logs', ['status' => 'approved']);
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }
}