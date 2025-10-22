<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-undo"></i> Add New Vendor Return
                <small>Process a vendor return for defective or expired items</small>
            </h1>
        </div>
    </div>
    
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('stocks_new/vendor_returns'); ?>">Vendor Returns</a></li>
                <li class="active">Add Return</li>
            </ol>
        </div>
    </div>
    
    <!-- Add Vendor Return Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-undo"></i> Vendor Return Information
                </div>
                <div class="panel-body">
                    <?php if(validation_errors()): ?>
                        <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo base_url('stocks_new/add_vendor_return'); ?>" method="post" class="form-horizontal">
                        <input type="hidden" name="action" value="add_vendor_return">
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Vendor *</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="vendor_id" required>
                                            <option value="">Select Vendor</option>
                                            <?php if(isset($vendors) && !empty($vendors)): ?>
                                                <?php foreach($vendors as $vendor): ?>
                                                    <option value="<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>" <?php echo set_select('vendor_id', isset($vendor->ID) ? $vendor->ID : $vendor->id); ?>>
                                                        <?php echo isset($vendor->vendor_name) ? $vendor->vendor_name : (isset($vendor->name) ? $vendor->name : 'N/A'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Return Date *</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" name="return_date" value="<?php echo set_value('return_date', date('Y-m-d')); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Return Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="return_number" value="<?php echo set_value('return_number', 'RET' . date('Ymd') . rand(1000, 9999)); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Center</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="center_id">
                                            <option value="1">Central Warehouse</option>
                                            <!-- Add more centers as needed -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Return Reason -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Return Reason *</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="return_reason" rows="3" placeholder="Enter reason for return (e.g., defective items, expired products, wrong delivery)" required><?php echo set_value('return_reason'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Items to Return -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <i class="fa fa-list"></i> Items to Return
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="returnItemsTable">
                                                <thead>
                                                    <tr>
                                                        <th>Medicine</th>
                                                        <th>Batch Number</th>
                                                        <th>Expiry Date</th>
                                                        <th>Available Qty</th>
                                                        <th>Return Qty</th>
                                                        <th>Unit Price</th>
                                                        <th>Total Value</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="returnItemsBody">
                                                    <!-- Items will be added dynamically -->
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-success" onclick="addReturnItem()">
                                                    <i class="fa fa-plus"></i> Add Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Total Items</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="total_items" id="totalItems" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Total Quantity</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="total_quantity" id="totalQuantity" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Total Value</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="total_value" id="totalValue" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="status">
                                            <option value="PENDING" selected>Pending</option>
                                            <option value="APPROVED">Approved</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Remarks -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="remarks" rows="3" placeholder="Additional remarks or notes"><?php echo set_value('remarks'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Process Return
                                        </button>
                                        <a href="<?php echo base_url('stocks_new/vendor_returns'); ?>" class="btn btn-default">
                                            <i class="fa fa-arrow-left"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Batches Modal -->
    <div class="modal fade" id="availableBatchesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Items to Return</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="availableBatchesTable">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Expiry Date</th>
                                    <th>Available Qty</th>
                                    <th>Unit Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($available_batches) && !empty($available_batches)): ?>
                                    <?php foreach($available_batches as $batch): ?>
                                        <tr>
                                            <td><input type="checkbox" class="batch-checkbox" value="<?php echo $batch->batch_id; ?>"></td>
                                            <td><?php echo $batch->medicine_name; ?></td>
                                            <td><?php echo $batch->batch_number; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($batch->expiry_date)); ?></td>
                                            <td><?php echo $batch->quantity_remaining; ?></td>
                                            <td>₹<?php echo number_format($batch->selling_price, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addSelectedItems()">Add Selected Items</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let returnItemCounter = 0;
    
    function addReturnItem() {
        $('#availableBatchesModal').modal('show');
    }
    
    function addSelectedItems() {
        $('.batch-checkbox:checked').each(function() {
            const row = $(this).closest('tr');
            const batchId = $(this).val();
            const medicineName = row.find('td:eq(1)').text();
            const batchNumber = row.find('td:eq(2)').text();
            const expiryDate = row.find('td:eq(3)').text();
            const availableQty = parseInt(row.find('td:eq(4)').text());
            const unitPrice = parseFloat(row.find('td:eq(5)').text().replace('₹', '').replace(',', ''));
            
            addReturnItemRow(batchId, medicineName, batchNumber, expiryDate, availableQty, unitPrice);
        });
        
        $('#availableBatchesModal').modal('hide');
        $('.batch-checkbox').prop('checked', false);
    }
    
    function addReturnItemRow(batchId, medicineName, batchNumber, expiryDate, availableQty, unitPrice) {
        returnItemCounter++;
        const row = `
            <tr id="returnItem${returnItemCounter}">
                <td>${medicineName}</td>
                <td>${batchNumber}</td>
                <td>${expiryDate}</td>
                <td>${availableQty}</td>
                <td>
                    <input type="number" class="form-control return-qty" min="1" max="${availableQty}" value="1" 
                           onchange="calculateRowTotal(${returnItemCounter}, ${unitPrice})">
                    <input type="hidden" name="return_items[${returnItemCounter}][batch_id]" value="${batchId}">
                </td>
                <td>₹${unitPrice.toFixed(2)}</td>
                <td>
                    <span class="row-total">₹${unitPrice.toFixed(2)}</span>
                    <input type="hidden" name="return_items[${returnItemCounter}][unit_price]" value="${unitPrice}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeReturnItem(${returnItemCounter})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#returnItemsBody').append(row);
        updateTotals();
    }
    
    function removeReturnItem(counter) {
        $(`#returnItem${counter}`).remove();
        updateTotals();
    }
    
    function calculateRowTotal(counter, unitPrice) {
        const qty = parseInt($(`#returnItem${counter} .return-qty`).val()) || 0;
        const total = qty * unitPrice;
        $(`#returnItem${counter} .row-total`).text('₹' + total.toFixed(2));
        updateTotals();
    }
    
    function updateTotals() {
        let totalItems = 0;
        let totalQuantity = 0;
        let totalValue = 0;
        
        $('#returnItemsBody tr').each(function() {
            totalItems++;
            const qty = parseInt($(this).find('.return-qty').val()) || 0;
            const value = parseFloat($(this).find('.row-total').text().replace('₹', '').replace(',', '')) || 0;
            
            totalQuantity += qty;
            totalValue += value;
        });
        
        $('#totalItems').val(totalItems);
        $('#totalQuantity').val(totalQuantity);
        $('#totalValue').val(totalValue.toFixed(2));
    }
    
    // Initialize DataTable for available batches
    $(document).ready(function() {
        $('#availableBatchesTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10
        });
    });
    </script>
