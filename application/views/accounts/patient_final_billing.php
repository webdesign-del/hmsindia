<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Helper function to convert number to words
function numberToWords($number) {
    $ones = array(
        0 => 'ZERO', 1 => 'ONE', 2 => 'TWO', 3 => 'THREE', 4 => 'FOUR', 5 => 'FIVE',
        6 => 'SIX', 7 => 'SEVEN', 8 => 'EIGHT', 9 => 'NINE', 10 => 'TEN',
        11 => 'ELEVEN', 12 => 'TWELVE', 13 => 'THIRTEEN', 14 => 'FOURTEEN', 15 => 'FIFTEEN',
        16 => 'SIXTEEN', 17 => 'SEVENTEEN', 18 => 'EIGHTEEN', 19 => 'NINETEEN'
    );
    
    $tens = array(
        2 => 'TWENTY', 3 => 'THIRTY', 4 => 'FORTY', 5 => 'FIFTY',
        6 => 'SIXTY', 7 => 'SEVENTY', 8 => 'EIGHTY', 9 => 'NINETY'
    );
    
    if ($number < 20) {
        return $ones[$number];
    } elseif ($number < 100) {
        return $tens[floor($number / 10)] . ($number % 10 ? ' ' . $ones[$number % 10] : '');
    } elseif ($number < 1000) {
        return $ones[floor($number / 100)] . ' HUNDRED' . ($number % 100 ? ' ' . numberToWords($number % 100) : '');
    } elseif ($number < 100000) {
        return numberToWords(floor($number / 1000)) . ' THOUSAND' . ($number % 1000 ? ' ' . numberToWords($number % 1000) : '');
    } elseif ($number < 10000000) {
        return numberToWords(floor($number / 100000)) . ' LAC' . ($number % 100000 ? ' ' . numberToWords($number % 100000) : '');
    } else {
        return numberToWords(floor($number / 10000000)) . ' CRORE' . ($number % 10000000 ? ' ' . numberToWords($number % 10000000) : '');
    }
}
?>

