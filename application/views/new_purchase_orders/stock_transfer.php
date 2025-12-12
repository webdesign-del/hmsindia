<?php $all_method =&get_instance(); ?>
<!-- Select2 CSS and JS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
<script src="<?php echo base_url();?>assets/js/select2.min.js"></script>

<style>
    .stock-transfer-header {
        background: #f8f9fa;
        padding: 20px;
        margin: -20px -20px 20px -20px;
        border-bottom: 3px solid #007bff;
    }

    .stock-transfer-header h2 {
        color: #333;
        font-weight: bold;
        margin: 0 0 10px 0;
        font-size: 28px;
    }

    .stock-transfer-header p {
        color: #666;
        margin: 0;
        font-size: 14px;
    }

    .stock-transfer-window {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .window-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .window-title {
        font-weight: bold;
        font-size: 16px;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover {
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
    }

    .tab-navigation {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0;
        margin: 0;
    }

    .tab-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .tab-item {
        margin: 0;
    }

    .tab-link {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        color: #495057;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .tab-link:hover {
        background: #e9ecef;
        color: #007bff;
        text-decoration: none;
    }

    .tab-link.active {
        background: white;
        color: #007bff;
        border-bottom-color: #007bff;
        font-weight: 600;
    }

    .stock-transfer-section {
        padding: 20px;
    }

    .section-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        color: #007bff;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group .form-control {
        padding-right: 35px;
    }

    .input-group .clear-btn,
    .input-group .dropdown-btn {
        position: absolute;
        right: 8px;
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 2px;
        font-size: 12px;
    }

    .input-group .dropdown-btn {
        right: 25px;
    }

    .items-toolbar {
        background: #f8f9fa;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-bottom: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .toolbar-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .toolbar-btn:hover {
        background: #5a6268;
    }

    .toolbar-btn.primary {
        background: #007bff;
    }

    .toolbar-btn.primary:hover {
        background: #0056b3;
    }

    .toolbar-btn.success {
        background: #28a745;
    }

    .toolbar-btn.success:hover {
        background: #1e7e34;
    }

    .toolbar-btn.danger {
        background: #dc3545;
    }

    .toolbar-btn.danger:hover {
        background: #c82333;
    }

    .items-grid {
        border: 1px solid #dee2e6;
        border-radius: 0 0 4px 4px;
        overflow: hidden;
    }

    .grid-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    .grid-table th {
        background: #e9ecef;
        color: #495057;
        font-weight: 600;
        padding: 12px 8px;
        text-align: left;
        border: 1px solid #dee2e6;
        font-size: 13px;
    }

    .grid-table td {
        padding: 10px 8px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .grid-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .grid-table input[type="text"],
    .grid-table input[type="number"] {
        border: 1px solid #ced4da;
        border-radius: 3px;
        padding: 6px 8px;
        width: 100%;
        font-size: 13px;
    }

    .grid-table input[type="text"]:focus,
    .grid-table input[type="number"]:focus {
        border-color: #007bff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .summary-section {
        background: #f8f9fa;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .summary-label {
        font-weight: 600;
        color: #495057;
    }

    .summary-input {
        width: 120px;
        text-align: right;
        font-weight: 600;
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #004085);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 4px;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>

<div class="col-md-12" style="margin-top: 20px;">
   <div class="stock-transfer-header">
      <h2>TRANSFER STOCK FORMAT ONE LOCATION TO OTHER LOCATION</h2>
      <p>This screen is to allow user transfer stock between two location.</p>
   </div>
   <div class="stock-transfer-window">
      <!-- Window Header -->
      <div class="window-header">
         <div class="window-title">
            <i class="fa fa-cube"></i> Stock Transfers
         </div>
         <button class="close-btn" onclick="closeWindow()">
            <i class="fa fa-times"></i>
         </button>
      </div>
      
      <!-- Tab Navigation -->
      <div class="tab-navigation">
         <ul class="tab-list">
            <li class="tab-item">
               <a href="#details" class="tab-link active" onclick="switchTab('details')">Details</a>
            </li>
            <li class="tab-item">
               <a href="#others" class="tab-link" onclick="switchTab('others')">Others</a>
            </li>
            <li class="tab-item">
               <a href="#files" class="tab-link" onclick="switchTab('files')">Files</a>
            </li>
         </ul>
      </div>
      
      <!-- Tab Content -->
      <div class="tab-content">
         <!-- Details Tab -->
         <div id="details-tab" class="tab-pane active">
            <div class="stock-transfer-section">
               <div class="section-title">
                  <i class="fa fa-exchange"></i> Stock Transfer
               </div>
               
               <form method="post" action="<?php echo base_url('new_purchase_orders/save_stock_transfer'); ?>" id="stock_transfer_form">
                  
                  <!-- Location and Project Row -->
                  <div class="form-row">
                     <div class="form-group">
                        <label for="from_location">Location:</label>
                        <div class="input-group">
                           <select class="form-control" id="from_location" name="from_location" required>
                              <option value="">-- Select Location --</option>
                              <?php if (!empty($centers)): ?>
                                 <?php foreach ($centers as $center): ?>
                                    <option value="<?php echo $center['center_number']; ?>">
                                       <?php echo $center['center_name']; ?>
                                    </option>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                           </select>
                           <button type="button" class="clear-btn" onclick="clearField('from_location')">
                              <i class="fa fa-times"></i>
                           </button>
                           <button type="button" class="dropdown-btn">
                              <i class="fa fa-chevron-down"></i>
                           </button>
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <label for="from_project">Project:</label>
                        <div class="input-group">
                           <select class="form-control" id="from_project" name="from_project">
                              <option value="">-- Select Project --</option>
                              <option value="project1">Project 1</option>
                              <option value="project2">Project 2</option>
                           </select>
                           <button type="button" class="clear-btn" onclick="clearField('from_project')">
                              <i class="fa fa-times"></i>
                           </button>
                           <button type="button" class="dropdown-btn">
                              <i class="fa fa-chevron-down"></i>
                           </button>
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" 
                           placeholder="Enter transfer description">
                     </div>
                  </div>
                  
                  <!-- To Location and Project Row -->
                  <div class="form-row">
                     <div class="form-group">
                        <label for="to_location">To Location:</label>
                        <div class="input-group">
                           <select class="form-control" id="to_location" name="to_location" required>
                              <option value="">-- Select To Location --</option>
                              <?php if (!empty($centers)): ?>
                                 <?php foreach ($centers as $center): ?>
                                    <option value="<?php echo $center['center_number']; ?>">
                                       <?php echo $center['center_name']; ?>
                                    </option>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                           </select>
                           <button type="button" class="clear-btn" onclick="clearField('to_location')">
                              <i class="fa fa-times"></i>
                           </button>
                           <button type="button" class="dropdown-btn">
                              <i class="fa fa-chevron-down"></i>
                           </button>
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <label for="to_project">To Project:</label>
                        <div class="input-group">
                           <select class="form-control" id="to_project" name="to_project">
                              <option value="">-- Select To Project --</option>
                              <option value="project1">Project 1</option>
                              <option value="project2">Project 2</option>
                           </select>
                           <button type="button" class="clear-btn" onclick="clearField('to_project')">
                              <i class="fa fa-times"></i>
                           </button>
                           <button type="button" class="dropdown-btn">
                              <i class="fa fa-chevron-down"></i>
                           </button>
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <label for="doc_date">Doc Date:</label>
                        <div class="input-group">
                           <input type="text" class="form-control" id="doc_date" name="doc_date" 
                              value="15/06/2015" readonly>
                           <button type="button" class="dropdown-btn" onclick="openDatePicker('doc_date')">
                              <i class="fa fa-calendar"></i>
                           </button>
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <label for="transfer_number">Transfer #:</label>
                        <div class="input-group">
                           <input type="text" class="form-control" id="transfer_number" name="transfer_number" 
                              value="STT1506/001" readonly>
                           <button type="button" class="dropdown-btn">
                              <i class="fa fa-chevron-down"></i>
                           </button>
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <label for="ref_number">Ref #:</label>
                        <input type="text" class="form-control" id="ref_number" name="ref_number" 
                           placeholder="Enter reference number">
                     </div>
                  </div>
                  
                  <!-- Items Grid -->
                  <div class="items-toolbar">
                     <button type="button" class="toolbar-btn primary" onclick="addStockItem()">
                        <i class="fa fa-plus"></i> Add Row
                     </button>
                     <button type="button" class="toolbar-btn danger" onclick="deleteSelectedRows()">
                        <i class="fa fa-trash"></i> Delete Row
                     </button>
                     <button type="button" class="toolbar-btn success" onclick="scanBarcode()">
                        <i class="fa fa-barcode"></i> Barcode
                     </button>
                     <div class="dropdown">
                        <button type="button" class="toolbar-btn" onclick="toggleDiagnostic()">
                            <i class="fa fa-cog"></i> Diagnostic
                            <i class="fa fa-chevron-down"></i>
                        </button>
                     </div>
                  </div>
                  
                  <div class="items-grid">
                     <table class="grid-table" id="stock_items_table">
                        <thead>
                           <tr>
                              <th width="8%"># Stock</th>
                              <th width="25%">Description</th>
                              <th width="12%">Qty</th>
                              <th width="10%">UOM</th>
                              <th width="15%">Price</th>
                              <th width="15%">Amount</th>
                              <th width="5%">Select</th>
                           </tr>
                        </thead>
                        <tbody id="stock_items_tbody">
                           <!-- Items will be added dynamically -->
                        </tbody>
                     </table>
                  </div>
                  
                  <!-- Summary Section -->
                  <div class="summary-section">
                     <div class="summary-row">
                        <span class="summary-label">SUM=</span>
                        <input type="text" class="form-control summary-input" id="sum_amount" name="sum_amount" 
                           value="0.00" readonly>
                     </div>
                     <div class="summary-row">
                        <span class="summary-label">Total Amt:</span>
                        <input type="text" class="form-control summary-input" id="total_amount" name="total_amount" 
                           value="0.00" readonly>
                     </div>
                  </div>
                  
                  <!-- Action Buttons -->
                  <div class="text-center" style="margin-top: 30px;">
                     <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Save Stock Transfer
                     </button>
                     <a href="<?php echo base_url('new_purchase_orders'); ?>" class="btn btn-secondary btn-lg">
                        <i class="fa fa-times"></i> Cancel
                     </a>
                  </div>
               </form>
            </div>
         </div>
         
         <!-- Others Tab -->
         <div id="others-tab" class="tab-pane" style="display: none;">
            <div class="stock-transfer-section">
               <h4>Others Tab Content</h4>
               <p>Additional information and settings for stock transfer.</p>
            </div>
         </div>
         
         <!-- Files Tab -->
         <div id="files-tab" class="tab-pane" style="display: none;">
            <div class="stock-transfer-section">
               <h4>Files Tab Content</h4>
               <p>File attachments and documents for stock transfer.</p>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
var stockItemCounter = 0;

function switchTab(tabName) {    
    // Hide all tab panes
    $('.tab-pane').hide();
    $('.tab-link').removeClass('active');
    
    // Show selected tab pane
    $('#' + tabName + '-tab').show();
    $('a[href="#' + tabName + '"]').addClass('active');
}

function clearField(fieldId) {
    $('#' + fieldId).val('').trigger('change');
}

function openDatePicker(fieldId) {
    $('#' + fieldId).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function(dateText) {
            $(this).val(dateText);
        }
    }).datepicker('show');
}

function addStockItem() {
    stockItemCounter++;
    var newRow = `
        <tr class="stock-item-row" id="stock_row_${stockItemCounter}">
            <td>
                <input type="text" class="form-control" name="stock_number_${stockItemCounter}" 
                    id="stock_number_${stockItemCounter}" placeholder="Stock #" style="width:100% !important;padding: 0 !important;">
            </td>
            <td>
                <input type="text" class="form-control" name="description_${stockItemCounter}" 
                    id="description_${stockItemCounter}" placeholder="Item Description" style="width:100% !important;padding: 0 !important;">
            </td>
            <td>
                <input type="number" class="form-control" name="quantity_${stockItemCounter}" 
                    id="quantity_${stockItemCounter}" value="1" step="0.001" 
                    onchange="updateAmount(${stockItemCounter})" style="width:100% !important;padding: 0 !important;">
            </td>
            <td>
                <select class="form-control" name="uom_${stockItemCounter}" id="uom_${stockItemCounter}" style="width:100% !important;padding: 0 !important;">
                    <option value="PCS">PCS</option>
                    <option value="KG">KG</option>
                    <option value="LTR">LTR</option>
                    <option value="BOX">BOX</option>
                    <option value="GM">GM</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control" name="price_${stockItemCounter}" 
                    id="price_${stockItemCounter}" value="0.00" step="0.01" 
                    onchange="updateAmount(${stockItemCounter})" style="width:100% !important;padding: 0 !important;">
            </td>
            <td>
                <input type="number" class="form-control" name="amount_${stockItemCounter}" 
                    id="amount_${stockItemCounter}" value="0.00" step="0.01" readonly style="width:100% !important;padding: 0 !important;">
            </td>
            <td class="text-center">
                <input type="checkbox" name="select_item_${stockItemCounter}" 
                    id="select_item_${stockItemCounter}" style="width:100% !important;padding: 0 !important;left: 0px !important;opacity: 1 !important;position: unset !important;">
            </td>
        </tr>
    `;
    
    $('#stock_items_tbody').append(newRow);
    updateRowNumbers();
}

function deleteSelectedRows() {
    $('input[name^="select_item_"]:checked').each(function() {
        $(this).closest('tr').remove();
    });
    updateRowNumbers();
    calculateTotals();
}

function updateAmount(rowId) {
    var quantity = parseFloat($('#quantity_' + rowId).val()) || 0;
    var price = parseFloat($('#price_' + rowId).val()) || 0;
    var amount = quantity * price;
    
    $('#amount_' + rowId).val(amount.toFixed(2));
    calculateTotals();
}

function calculateTotals() {
    var sum = 0;
    $('input[name^="amount_"]').each(function() {
        sum += parseFloat($(this).val()) || 0;
    });
    
    $('#sum_amount').val(sum.toFixed(2));
    $('#total_amount').val(sum.toFixed(2));
}

function updateRowNumbers() {
    $('.stock-item-row').each(function(index) {
        $(this).find('td:first input').attr('placeholder', 'Stock #' + (index + 1));
    });
}

function scanBarcode() {
    alert('Barcode scanner functionality would be implemented here');
}

function toggleDiagnostic() {
    alert('Diagnostic options would be shown here');
}

function closeWindow() {
    if (confirm('Are you sure you want to close this window? Any unsaved changes will be lost.')) {
        window.location.href = '<?php echo base_url('new_purchase_orders'); ?>';
    }
}

$(document).ready(function() {
    // Initialize date picker for doc_date
    $('#doc_date').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
    
    // Initialize form validation
    $('#stock_transfer_form').on('submit', function(e) {
        var isValid = true;
        
        // Check if at least one item is added
        if ($('.stock-item-row').length === 0) {
            alert('Please add at least one item to transfer.');
            isValid = false;
        }
        
        // Check if from and to locations are different
        if ($('#from_location').val() === $('#to_location').val()) {
            alert('From location and To location must be different.');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Add initial row
    addStockItem();
});
</script>
