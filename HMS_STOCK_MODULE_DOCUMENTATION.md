# Hospital Management System (HMS) Stock Module - Complete Documentation

## Overview
this is complete hms stocks management system flow 

## 🏗️ **Database Schema Design**

### **Master Tables (Reference Data)**

#### 1. **medicine_brands** - Medicine Brand Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- brand_name (VARCHAR(150)) - Brand name (UNIQUE)
- manufacturer (VARCHAR(200)) - Manufacturer name
- country_of_origin (VARCHAR(100)) - Country of origin
- description (TEXT) - Brand description
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

#### 2. **vendors** - Vendor/Supplier Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- vendor_code (VARCHAR(50)) - Vendor code (UNIQUE)
- vendor_name (VARCHAR(200)) - Vendor name
- contact_person (VARCHAR(100)) - Contact person
- phone (VARCHAR(20)) - Phone number
- email (VARCHAR(150)) - Email address
- address (TEXT) - Vendor address
- city, state, pincode - Location details
- gst_number (VARCHAR(50)) - GST number
- pan_number (VARCHAR(20)) - PAN number
- payment_terms (VARCHAR(100)) - Payment terms
- credit_limit (DECIMAL(15,2)) - Credit limit
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

#### 3. **medicines** - Medicine Master (Single Entry Per Medicine)
```sql
- id (BIGINT UNSIGNED) - Primary key
- medicine_code (VARCHAR(50)) - Medicine code (UNIQUE)
- brand_id (BIGINT UNSIGNED) - FK to medicine_brands
- medicine_name (VARCHAR(200)) - Medicine name
- generic_name (VARCHAR(200)) - Generic name
- strength (VARCHAR(50)) - Medicine strength
- unit (VARCHAR(50)) - Unit of measure
- category (VARCHAR(100)) - Medicine category
- pack_size (VARCHAR(100)) - Pack size
- hsn_code (VARCHAR(100)) - HSN code
- gst_rate (DECIMAL(5,2)) - GST rate
- min_stock_level (INT) - Minimum stock level
- max_stock_level (INT) - Maximum stock level
- reorder_level (INT) - Reorder level
- is_narcotic (BOOLEAN) - Narcotic flag
- is_controlled_substance (BOOLEAN) - Controlled substance flag
- is_psychotropic (BOOLEAN) - Psychotropic flag
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

#### 4. **centers** - 
```sql
- id (BIGINT UNSIGNED) - Primary key
- center_code (VARCHAR(50)) - Center code (UNIQUE)
- center_name (VARCHAR(150)) - Center name
- center_type (ENUM) - Central warehouse/Hospital/Pharmacy/Clinic
- address (TEXT) - Center address
- city, state, pincode - Location details
- contact_person (VARCHAR(100)) - Contact person
- phone (VARCHAR(20)) - Phone number
- email (VARCHAR(100)) - Email address
- is_central_warehouse (BOOLEAN) - Central warehouse flag
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