<div class="container-fluid">
        <div class="row mx-5">
            <div class="col-lg-12">
                <h1 class="page-header">Patient Final Billing</h1>
            </div>
        </div>

        <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <?php if(!isset($show_bill) || !$show_bill): ?>
        <!-- Search Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-search"></i> Search Patient for Final Billing
                    </div>
                    <div class="panel-body">
                        <form id="patientSearchForm">
                            <div class="form-group">
                                <label for="search_term">Search Patient:</label>
                                <input type="text" class="form-control" id="search_term" name="search_term" 
                                       placeholder="Enter Patient ID, Name, or Phone Number" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </form>
                        
                        <div id="searchResults" style="margin-top: 20px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient List -->
        <div class="row" id="patientListSection" style="display: none;">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Search Results
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="patientTable">
                                <thead>
                                    <tr>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        <th>Husband Name</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="patientTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($show_bill) && $show_bill && isset($billing_data)): ?>
        <?php
        $patient = $billing_data['patient'];
        $payment_status = $billing_data['payment_status'];
        $payment_history = $billing_data['payment_history'];
        $center_info = $billing_data['center_info'];

        // Calculate totals
        $total_amount = $payment_status['total_amount'];
        $total_discount = 0;
        $net_amount = $total_amount - $total_discount;
        $total_paid = $payment_status['total_paid'];

        // Calculate discount from procedure details
        foreach ($payment_status['procedure_details'] as $proc) {
            $total_discount += $proc['discount'];
        }
        $net_amount = $total_amount - $total_discount;
        ?>

        <!-- Bill Display Section -->
        <div class="row mx-5">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-file-text"></i> Final Bill - <?php echo $patient['patient_id']; ?>
                        <div class="pull-right">
                            <button class="btn btn-success" onclick="window.print()">
                                <i class="fa fa-print"></i> Print Bill
                            </button>
                            <a href="<?php echo base_url(); ?>accounts/patient_final_billing" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Back to Search
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="billContent">
                            <!-- Bill Content -->
                            <div class="bill-container" style="max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
                                <!-- Header Section -->
                                <div class="header" style="text-align: center; border-bottom: 3px solid #2c5aa0; padding-bottom: 20px; margin-bottom: 30px;">
                                    <div class="logo-section" style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                        <div class="logo" style="width: 60px; height: 60px; background: #2c5aa0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">IVF</div>
                                        <div>
                                            <div class="clinic-name" style="font-size: 24px; font-weight: bold; color: #2c5aa0; margin-bottom: 10px;">INDIA IVF CLINIC</div>
                                            <div class="clinic-subtitle" style="font-size: 14px; color: #666; margin-bottom: 15px;">(A UNIT OF PASHUPATI LIFECARE PVT LTD)</div>
                                        </div>
                                    </div>
                                    <div class="clinic-details" style="font-size: 12px; color: #333; line-height: 1.4;">
                                        <div><strong>GSTIN NO:</strong> 09AAHCP5838M1ZP</div>
                                        <div><strong>Address:</strong> Third Floor, N-26, Captain Vijayant Thapar Marg, Dr. Lal Path Labs, Sec.-18, Noida Gautambuddha Nagar Uttar Pradesh, 201301</div>
                                        <div><strong>Telephone No:</strong> 73538 73538 | <strong>Email:</strong> INDIAIVFCLINIC@GMAIL.COM | <strong>Website:</strong> WWW.INDIAIVF.IN</div>
                                    </div>
                                </div>

                                <!-- Patient and Billing Information -->
                                <div class="patient-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                                    <div class="patient-info" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                                        <div class="section-title" style="font-size: 16px; font-weight: bold; color: #2c5aa0; margin-bottom: 15px; border-bottom: 2px solid #2c5aa0; padding-bottom: 5px;">Patient Details</div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Patient Name:</span>
                                            <span class="info-value" style="color: #666;"><?php echo $patient['wife_name']; ?></span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">IIC ID:</span>
                                            <span class="info-value" style="color: #666;"><?php echo $patient['patient_id']; ?></span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Age/Sex:</span>
                                            <span class="info-value" style="color: #666;"><?php echo $patient['wife_age']; ?> YEARS</span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Primary Doctor:</span>
                                            <span class="info-value" style="color: #666;">DR. RICHIKA SAHAY</span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Contact No:</span>
                                            <span class="info-value" style="color: #666;"><?php echo $patient['wife_phone']; ?></span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Payor Name:</span>
                                            <span class="info-value" style="color: #666;"><?php echo $patient['husband_name']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="billing-info" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                                        <div class="section-title" style="font-size: 16px; font-weight: bold; color: #2c5aa0; margin-bottom: 15px; border-bottom: 2px solid #2c5aa0; padding-bottom: 5px;">Billing Details</div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Bill No:</span>
                                            <span class="info-value" style="color: #666;"><?php echo 'FINAL/' . date('Y') . '/' . $patient['patient_id']; ?></span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Bill Type:</span>
                                            <span class="info-value" style="color: #666;">Final Bill</span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Bill Date:</span>
                                            <span class="info-value" style="color: #666;"><?php echo date('d-m-Y'); ?></span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Print Date:</span>
                                            <span class="info-value" style="color: #666;"><?php echo date('d-m-Y'); ?></span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">Discount Scheme:</span>
                                            <span class="info-value" style="color: #666;">FINAL BILLING</span>
                                        </div>
                                        <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                            <span class="info-label" style="font-weight: bold; color: #333;">CIN No:</span>
                                            <span class="info-value" style="color: #666;">NA</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Services Details -->
                                <table class="services-table" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">SL NO</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">Particulars</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">HSN Code</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">Service Amount</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">Discount</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">Net Amount</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">Paid Amount</th>
                                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left; background-color: #2c5aa0; color: white; font-weight: bold;">Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sl_no = 1;
                                        
                                        // Get procedure data with payment details
                                        $procedures_with_payments = array();
                                        foreach ($payment_status['procedures'] as $procedure) {
                                            $procedure_data = unserialize($procedure['data']);
                                            if (isset($procedure_data['patient_procedures'])) {
                                                foreach ($procedure_data['patient_procedures'] as $proc) {
                                                    // Get procedure name from hms_procedures table
                                                    $procedure_name = $proc['sub_procedure']; // fallback
                                                    if (!empty($proc['sub_procedures_code'])) {
                                                        $this->db->select('procedure_name');
                                                        $this->db->from('hms_procedures');
                                                        $this->db->where('code', $proc['sub_procedures_code']);
                                                        $proc_query = $this->db->get();
                                                        if ($proc_query->num_rows() > 0) {
                                                            $proc_result = $proc_query->row_array();
                                                            $procedure_name = $proc_result['procedure_name'];
                                                        }
                                                    }
                                                    
                                                    // Handle null/empty values
                                                    $price = !empty($proc['sub_procedures_price']) ? floatval($proc['sub_procedures_price']) : 0;
                                                    $discount = !empty($proc['sub_procedures_discount']) ? floatval($proc['sub_procedures_discount']) : 0;
                                                    $paid_price = !empty($proc['sub_procedures_paid_price']) ? floatval($proc['sub_procedures_paid_price']) : 0;
                                                    
                                                    // Skip procedures with invalid data
                                                    if ($price == 0 && $discount == 0 && $paid_price == 0) {
                                                        continue;
                                                    }
                                                    
                                                    $procedures_with_payments[] = array(
                                                        'procedure_name' => $procedure_name,
                                                        'procedure_code' => !empty($proc['sub_procedures_code']) ? $proc['sub_procedures_code'] : 'N/A',
                                                        'price' => $price,
                                                        'discount' => $discount,
                                                        'paid_price' => $paid_price,
                                                        'billing_id' => $procedure['billing_id'],
                                                        'on_date' => $procedure['on_date'],
                                                        'total_package' => $procedure['totalpackage'],
                                                        'total_discount' => $procedure['discount_amount'],
                                                        'total_paid' => $procedure['payment_done'],
                                                        'remaining_amount' => $procedure['remaining_amount']
                                                    );
                                                }
                                            }
                                        }
                                        
                                        // Group procedures by billing_id for display
                                        $grouped_procedures = array();
                                        foreach ($procedures_with_payments as $proc) {
                                            if (!isset($grouped_procedures[$proc['billing_id']])) {
                                                $grouped_procedures[$proc['billing_id']] = array(
                                                    'procedures' => array(),
                                                    'total_package' => 0,
                                                    'total_discount' => 0,
                                                    'total_paid' => 0,
                                                    'remaining_amount' => 0,
                                                    'on_date' => ''
                                                );
                                            }
                                            $grouped_procedures[$proc['billing_id']]['procedures'][] = $proc;
                                            $grouped_procedures[$proc['billing_id']]['total_package'] = $proc['total_package'];
                                            $grouped_procedures[$proc['billing_id']]['total_discount'] = $proc['total_discount'];
                                            $grouped_procedures[$proc['billing_id']]['total_paid'] = $proc['total_paid'];
                                            $grouped_procedures[$proc['billing_id']]['remaining_amount'] = $proc['remaining_amount'];
                                            $grouped_procedures[$proc['billing_id']]['on_date'] = $proc['on_date'];
                                        }
                                        
                                        foreach ($grouped_procedures as $billing_id => $group_data) {
                                            $net_amount = $group_data['total_package'] - $group_data['total_discount'];
                                        ?>
                                        <tr style="background-color: <?php echo $sl_no % 2 == 0 ? '#f8f9fa' : 'white'; ?>;">
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left;"><?php echo $sl_no; ?></td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left;">
                                                <strong>Comprehensive IVF Treatment</strong>
                                                <div style="font-size: 10px; color: #888; margin-top: 5px;">
                                                    Bill ID: <?php echo $billing_id; ?> | Date: <?php echo date('d-m-Y', strtotime($group_data['on_date'])); ?>
                                                </div>
                                                <?php foreach ($group_data['procedures'] as $proc): ?>
                                                <div class="sub-procedure" style="font-size: 12px; color: #666; margin-left: 10px; margin-top: 3px;">
                                                    • <?php echo $proc['procedure_name']; ?> (<?php echo $proc['procedure_code']; ?>) - Rs. <?php echo number_format($proc['price']); ?>
                                                    <?php if($proc['discount'] > 0): ?>
                                                        <span style="color: #28a745;">[Discount: Rs. <?php echo number_format($proc['discount']); ?>]</span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left;">999311</td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left;">Rs. <?php echo number_format($group_data['total_package']); ?></td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left;">Rs. <?php echo number_format($group_data['total_discount']); ?></td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left;">Rs. <?php echo number_format($net_amount); ?></td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left; color: #28a745; font-weight: bold;">Rs. <?php echo number_format($group_data['total_paid']); ?></td>
                                            <td style="border: 1px solid #ddd; padding: 12px; text-align: left; color: <?php echo $group_data['remaining_amount'] > 0 ? '#dc3545' : '#28a745'; ?>; font-weight: bold;">
                                                Rs. <?php echo number_format($group_data['remaining_amount']); ?>
                                                <?php if($group_data['remaining_amount'] == 0): ?>
                                                    <span style="color: #28a745;">✓</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php 
                                        $sl_no++;
                                        } 
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Detailed Procedure Breakdown -->
                                <div class="panel panel-info" style="margin-bottom: 30px;">
                                    <div class="panel-heading" style="background-color: #2c5aa0; color: white; font-weight: bold;">
                                        <i class="fa fa-list"></i> Detailed Procedure Breakdown
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr style="background-color: #f8f9fa;">
                                                        <th style="padding: 10px; text-align: left;">Procedure Name</th>
                                                        <th style="padding: 10px; text-align: left;">Code</th>
                                                        <th style="padding: 10px; text-align: right;">Original Price</th>
                                                        <th style="padding: 10px; text-align: right;">Discount</th>
                                                        <th style="padding: 10px; text-align: right;">Net Price</th>
                                                        <th style="padding: 10px; text-align: right;">Paid Amount</th>
                                                        <th style="padding: 10px; text-align: right;">Remaining</th>
                                                        <th style="padding: 10px; text-align: center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $total_original = 0;
                                                    $total_discount = 0;
                                                    $total_net = 0;
                                                    $total_paid = 0;
                                                    $total_remaining = 0;
                                                    
                                                    foreach ($procedures_with_payments as $proc): 
                                                        $net_price = $proc['price'] - $proc['discount'];
                                                        $remaining = $net_price - $proc['paid_price'];
                                                        
                                                        $total_original += $proc['price'];
                                                        $total_discount += $proc['discount'];
                                                        $total_net += $net_price;
                                                        $total_paid += $proc['paid_price'];
                                                        $total_remaining += $remaining;
                                                    ?>
                                                    <tr>
                                                        <td style="padding: 10px; text-align: left;"><?php echo $proc['procedure_name']; ?></td>
                                                        <td style="padding: 10px; text-align: left;"><?php echo $proc['procedure_code']; ?></td>
                                                        <td style="padding: 10px; text-align: right;">Rs. <?php echo number_format($proc['price']); ?></td>
                                                        <td style="padding: 10px; text-align: right; color: #28a745;">
                                                            <?php if($proc['discount'] > 0): ?>
                                                                Rs. <?php echo number_format($proc['discount']); ?>
                                                            <?php else: ?>
                                                                Rs. 0
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="padding: 10px; text-align: right; font-weight: bold;">Rs. <?php echo number_format($net_price); ?></td>
                                                        <td style="padding: 10px; text-align: right; color: #28a745; font-weight: bold;">Rs. <?php echo number_format($proc['paid_price']); ?></td>
                                                        <td style="padding: 10px; text-align: right; color: <?php echo $remaining > 0 ? '#dc3545' : '#28a745'; ?>; font-weight: bold;">
                                                            Rs. <?php echo number_format($remaining); ?>
                                                        </td>
                                                        <td style="padding: 10px; text-align: center;">
                                                            <?php if($remaining <= 0): ?>
                                                                <span class="label label-success">✓ Paid</span>
                                                            <?php else: ?>
                                                                <span class="label label-warning">Pending</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot style="background-color: #f8f9fa; font-weight: bold;">
                                                    <tr>
                                                        <td colspan="2" style="padding: 10px; text-align: left;"><strong>TOTAL</strong></td>
                                                        <td style="padding: 10px; text-align: right;"><strong>Rs. <?php echo number_format($total_original); ?></strong></td>
                                                        <td style="padding: 10px; text-align: right; color: #28a745;"><strong>Rs. <?php echo number_format($total_discount); ?></strong></td>
                                                        <td style="padding: 10px; text-align: right;"><strong>Rs. <?php echo number_format($total_net); ?></strong></td>
                                                        <td style="padding: 10px; text-align: right; color: #28a745;"><strong>Rs. <?php echo number_format($total_paid); ?></strong></td>
                                                        <td style="padding: 10px; text-align: right; color: <?php echo $total_remaining > 0 ? '#dc3545' : '#28a745'; ?>;">
                                                            <strong>Rs. <?php echo number_format($total_remaining); ?></strong>
                                                        </td>
                                                        <td style="padding: 10px; text-align: center;">
                                                            <?php if($total_remaining <= 0): ?>
                                                                <span class="label label-success">✓ All Paid</span>
                                                            <?php else: ?>
                                                                <span class="label label-danger">Outstanding</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary -->
                                <div class="payment-summary" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                                    <div class="section-title" style="font-size: 16px; font-weight: bold; color: #2c5aa0; margin-bottom: 15px; border-bottom: 2px solid #2c5aa0; padding-bottom: 5px;">Payment Summary</div>
                                    <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                        <span>Total Amount:</span>
                                        <span>Rs. <?php echo number_format($total_amount); ?></span>
                                    </div>
                                    <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                        <span>Total Discount:</span>
                                        <span>Rs. <?php echo number_format($total_discount); ?></span>
                                    </div>
                                    <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                        <span>Tax Amount:</span>
                                        <span>Rs. 0.00</span>
                                    </div>
                                    <div class="summary-row total" style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px; font-weight: bold; border-top: 2px solid #2c5aa0; padding-top: 10px; margin-top: 10px;">
                                        <span>Net Amount:</span>
                                        <span>Rs. <?php echo number_format($net_amount); ?></span>
                                    </div>
                                    <div class="summary-row total" style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px; font-weight: bold;">
                                        <span>Paid By Patient:</span>
                                        <span>Rs. <?php echo number_format($total_paid); ?></span>
                                    </div>
                                    <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                        <span>Rupees in Words:</span>
                                        <span><strong><?php echo strtoupper(numberToWords($net_amount)) . ' ONLY'; ?></strong></span>
                                    </div>
                                </div>

                                <!-- Payor Details -->
                                <div class="patient-info" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                                    <div class="section-title" style="font-size: 16px; font-weight: bold; color: #2c5aa0; margin-bottom: 15px; border-bottom: 2px solid #2c5aa0; padding-bottom: 5px;">Payor Details</div>
                                    <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                        <span class="info-label" style="font-weight: bold; color: #333;">Name:</span>
                                        <span class="info-value" style="color: #666;"><?php echo $patient['husband_name']; ?></span>
                                    </div>
                                    <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                        <span class="info-label" style="font-weight: bold; color: #333;">Address:</span>
                                        <span class="info-value" style="color: #666;"><?php echo $patient['address']; ?></span>
                                    </div>
                                    <div class="info-row" style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                                        <span class="info-label" style="font-weight: bold; color: #333;">GSTIN:</span>
                                        <span class="info-value" style="color: #666;">NA</span>
                                    </div>
                                </div>

                                <!-- Note Section -->
                                <div class="note-section" style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <div class="note-text" style="font-size: 12px; color: #856404; font-style: italic;">
                                        <strong>Note:</strong> All doctor visits would be chargeable, all refunds against cards/cash/online payment to be made by NEFT only.
                                    </div>
                                    </div>
                                </div>

                                <!-- Receipt Details -->
                                <table class="receipt-table" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #2c5aa0; color: white; font-weight: bold;">SL NO</th>
                                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #2c5aa0; color: white; font-weight: bold;">RECEIPT NO</th>
                                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #2c5aa0; color: white; font-weight: bold;">RECEIPT DATE</th>
                                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #2c5aa0; color: white; font-weight: bold;">AMOUNT</th>
                                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #2c5aa0; color: white; font-weight: bold;">PAYMENT MODE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $receipt_sl = 1;
                                        foreach ($payment_history as $payment): 
                                        ?>
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;"><?php echo $receipt_sl; ?></td>
                                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;"><?php echo $payment['refrence_number']; ?></td>
                                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;"><?php echo date('d.m.Y', strtotime($payment['on_date'])); ?></td>
                                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">Rs. <?php echo number_format($payment['payment_done']); ?></td>
                                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;"><?php echo strtoupper($payment['payment_method']); ?></td>
                                        </tr>
                                        <?php 
                                        $receipt_sl++;
                                        endforeach; 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
   

