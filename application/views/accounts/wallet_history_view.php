<?php 
    $all_method =& get_instance(); 
    // Load the helper if not loaded in controller
  //  $this->load->helper('billing');
    
    // 1. Try to get the ID from $patient_data first. If it doesn't exist, grab it from the URL!
    $patient_id = $patient_data['patient_id'] ?? $patient_data['uhid'] ?? $this->uri->segment(3);    
?>

<div class="container-fluid mt-4">

<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="well" style="background: #ecf0f5; border-left: 5px solid #00a65a;">
            <h4 style="margin-top:0;">Wallet Summary</h4>
            <p>IIC Id: <strong><?php echo !empty($patient_id) ? $patient_id : 'N/A'; ?></strong></p>
            <p>Name: <strong><?php echo isset($patient_data['patient_name']) ? $patient_data['patient_name'] : 'N/A'; ?></strong></p>
            <p>Origin Center: <span style="color:green; font-size: 20px; font-weight:bold;">₹0.00</span></p>
        </div>
    </div>
    <div class="col-md-6 text-right">
       
    </div>
</div>
    
    <div class="row mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="text-white-50 small font-weight-bold text-uppercase mb-1">Money Wallet (W1)</div>
                    <div class="h3 mb-0 font-weight-bold">₹ <?php echo number_format($wallets['wallet_1_balance'], 2); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <div class="text-dark-50 small font-weight-bold text-uppercase mb-1">Package Wallet (W2)</div>
                    <div class="h3 mb-0 font-weight-bold">₹ <?php echo number_format($wallets['wallet_2_balance'], 2); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Transaction Passbook</h5>
            
            <div class="d-flex align-items-center">
                <div class="mr-3">
                    <label class="small font-weight-bold d-block mb-0">Search Amount</label>
                    <input type="number" id="searchAmount" class="form-control form-control-sm" placeholder="₹ 0.00" step="0.01">
                </div>
                <div class="mr-2">
                    <label class="small font-weight-bold d-block mb-0">From Date</label>
                    <input type="date" id="min" class="form-control form-control-sm">
                </div>
                <div>
                    <label class="small font-weight-bold d-block mb-0">To Date</label>
                    <input type="date" id="max" class="form-control form-control-sm">
                </div>
            </div>    
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="walletTable" class="table table-bordered table-hover w-100">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Amount (Col 3)</th>
                            <th>Money Balance</th>
                            <th>Package Balance</th>
                            <th>Sub Category</th>
                            <th>Proof</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($history)): foreach($history as $row): ?>
                        <tr>
                            <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                            <td>
                                <span class="badge <?php echo ($row['action_type'] == 'DEPOSIT_W1' ? 'badge-success' : 'badge-info'); ?>">
                                    <?php echo $row['action_type']; ?>
                                </span>
                            </td>
                            <td class="font-weight-bold text-primary">₹ <?php echo number_format($row['amount'], 2); ?></td>
                            <td>₹ <?php echo number_format($row['closing_w1'], 2); ?></td>
                            <td>₹ <?php echo number_format($row['closing_w2'], 2); ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td class="text-center">
                                <?php if(!empty($row['screenshot'])): ?>
                                    <a href="<?php echo base_url('uploads/wallet_screenshots/'.$row['screenshot']); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="fa fa-image"></i> View
                                    </a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td><small><?php echo $row['remarks']; ?></small></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css"/>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    
    // A. Custom Filter: Date Range (Targeting Column 0)
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = $('#min').val();
            var max = $('#max').val();
            var date = data[0].substring(0, 10); // Extract YYYY-MM-DD from Column 0
 
            if (
                ( min === "" && max === "" ) ||
                ( min === "" && date <= max ) ||
                ( min <= date && max === "" ) ||
                ( min <= date && date <= max )
            ) { return true; }
            return false;
        }
    );

    // B. Initialize DataTable
    var table = $('#walletTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { 
                extend: 'excel', 
                className: 'btn btn-success btn-sm mb-3',
                title: 'Wallet_Report_<?php echo date("Y-m-d"); ?>',
                exportOptions: { columns: [0, 1, 2, 3, 4, 6] } // Exclude Proof column
            },
            { 
                extend: 'pdf', 
                className: 'btn btn-danger btn-sm mb-3',
                title: 'Wallet_Report',
                exportOptions: { columns: [0, 1, 2, 3, 4, 6] }
            },
            { 
                extend: 'print', 
                className: 'btn btn-dark btn-sm mb-3',
                exportOptions: { columns: [0, 1, 2, 3, 4, 6] }
            }
        ],
        order: [[0, 'desc']], // Sort newest date first
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Global Search..."
        }
    });

    // C. Search Amount Logic (Targeting Column 2)
    $('#searchAmount').on('keyup change', function() {
        table.column(2).search(this.value).draw();
    });

    // D. Trigger Date Filter Redraw
    $('#min, #max').on('change', function() {
        table.draw();
    });

});
</script>

<style>
    /* Styling to make export buttons look cleaner */
    .dt-buttons {
        margin-bottom: 15px;
    }
    .dataTables_filter {
        float: right;
    }
    .card-header label {
        color: #4e73df;
    }
</style>