#### 5. **users** - User Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- username (VARCHAR(50)) - Username (UNIQUE)
- name (VARCHAR(255)) - Full name
- email (VARCHAR(150)) - Email (UNIQUE)
- phone (VARCHAR(20)) - Phone number
- role (ENUM) - Admin/Stock Manager/Pharmacist/Doctor/Nurse
- center_id (BIGINT UNSIGNED) - FK to centers
- status (ENUM) - Active/Inactive status
- created_at, updated_at (TIMESTAMP)
```

### **Batch Management Tables**

#### 6. **medicine_batches** - Batch Management (New Batch for Each Purchase)
```sql
- id (BIGINT UNSIGNED) - Primary key
- batch_number (VARCHAR(100)) - Batch number
- medicine_id (BIGINT UNSIGNED) - FK to medicines
- vendor_id (BIGINT UNSIGNED) - FK to vendors
- manufacturing_date (DATE) - Manufacturing date
- expiry_date (DATE) - Expiry date
- expiry_days (INT) - Generated column (DATEDIFF)
- purchase_price (DECIMAL(10,2)) - Purchase price
- selling_price (DECIMAL(10,2)) - Selling price
- mrp (DECIMAL(10,2)) - MRP
- quantity_purchased (INT) - Total purchased
- quantity_remaining (INT) - Remaining quantity
- purchase_date (DATE) - Purchase date
- invoice_number (VARCHAR(100)) - Invoice number
- invoice_date (DATE) - Invoice date
- quality_status (ENUM) - Quality status
- batch_status (ENUM) - Batch status
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at, updated_at (TIMESTAMP)
```

### **Stock Management Tables**

#### 7. **central_stocks** - Central Warehouse Stocks
```sql
- id (BIGINT UNSIGNED) - Primary key
- batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- quantity (INT) - Current quantity
- reserved_quantity (INT) - Reserved quantity
- available_quantity (INT) - Generated column (quantity - reserved)
- last_movement_date (TIMESTAMP) - Last movement date
- status (ENUM) - Stock status
- remarks (TEXT) - Remarks
- created_at, updated_at (TIMESTAMP)
```

#### 8. **center_stocks** - Branch/Center Stocks
```sql
- id (BIGINT UNSIGNED) - Primary key
- batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- center_id (BIGINT UNSIGNED) - FK to centers
- quantity (INT) - Current quantity
- reserved_quantity (INT) - Reserved quantity
- available_quantity (INT) - Generated column (quantity - reserved)
- last_movement_date (TIMESTAMP) - Last movement date
- status (ENUM) - Stock status
- remarks (TEXT) - Remarks
- created_at, updated_at (TIMESTAMP)
```

### **Transfer Management Tables**

#### 9. **stock_transfers** - Stock Transfer Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- transfer_number (VARCHAR(100)) - Transfer number (UNIQUE)
- transfer_type (ENUM) - Central to Center/Center to Center/Center to Central
- from_center_id (BIGINT UNSIGNED) - Source center ID
- to_center_id (BIGINT UNSIGNED) - Destination center ID
- transfer_date (DATE) - Transfer date
- expected_delivery_date (DATE) - Expected delivery date
- actual_delivery_date (DATE) - Actual delivery date
- total_items (INT) - Total items
- total_quantity (INT) - Total quantity
- total_value (DECIMAL(15,2)) - Total value
- status (ENUM) - Transfer status
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- approved_by (BIGINT UNSIGNED) - FK to users
- created_at, updated_at (TIMESTAMP)
```

#### 10. **stock_transfer_items** - Transfer Items
```sql
- id (BIGINT UNSIGNED) - Primary key
- transfer_id (BIGINT UNSIGNED) - FK to stock_transfers
- batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- quantity_transferred (INT) - Quantity transferred
- quantity_received (INT) - Quantity received
- unit_price (DECIMAL(10,2)) - Unit price
- total_price (DECIMAL(15,2)) - Total price
- remarks (TEXT) - Remarks
- created_at, updated_at (TIMESTAMP)
```

### **Sales Management Tables**

#### 11. **sales** - Sales Management
```sql
- id (BIGINT UNSIGNED) - Primary key
- sale_number (VARCHAR(100)) - Sale number (UNIQUE)
- center_id (BIGINT UNSIGNED) - FK to centers
- patient_id (VARCHAR(50)) - Patient ID
- patient_name (VARCHAR(255)) - Patient name
- doctor_id (BIGINT UNSIGNED) - FK to users (doctor)
- doctor_name (VARCHAR(255)) - Doctor name
- sale_date (DATE) - Sale date
- sale_time (TIME) - Sale time
- total_items (INT) - Total items
- total_quantity (INT) - Total quantity
- subtotal (DECIMAL(15,2)) - Subtotal
- discount_amount (DECIMAL(15,2)) - Discount amount
- tax_amount (DECIMAL(15,2)) - Tax amount
- total_amount (DECIMAL(15,2)) - Total amount
- payment_method (ENUM) - Payment method
- payment_status (ENUM) - Payment status
- status (ENUM) - Sale status
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at, updated_at (TIMESTAMP)
```

#### 12. **sale_items** - Sales Items
```sql
- id (BIGINT UNSIGNED) - Primary key
- sale_id (BIGINT UNSIGNED) - FK to sales
- batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- quantity_sold (INT) - Quantity sold
- unit_price (DECIMAL(10,2)) - Unit price
- subtotal (DECIMAL(15,2)) - Subtotal
- discount_amount (DECIMAL(15,2)) - Discount amount
- tax_amount (DECIMAL(15,2)) - Tax amount
- total (DECIMAL(15,2)) - Total
- remarks (TEXT) - Remarks
- created_at, updated_at (TIMESTAMP)
```

### **Audit Trail Table**

