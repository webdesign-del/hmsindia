<?php  $all_method =&get_instance(); ?>
<div class="col-md-12">
  <!-- Modern Page Header -->
  <div class="page-header-modern">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="page-title-section">
            <h1 class="page-title">
              <i class="fa fa-database"></i> Master Central Item Sheet
            </h1>
            <p class="page-subtitle">Comprehensive inventory management for all stock items</p>
          </div>
        </div>
        <!-- <div class="col-md-4 text-right">
          <div class="header-actions">
            <a href="<?php echo base_url('stocks/stocks?export-billing=1&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&generic_name=' . urlencode($generic_name) . '&item_name=' . urlencode($item_name) . '&batch_number=' . urlencode($batch_number) . '&item_number=' . urlencode($item_number)); ?>" class="btn btn-export">
              <i class="fa fa-file-excel-o"></i> Export Medicine
            </a>
          </div>
        </div> -->
      </div>
    </div>
  </div>

  <!-- Advanced Search Panel -->
  <div class="search-panel">
    <div class="search-header">
      <h3 class="search-title">
        <i class="fa fa-search"></i> Search & Filter Options
      </h3>
      <button class="btn btn-link toggle-filters" data-toggle="collapse" data-target="#searchFilters">
        <i class="fa fa-chevron-down"></i>
      </button>
    </div>
    <div id="searchFilters" class="search-content collapse in">
      <form action="<?php echo base_url().'stocks/stocks'; ?>" method="get" class="search-form">
        <div class="search-grid">
          <!-- Date Range Section -->
          <div class="search-section">
            <h4 class="section-title">Date Range</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Expiry Start Date</label>
                <div class="input-wrapper">
                  <!-- <i class="fa fa-calendar input-icon"></i> -->
                  <input type="text" class="particular_date_filter form-control modern-input" 
                         id="start_date" name="start_date" value="<?php echo $start_date;?>" 
                         placeholder="Select start date" />
                </div>
              </div>
              <div class="form-group">
                <label>Expiry End Date</label>
                <div class="input-wrapper">
                  <!-- <i class="fa fa-calendar input-icon"></i> -->
                  <input type="text" class="particular_date_filter form-control modern-input" 
                         id="end_date" name="end_date" value="<?php echo $end_date;?>" 
                         placeholder="Select end date" />
                </div>
              </div>
            </div>
          </div>

          <!-- Product Information Section -->
          <div class="search-section">
            <h4 class="section-title">Product Information</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Generic Name</label>
                <div class="input-wrapper">
                  <!-- <i class="fa fa-tag input-icon"></i> -->
                  <input type="text" class="form-control modern-input" 
                         id="generic_name" name="generic_name" value="<?php echo $generic_name;?>" 
                         placeholder="Enter generic name" />
                </div>
              </div>
              <div class="form-group">
                <label>Medicine Name</label>
                <div class="input-wrapper">
                  <!-- <i class="fa fa-medkit input-icon"></i> -->
                  <input type="text" class="form-control modern-input" 
                         id="item_name" name="item_name" value="<?php echo $item_name;?>" 
                         placeholder="Enter medicine name" />
                </div>
              </div>
            </div>
          </div>

          <!-- Identification Section -->
          <div class="search-section">
            <h4 class="section-title">Identification</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Batch Number</label>
                <div class="input-wrapper">
                  <!-- <i class="fa fa-barcode input-icon"></i> -->
                  <input type="text" class="form-control modern-input" 
                         id="batch_number" name="batch_number" value="<?php echo $batch_number;?>" 
                         placeholder="Enter batch number" />
                </div>
              </div>
              <div class="form-group">
                <label>Item Number</label>
                <div class="input-wrapper">
                  <!-- <i class="fa fa-hashtag input-icon"></i> -->
                  <input type="text" class="form-control modern-input" 
                         id="item_number" name="item_number" value="<?php echo $item_number;?>" 
                         placeholder="Enter item number" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="search-actions">
          <div class="action-buttons-group">
            <button name="btnsearch" id="btnsearch" type="submit" class="btn btn-search-modern">
              <i class="fa fa-search"></i>
              <span>Search</span>
            </button>
            <a href="<?php echo base_url().'stocks/stocks'; ?>" class="btn btn-reset-modern">
              <i class="fa fa-refresh"></i>
              <span>Reset</span>
            </a>
            <a href="<?php echo base_url('stocks/stocks?export-billing=1&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&generic_name=' . urlencode($generic_name) . '&item_name=' . urlencode($item_name) . '&batch_number=' . urlencode($batch_number) . '&item_number=' . urlencode($item_number)); ?>" class="btn btn-export">
              <i class="fa fa-file-excel-o"></i> Export Medicine
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Modern Data Table -->
  <div class="data-table-container">
    <div class="table-header">
      <div class="table-title">
        <h3><i class="fa fa-table"></i> Master Item Data</h3>
        <span class="item-count"><?php echo count($investigate_result); ?> items found</span>
      </div>
      <div class="table-actions">
        <div class="view-options">
          <button class="btn btn-sm btn-outline" id="toggleColumns">
            <i class="fa fa-columns"></i> Columns
          </button>
        </div>
      </div>
    </div>
    
    <div class="table-wrapper">
      <table class="table modern-table" id="centre_stock_list1">
        <thead>
          <tr>
             <th><input type="checkbox" id="select_all_items" style="left: 0px !important;opacity: 1 !important;position: unset !important;" /></th>
            <th class="col-generic">Generic Name</th>
            <th class="col-company">Company</th>
            <th class="col-item-id">Item ID</th>
            <th class="col-product-id">Product ID</th>
            <th class="col-item-name">Item Name</th>
            <th class="col-vendor">Vendor</th>
            <th class="col-brand">Brand</th>
            <th class="col-batch">Batch No</th>
            <th class="col-category">Category</th>
            <th class="col-price-wo-gst">Price w/o GST</th>
            <th class="col-mrp">MRP</th>
            <th class="col-gst-rate">GST Rate</th>
            <th class="col-gst-amount">GST Amount</th>
            <th class="col-price-w-gst">Price w/ GST</th>
            <th class="col-pack-size">Pack Size</th>
            <th class="col-quantity">Quantity</th>
            <th class="col-expiry">Expiry</th>
            <th class="col-safety-stock">Safety Stock</th>
            <!-- <th class="col-medicine-type">Medicine Type</th> -->
            <th class="col-status">Status</th>
            <th class="col-actions">Actions</th>
          </tr>
        </thead>
        <tbody id="table_content">
          <?php $count=1; foreach($investigate_result as $vl){
            if (!empty($vl['gstdivision']) && $vl['gstdivision'] != 0) {
              $gstamount = $vl['vendor_price'] / $vl['gstdivision'];
            } else {
              $gstamount = 0;
            }
          ?>			 
            <tr class="data-row">
              <td><input type="checkbox" class="row-select" value="<?php echo $vl['item_number']?>" style="left: 0px !important;opacity: 1 !important;position: unset !important;"/></td>
              <td class="col-generic">
                <div class="generic-name"><?php echo $vl['generic_name']?></div>
              </td>
              <td class="col-company">
                <div class="company-name"><?php echo $vl['company'];?></div>
              </td>
              <td class="col-item-id">
                <a href="<?php echo base_url(); ?>stocks/details/<?php echo $vl['item_number']?>" class="item-link">
                  <?php echo $vl['item_number']?>
                </a>
              </td>
              <td class="col-product-id">
                <span class="product-id"><?php echo $vl['product_id']?></span>
              </td>
              <td class="col-item-name">
                <div class="item-name"><?php echo $vl['item_name']?></div>
              </td>
              <td class="col-vendor">
                <div class="vendor-name"><?php echo $all_method->get_vendor_name($vl['vendor_number']);?></div>
              </td>
              <td class="col-brand">
                <span class="brand-tag"><?php echo $all_method->get_brand_name($vl['brand_name']);?></span>
              </td>
              <td class="col-batch">
                <span class="batch-number"><?php echo $vl['batch_number']?></span>
              </td>
              <td class="col-category">
                <div class="category-name"><?php $category_name = $all_method->get_category_name($vl['category']); echo $category_name; ?></div>
              </td>
              <td class="col-price-wo-gst">
                <div class="price price-wo-gst">₹<?php echo round($gstamount,2) ?></div>
              </td>
              <td class="col-mrp">
                <div class="price mrp-price">₹<?php echo $vl['mrp']; ?></div>
              </td>
              <td class="col-gst-rate">
                <span class="gst-rate"><?php echo $vl['gstrate']; ?>%</span>
              </td>
              <td class="col-gst-amount">
                <div class="price gst-amount">₹<?php echo round($vl['vendor_price']- $gstamount,2) ?></div>
              </td>
              <td class="col-price-w-gst">
                <div class="price price-w-gst">₹<?php echo $vl['vendor_price']; ?></div>
              </td>
              <td class="col-pack-size">
                <div class="pack-size"><?php echo $vl['pack_size']; ?></div>
              </td> 
              <td class="col-quantity">
                <span class="quantity-badge"><?php echo $vl['quantity']?></span>
              </td>
              <?php
              $expiryDate = new DateTime($vl['expiry']);
              $today = new DateTime();
              $twoMonthsFromNow = (clone $today)->modify('+2 months');
              $expiryClass = '';
              if ($expiryDate <= $twoMonthsFromNow) {
                $expiryClass = 'expiry-warning';
              } else {
                $expiryClass = 'expiry-safe';
              }
              ?>
              <td class="col-expiry">
                <span class="expiry-date <?php echo $expiryClass; ?>"><?php echo $vl['expiry']; ?></span>
              </td>
              <td class="col-safety-stock">
                <span class="safety-stock"><?php echo $vl['safety_stock']; ?></span>
              </td>
              <!-- <td class="col-medicine-type">
                <div class="medicine-type-container">
                  <label class="medicine-type-toggle">
                    <input type="checkbox" class="medicine-type-checkbox" 
                           data-item-number="<?php echo $vl['item_number']; ?>"
                           <?php echo (isset($vl['medicine_type']) && $vl['medicine_type'] == 'ipd') ? 'checked' : ''; ?>>
                    <span class="toggle-label">
                      <span class="toggle-text"><?php echo (isset($vl['medicine_type']) && $vl['medicine_type'] == 'ipd') ? 'IPD' : 'OPD'; ?></span>
                    </span>
                  </label>
                </div>
              </td> -->
              <td class="col-status">
                <?php if($vl['status'] == '1'){ ?>
                  <span class="status-badge status-active">Active</span>
                <?php } else { ?>
                  <span class="status-badge status-inactive">Inactive</span>
                <?php } ?>
                <div class="manager-name"><?php echo $_SESSION['logged_stock_manager']['name']; ?></div>
              </td>
              <td class="col-actions">
                <?php if (isset($_SESSION['logged_central_stock_manager']['username']) && $_SESSION['logged_central_stock_manager']['username'] === "sahil.kumar@indiaivf.in") { ?>
                  <div class="action-buttons">
                    <a href="<?php echo base_url();?>stocks/edit?item_number=<?php echo $vl['item_number']?>" 
                       class="action-btn edit-btn" title="Edit">
                      <i class="fa fa-edit"></i>
                    </a>
                  </div>
                <?php } ?>
              </td>
            </tr>
          <?php $count++;} ?>
        </tbody>
      </table>
    </div>
    <div class="bulk-actions" style="margin-top:10px; display:flex; gap:10px; justify-content:flex-start; flex-wrap: wrap;">
      <button type="button" id="bulk_activate" class="btn btn-search-modern" style="min-width:unset; padding:8px 16px;">
        <i class="fa fa-check"></i> Activate Selected
      </button>
      <button type="button" id="bulk_deactivate" class="btn btn-reset-modern" style="min-width:unset; padding:8px 16px;">
        <i class="fa fa-ban"></i> Deactivate Selected
      </button>
      <!-- <button type="button" id="bulk_set_ipd" class="btn btn-success" style="min-width:unset; padding:8px 16px;">
        <i class="fa fa-hospital-o"></i> Set Selected to IPD
      </button>
      <button type="button" id="bulk_set_opd" class="btn btn-danger" style="min-width:unset; padding:8px 16px;">
        <i class="fa fa-user-md"></i> Set Selected to OPD
      </button> -->
    </div>
    
    <!-- Modern Pagination -->
    <div class="pagination-container-modern">
      <div class="pagination-info">
        <span class="pagination-text">Showing results</span>
      </div>
      <div class="pagination-controls">
        <?php echo $links; ?>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
  console.log('DOM ready, jQuery version:', $.fn.jquery);
  console.log('Select all checkbox exists:', $('#select_all_items').length);
  console.log('Bulk activate button exists:', $('#bulk_activate').length);
  console.log('Bulk deactivate button exists:', $('#bulk_deactivate').length);
  console.log('Row select checkboxes exist:', $('.row-select').length);
  
  $(".particular_date_filter").datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    onSelect: function(dateStr) {
      $('#loader_div').hide();				
      var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
      var data = {appointment_date:startDate, type:'particular_date_filter'};
    }
  });
  
  // Select all toggle
  $('#select_all_items').on('change', function(){
    var checked = $(this).is(':checked');
    console.log('Select all changed:', checked);
    $('#table_content .row-select').prop('checked', checked);
    console.log('Updated checkboxes count:', $('#table_content .row-select:checked').length);
  });

  function collectSelectedItems(){
    var items = [];
    $('#table_content .row-select:checked').each(function(){
      items.push($(this).val());
    });
    console.log('Collected selected items:', items);
    return items;
  }

  function bulkUpdateStatus(statusValue){
    var selected = collectSelectedItems();
    if(selected.length === 0){
      alert('Please select at least one item.');
      return;
    }
    
    console.log('Updating status to:', statusValue, 'for items:', selected);
    
    // First try with dataType: 'text' to avoid decoding issues
    $.ajax({
      url: '<?php echo base_url(); ?>stocks/bulk_status',
      type: 'POST',
      dataType: 'text',
      data: { item_numbers: selected, status: String(statusValue) },
      cache: false,
      processData: true,
      headers: {
        'Accept': 'application/json, text/plain, */*',
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      success: function(responseText){
        console.log('Raw response received:', responseText);
        
        try {
          var resp = JSON.parse(responseText);
          console.log('Parsed JSON response:', resp);
          
          if(resp && resp.status == 1){
            alert('Successfully updated ' + resp.updated + ' items.');
            location.reload();
          }else{
            alert('Failed to update: ' + (resp.message || 'Unknown error'));
          }
        } catch(e) {
          console.error('Failed to parse JSON:', e);
          console.log('Response is not valid JSON:', responseText);
          alert('Server returned invalid response: ' + responseText);
        }
      },
      error: function(xhr, status, error){
        console.error('AJAX Error:', xhr.responseText);
        console.error('Status:', status, 'Error:', error);
        console.error('Response headers:', xhr.getAllResponseHeaders());
        
        // Try to parse response as text if JSON fails
        var responseText = xhr.responseText;
        try {
          var jsonResponse = JSON.parse(responseText);
          console.log('Parsed JSON response from error:', jsonResponse);
          if(jsonResponse && jsonResponse.status == 1){
            alert('Successfully updated ' + jsonResponse.updated + ' items.');
            location.reload();
            return;
          }
        } catch(e) {
          console.log('Response is not valid JSON:', responseText);
        }
        
        alert('Request failed: ' + error + '\nStatus: ' + status + '\nResponse: ' + responseText);
      }
    });
  }

  $('#bulk_activate').on('click', function(){ 
    console.log('Activate button clicked');
    bulkUpdateStatus(1); 
  });
  $('#bulk_deactivate').on('click', function(){ 
    console.log('Deactivate button clicked');
    bulkUpdateStatus(0); 
  });

  // Medicine type toggle functionality
  $('.medicine-type-checkbox').on('change', function(){
    var itemNumber = $(this).data('item-number');
    var medicineType = $(this).is(':checked') ? 'ipd' : 'opd';
    var toggleText = $(this).siblings('.toggle-label').find('.toggle-text');
    
    console.log('Medicine type changed for item:', itemNumber, 'to:', medicineType);
    
    // Update the toggle text immediately for better UX
    toggleText.text(medicineType.toUpperCase());
    
    // Send AJAX request to update medicine type
    $.ajax({
      url: '<?php echo base_url(); ?>stocks/update_medicine_type',
      type: 'POST',
      dataType: 'json',
      data: { 
        item_number: itemNumber, 
        medicine_type: medicineType 
      },
      success: function(response){
        if(response.status == 1){
          console.log('Medicine type updated successfully');
          // Optional: Show success message
          // alert('Medicine type updated successfully');
        } else {
          console.error('Failed to update medicine type:', response.message);
          // Revert the toggle if update failed
          $(this).prop('checked', !$(this).prop('checked'));
          toggleText.text($(this).is(':checked') ? 'IPD' : 'OPD');
          alert('Failed to update medicine type: ' + response.message);
        }
      },
      error: function(xhr, status, error){
        console.error('AJAX Error updating medicine type:', error);
        // Revert the toggle if update failed
        $(this).prop('checked', !$(this).prop('checked'));
        toggleText.text($(this).is(':checked') ? 'IPD' : 'OPD');
        alert('Request failed: ' + error);
      }
    });
  });

  // Bulk medicine type update functionality
  function bulkUpdateMedicineType(medicineType){
    var selected = collectSelectedItems();
    if(selected.length === 0){
      alert('Please select at least one item.');
      return;
    }
      
    $.ajax({
      url: '<?php echo base_url(); ?>stocks/bulk_update_medicine_type',
      type: 'POST',
      dataType: 'json',
      data: { 
        item_numbers: selected, 
        medicine_type: medicineType 
      },
      success: function(response){
        console.log('Bulk medicine type update response:', response);
        
        if(response && response.status == 1){
          alert('Successfully updated ' + response.updated + ' items to ' + medicineType.toUpperCase() + '.');
          location.reload();
        } else {
          alert('Failed to update: ' + (response.message || 'Unknown error'));
        }
      },
      error: function(xhr, status, error){
        console.error('AJAX Error:', xhr.responseText);
        alert('Request failed: ' + error);
      }
    });
  }

  $('#bulk_set_ipd').on('click', function(){ 
    console.log('Bulk set IPD button clicked');
    bulkUpdateMedicineType('ipd'); 
  });
  
  $('#bulk_set_opd').on('click', function(){ 
    console.log('Bulk set OPD button clicked');
    bulkUpdateMedicineType('opd'); 
  });
});
</script>

