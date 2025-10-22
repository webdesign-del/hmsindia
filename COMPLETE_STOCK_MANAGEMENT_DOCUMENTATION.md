# Complete Stock Management System - Comprehensive Documentation

## Overview
This is a complete, modular stock management system designed for pharmaceutical inventory management. The system uses proper table relationships with foreign keys to ensure data integrity and provides comprehensive tracking of all stock movements.

## System Architecture

### 🏗️ **Modular Design**
The system is divided into two main categories:
1. **Master Tables** - Reference data (medicines, vendors, centers, departments, employees)
2. **Transaction Tables** - Operational data (batches, orders, receipts, transfers, movements)

### 🔗 **Table Relationships**
All tables are properly connected with foreign key relationships to ensure data integrity and consistency.

## Database Structure

### 📋 **Master Tables (Reference Data)**

#### 1. **hms_medicines** - Medicine Master
```sql
-- Stores basic medicine information
- ID (Primary Key)
- item_number (Unique identifier)
- name, generic_name, company, brand_name
- category, pack_size, unit_of_measure
- hsn_code, gst_rate, gst_division
- min_stock_level, max_stock_level, reorder_level
- is_narcotic, is_controlled_substance
```

#### 2. **hms_vendors** - Vendor Master
```sql
-- Stores vendor/supplier information
- ID (Primary Key)
- vendor_number (Unique identifier)
- vendor_name, vendor_address, vendor_phone
- contact_person, gst_number, pan_number
- payment_terms, credit_limit
```

#### 3. **hms_centers** - Center Master
```sql
-- Stores center/location information
- ID (Primary Key)
- center_number (Unique identifier)
- center_name, center_address, center_phone
- center_classification (hub/spoke)
- is_central_warehouse
```

#### 4. **hms_departments** - Department Master
```sql
-- Stores department information
- ID (Primary Key)
- department_code (Unique identifier)
- department_name, center_id (Foreign Key)
- description
```

#### 5. **hms_employees** - Employee Master
```sql
-- Stores employee information
- ID (Primary Key)
- employee_number (Unique identifier)
- name, email, phone, role
- center_id, department_id (Foreign Keys)
```

### 📊 **Transaction Tables (Operational Data)**

#### 6. **hms_medicine_batches** - Batch Management
```sql
-- Stores batch-specific information for each medicine
- ID (Primary Key)
- medicine_id (Foreign Key → hms_medicines)
- vendor_id (Foreign Key → hms_vendors)
- batch_number, expiry_date, expiry_days
- vendor_price, mrp, selling_price
- current_quantity, available_quantity, reserved_quantity
- center_id, department_id (Foreign Keys)
- quality_status, batch_status
- created_by, updated_by (Foreign Keys → hms_employees)
```

#### 7. **hms_purchase_orders** - Purchase Orders
```sql
-- Stores purchase order information
- ID (Primary Key)
- po_number (Unique identifier)
- vendor_id, center_id, department_id (Foreign Keys)
- po_date, expected_delivery_date
- total_amount, status
- created_by, updated_by (Foreign Keys → hms_employees)
```

#### 8. **hms_purchase_order_items** - Purchase Order Items
```sql
-- Stores items in purchase orders
- ID (Primary Key)
- po_id (Foreign Key → hms_purchase_orders)
- medicine_id (Foreign Key → hms_medicines)
- quantity_ordered, quantity_received
- unit_price, total_price
```

#### 9. **hms_purchase_receipts** - Purchase Receipts
```sql
-- Stores purchase receipt information
- ID (Primary Key)
- receipt_number (Unique identifier)
- po_id, vendor_id, center_id, department_id (Foreign Keys)
- receipt_date, invoice_number, invoice_date
- total_amount, status
- created_by, updated_by (Foreign Keys → hms_employees)
```

#### 10. **hms_purchase_receipt_items** - Purchase Receipt Items
```sql
-- Stores items in purchase receipts
- ID (Primary Key)
- receipt_id (Foreign Key → hms_purchase_receipts)
- medicine_id (Foreign Key → hms_medicines)
- batch_number, expiry_date
- quantity_received, unit_price, total_price
- quality_status
```

#### 11. **hms_stock_transfers** - Stock Transfers
```sql
-- Stores stock transfer information
- ID (Primary Key)
- transfer_number (Unique identifier)
- from_center_id, to_center_id (Foreign Keys → hms_centers)
- from_department_id, to_department_id (Foreign Keys → hms_departments)
- transfer_date, expected_delivery_date
- status, remarks
- created_by, updated_by (Foreign Keys → hms_employees)
```

#### 12. **hms_stock_transfer_items** - Stock Transfer Items
```sql
-- Stores items in stock transfers
- ID (Primary Key)
- transfer_id (Foreign Key → hms_stock_transfers)
- batch_id (Foreign Key → hms_medicine_batches)
- quantity_transferred, quantity_received
```