#### 13. **stock_movements** - Complete Audit Trail
```sql
- id (BIGINT UNSIGNED) - Primary key
- batch_id (BIGINT UNSIGNED) - FK to medicine_batches
- movement_type (ENUM) - Movement type
- from_location_type (ENUM) - Source location type
- from_location_id (BIGINT UNSIGNED) - Source location ID
- to_location_type (ENUM) - Destination location type
- to_location_id (BIGINT UNSIGNED) - Destination location ID
- quantity_before (INT) - Quantity before movement
- quantity_change (INT) - Quantity change
- quantity_after (INT) - Quantity after movement
- unit_price (DECIMAL(10,2)) - Unit price
- total_value (DECIMAL(15,2)) - Total value
- reference_type (ENUM) - Reference type
- reference_id (BIGINT UNSIGNED) - Reference ID
- reference_number (VARCHAR(100)) - Reference number
- patient_id (VARCHAR(50)) - Patient ID
- patient_name (VARCHAR(255)) - Patient name
- remarks (TEXT) - Remarks
- created_by (BIGINT UNSIGNED) - FK to users
- created_at (TIMESTAMP) - Created timestamp
```

## 🔄 **Workflow Explanation**

### **1. Add Stock (Purchase Process)**

#### **Step 1: Create Medicine (if new)**
```sql
-- Insert new medicine (only if not exists)
INSERT INTO medicines (
    medicine_code, brand_id, medicine_name, generic_name, 
    strength, unit, category, pack_size, hsn_code, gst_rate,
    min_stock_level, max_stock_level, status
) VALUES (
    'MED001', 1, 'Paracetamol 500mg', 'Paracetamol', 
    '500mg', 'tablet', 'Analgesic', '10x10', '30049099', 12.00,
    50, 500, 'active'
);
```

#### **Step 2: Create New Batch (for each purchase)**
```sql
-- Insert new batch (even for same medicine with different price/batch)
INSERT INTO medicine_batches (
    batch_number, medicine_id, vendor_id, manufacturing_date, 
    expiry_date, purchase_price, selling_price, mrp,
    quantity_purchased, quantity_remaining, purchase_date,
    invoice_number, invoice_date, quality_status, batch_status,
    created_by
) VALUES (
    'BATCH001', 1, 1, '2024-01-01', 
    '2026-01-01', 2.50, 5.00, 5.50,
    100, 100, '2024-01-15',
    'INV001', '2024-01-15', 'APPROVED', 'ACTIVE',
    1
);
```

#### **Step 3: Add to Central Stock**
```sql
-- Add to central warehouse
INSERT INTO central_stocks (
    batch_id, quantity, last_movement_date, status
) VALUES (
    1, 100, NOW(), 'ACTIVE'
);
```

#### **Step 4: Record Stock Movement**
```sql
-- Record purchase movement
INSERT INTO stock_movements (
    batch_id, movement_type, from_location_type, from_location_id,
    to_location_type, to_location_id, quantity_before, quantity_change,
    quantity_after, unit_price, total_value, reference_type,
    reference_number, created_by, created_at
) VALUES (
    1, 'PURCHASE', 'VENDOR', 1,
    'CENTRAL', 1, 0, 100,
    100, 2.50, 250.00, 'PURCHASE_RECEIPT',
    'INV001', 1, NOW()
);
```

### **2. Transfer Stock**

#### **Central → Center Transfer**
```sql
-- Create transfer record
INSERT INTO stock_transfers (
    transfer_number, transfer_type, from_center_id, to_center_id,
    transfer_date, total_items, total_quantity, status, created_by
) VALUES (
    'TR20240115001', 'CENTRAL_TO_CENTER', 1, 2,
    '2024-01-15', 1, 50, 'COMPLETED', 1
);

-- Add transfer item
INSERT INTO stock_transfer_items (
    transfer_id, batch_id, quantity_transferred, unit_price, total_price
) VALUES (
    1, 1, 50, 2.50, 125.00
);

-- Update central stock (reduce)
UPDATE central_stocks 
SET quantity = quantity - 50,
    last_movement_date = NOW()
WHERE batch_id = 1;

-- Add to center stock
INSERT INTO center_stocks (
    batch_id, center_id, quantity, last_movement_date, status
) VALUES (
    1, 2, 50, NOW(), 'ACTIVE'
);
```

#### **Center → Center Transfer**
```sql
-- Use stored procedure for FIFO/FEFO transfer
CALL sp_transfer_stock_with_fifo(
    2,                    -- from_center_id
    3,                    -- to_center_id
    1,                    -- medicine_id
    25,                   -- required_quantity
    1,                    -- created_by
    'FIFO',               -- fifo_type
    'Center to center transfer' -- remarks
);
```

### **3. Sell Stock (FIFO/FEFO)**

#### **Process Sale with FIFO**
```sql
-- Use stored procedure for FIFO sale
CALL sp_process_sale_with_fifo(
    2,                    -- center_id
    1,                    -- medicine_id
    30,                   -- required_quantity
    'PAT001',             -- patient_id
    'John Doe',           -- patient_name
    1,                    -- doctor_id
    1,                    -- created_by
    'FIFO',               -- fifo_type
    'FIFO sale'           -- remarks
);
```

