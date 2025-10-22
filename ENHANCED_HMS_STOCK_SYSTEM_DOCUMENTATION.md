# Enhanced HMS Stock Management System with FIFO Implementation

## Overview
This is an enhanced version of your proposed stock management system with proper FIFO (First In, First Out) implementation, improved audit trails, and comprehensive stock management features.

## 🎯 **Key Improvements Over Your Original Structure**

### ✅ **FIFO Implementation**
- **Proper FIFO ranking** in views and procedures
- **FIFO-based allocation** for sales and transfers
- **Expiry date prioritization** for stock rotation

### ✅ **Enhanced Data Types**
- **BIGINT UNSIGNED** for all IDs (better performance)
- **Generated columns** for calculated fields
- **Proper indexing** for FIFO queries

### ✅ **Improved Audit Trail**
- **Comprehensive stock movements** tracking
- **Reference tracking** for all operations
- **Patient information** in sales

### ✅ **Better Stock Management**
- **    ty** tracking
- **Available quantity** calculation
- **Last movement date** tracking

## 🏗️ **Enhanced Table Structure**

### **Master Tables (Reference Data)**

#### 1. **medicine_brands** - Enhanced Brand Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- name (VARCHAR(150)) - Brand name (UNIQUE)
- description (TEXT) - Optional details
- manufacturer (VARCHAR(200)) - Manufacturer name
- country_of_origin (VARCHAR(100)) - Country of origin
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

#### 2. **vendors** - Enhanced Vendor Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- name (VARCHAR(200)) - Vendor name
- contact_person (VARCHAR(100)) - Contact person
- phone (VARCHAR(50)) - Phone number
- email (VARCHAR(150)) - Email address
- address (TEXT) - Vendor address
- gst_number (VARCHAR(50)) - GST number
- pan_number (VARCHAR(20)) - PAN number
- payment_terms (VARCHAR(100)) - Payment terms
- credit_limit (DECIMAL(15,2)) - Credit limit
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

#### 3. **medicines** - Enhanced Medicine Catalog
```sql
- id (BIGINT UNSIGNED) - Primary key
- brand_id (BIGINT UNSIGNED) - FK to medicine_brands
- name (VARCHAR(200)) - Medicine name
- generic_name (VARCHAR(200)) - Generic name
- unit (VARCHAR(50)) - Unit of measure
- strength (VARCHAR(50)) - Medicine strength
- category (VARCHAR(100)) - Medicine category
- hsn_code (VARCHAR(100)) - HSN code
- gst_rate (DECIMAL(5,2)) - GST rate
- min_stock_level (INT) - Minimum stock level
- max_stock_level (INT) - Maximum stock level
- reorder_level (INT) - Reorder level
- is_narcotic (BOOLEAN) - Narcotic flag
- is_controlled_substance (BOOLEAN) - Controlled substance flag
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```



### **Stock Management Tables**

#### 6. **medicine_batches** - Enhanced Batch Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- medicine_id (BIGINT UNSIGNED) - FK to medicines
- vendor_id (BIGINT UNSIGNED) - FK to vendors
- batch_no (VARCHAR(100)) - Batch number
- manufacturing_date (DATE) - Manufacturing date
- expiry_date (DATE) - Expiry date
- expiry_days (INT) - Generated column (DATEDIFF)
- purchase_price (DECIMAL(10,2)) - Purchase price
- selling_price (DECIMAL(10,2)) - Selling price
- quantity_purchased (INT) - Total purchased
- quantity_remaining (INT) - Remaining quantity
- purchase_date (DATE) - Purchase date
- invoice_number (VARCHAR(100)) - Invoice number
- quality_status (ENUM) - Quality status
- batch_status (ENUM) - Batch status
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at, updated_at (TIMESTAMP)
```

#### 7. **stocks** - Enhanced Stock Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- medicine_batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- location_type (ENUM) - Central/Center type
- location_id (BIGINT UNSIGNED) - FK to centers
- quantity (INT) - Current quantity
- reserved_quantity (INT) - Reserved quantity
- available_quantity (INT) - Generated column (quantity - reserved)
- last_movement_date (TIMESTAMP) - Last movement date
- status (ENUM) - Stock status
- created_at, updated_at (TIMESTAMP)
```

### **Transfer Management Tables**

