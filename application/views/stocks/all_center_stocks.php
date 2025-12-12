<?php $all_method =&get_instance(); ?>
<div class="col-md-12">
  <!-- Modern Page Header -->
  <div class="page-header-modern">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="page-title-section">
            <h1 class="page-title">
              <i class="fa fa-cubes"></i> All Center Stocks
            </h1>
            <p class="page-subtitle">Comprehensive stock management across all centers</p>
          </div>
        </div>
        <div class="col-md-4 text-right">
          <div class="header-actions">
            <a href="<?php echo base_url('stocks/center_stock_export/'.$_SESSION['logged_stock_manager']['center']); ?>" 
               class="btn btn-export">
              <i class="fa fa-download"></i> Export Data
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Advanced Search Panel -->
  <div class="search-panel">
    <div class="search-header">
      <h3 class="search-title">
        <i class="fa fa-search"></i> Advanced Search & Filters
      </h3>
      <button class="btn btn-link toggle-filters" data-toggle="collapse" data-target="#searchFilters">
        <i class="fa fa-chevron-down"></i>
      </button>
    </div>
    <div id="searchFilters" class="search-content collapse in">



      <form action="<?php echo base_url().'stocks/all_center_stocks'; ?>" method="get" class="search-form">
        <div class="search-grid">
          <!-- Date Range Section -->
          <div class="search-section">
            <h4 class="section-title">Date Range</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Start Date</label>
                
                <div class="input-wrapper">
                  <input type="text" class="form-control modern-input particular_date_filter" 
                         id="start_date" name="start_date" value="<?php echo $start_date;?>" 
                         placeholder="Select start date" />
                </div>
              </div>
              <div class="form-group">
                <label>End Date</label>
                <div class="input-wrapper">
                  <input type="text" class="form-control modern-input particular_date_filter" 
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

          <!-- Employee Filter Section -->
          <div class="search-section">
            <h4 class="section-title">Employee Filter</h4>
            <div class="form-row">
              <div class="form-group full-width">
                <label>Filter by Employee</label>
                <div class="input-wrapper">
                  <i class="fa fa-user input-icon"></i>
                  <select class="form-control modern-input" id="employee_number" name="employee_number">
                    <option value=''>Select an employee</option>
                    <?php $all_emplyee = $all_method->get_employee_list();
                      foreach($all_emplyee as $key => $val){
                        if($employee_number == $val['name']){
                          echo '<option value="'.$val['employee_number'].'" selected>'.$val['name'].'</option>';
                        }else{
                          echo '<option value="'.$val['employee_number'].'">'.$val['name'].'</option>';
                        }
                      } 
                    ?>
                  </select>
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
            <a href="<?php echo base_url().'stocks/all_center_stocks'; ?>" class="btn btn-reset-modern">
              <i class="fa fa-refresh"></i>
              <span>Reset</span>
            </a>
            <a href="#" id="exportBtn" class="btn btn-export-modern">
              <i class="fa fa-file-excel-o"></i>
              <span>Export</span>
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
        <h3><i class="fa fa-table"></i> Stock Items Data</h3>
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
            <th class="col-bill">Bill No</th>
            <th class="col-generic">Generic Name</th>
            <th class="col-company">Company</th>
            <th class="col-item-id">Item ID</th>
            <th class="col-product-id">Product ID</th>
            <th class="col-item-name">Item Name</th>
            <th class="col-vendor">Vendor</th>
            <th class="col-brand">Brand</th>
            <th class="col-batch">Batch No</th>
            <th class="col-category">Category</th>
            <th class="col-quantity">Qty</th>
            <th class="col-expiry">Expiry</th>
            <th class="col-hsn">HSN</th>
            <th class="col-gst">GST</th>
            <th class="col-pack">Pack Size</th>
            <th class="col-gst-div">GST Div</th>
            <th class="col-vendor-price">Vendor Price</th>
            <th class="col-mrp">MRP</th>
            <th class="col-center">Center</th>
            <th class="col-dept">Department</th>
            <th class="col-status">Status</th>
            <th class="col-purchase-date">Purchase Date</th>
            <th class="col-ageing">Ageing</th>
            <th class="col-actions">Actions</th>
            <th class="col-add-date">Add Date</th>
          </tr>
        </thead>
        <tbody id="table_content">
          <?php $count=1; foreach($investigate_result as $vl){ ?>
            <tr class="data-row">
              <td><input type="checkbox" class="row-select" value="<?php echo $vl['ID']?>" style="left: 0px !important;opacity: 1 !important;position: unset !important;"/></td>
              <td class="col-bill">
                <span class="bill-number"><?php echo $vl['invoice_no']?></span>
              </td>
              <td class="col-generic">
                <div class="generic-name"><?php echo $vl['generic_name']?></div>
              </td>
              <td class="col-company">
                <div class="company-name"><?php echo $vl['company']?></div>
              </td>
              <td class="col-item-id">
                <span class="item-id"><?php echo $vl['item_number']?></span>
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
              <td class="col-quantity">
                <span class="quantity-badge"><?php echo $vl['quantity']?></span>
              </td>
              <?php
              // Assuming $vl['expiry'] is the expiry date in 'Y-m-d' format
              $expiryDate = new DateTime($vl['expiry']);
              $today = new DateTime();
              $twoMonthsFromNow = (clone $today)->modify('+2 months');
              $expiryClass = '';
              if ($expiryDate <= $twoMonthsFromNow) {
                $expiryClass = 'expiry-warning'; // Red if within 2 months
              } else {
                $expiryClass = 'expiry-safe'; // Green if more than 2 months
              }
              ?>
              <td class="col-expiry">
                <span class="expiry-date <?php echo $expiryClass; ?>"><?php echo $vl['expiry']; ?></span>
              </td>
              
              <td class="col-hsn">
                <span class="hsn-code"><?php echo $vl['hsn']?></span>
              </td>
              <td class="col-gst">
                <span class="gst-rate"><?php echo $vl['gstrate']?>%</span>
              </td>
              <td class="col-pack">
                <div class="pack-size"><?php echo $vl['pack_size']?></div>
              </td>
              <td class="col-gst-div">
                <div class="gst-division"><?php echo $vl['gstdivision']?></div>
              </td>
              <td class="col-vendor-price">
                <div class="price vendor-price">₹<?php echo $vl['vendor_price']; ?></div>
              </td>
              <td class="col-mrp">
                <div class="price mrp-price">₹<?php echo $vl['mrp']?></div>
              </td>
              <td class="col-center">
                <?php if(!empty($vl['center_number'])){
                  $sql1 = "select * from hms_centers where center_number='".$vl['center_number']."'"; 
                  $query = $this->db->query($sql1);
                  $select_result1 = $query->result(); 
                  foreach ($select_result1 as $res_val){
                    echo '<span class="center-tag">'.$res_val->center_name.'</span>';
                  }
                } ?>
              </td>
              <td class="col-dept">
                <div class="department"><?php $employee_name = $all_method->get_employee_name($vl['employee_number']); echo $employee_name; ?></div>
              </td>
              <td class="col-status">
                <?php if($vl['status'] == '1'){ ?>
                  <span class="status-badge status-active">Active</span>
                <?php } else { ?>
                  <span class="status-badge status-inactive">Inactive</span>
                <?php } ?>
              </td>
              <td class="col-purchase-date">
                <div class="purchase-date"><?php echo $vl['date_of_purchase']; ?></div>
              </td> 
              <td class="col-ageing">
                <?php
                $date1 = new DateTime($vl['date_of_purchase']);
                $date2 = new DateTime(date('Y-m-d'));
                $diff = $date1->diff($date2);
                $days = $diff->days;
                $ageingClass = ($days > 365) ? 'ageing-old' : 'ageing-new';
                ?>
                <span class="ageing-badge <?php echo $ageingClass; ?>"><?php echo $days; ?>d</span>
              </td>
              <?php if (!empty($_SESSION['logged_central_stock_manager'])) { ?>
              <td class="col-actions">
                <div class="action-buttons">
                  <a href="<?php echo base_url();?>stocks/edit_center_item?ID=<?php echo $vl['ID']?>" 
                     class="action-btn edit-btn" title="Edit">
                    <i class="fa fa-edit"></i>
                  </a>
                  <a href="<?php echo base_url();?>stocks/delete_center_item?ID=<?php echo $vl['ID']?>" 
                     class="action-btn delete-btn" title="Delete" 
                     onclick="return confirm('Are you sure you want to delete this item?')">
                    <i class="fa fa-trash"></i>
                  </a>
                  <a href="<?php echo base_url();?>stocks/audit_stocks?ID=<?php echo $vl['ID']?>" 
                     class="action-btn audit-btn" title="Audit Stocks">
                    <i class="fa fa-search"></i>
                  </a>
                  <?php if($vl['status'] == '1'){ ?>
                  <a href="<?php echo base_url();?>stocks/transfer_stocks/<?php echo $vl['ID']?>" 
                     class="action-btn transfer-btn" title="Transfer">
                    <i class="fa fa-exchange"></i>
                  </a>
                  <?php } ?>
                  <a href="#" class="action-btn history-btn" title="Transfer History" 
                     onclick="showTransferHistory('<?php echo $vl['item_number']; ?>', '<?php echo $vl['item_name']; ?>')">
                    <i class="fa fa-history"></i>
                  </a>
                </div>
              </td>
              <?php } ?>
              <td class="col-add-date">
                <div class="add-date"><?php echo $vl['add_date']?></div>
              </td>
            </tr>
          <?php $count++;} ?>
        </tbody>
      </table>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bulk-actions" style="margin-top:10px; display:flex; gap:10px; justify-content:flex-start;">
      <button type="button" id="bulk_activate" class="btn btn-search-modern" style="min-width:unset; padding:8px 16px;">
        <i class="fa fa-check"></i> Activate Selected
      </button>
      <button type="button" id="bulk_deactivate" class="btn btn-reset-modern" style="min-width:unset; padding:8px 16px;">
        <i class="fa fa-ban"></i> Deactivate Selected
      </button>
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

