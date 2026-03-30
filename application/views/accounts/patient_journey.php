<?php $all_method =& get_instance(); ?>
<div class="col-md-12 card">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-12"><h3> Patient Journey </h3></div>
        <div class="clearfix"></div>
        
        <form action="<?php echo base_url('accounts/patient_journey'); ?>" method="get">
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
                <label>Filter by billing at</label>
                <select class="form-control" id="billing_at" name="billing_at">
                    <option value=''>--Select Center--</option>
                    <?php 
                    $all_centers = $all_method->get_all_centers();
                    foreach($all_centers as $val){
                        $selected = ($billing_at == $val['center_number']) ? 'selected' : '';
                        echo '<option value="'.$val['center_number'].'" '.$selected.'>'.$val['center_name'].'</option>';
                    } ?>
                </select>
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
                <label>Start Booking Date</label>
                <input type="text" class="particular_date_filter form-control" name="start_date" value="<?php echo $start_date;?>" autocomplete="off" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
                <label>End Booking Date</label>
                <input type="text" class="particular_date_filter form-control" name="end_date" value="<?php echo $end_date;?>" autocomplete="off" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
                <label>IIC ID / Patient ID</label>
                <input type="text" class="form-control" name="iic_id" value="<?php echo $iic_id; ?>" />
            </div>
            <div class="col-sm-12" style="margin-top: 15px;">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="<?php echo base_url('accounts/patient_journey'); ?>" class="btn btn-secondary">RESET</a>
            </div>		
        </form>  
    </div>

    <div class="card-content">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Booking Date</th>
                        <th>IIC ID</th>
                        <th>Patient Name</th>
                        <th>Center</th>
                        <th>Procedure</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Actual Stimulation Start</th>
                        <th>Trigger Date</th>
                        <th>Actual OPU Date</th>
                        <th>Embryo Transfer Date</th>
                        <th>HCG On Date</th>
                        <th>Gestational Sacs</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $count = 1; 
                foreach($procedure_result as $vl): 
                    // 1. Fetch Related Data (Optimized)
                    $receipt = $vl['receipt_number'];
                    
                    // Fetch Stimulation Data
                    $stim = $this->db->get_where('ovulation_induction_protocol', ['receipt_number' => $receipt])->row_array();
                    
                    // Fetch Trigger/OPU Data
                    $trigger = $this->db->get_where('trigger_module', ['receipt_number' => $receipt])->row_array();
                    
                    // Fetch ET Data
                    $et = $this->db->get_where('embryo_transfer', ['receipt_number' => $receipt])->row_array();
                    
                    // Fetch HCG Data
                    $hcg = $this->db->get_where('hms_serum_bete_hcg_on', ['receipt_number' => $receipt])->row_array();
                ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo date('d-M-y', strtotime($vl['on_date'])); ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo strtoupper($all_method->get_patient_name($vl['patient_id'])); ?></td>
                        <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
                        <td><?php echo $vl['procedure_name']; ?></td>
                        <td><?php echo $vl['code']; ?></td>
                        <td><span class="label label-info"><?php echo $vl['status']; ?></span></td>
                        <td><?php echo !empty($stim['date1']) ? $stim['date1'] : 'N/A'; ?></td>
                        <td><?php echo !empty($trigger['last_inj_fsh']) ? $trigger['last_inj_fsh'] : 'N/A'; ?></td>
                        <td><?php echo !empty($trigger['ovum_pick_up_on']) ? $trigger['ovum_pick_up_on'] : 'N/A'; ?></td>
                        <td><?php echo !empty($et['transfer_date']) ? $et['transfer_date'] : '-'; ?></td>
                        <td><?php echo !empty($hcg['date']) ? $hcg['date'] : '-'; ?></td>
                        <td><?php echo !empty($hcg['no_of_gestational']) ? $hcg['no_of_gestational'] : '0'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pull-right"><?php echo $links; ?></div>
        </div>
    </div>
</div>

<script>
$(function() {
    $(".particular_date_filter").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
});
</script>

<style>
    .table th { background: #f4f4f4; vertical-align: middle !important; text-align: center; }
    .btn { height: 34px; margin-right: 5px; }
    .label { padding: 5px; }
</style>