<style>
/* Modern Master Central Item Sheet Interface */

/* Page Header */
.page-header-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 30px 0;
  margin: -20px -20px 30px -20px;
  border-radius: 0;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.page-title-section h1 {
  margin: 0;
  font-size: 28px;
  font-weight: 300;
  display: flex;
  align-items: center;
  gap: 10px;
}

.page-subtitle {
  margin: 5px 0 0 0;
  opacity: 0.9;
  font-size: 14px;
}

.header-actions .btn-export {
  background: rgba(255,255,255,0.2);
  border: 1px solid rgba(255,255,255,0.3);
  color: white;
  padding: 10px 20px;
  border-radius: 25px;
  transition: all 0.3s ease;
}

.header-actions .btn-export:hover {
  background: rgba(255,255,255,0.3);
  transform: translateY(-2px);
}

/* Search Panel */
.search-panel {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.08);
  margin-bottom: 30px;
  overflow: hidden;
}

.search-header {
  background: #f8f9fa;
  padding: 20px 25px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.search-title {
  margin: 0;
  font-size: 18px;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 8px;
}

.toggle-filters {
  color: #6c757d;
  font-size: 16px;
  padding: 5px;
}

.search-content {
  padding: 25px;
}

.search-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 25px;
  margin-bottom: 25px;
}