#### 8. **stock_transfers** - Enhanced Transfer Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- transfer_number (VARCHAR(100)) - Transfer number (UNIQUE)
- from_type (ENUM) - Source type
- from_id (BIGINT UNSIGNED) - Source ID
- to_type (ENUM) - Destination type
- to_id (BIGINT UNSIGNED) - Destination ID
- transfer_date (DATE) - Transfer date
- expected_delivery_date (DATE) - Expected delivery
- actual_delivery_date (DATE) - Actual delivery
- total_items (INT) - Total items
- total_quantity (INT) - Total quantity
- total_value (DECIMAL(15,2)) - Total value
- status (ENUM) - Transfer status
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at, updated_at (TIMESTAMP)
```

#### 9. **stock_transfer_items** - Transfer Items
```sql
- id (BIGINT UNSIGNED) - Primary key
- stock_transfer_id (BIGINT UNSIGNED) - FK to stock_transfers
- medicine_batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- quantity (INT) - Quantity transferred
- unit_price (DECIMAL(10,2)) - Unit price
- total_price (DECIMAL(15,2)) - Total price
- remarks (TEXT) - Remarks
- created_at, updated_at (TIMESTAMP)
```

### **Sales Management Tables**

#### 10. **sales** - Enhanced Sales Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- sale_number (VARCHAR(100)) - Sale number (UNIQUE)
- center_id (BIGINT UNSIGNED) - FK to centers
- patient_id (VARCHAR(50)) - Patient ID
- patient_name (VARCHAR(255)) - Patient name
- sale_date (DATE) - Sale date
- total_items (INT) - Total items
- total_quantity (INT) - Total quantity
- total_amount (DECIMAL(15,2)) - Total amount
- discount_amount (DECIMAL(15,2)) - Discount amount
- net_amount (DECIMAL(15,2)) - Net amount
- payment_method (VARCHAR(50)) - Payment method
- payment_status (ENUM) - Payment status
- status (ENUM) - Sale status
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at, updated_at (TIMESTAMP)
```

#### 11. **sale_items** - Sales Items
```sql
- id (BIGINT UNSIGNED) - Primary key
- sale_id (BIGINT UNSIGNED) - FK to sales
- medicine_batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- quantity (INT) - Quantity sold
- unit_price (DECIMAL(10,2)) - Unit price
- total (DECIMAL(15,2)) - Total amount
- discount_amount (DECIMAL(15,2)) - Discount amount
- net_total (DECIMAL(15,2)) - Net total
- remarks (TEXT) - Remarks
- created_at, updated_at (TIMESTAMP)
```

### **Audit Trail Table**

#### 12. **stock_movements** - Complete Audit Trail
```sql
- id (BIGINT UNSIGNED) - Primary key
- medicine_batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- from_type (ENUM) - Source type
- from_id (BIGINT UNSIGNED) - Source ID
- to_type (ENUM) - Destination type
- to_id (BIGINT UNSIGNED) - Destination ID
- quantity (INT) - Movement quantity
- movement_type (ENUM) - In/Out type
- unit_price (DECIMAL(10,2)) - Unit price
- total_value (DECIMAL(15,2)) - Total value
- reference_table (VARCHAR(100)) - Reference table
- reference_id (BIGINT UNSIGNED) - Reference ID
- reference_number (VARCHAR(100)) - Reference number
- patient_id (VARCHAR(50)) - Patient ID
- patient_name (VARCHAR(255)) - Patient name
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at (TIMESTAMP) - Created timestamp
```

## 🔄 **FIFO Implementation**

### **Key Features:**

#### 1. **FIFO Ranking in Views**
```sql
-- FIFO ranking in v_current_stock_levels
ROW_NUMBER() OVER (
    PARTITION BY m.id, s.location_type, s.location_id 
    ORDER BY mb2.expiry_date ASC, mb2.created_at ASC
) as fifo_rank
```

#### 2. **FIFO Stored Procedures**
- **`sp_get_fifo_batches_for_sale`** - Get FIFO batches for sale
- **`sp_process_sale_with_fifo`** - Process sale using FIFO
- **`sp_transfer_stock_with_fifo`** - Transfer stock using FIFO

#### 3. **FIFO Allocation Logic**
```sql
-- FIFO allocation in procedures
ORDER BY mb.expiry_date ASC, mb.created_at ASC
```

## 📊 **Enhanced Views**

### 1. **v_current_stock_levels** - Current Stock with FIFO
```sql
-- Shows current stock levels with FIFO ranking
SELECT 
    medicine_id, medicine_name, generic_name, brand_name,
    batch_id, batch_no, expiry_date, expiry_days,
    purchase_price, selling_price, quantity_remaining,
    location_type, center_name, current_stock, available_quantity,
    expiry_status, fifo_rank
FROM v_current_stock_levels
WHERE location_type = 'center' AND location_id = 1
ORDER BY medicine_name, fifo_rank;
```