<!-- Transfer History Modal -->
<div id="transferHistoryModal" class="modal modal-fixed-footer">
  <div class="modal-content">
    <h4 id="transferHistoryModalLabel" class="modal-title">
      <i class="fa fa-history"></i> Transfer History
    </h4>
    <div id="transferHistoryContent">
      <div class="text-center">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
        <p>Loading transfer history...</p>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    <a href="#!" class="modal-action waves-effect waves-green btn" onclick="exportTransferHistory()">
      <i class="fa fa-download"></i> Export History
    </a>
  </div>
</div>
<script>
$(function() {
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
});
</script>

<style>
  /* Modern Stock Management Interface */

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

  .form-group.full-width {
    grid-column: 1 / -1;
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
  .btn-reset-modern,
  .btn-export-modern {
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

  .btn-search-modern:active {
    transform: translateY(0);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
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

  .btn-reset-modern:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .btn-export-modern {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
  }

  .btn-export-modern:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    color: white;
    text-decoration: none;
  }

  .btn-export-modern:active {
    transform: translateY(0);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
  }

  /* Button ripple effect */
  .btn-search-modern::before,
  .btn-reset-modern::before,
  .btn-export-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .btn-search-modern:active::before,
  .btn-reset-modern:active::before,
  .btn-export-modern:active::before {
    width: 300px;
    height: 300px;
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
  .col-bill { width: 80px; }
  .col-generic { width: 120px; }
  .col-company { width: 100px; }
  .col-item-id { width: 80px; }
  .col-product-id { width: 80px; }
  .col-item-name { width: 150px; }
  .col-vendor { width: 100px; }
  .col-brand { width: 100px; }
  .col-batch { width: 80px; }
  .col-category { width: 100px; }
  .col-quantity { width: 60px; }
  .col-expiry { width: 80px; }
  .col-hsn { width: 80px; }
  .col-gst { width: 60px; }
  .col-pack { width: 80px; }
  .col-gst-div { width: 80px; }
  .col-vendor-price { width: 90px; }
  .col-mrp { width: 80px; }
  .col-center { width: 100px; }
  .col-dept { width: 100px; }
  .col-status { width: 70px; }
  .col-purchase-date { width: 100px; }
  .col-ageing { width: 70px; }
  .col-actions { width: 120px; }
  .col-add-date { width: 100px; }

  /* Data styling */
  .bill-number {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
  }

  .generic-name, .item-name {
    font-weight: 600;
    color: #2c3e50;
    text-align: left;
  }

  .company-name, .vendor-name, .category-name, .department {
    color: #6c757d;
    font-size: 12px;
    text-align: left;
  }

  .item-id, .product-id, .hsn-code {
    background: #f8f9fa;
    color: #495057;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
    font-size: 11px;
  }

  .brand-tag, .center-tag {
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

  .pack-size, .gst-division {
    font-size: 12px;
    color: #6c757d;
  }

  .price {
    font-weight: 600;
    font-size: 12px;
  }

  .vendor-price {
    color: #28a745;
  }

  .mrp-price {
    color: #dc3545;
  }

  .status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-active {
    background: #d4edda;
    color: #155724;
  }

  .status-inactive {
    background: #f8d7da;
    color: #721c24;
  }

  .purchase-date, .add-date {
    font-size: 11px;
    color: #6c757d;
  }

  .ageing-badge {
    padding: 3px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
  }

  .ageing-new {
    background: #cce5ff;
    color: #004085;
  }

  .ageing-old {
    background: #ffeaa7;
    color: #6c5ce7;
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

  .delete-btn {
    background: #dc3545;
    color: white;
  }

  .delete-btn:hover {
    background: #c82333;
    transform: scale(1.1);
    color: white;
    text-decoration: none;
  }

  .audit-btn {
    background: #17a2b8;
    color: white;
  }

  .audit-btn:hover {
    background: #138496;
    transform: scale(1.1);
    color: white;
    text-decoration: none;
  }

  .transfer-btn {
    background: #ffc107;
    color: #212529;
  }

  .transfer-btn:hover {
    background: #e0a800;
    transform: scale(1.1);
    color: #212529;
    text-decoration: none;
  }

  .history-btn {
    background: #6f42c1;
    color: white;
  }

  .history-btn:hover {
    background: #5a32a3;
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

  .pagination-controls .pagination a:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
  }

  .pagination-controls .pagination .current {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .pagination-controls .pagination .current:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
  }

  /* Pagination button ripple effect */
  .pagination-controls .pagination a::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .pagination-controls .pagination a:active::before {
    width: 100px;
    height: 100px;
  }

  /* Responsive pagination */
  @media (max-width: 768px) {
    .pagination-container-modern {
      flex-direction: column;
      gap: 15px;
      padding: 15px;
    }
    
    .pagination-controls .pagination a,
    .pagination-controls .pagination span {
      min-width: 35px;
      height: 35px;
      padding: 0 8px;
      font-size: 12px;
    }
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

  /* Transfer History Modal Styles */
  .transfer-history-container {
    max-height: 500px;
    overflow-y: auto;
  }

  .history-summary {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #6f42c1;
  }

  .summary-card {
    text-align: center;
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .summary-card h5 {
    margin: 0 0 10px 0;
    color: #6c757d;
    font-size: 14px;
    font-weight: 600;
  }

  .summary-value {
    font-size: 24px;
    font-weight: bold;
    color: #6f42c1;
  }

  .transfer-table-container {
    max-height: 300px;
    overflow-y: auto;
  }

  .transfer-table-container table {
    margin-bottom: 0;
  }

  .transfer-table-container thead th {
    background: #6f42c1;
    color: white;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 8px;
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .transfer-table-container td {
    padding: 10px 8px;
    font-size: 13px;
    vertical-align: middle;
  }

  .badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    color: white;
  }

  .badge.blue {
    background: #2196F3;
  }

  .badge.green {
    background: #4CAF50;
  }

  .badge.red {
    background: #f44336;
  }

  .badge.orange {
    background: #ff9800;
  }

  /* Materialize Modal Enhancements */
  #transferHistoryModal {
    width: 90%;
    max-width: 1000px;
  }

  #transferHistoryModal .modal-content {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
  }

  #transferHistoryModal .modal-title {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    color: white;
    margin: -20px -20px 20px -20px;
    padding: 15px 20px;
    font-weight: 600;
    font-size: 18px;
    border-radius: 0;
  }

  #transferHistoryModal .modal-footer {
    border-top: 1px solid #e0e0e0;
    background: #f8f9fa;
    padding: 15px 20px;
  }

  #transferHistoryModal .modal-footer .btn {
    margin-left: 10px;
  }

  /* Additional Bootstrap 3 compatibility */
  .alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
  }

  .alert-warning {
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #faebcc;
  }

  .alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
  }

  .alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
  }

  .striped > tbody > tr:nth-child(odd) {
    background-color: #f9f9f9;
  }

  .striped > tbody > tr:hover {
    background-color: #f5f5f5;
  }
  </style>

  <script>
  $(document).ready(function() {
      // Handle export button click
      $('#exportBtn').click(function(e) {
          e.preventDefault();
          
          // Get form values
          var startDate = $('#start_date').val();
          var endDate = $('#end_date').val();
          var genericName = $('#generic_name').val();
          var itemName = $('#item_name').val();
          var batchNumber = $('#batch_number').val();
          var itemNumber = $('#item_number').val();
          var employeeNumber = $('#employee_number').val();
          
          // Build export URL with parameters
          var exportUrl = '<?php echo base_url("stocks/All-Center-Medicine"); ?>?export-billing=1';
          
          if (startDate) exportUrl += '&start_date=' + encodeURIComponent(startDate);
          if (endDate) exportUrl += '&end_date=' + encodeURIComponent(endDate);
          if (genericName) exportUrl += '&generic_name=' + encodeURIComponent(genericName);
          if (itemName) exportUrl += '&item_name=' + encodeURIComponent(itemName);
          if (batchNumber) exportUrl += '&batch_number=' + encodeURIComponent(batchNumber);
          if (itemNumber) exportUrl += '&item_number=' + encodeURIComponent(itemNumber);
          if (employeeNumber) exportUrl += '&employee_number=' + encodeURIComponent(employeeNumber);
          
          // Redirect to export URL
          window.location.href = exportUrl;
      });
      
      // Select all toggle
      $('#select_all_items').on('change', function(){
          var checked = $(this).is(':checked');
          $('#table_content .row-select').prop('checked', checked);
      });

      function collectSelectedItems(){
          var items = [];
          $('#table_content .row-select:checked').each(function(){
              items.push($(this).val());
          });
          return items;
      }

      function bulkUpdateStatus(statusValue){
          var selected = collectSelectedItems();
          if(selected.length === 0){
              alert('Please select at least one item.');
              return;
          }
          
          // Show loading state
          var $buttons = $('#bulk_activate, #bulk_deactivate');
          $buttons.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
          
          $.ajax({
              url: '<?php echo base_url(); ?>stocks/bulk_status_center',
              type: 'POST',
              dataType: 'json',
              data: { item_ids: selected, status: String(statusValue) },
              success: function(resp){
                  if(resp && resp.status == 1){
                      alert('Successfully updated ' + resp.updated + ' items.');
                      location.reload();
                  }else{
                      alert('Failed to update: ' + (resp.message || 'Unknown error'));
                  }
              },
              error: function(xhr, status, error){
                  console.error('AJAX Error:', xhr.responseText);
                  alert('Request failed: ' + error + '\nStatus: ' + status);
              },
              complete: function(){
                  // Re-enable buttons
                  $buttons.prop('disabled', false);
                  $('#bulk_activate').html('<i class="fa fa-check"></i> Activate Selected');
                  $('#bulk_deactivate').html('<i class="fa fa-ban"></i> Deactivate Selected');
              }
          });
      }

      $('#bulk_activate').on('click', function(){ bulkUpdateStatus(1); });
      $('#bulk_deactivate').on('click', function(){ bulkUpdateStatus(0); });
  });

  // Transfer History Functions
  var currentItemNumber = '';
  var currentItemName = '';

  function showTransferHistory(itemNumber, itemName) {
      currentItemNumber = itemNumber;
      currentItemName = itemName;
      
      // Update modal title
      $('#transferHistoryModalLabel').html('<i class="fa fa-history"></i> Transfer History - ' + itemName);
      
      // Show modal using Materialize v0.97.8 syntax
      $('#transferHistoryModal').modal('open');
      
      // Load transfer history
      loadTransferHistory(itemNumber);
  }

  function loadTransferHistory(itemNumber) {
      $('#transferHistoryContent').html(`
          <div class="text-center">
              <i class="fa fa-spinner fa-spin fa-2x"></i>
              <p>Loading transfer history...</p>
          </div>
      `);
      
      $.ajax({
          url: '<?php echo base_url(); ?>stocks/get_transfer_history',
          type: 'POST',
          dataType: 'json',
          data: { item_number: itemNumber },
          success: function(response) {
              if (response.status === 'success') {
                  displayTransferHistory(response.data);
              } else {
                  $('#transferHistoryContent').html(`
                      <div class="alert alert-warning text-center">
                          <i class="fa fa-exclamation-triangle"></i>
                          <p>No transfer history found for this item.</p>
                      </div>
                  `);
              }
          },
          error: function(xhr, status, error) {
              console.error('Error loading transfer history:', error);
              $('#transferHistoryContent').html(`
                  <div class="alert alert-danger text-center">
                      <i class="fa fa-exclamation-circle"></i>
                      <p>Error loading transfer history. Please try again.</p>
                  </div>
              `);
          }
      });
  }

  function displayTransferHistory(transfers) {
      if (transfers.length === 0) {
          $('#transferHistoryContent').html(`
              <div class="alert alert-info text-center">
                  <i class="fa fa-info-circle"></i>
                  <p>No transfer records found for this item.</p>
              </div>
          `);
          return;
      }
      
      let html = `
          <div class="transfer-history-container">
              <div class="history-summary">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="summary-card">
                              <h5><i class="fa fa-cube"></i> Total Transfers</h5>
                              <span class="summary-value">${transfers.length}</span>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="summary-card">
                              <h5><i class="fa fa-exchange"></i> Total Quantity</h5>
                              <span class="summary-value">${transfers.reduce((sum, t) => sum + parseInt(t.quantity || 0), 0)}</span>
                          </div>
                      </div>
                  </div>
              </div>
              
              <div class="transfer-table-container">
                  <table class="striped responsive-table">
                      <thead>
                          <tr>
                              <th>Transfer Date</th>
                              <th>From Center</th>
                              <th>To Center</th>
                              <th>Quantity</th>
                              <th>Status</th>
                              <th>Employee</th>
                              <th>Remarks</th>
                          </tr>
                      </thead>
                      <tbody>
      `;
      
      transfers.forEach(function(transfer) {
          const statusClass = transfer.status == '1' ? 'success' : transfer.status == '2' ? 'danger' : 'warning';
          const statusText = transfer.status == '1' ? 'Approved' : transfer.status == '2' ? 'Rejected' : 'Pending';
          
          html += `
              <tr>
                  <td>${transfer.add_date || 'N/A'}</td>
                  <td>${transfer.center_number || 'N/A'}</td>
                  <td>${transfer.r_center_number || 'N/A'}</td>
                  <td><span class="badge blue">${transfer.quantity || 0}</span></td>
                  <td><span class="badge ${statusClass === 'success' ? 'green' : statusClass === 'danger' ? 'red' : 'orange'}">${statusText}</span></td>
                  <td>${transfer.employee_number || 'N/A'}</td>
                  <td>${transfer.remarks || 'N/A'}</td>
              </tr>
          `;
      });
      
      html += `
                      </tbody>
                  </table>
              </div>
          </div>
      `;
      
      $('#transferHistoryContent').html(html);
  }

  function exportTransferHistory() {
      if (!currentItemNumber) {
          alert('No item selected for export.');
          return;
      }
      
      // Create export URL
      const exportUrl = '<?php echo base_url(); ?>stocks/export_transfer_history?item_number=' + encodeURIComponent(currentItemNumber);
      
      // Open in new window to trigger download
      window.open(exportUrl, '_blank');
  }

  // Initialize Materialize modals when document is ready
  $(document).ready(function() {
      // Initialize modals with Materialize v0.97.8 options
      $('.modal').modal({
          dismissible: true,
          opacity: 0.5,
          inDuration: 300,
          outDuration: 200,
          startingTop: '4%',
          endingTop: '10%'
      });
  });
</script>