#### 13. **hms_stock_movements** - Stock Movements (Audit Trail)
```sql
-- Stores all stock movements for audit trail
- ID (Primary Key)
- batch_id (Foreign Key → hms_medicine_batches)
- movement_type (PURCHASE, SALE, TRANSFER, etc.)
- quantity_before, quantity_change, quantity_after
- unit_price, total_value
- reference_type, reference_id, reference_number
- patient_id, patient_name (for sales)
- created_by (Foreign Key → hms_employees)
```

## 🔗 **Table Relationships Diagram**

```
hms_medicines (1) ──→ (Many) hms_medicine_batches
hms_vendors (1) ──→ (Many) hms_medicine_batches
hms_centers (1) ──→ (Many) hms_medicine_batches
hms_departments (1) ──→ (Many) hms_medicine_batches
hms_employees (1) ──→ (Many) hms_medicine_batches

hms_purchase_orders (1) ──→ (Many) hms_purchase_order_items
hms_medicines (1) ──→ (Many) hms_purchase_order_items

hms_purchase_receipts (1) ──→ (Many) hms_purchase_receipt_items
hms_medicines (1) ──→ (Many) hms_purchase_receipt_items

hms_stock_transfers (1) ──→ (Many) hms_stock_transfer_items
hms_medicine_batches (1) ──→ (Many) hms_stock_transfer_items

hms_medicine_batches (1) ──→ (Many) hms_stock_movements
hms_employees (1) ──→ (Many) hms_stock_movements
```

## 🚀 **Key Features**

### ✅ **Complete Traceability**
- Every stock movement is recorded in `hms_stock_movements`
- Full audit trail from purchase to sale
- Batch-level tracking with expiry dates

### ✅ **Proper Data Integrity**
- Foreign key constraints ensure data consistency
- Unique constraints prevent duplicates
- Referential integrity maintained

### ✅ **Modular Design**
- Master tables for reference data
- Transaction tables for operational data
- Easy to extend and maintain

### ✅ **Comprehensive Reporting**
- Pre-built views for common queries
- Stock levels, expiry alerts, summaries
- Easy to create custom reports

## 📊 **Views for Reporting**

### 1. **v_current_stock_levels**
Shows current stock levels for all medicines with batch details.

### 2. **v_medicine_stock_summary**
Provides summary statistics for each medicine across all batches.

### 3. **v_expired_stock_alerts**
Identifies expired and expiring stock items.

## 🔧 **Stored Procedures**

### 1. **sp_add_batch_for_existing_medicine**
Adds new batch for existing medicine or updates existing batch.

### 2. **sp_transfer_stock_between_centers**
Transfers stock between centers with proper validation.

## 💻 **PHP Integration**

### 1. **Model Structure**
```php
class Stock_model extends CI_Model {
    
    // Master table operations
    public function get_medicines() {
        return $this->db->get('hms_medicines')->result_array();
    }
    
    public function get_vendors() {
        return $this->db->get('hms_vendors')->result_array();
    }
    
    public function get_centers() {
        return $this->db->get('hms_centers')->result_array();
    }
    
    // Batch operations
    public function add_batch_for_existing_medicine($data) {
        $sql = "CALL sp_add_batch_for_existing_medicine(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, [
            $data['item_number'],
            $data['batch_number'],
            $data['vendor_number'],
            $data['expiry_date'],
            $data['vendor_price'],
            $data['mrp'],
            $data['quantity'],
            $data['center_number'],
            $data['department_code'],
            $data['receipt_number'],
            $data['employee_number'],
            $data['remarks']
        ]);
        return $result->row_array();
    }
    
    // Stock level queries
    public function get_current_stock_levels($center_number = null) {
        $this->db->select('*');
        $this->db->from('v_current_stock_levels');
        if($center_number) {
            $this->db->where('center_number', $center_number);
        }
        return $this->db->get()->result_array();
    }
    
    public function get_expired_stock_alerts($center_number = null) {
        $this->db->select('*');
        $this->db->from('v_expired_stock_alerts');
        if($center_number) {
            $this->db->where('center_name', $center_number);
        }
        return $this->db->get()->result_array();
    }
}
```