### 2. **v_medicine_stock_summary** - Medicine Summary
```sql
-- Shows summary statistics for each medicine
SELECT 
    medicine_id, medicine_name, generic_name, brand_name,
    total_batches, total_quantity, total_available,
    earliest_expiry, latest_expiry,
    avg_purchase_price, avg_selling_price, stock_status
FROM v_medicine_stock_summary
ORDER BY medicine_name;
```

### 3. **v_expired_stock_alerts** - Expiry Alerts
```sql
-- Shows expired and expiring stock
SELECT 
    medicine_name, brand_name, batch_no, expiry_date,
    expiry_days, current_stock, center_name, alert_level
FROM v_expired_stock_alerts
WHERE alert_level IN ('EXPIRED', 'CRITICAL')
ORDER BY expiry_date ASC;
```

## 🔧 **FIFO Stored Procedures**

### 1. **sp_get_fifo_batches_for_sale**
```sql
-- Get FIFO batches for sale
CALL sp_get_fifo_batches_for_sale(
    1,                    -- medicine_id
    'center',             -- location_type
    2,                    -- location_id
    50                    -- required_quantity
);
```

### 2. **sp_process_sale_with_fifo**
```sql
-- Process sale using FIFO
CALL sp_process_sale_with_fifo(
    2,                    -- center_id
    1,                    -- medicine_id
    50,                   -- required_quantity
    'PAT001',             -- patient_id
    'John Doe',           -- patient_name
    3,                    -- created_by
    'FIFO sale'           -- remarks
);
```

### 3. **sp_transfer_stock_with_fifo**
```sql
-- Transfer stock using FIFO
CALL sp_transfer_stock_with_fifo(
    'central',            -- from_type
    1,                    -- from_id
    'center',             -- to_type
    2,                    -- to_id
    1,                    -- medicine_id
    100,                  -- required_quantity
    3,                    -- created_by
    'FIFO transfer'       -- remarks
);
```

## 💻 **PHP Integration**

### **Model Structure**
```php
class Stock_model extends CI_Model {
    
    // FIFO operations
    public function get_fifo_batches_for_sale($medicine_id, $location_type, $location_id, $required_quantity) {
        $sql = "CALL sp_get_fifo_batches_for_sale(?, ?, ?, ?)";
        $result = $this->db->query($sql, [$medicine_id, $location_type, $location_id, $required_quantity]);
        return $result->result_array();
    }
    
    public function process_sale_with_fifo($data) {
        $sql = "CALL sp_process_sale_with_fifo(?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, [
            $data['center_id'],
            $data['medicine_id'],
            $data['required_quantity'],
            $data['patient_id'],
            $data['patient_name'],
            $data['created_by'],
            $data['remarks']
        ]);
        return $result->row_array();
    }
    
    public function transfer_stock_with_fifo($data) {
        $sql = "CALL sp_transfer_stock_with_fifo(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, [
            $data['from_type'],
            $data['from_id'],
            $data['to_type'],
            $data['to_id'],
            $data['medicine_id'],
            $data['required_quantity'],
            $data['created_by'],
            $data['remarks']
        ]);
        return $result->row_array();
    }
    
    // Stock level queries
    public function get_current_stock_levels($location_type = null, $location_id = null) {
        $this->db->select('*');
        $this->db->from('v_current_stock_levels');
        if($location_type) {
            $this->db->where('location_type', $location_type);
        }
        if($location_id) {
            $this->db->where('location_id', $location_id);
        }
        $this->db->order_by('medicine_name', 'ASC');
        $this->db->order_by('fifo_rank', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function get_medicine_stock_summary() {
        return $this->db->get('v_medicine_stock_summary')->result_array();
    }
    
    public function get_expired_stock_alerts($alert_level = null) {
        $this->db->select('*');
        $this->db->from('v_expired_stock_alerts');
        if($alert_level) {
            $this->db->where('alert_level', $alert_level);
        }
        $this->db->order_by('expiry_date', 'ASC');
        return $this->db->get()->result_array();
    }
}
```