.search-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  padding: 25px;
  border-radius: 12px;
  border: 1px solid #e9ecef;
  border-left: 4px solid #667eea;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.search-section:hover {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transform: translateY(-2px);
}

.section-title {
  font-size: 14px;
  font-weight: 600;
  color: #495057;
  margin: 0 0 15px 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-group {
  margin-bottom: 0;
}

.form-group label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: #495057;
  margin-bottom: 5px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.input-wrapper {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
  font-size: 14px;
  z-index: 2;
}

.modern-input {
  width: 100%;
  height: 45px;
  padding: 0 15px 0 40px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  background: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.modern-input:focus {
  border-color: #667eea;
  outline: none;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 4px 12px rgba(0, 0, 0, 0.1);
  transform: translateY(-1px);
}

.modern-input:hover {
  border-color: #adb5bd;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

.search-actions {
  padding: 25px 0 0 0;
  border-top: 1px solid #e9ecef;
  margin-top: 20px;
}

.action-buttons-group {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-search-modern,
.btn-reset-modern {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 14px 24px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  min-width: 120px;
  justify-content: center;
}

.btn-search-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-search-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  color: white;
  text-decoration: none;
}

.btn-reset-modern {
  background: #f8f9fa;
  color: #6c757d;
  border: 2px solid #e9ecef;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-reset-modern:hover {
  background: #e9ecef;
  border-color: #dee2e6;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
  color: #495057;
  text-decoration: none;
}

/* Data Table */
.data-table-container {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.08);
  overflow: hidden;
}

.table-header {
  background: #f8f9fa;
  padding: 20px 25px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-title h3 {
  margin: 0;
  font-size: 18px;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 8px;
}

.item-count {
  font-size: 12px;
  color: #6c757d;
  background: #e9ecef;
  padding: 4px 8px;
  border-radius: 12px;
  margin-left: 10px;
}

.table-actions .btn-outline {
  background: transparent;
  border: 1px solid #dee2e6;
  color: #6c757d;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 12px;
  transition: all 0.3s ease;
}

.table-actions .btn-outline:hover {
  background: #f8f9fa;
  border-color: #adb5bd;
}

.table-wrapper {
  overflow-x: auto;
  max-height: 600px;
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}

.modern-table thead th {
  background: #495057;
  color: white;
  padding: 15px 8px;
  font-weight: 600;
  text-align: center;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  position: sticky;
  top: 0;
  z-index: 10;
}

.modern-table tbody tr {
  border-bottom: 1px solid #f1f3f4;
  transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
  transform: scale(1.001);
}

.modern-table tbody td {
  padding: 12px 8px;
  vertical-align: middle;
  text-align: center;
}

/* Column specific styles */
.col-generic { width: 120px; }
.col-company { width: 100px; }
.col-item-id { width: 80px; }
.col-product-id { width: 80px; }
.col-item-name { width: 150px; }
.col-vendor { width: 100px; }
.col-brand { width: 100px; }
.col-batch { width: 80px; }
.col-category { width: 100px; }
.col-price-wo-gst { width: 90px; }
.col-mrp { width: 80px; }
.col-gst-rate { width: 70px; }
.col-gst-amount { width: 90px; }
.col-price-w-gst { width: 90px; }
.col-pack-size { width: 80px; }
.col-quantity { width: 60px; }
.col-expiry { width: 80px; }
.col-safety-stock { width: 80px; }
.col-medicine-type { width: 100px; }
.col-status { width: 100px; }
.col-actions { width: 80px; }

/* Data styling */
.generic-name, .item-name {
  font-weight: 600;
  color: #2c3e50;
  text-align: left;
}

.company-name, .vendor-name, .category-name {
  color: #6c757d;
  font-size: 12px;
  text-align: left;
}

.item-link {
  color: #007bff;
  text-decoration: none;
  font-weight: 600;
  font-family: monospace;
  font-size: 11px;
}

.item-link:hover {
  color: #0056b3;
  text-decoration: underline;
}

.product-id {
  background: #f8f9fa;
  color: #495057;
  padding: 2px 6px;
  border-radius: 3px;
  font-family: monospace;
  font-size: 11px;
}

.brand-tag {
  background: #e9ecef;
  color: #495057;
  padding: 2px 6px;
  border-radius: 3px;
  font-size: 11px;
}

.batch-number {
  background: #fff3cd;
  color: #856404;
  padding: 2px 6px;
  border-radius: 3px;
  font-size: 11px;
  font-family: monospace;
}

.quantity-badge {
  background: #007bff;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 11px;
}

.expiry-date {
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: 600;
  font-size: 11px;
}

.expiry-warning {
  background: #f8d7da;
  color: #721c24;
}

.expiry-safe {
  background: #d4edda;
  color: #155724;
}

.gst-rate {
  background: #fff3cd;
  color: #856404;
  padding: 2px 6px;
  border-radius: 3px;
  font-size: 11px;
  font-weight: 600;
}

.pack-size {
  font-size: 12px;
  color: #6c757d;
}

.price {
  font-weight: 600;
  font-size: 12px;
}

.price-wo-gst {
  color: #28a745;
}

.mrp-price {
  color: #dc3545;
}

.gst-amount {
  color: #ffc107;
}

.price-w-gst {
  color: #17a2b8;
}

.safety-stock {
  background: #e3f2fd;
  color: #1976d2;
  padding: 2px 6px;
  border-radius: 3px;
  font-size: 11px;
  font-weight: 600;
}

/* Medicine Type Toggle Styles */
.medicine-type-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.medicine-type-toggle {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 30px;
  cursor: pointer;
}

.medicine-type-toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-label {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #dc3545; /* OPD - Red */
  border-radius: 15px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toggle-label:before {
  position: absolute;
  content: "";
  height: 22px;
  width: 22px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  border-radius: 50%;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.medicine-type-checkbox:checked + .toggle-label {
  background-color: #28a745; /* IPD - Green */
}

.medicine-type-checkbox:checked + .toggle-label:before {
  transform: translateX(30px);
}

.toggle-text {
  color: white;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  z-index: 1;
  position: relative;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  display: block;
  margin-bottom: 4px;
}

.status-active {
  background: #d4edda;
  color: #155724;
}

.status-inactive {
  background: #f8d7da;
  color: #721c24;
}

.manager-name {
  font-size: 10px;
  color: #6c757d;
  font-style: italic;
}

/* Action buttons */
.action-buttons {
  display: flex;
  gap: 5px;
  justify-content: center;
}

.action-btn {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 11px;
  transition: all 0.2s ease;
}

.edit-btn {
  background: #007bff;
  color: white;
}

.edit-btn:hover {
  background: #0056b3;
  transform: scale(1.1);
  color: white;
  text-decoration: none;
}

/* Modern Pagination */
.pagination-container-modern {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 25px;
  background: #f8f9fa;
  border-top: 1px solid #e9ecef;
}

.pagination-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.pagination-text {
  font-size: 14px;
  color: #6c757d;
  font-weight: 500;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 8px;
}

.pagination-controls .pagination {
  margin: 0;
  display: flex;
  gap: 6px;
  align-items: center;
}

.pagination-controls .pagination a,
.pagination-controls .pagination span {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  height: 40px;
  padding: 0 12px;
  border: 2px solid #e9ecef;
  color: #6c757d;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.pagination-controls .pagination a:hover {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: #667eea;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  text-decoration: none;
}

.pagination-controls .pagination .current {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: #667eea;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Bulk Action Buttons */
.btn-success {
  background: #28a745;
  border-color: #28a745;
  color: white;
}

.btn-success:hover {
  background: #218838;
  border-color: #1e7e34;
  color: white;
}

.btn-danger {
  background: #dc3545;
  border-color: #dc3545;
  color: white;
}

.btn-danger:hover {
  background: #c82333;
  border-color: #bd2130;
  color: white;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .search-grid {
    grid-template-columns: 1fr;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .page-header-modern {
    padding: 20px 0;
  }
  
  .page-title-section h1 {
    font-size: 24px;
  }
  
  .search-content {
    padding: 15px;
  }
  
  .search-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .search-actions .btn {
    width: 100%;
    max-width: 200px;
  }
  
  .table-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .modern-table {
    font-size: 11px;
  }
  
  .modern-table thead th,
  .modern-table tbody td {
    padding: 8px 4px;
  }
  
  .pagination-container-modern {
    flex-direction: column;
    gap: 15px;
    padding: 15px;
  }
}

/* Loading and Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.search-panel, .data-table-container {
  animation: fadeIn 0.5s ease-out;
}

/* Scrollbar Styling */
.table-wrapper::-webkit-scrollbar {
  height: 8px;
}

.table-wrapper::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>