<script>
$(document).ready(function() {
    // Patient search form submission
    $('#patientSearchForm').on('submit', function(e) {
        e.preventDefault();
        
        var searchTerm = $('#search_term').val();
        if(searchTerm.length < 3) {
            alert('Please enter at least 3 characters to search');
            return;
        }
        
        $.ajax({
            url: '<?php echo base_url(); ?>accounts/search_patients_for_final_billing',
            type: 'POST',
            data: { search_term: searchTerm },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    displaySearchResults(response.patients);
                } else {
                    $('#searchResults').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#searchResults').html('<div class="alert alert-danger">Error occurred while searching</div>');
            }
        });
    });
    
    function displaySearchResults(patients) {
        var html = '';
        if(patients.length > 0) {
            html += '<div class="table-responsive"><table class="table table-striped table-bordered table-hover">';
            html += '<thead><tr><th>Patient ID</th><th>Patient Name</th><th>Husband Name</th><th>Phone</th><th>Action</th></tr></thead>';
            html += '<tbody>';
            
            patients.forEach(function(patient) {
                html += '<tr>';
                html += '<td>' + patient.patient_id + '</td>';
                html += '<td>' + patient.wife_name + '</td>';
                html += '<td>' + patient.husband_name + '</td>';
                html += '<td>' + patient.wife_phone + '</td>';
                html += '<td>';
                html += '<button class="btn btn-info btn-sm check-eligibility" data-patient-id="' + patient.patient_id + '">Check Eligibility</button> ';
                html += '<button class="btn btn-success btn-sm generate-bill" data-patient-id="' + patient.patient_id + '">Generate Bill</button>';
                html += '</td>';
                html += '</tr>';
            });
            
            html += '</tbody></table></div>';
        } else {
            html = '<div class="alert alert-info">No patients found</div>';
        }
        
        $('#searchResults').html(html);
    }
    
    // Check eligibility button click
    $(document).on('click', '.check-eligibility', function() {
        var patientId = $(this).data('patient-id');
        
        $.ajax({
            url: '<?php echo base_url(); ?>accounts/check_final_billing_eligibility',
            type: 'POST',
            data: { patient_id: patientId },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    var message = response.eligible ? 
                        '<div class="alert alert-success"><strong>Eligible for Final Billing</strong><br>' +
                        'Total Amount: Rs. ' + response.total_amount + '<br>' +
                        'Total Paid: Rs. ' + response.total_paid + '<br>' +
                        'Procedures: ' + response.procedures_count + '</div>' :
                        '<div class="alert alert-warning"><strong>Not Eligible</strong><br>' +
                        'Remaining Amount: Rs. ' + response.remaining_amount + '<br>' +
                        response.message + '</div>';
                    
                    $('#searchResults').prepend(message);
                    
                    // Show detailed procedure breakdown
                    showProcedureDetails(patientId);
                } else {
                    $('#searchResults').prepend('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#searchResults').prepend('<div class="alert alert-danger">Error occurred while checking eligibility</div>');
            }
        });
    });
    
    // Show detailed procedure breakdown
    function showProcedureDetails(patientId) {
        $.ajax({
            url: '<?php echo base_url(); ?>accounts/get_patient_procedure_details',
            type: 'POST',
            data: { patient_id: patientId },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    var html = '<div class="panel panel-info" style="margin-top: 20px;">';
                    html += '<div class="panel-heading" style="background-color: #2c5aa0; color: white; font-weight: bold;">';
                    html += '<i class="fa fa-list"></i> Procedure Details & Payment Status';
                    html += '</div>';
                    html += '<div class="panel-body">';
                    html += '<div class="table-responsive">';
                    html += '<table class="table table-striped table-bordered">';
                    html += '<thead>';
                    html += '<tr style="background-color: #f8f9fa;">';
                    html += '<th style="padding: 10px; text-align: left;">Procedure Name</th>';
                    html += '<th style="padding: 10px; text-align: left;">Code</th>';
                    html += '<th style="padding: 10px; text-align: right;">Original Price</th>';
                    html += '<th style="padding: 10px; text-align: right;">Discount</th>';
                    html += '<th style="padding: 10px; text-align: right;">Net Price</th>';
                    html += '<th style="padding: 10px; text-align: right;">Paid Amount</th>';
                    html += '<th style="padding: 10px; text-align: right;">Remaining</th>';
                    html += '<th style="padding: 10px; text-align: center;">Status</th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody>';
                    
                    var totalOriginal = 0;
                    var totalDiscount = 0;
                    var totalNet = 0;
                    var totalPaid = 0;
                    var totalRemaining = 0;
                    
                    var allPaymentsComplete = true;
                    
                    response.procedures.forEach(function(proc) {
                        // Handle null/undefined values
                        var price = parseFloat(proc.price) || 0;
                        var discount = parseFloat(proc.discount) || 0;
                        var paidPrice = parseFloat(proc.paid_price) || 0;
                        var netPrice = price - discount;
                        var remaining = netPrice - paidPrice;
                        
                        // Skip procedures with invalid data
                        if (isNaN(price) || isNaN(discount) || isNaN(paidPrice)) {
                            return;
                        }
                        
                        totalOriginal += price;
                        totalDiscount += discount;
                        totalNet += netPrice;
                        totalPaid += paidPrice;
                        totalRemaining += remaining;
                        
                        if(remaining > 0) {
                            allPaymentsComplete = false;
                        }
                        
                        html += '<tr>';
                        html += '<td style="padding: 10px; text-align: left;">' + (proc.procedure_name || 'Unknown Procedure') + '</td>';
                        html += '<td style="padding: 10px; text-align: left;">' + (proc.procedure_code || 'N/A') + '</td>';
                        html += '<td style="padding: 10px; text-align: right;">Rs. ' + price.toLocaleString() + '</td>';
                        html += '<td style="padding: 10px; text-align: right; color: #28a745;">Rs. ' + discount.toLocaleString() + '</td>';
                        html += '<td style="padding: 10px; text-align: right; font-weight: bold;">Rs. ' + netPrice.toLocaleString() + '</td>';
                        html += '<td style="padding: 10px; text-align: right; color: #28a745; font-weight: bold;">Rs. ' + paidPrice.toLocaleString() + '</td>';
                        html += '<td style="padding: 10px; text-align: right; color: ' + (remaining > 0 ? '#dc3545' : '#28a745') + '; font-weight: bold;">Rs. ' + remaining.toLocaleString() + '</td>';
                        html += '<td style="padding: 10px; text-align: center;">';
                        if(remaining <= 0) {
                            html += '<span class="label label-success">✓ Paid</span>';
                        } else {
                            html += '<span class="label label-warning">Pending</span>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });
                    
                    html += '</tbody>';
                    html += '<tfoot style="background-color: #f8f9fa; font-weight: bold;">';
                    html += '<tr>';
                    html += '<td colspan="2" style="padding: 10px; text-align: left;"><strong>TOTAL</strong></td>';
                    html += '<td style="padding: 10px; text-align: right;"><strong>Rs. ' + totalOriginal.toLocaleString() + '</strong></td>';
                    html += '<td style="padding: 10px; text-align: right; color: #28a745;"><strong>Rs. ' + totalDiscount.toLocaleString() + '</strong></td>';
                    html += '<td style="padding: 10px; text-align: right;"><strong>Rs. ' + totalNet.toLocaleString() + '</strong></td>';
                    html += '<td style="padding: 10px; text-align: right; color: #28a745;"><strong>Rs. ' + totalPaid.toLocaleString() + '</strong></td>';
                    html += '<td style="padding: 10px; text-align: right; color: ' + (totalRemaining > 0 ? '#dc3545' : '#28a745') + ';"><strong>Rs. ' + totalRemaining.toLocaleString() + '</strong></td>';
                    html += '<td style="padding: 10px; text-align: center;">';
                    if(totalRemaining <= 0) {
                        html += '<span class="label label-success">✓ All Paid</span>';
                    } else {
                        html += '<span class="label label-danger">Outstanding</span>';
                    }
                    html += '</td>';
                    html += '</tr>';
                    html += '</tfoot>';
                    html += '</table>';
                    html += '</div>';
                    
                    // Add print button only if all payments are complete
                    if(allPaymentsComplete) {
                        html += '<div style="text-align: center; margin-top: 20px;">';
                        html += '<button class="btn btn-success btn-lg" onclick="generateFinalBill(\'' + patientId + '\')">';
                        html += '<i class="fa fa-print"></i> Generate & Print Final Bill';
                        html += '</button>';
                        html += '</div>';
                    } else {
                        html += '<div style="text-align: center; margin-top: 20px;">';
                        html += '<div class="alert alert-warning">';
                        html += '<i class="fa fa-exclamation-triangle"></i> ';
                        html += '<strong>Cannot generate final bill</strong> - Patient has outstanding payments. ';
                        html += 'Please complete all payments before generating the final bill.';
                        html += '</div>';
                        html += '</div>';
                    }
                    
                    html += '</div>';
                    html += '</div>';
                    
                    $('#searchResults').append(html);
                }
            },
            error: function() {
                $('#searchResults').append('<div class="alert alert-danger">Error loading procedure details</div>');
            }
        });
    }
    
    // Generate bill button click
    $(document).on('click', '.generate-bill', function() {
        var patientId = $(this).data('patient-id');
        window.location.href = '<?php echo base_url(); ?>accounts/generate_final_bill/' + patientId;
    });
    
    // Generate final bill function (called from procedure details)
    window.generateFinalBill = function(patientId) {
        window.location.href = '<?php echo base_url(); ?>accounts/generate_final_bill/' + patientId;
    };
});
</script>

<style>
@media print {
    .panel-heading, .btn, .navbar, .sidebar, #page-wrapper > .row:first-child {
        display: none !important;
    }
    #billContent {
        margin: 0 !important;
        padding: 0 !important;
    }
    .bill-container {
        box-shadow: none !important;
        margin: 0 !important;
        padding: 20px !important;
    }
}
</style>