### 2. **Controller Structure**
```php
class Stocks extends CI_Controller {
    
    public function add_medicine_batch() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if(isset($_POST['action']) && $_POST['action'] == 'add_batch') {
                
                $data = [
                    'item_number' => $_POST['item_number'],
                    'batch_number' => $_POST['batch_number'],
                    'vendor_number' => $_POST['vendor_number'],
                    'expiry_date' => $_POST['expiry_date'],
                    'vendor_price' => $_POST['vendor_price'],
                    'mrp' => $_POST['mrp'],
                    'quantity' => $_POST['quantity'],
                    'center_number' => $_POST['center_number'],
                    'department_code' => $_POST['department_code'],
                    'receipt_number' => $_POST['receipt_number'],
                    'employee_number' => $_POST['employee_number'],
                    'remarks' => $_POST['remarks']
                ];
                
                $result = $this->stock_model->add_batch_for_existing_medicine($data);
                
                if($result['status'] == 'SUCCESS') {
                    $this->session->set_flashdata('success', 'Batch added successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Error: ' . $result['message']);
                }
                
                redirect('stocks/add_medicine_batch');
            }
            
            // Load view with data
            $data['medicines'] = $this->stock_model->get_medicines();
            $data['vendors'] = $this->stock_model->get_vendors();
            $data['centers'] = $this->stock_model->get_centers();
            $data['departments'] = $this->stock_model->get_departments();
            
            $this->load->view('stocks/add_medicine_batch', $data);
        }
    }
    
    public function stock_levels() {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['stock_levels'] = $this->stock_model->get_current_stock_levels();
            $data['expired_alerts'] = $this->stock_model->get_expired_stock_alerts();
            
            $this->load->view('stocks/stock_levels', $data);
        }
    }
}
```

### 3. **View Structure**
```php
<!-- Add Medicine Batch Form -->
<form method="post" action="<?php echo base_url('stocks/add_medicine_batch'); ?>">
    <input type="hidden" name="action" value="add_batch">
    
    <div class="form-group">
        <label>Medicine</label>
        <select name="item_number" class="form-control" required>
            <option value="">Select Medicine</option>
            <?php foreach($medicines as $medicine): ?>
                <option value="<?php echo $medicine['item_number']; ?>">
                    <?php echo $medicine['name']; ?> (<?php echo $medicine['item_number']; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Batch Number</label>
        <input type="text" name="batch_number" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Vendor</label>
        <select name="vendor_number" class="form-control" required>
            <option value="">Select Vendor</option>
            <?php foreach($vendors as $vendor): ?>
                <option value="<?php echo $vendor['vendor_number']; ?>">
                    <?php echo $vendor['vendor_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Vendor Price</label>
        <input type="number" name="vendor_price" step="0.01" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>MRP</label>
        <input type="number" name="mrp" step="0.01" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Center</label>
        <select name="center_number" class="form-control" required>
            <option value="">Select Center</option>
            <?php foreach($centers as $center): ?>
                <option value="<?php echo $center['center_number']; ?>">
                    <?php echo $center['center_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Department</label>
        <select name="department_code" class="form-control" required>
            <option value="">Select Department</option>
            <?php foreach($departments as $department): ?>
                <option value="<?php echo $department['department_code']; ?>">
                    <?php echo $department['department_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Receipt Number</label>
        <input type="text" name="receipt_number" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Remarks</label>
        <textarea name="remarks" class="form-control"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Add Batch</button>
</form>
```

## 📈 **Reporting Examples**

### 1. **Current Stock Levels Report**
```sql
SELECT 
    item_number,
    medicine_name,
    brand_name,
    batch_number,
    expiry_date,
    current_quantity,
    center_name,
    department_name,
    expiry_status
FROM v_current_stock_levels
WHERE center_number = 'CENTER001'
ORDER BY medicine_name, expiry_date;
```

### 2. **Expired Stock Report**
```sql
SELECT 
    item_number,
    medicine_name,
    batch_number,
    expiry_date,
    expiry_days,
    current_quantity,
    center_name,
    alert_level
FROM v_expired_stock_alerts
WHERE alert_level IN ('EXPIRED', 'CRITICAL')
ORDER BY expiry_date ASC;
```

### 3. **Medicine Summary Report**
```sql
SELECT 
    item_number,
    medicine_name,
    brand_name,
    total_batches,
    total_quantity,
    total_available,
    earliest_expiry,
    latest_expiry,
    avg_vendor_price,
    avg_mrp,
    stock_status
FROM v_medicine_stock_summary
ORDER BY medicine_name;
```

## 🔒 **Security Features**

### 1. **Data Integrity**
- Foreign key constraints
- Unique constraints
- Check constraints
- Referential integrity

### 2. **Access Control**
- Role-based access through application layer
- User authentication and authorization
- Audit trail for all changes

### 3. **Data Validation**
- Input validation in PHP
- Database constraints
- Business rule validation

## 🚀 **Benefits**

### ✅ **Scalability**
- Modular design allows easy extension
- Proper indexing for performance
- Optimized queries

### ✅ **Maintainability**
- Clear table relationships
- Well-documented structure
- Easy to understand and modify

### ✅ **Data Integrity**
- Foreign key constraints
- Referential integrity
- Consistent data structure

### ✅ **Comprehensive Tracking**
- Complete audit trail
- Batch-level tracking
- Movement history

### ✅ **Easy Reporting**
- Pre-built views
- Standardized queries
- Flexible reporting options

This complete stock management system provides a robust, scalable, and maintainable solution for pharmaceutical inventory management with proper table relationships and comprehensive documentation.