#### **Process Sale with FEFO**
```sql
-- Use stored procedure for FEFO sale
CALL sp_process_sale_with_fifo(
    2,                    -- center_id
    1,                    -- medicine_id
    30,                   -- required_quantity
    'PAT002',             -- patient_id
    'Jane Smith',         -- patient_name
    1,                    -- doctor_id
    1,                    -- created_by
    'FEFO',               -- fifo_type
    'FEFO sale'           -- remarks
);
```

### **4. Batch Handling**

#### **New Batch for Same Medicine (Different Price)**
```sql
-- Same medicine, different batch, different price
INSERT INTO medicine_batches (
    batch_number, medicine_id, vendor_id, manufacturing_date, 
    expiry_date, purchase_price, selling_price, mrp,
    quantity_purchased, quantity_remaining, purchase_date,
    invoice_number, quality_status, batch_status, created_by
) VALUES (
    'BATCH002', 1, 1, '2024-02-01', 
    '2026-02-01', 2.75, 5.50, 6.00,  -- Different price
    150, 150, '2024-02-15',
    'INV002', 'APPROVED', 'ACTIVE', 1
);
```

#### **Batch Status Management**
```sql
-- Update batch status on expiry
UPDATE medicine_batches 
SET batch_status = 'EXPIRED'
WHERE expiry_date < CURDATE() 
AND batch_status = 'ACTIVE';

-- Update batch status for damaged stock
UPDATE medicine_batches 
SET batch_status = 'DAMAGED'
WHERE id = 1;
```

## 🔧 **FIFO/FEFO Implementation**

### **FIFO (First In, First Out)**
- **Order by**: `created_at ASC` (oldest batch first)
- **Use case**: General stock rotation
- **Priority**: Time-based

### **FEFO (First Expiry, First Out)**
- **Order by**: `expiry_date ASC` (earliest expiry first)
- **Use case**: Medicine/pharmaceutical stock
- **Priority**: Expiry-based

### **FIFO/FEFO Stored Procedures**

#### **1. Get FIFO/FEFO Batches for Sale**
```sql
CALL sp_get_fifo_batches_for_sale(
    1,                    -- medicine_id
    2,                    -- center_id
    50,                   -- required_quantity
    'FIFO'                -- fifo_type (FIFO/FEFO)
);
```

#### **2. Process Sale with FIFO/FEFO**
```sql
CALL sp_process_sale_with_fifo(
    2,                    -- center_id
    1,                    -- medicine_id
    30,                   -- required_quantity
    'PAT001',             -- patient_id
    'John Doe',           -- patient_name
    1,                    -- doctor_id
    1,                    -- created_by
    'FIFO',               -- fifo_type
    'FIFO sale'           -- remarks
);
```

#### **3. Transfer Stock with FIFO/FEFO**
```sql
CALL sp_transfer_stock_with_fifo(
    2,                    -- from_center_id
    3,                    -- to_center_id
    1,                    -- medicine_id
    25,                   -- required_quantity
    1,                    -- created_by
    'FIFO',               -- fifo_type
    'FIFO transfer'       -- remarks
);
```

## 📊 **Reporting Views**

### **1. Current Stock Levels with FIFO/FEFO**
```sql
SELECT 
    medicine_code, medicine_name, brand_name, batch_number,
    expiry_date, expiry_days, purchase_price, selling_price,
    central_quantity, center_quantity, center_name,
    expiry_status, fifo_rank
FROM v_current_stock_levels
WHERE center_id = 2
ORDER BY medicine_name, fifo_rank;
```

### **2. Medicine Stock Summary**
```sql
SELECT 
    medicine_code, medicine_name, brand_name, category,
    total_batches, total_central_quantity, total_center_quantity,
    total_quantity, earliest_expiry, latest_expiry,
    avg_purchase_price, avg_selling_price, stock_status
FROM v_medicine_stock_summary
ORDER BY medicine_name;
```

### **3. Expired Stock Alerts**
```sql
SELECT 
    medicine_code, medicine_name, brand_name, batch_number,
    expiry_date, expiry_days, central_quantity, center_quantity,
    center_name, alert_level
FROM v_expired_stock_alerts
WHERE alert_level IN ('EXPIRED', 'CRITICAL', 'WARNING')
ORDER BY expiry_date ASC;
```

## 💻 **PHP Integration Examples**

