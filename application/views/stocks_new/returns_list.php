<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-list"></i> Medicine Returns List
                    <small>View all medicine returns</small>
                </h1>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bolt"></i> Quick Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/medicine_returns'); ?>" class="btn btn-primary">
                            <i class="fa fa-undo"></i> New Return
                        </a>
                        <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-info">
                            <i class="fa fa-shopping-cart"></i> Sales
                        </a>
                        <?php if(!empty($returns)): ?>
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-download"></i> Export <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo base_url('stocks_new/export_returns_list?format=excel'); ?>" target="_blank">
                                            <i class="fa fa-file-excel-o"></i> Export to Excel (CSV)
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('stocks_new/export_returns_list?format=pdf'); ?>" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i> Print as PDF
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Returns List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Returns History
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($returns)): ?>
                            <!-- Search Bar -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input type="text" id="searchReturns" class="form-control" placeholder="Search by return number, patient name, receipt number, center, medicine name, or reason...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="clearSearch">
                                                <i class="fa fa-times"></i> Clear
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="returnsTable">
                                    <thead>
                                        <tr>
                                            <th>Return #</th>
                                            <th>Patient</th>
                                            <th>Receipt #</th>
                                            <th>Center</th>
                                            <th>Department</th>
                                            <!-- <th>Medicine Names</th> -->
                                            <th>Return Date</th>
                                            <th>Reason</th>
                                            <th>Items</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($returns as $return): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($return->return_number); ?></strong></td>
                                                <td><?php echo htmlspecialchars($return->patient_name); ?></td>
                                                <td><?php echo htmlspecialchars($return->receipt_number); ?></td>
                                                <td><?php echo htmlspecialchars($return->center_name ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($return->department ?? 'N/A'); ?></td>
                                                <!-- <td>
                                                    <small><?php echo htmlspecialchars($return->medicine_names ?? 'N/A'); ?></small>
                                                </td> -->
                                                <td><?php echo date('M d, Y', strtotime($return->return_date)); ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo htmlspecialchars($return->return_reason); ?></span>
                                                </td>
                                                <td><?php echo $return->total_items ?? '0'; ?></td>
                                                <td>₹<?php echo number_format($return->total_return_amount ?? 0, 2); ?></td>
                                                <td>
                                                    <span class="badge badge-success">COMPLETED</span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('stocks_new/view_return/' . $return->id); ?>" class="btn btn-info btn-sm">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div id="noResults" style="display: none;" class="text-center text-muted">
                                <i class="fa fa-info-circle fa-2x"></i><br>
                                No returns found matching your search criteria.
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fa fa-info-circle fa-2x"></i><br>
                                No returns found. 
                                <a href="<?php echo base_url('stocks_new/medicine_returns'); ?>">Create a new return</a>.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            var $searchInput = $('#searchReturns');
            var $table = $('#returnsTable');
            var $rows = $table.find('tbody tr');
            var $noResults = $('#noResults');
            
            function filterTable() {
                var searchText = $searchInput.val().toLowerCase();
                var visibleCount = 0;
                
                if (searchText === '') {
                    $rows.show();
                    $noResults.hide();
                    return;
                }
                
                $rows.each(function() {
                    var $row = $(this);
                    var rowText = $row.text().toLowerCase();
                    
                    if (rowText.indexOf(searchText) !== -1) {
                        $row.show();
                        visibleCount++;
                    } else {
                        $row.hide();
                    }
                });
                
                if (visibleCount === 0) {
                    $table.hide();
                    $noResults.show();
                } else {
                    $table.show();
                    $noResults.hide();
                }
            }
            
            $searchInput.on('keyup', filterTable);
            
            $('#clearSearch').on('click', function() {
                $searchInput.val('');
                filterTable();
                $searchInput.focus();
            });
        });
        </script>
    </div>
</div>