### **Controller Structure**
```php
class Stocks extends CI_Controller {
    
    public function sell_medicine_fifo() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if(isset($_POST['action']) && $_POST['action'] == 'sell_fifo') {
                
                $data = [
                    'center_id' => $_POST['center_id'],
                    'medicine_id' => $_POST['medicine_id'],
                    'required_quantity' => $_POST['required_quantity'],
                    'patient_id' => $_POST['patient_id'],
                    'patient_name' => $_POST['patient_name'],
                    'created_by' => $_POST['created_by'],
                    'remarks' => $_POST['remarks']
                ];
                
                $result = $this->stock_model->process_sale_with_fifo($data);
                
                if($result['status'] == 'SUCCESS') {
                    $this->session->set_flashdata('success', 'Sale processed successfully using FIFO!');
                } else {
                    $this->session->set_flashdata('error', 'Error: ' . $result['message']);
                }
                
                redirect('stocks/sell_medicine_fifo');
            }
            
            // Load view with data
            $data['medicines'] = $this->stock_model->get_medicines();
            $data['centers'] = $this->stock_model->get_centers();
            
            $this->load->view('stocks/sell_medicine_fifo', $data);
        }
    }
    
    public function transfer_stock_fifo() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if(isset($_POST['action']) && $_POST['action'] == 'transfer_fifo') {
                
                $data = [
                    'from_type' => $_POST['from_type'],
                    'from_id' => $_POST['from_id'],
                    'to_type' => $_POST['to_type'],
                    'to_id' => $_POST['to_id'],
                    'medicine_id' => $_POST['medicine_id'],
                    'required_quantity' => $_POST['required_quantity'],
                    'created_by' => $_POST['created_by'],
                    'remarks' => $_POST['remarks']
                ];
                
                $result = $this->stock_model->transfer_stock_with_fifo($data);
                
                if($result['status'] == 'SUCCESS') {
                    $this->session->set_flashdata('success', 'Stock transfer completed using FIFO!');
                } else {
                    $this->session->set_flashdata('error', 'Error: ' . $result['message']);
                }
                
                redirect('stocks/transfer_stock_fifo');
            }
            
            // Load view with data
            $data['medicines'] = $this->stock_model->get_medicines();
            $data['centers'] = $this->stock_model->get_centers();
            
            $this->load->view('stocks/transfer_stock_fifo', $data);
        }
    }
}
```

## 📈 **Reporting Examples**

### 1. **FIFO Stock Report**
```sql
-- Get FIFO stock levels for a center
SELECT 
    medicine_name,
    batch_no,
    expiry_date,
    expiry_days,
    current_stock,
    available_quantity,
    fifo_rank,
    expiry_status
FROM v_current_stock_levels
WHERE location_type = 'center' 
AND location_id = 2
ORDER BY medicine_name, fifo_rank;
```

### 2. **Expiry Alerts Report**
```sql
-- Get expiring stock alerts
SELECT 
    medicine_name,
    brand_name,
    batch_no,
    expiry_date,
    expiry_days,
    current_stock,
    center_name,
    alert_level
FROM v_expired_stock_alerts
WHERE alert_level IN ('EXPIRED', 'CRITICAL', 'WARNING')
ORDER BY expiry_date ASC;
```

### 3. **Stock Summary Report**
```sql
-- Get medicine stock summary
SELECT 
    medicine_name,
    brand_name,
    total_batches,
    total_quantity,
    total_available,
    earliest_expiry,
    latest_expiry,
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

### 2. **FIFO Validation**
- Proper expiry date handling
- Batch status validation
- Quality status validation
- Quantity validation

### 3. **Audit Trail**
- Complete movement tracking
- Reference tracking
- User tracking
- Timestamp tracking

## 🚀 **Benefits**

### ✅ **Proper FIFO Implementation**
- **Expiry date prioritization** for stock rotation
- **Batch-level tracking** with FIFO ranking
- **Automatic allocation** using FIFO principles

### ✅ **Enhanced Performance**
- **BIGINT UNSIGNED** for better performance
- **Generated columns** for calculated fields
- **Optimized indexing** for FIFO queries

### ✅ **Complete Audit Trail**
- **Every movement tracked** in stock_movements
- **Reference tracking** for all operations
- **Patient information** in sales

### ✅ **Better Stock Management**
- **Reserved quantity** tracking
- **Available quantity** calculation
- **Last movement date** tracking

### ✅ **Scalable Design**
- **Modular structure** for easy extension
- **Proper relationships** between tables
- **Performance optimized** queries

This enhanced system provides a robust, FIFO-compliant solution for your HMS stock management requirements with proper audit trails, comprehensive reporting, and scalable architecture.