### **Model Structure**
```php
class Stock_model extends CI_Model {
    
    // FIFO/FEFO operations
    public function get_fifo_batches_for_sale($medicine_id, $center_id, $required_quantity, $fifo_type = 'FIFO') {
        $sql = "CALL sp_get_fifo_batches_for_sale(?, ?, ?, ?)";
        $result = $this->db->query($sql, [$medicine_id, $center_id, $required_quantity, $fifo_type]);
        return $result->result_array();
    }
    
    public function process_sale_with_fifo($data) {
        $sql = "CALL sp_process_sale_with_fifo(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, [
            $data['center_id'],
            $data['medicine_id'],
            $data['required_quantity'],
            $data['patient_id'],
            $data['patient_name'],
            $data['doctor_id'],
            $data['created_by'],
            $data['fifo_type'],
            $data['remarks']
        ]);
        return $result->row_array();
    }
    
    public function transfer_stock_with_fifo($data) {
        $sql = "CALL sp_transfer_stock_with_fifo(?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, [
            $data['from_center_id'],
            $data['to_center_id'],
            $data['medicine_id'],
            $data['required_quantity'],
            $data['created_by'],
            $data['fifo_type'],
            $data['remarks']
        ]);
        return $result->row_array();
    }
    
    // Stock level queries
    public function get_current_stock_levels($center_id = null) {
        $this->db->select('*');
        $this->db->from('v_current_stock_levels');
        if($center_id) {
            $this->db->where('center_id', $center_id);
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
                    'doctor_id' => $_POST['doctor_id'],
                    'created_by' => $_POST['created_by'],
                    'fifo_type' => $_POST['fifo_type'],
                    'remarks' => $_POST['remarks']
                ];
                
                $result = $this->stock_model->process_sale_with_fifo($data);
                
                if($result['status'] == 'SUCCESS') {
                    $this->session->set_flashdata('success', 'Sale processed successfully using ' . $data['fifo_type'] . '!');
                } else {
                    $this->session->set_flashdata('error', 'Error: ' . $result['message']);
                }
                
                redirect('stocks/sell_medicine_fifo');
            }
            
            // Load view with data
            $data['medicines'] = $this->stock_model->get_medicines();
            $data['centers'] = $this->stock_model->get_centers();
            $data['doctors'] = $this->stock_model->get_doctors();
            
            $this->load->view('stocks/sell_medicine_fifo', $data);
        }
    }
    
    public function transfer_stock_fifo() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if(isset($_POST['action']) && $_POST['action'] == 'transfer_fifo') {
                
                $data = [
                    'from_center_id' => $_POST['from_center_id'],
                    'to_center_id' => $_POST['to_center_id'],
                    'medicine_id' => $_POST['medicine_id'],
                    'required_quantity' => $_POST['required_quantity'],
                    'created_by' => $_POST['created_by'],
                    'fifo_type' => $_POST['fifo_type'],
                    'remarks' => $_POST['remarks']
                ];
                
                $result = $this->stock_model->transfer_stock_with_fifo($data);
                
                if($result['status'] == 'SUCCESS') {
                    $this->session->set_flashdata('success', 'Stock transfer completed using ' . $data['fifo_type'] . '!');
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

## 🔒 **Security Features**

### **1. Data Integrity**
- Foreign key constraints ensure referential integrity
- Unique constraints prevent duplicates
- Check constraints validate data ranges
- Generated columns for calculated fields

### **2. Audit Trail**
- Complete movement tracking in `stock_movements`
- Reference tracking for all operations
- User tracking for accountability
- Timestamp tracking for audit purposes

### **3. FIFO/FEFO Validation**
- Proper expiry date handling
- Batch status validation
- Quality status validation
- Quantity validation

## 🚀 **Benefits**

### ✅ **Complete Batch Tracking**
- **Each purchase creates new batch** (even for same medicine)
- **Batch-level pricing** and expiry tracking
- **No medicine duplication** - single medicine entry

### ✅ **FIFO/FEFO Stock Rotation**
- **FIFO**: First In, First Out (time-based)
- **FEFO**: First Expiry, First Out (expiry-based)
- **Automatic allocation** using stored procedures

### ✅ **Complete Audit Trail**
- **Every movement tracked** in stock_movements
- **Reference tracking** for all operations
- **Patient information** in sales

### ✅ **Scalable Design**
- **Normalized database** structure
- **Proper relationships** between tables
- **Performance optimized** queries

### ✅ **Comprehensive Reporting**
- **Pre-built views** for common queries
- **FIFO/FEFO ranking** in views
- **Expiry alerts** and stock summaries

