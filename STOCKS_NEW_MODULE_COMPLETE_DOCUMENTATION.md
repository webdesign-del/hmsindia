# STOCKS NEW MODULE - COMPLETE DOCUMENTATION

## Table of Contents
1. [Overview](#overview)
2. [Module Architecture](#module-architecture)
3. [Database Structure](#database-structure)
4. [Controller Methods](#controller-methods)
5. [Model Methods](#model-methods)
6. [View Components](#view-components)
7. [Key Features](#key-features)
8. [API Endpoints](#api-endpoints)
9. [User Interface](#user-interface)
10. [Workflow Processes](#workflow-processes)
11. [Integration Points](#integration-points)
12. [Security Features](#security-features)
13. [Performance Considerations](#performance-considerations)
14. [Troubleshooting](#troubleshooting)

---

## Overview

The **Stocks New Module** is a comprehensive inventory management system designed for healthcare facilities. It provides complete control over medicine inventory, stock movements, transfers, sales, and reporting. The module follows a hub-spoke model where central stock can be distributed to various centers.

### Key Capabilities:
- **Medicine Management**: Complete CRUD operations for medicines, brands, categories, and generic names
- **Batch Management**: Track medicine batches with expiry dates, FIFO management
- **Stock Transfers**: Transfer inventory between central and center locations
- **Sales Management**: Process medicine sales with patient tracking
- **Purchase Order Integration**: Add stock from completed purchase orders
- **Reporting & Analytics**: Comprehensive reporting with charts and analytics
- **Alerts & Notifications**: Low stock and expiry alerts
- **Audit Trail**: Complete tracking of all stock movements

---

## Module Architecture

### File Structure
```
application/
├── controllers/
│   └── Stocks_new.php          # Main controller (2,732 lines)
├── models/
│   └── Stock_model_new.php     # Data access layer (2,149 lines)
└── views/stocks_new/
    ├── dashboard.php           # Main dashboard
    ├── medicines.php          # Medicine listing
    ├── add_medicine.php       # Add new medicine
    ├── edit_medicine.php      # Edit medicine
    ├── batches.php            # Batch management
    ├── add_batch.php          # Add new batch
    ├── stock_levels.php       # Current stock levels
    ├── stock_summary.php      # Stock summary
    ├── transfers.php          # Transfer listing
    ├── add_transfer.php       # Create transfer
    ├── edit_transfer.php      # Edit transfer
    ├── sales.php              # Sales listing
    ├── add_sale.php           # Create sale
    ├── edit_sale.php          # Edit sale
    ├── reports.php            # Reports dashboard
    ├── stock_report.php       # Stock reports
    ├── sales_report.php       # Sales reports
    ├── transfer_report.php    # Transfer reports
    ├── low_stock_alerts.php   # Low stock alerts
    ├── expiry_alerts.php      # Expiry alerts
    ├── medicine_returns.php    # Medicine returns
    ├── stock_audit.php        # Stock audit
    ├── medicine_disposal.php   # Medicine disposal
    ├── invoices.php          # Invoice management
    ├── categories.php         # Medicine categories
    ├── add_category.php       # Add category
    ├── generic_names.php      # Generic names
    ├── add_generic_name.php   # Add generic name
    ├── vendor_returns.php     # Vendor returns
    ├── stock_tracking_panel.php # Stock tracking
    ├── stock_movements.php    # Stock movements
    ├── brands.php             # Brand management
    ├── add_brand.php          # Add brand
    ├── edit_brand.php         # Edit brand
    ├── vendors.php            # Vendor management
    ├── add_vendor.php         # Add vendor
    ├── edit_vendor.php        # Edit vendor
    ├── purchase_orders_for_stock.php # PO integration
    ├── add_stock_from_po.php  # Add stock from PO
    ├── po_stock_history.php   # PO stock history
    └── track_po_batches.php   # Track PO batches
```

### MVC Pattern Implementation
- **Model**: `Stock_model_new` handles all database operations
- **View**: Bootstrap 3 based responsive UI components
- **Controller**: `Stocks_new` manages business logic and user interactions

---

## Database Structure

### Core Tables

#### 1. Medicines Table
```sql
medicines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medicine_code VARCHAR(50) UNIQUE,
    medicine_name VARCHAR(255),
    generic_name VARCHAR(255),
    brand_id INT,
    strength VARCHAR(100),
    unit VARCHAR(50),
    category VARCHAR(100),
    pack_size VARCHAR(50),
    hsn_code VARCHAR(20),
    gst_rate DECIMAL(5,2),
    min_stock_level INT,
    max_stock_level INT,
    reorder_level INT,
    is_narcotic BOOLEAN,
    is_controlled_substance BOOLEAN,
    is_psychotropic BOOLEAN,
    status ENUM('active', 'inactive'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

#### 2. Medicine Batches Table
```sql
medicine_batches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medicine_id INT,
    batch_number VARCHAR(100),
    vendor_id INT,
    purchase_price DECIMAL(10,2),
    selling_price DECIMAL(10,2),
    quantity_purchased INT,
    quantity_remaining INT,
    expiry_date DATE,
    manufacturing_date DATE,
    invoice_number VARCHAR(100),
    batch_status ENUM('ACTIVE', 'EXPIRED', 'DISPOSED'),
    quality_status ENUM('PENDING', 'APPROVED', 'REJECTED'),
    expiry_days INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

#### 3. Stock Transfers Table
```sql
stock_transfers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transfer_number VARCHAR(50),
    transfer_type ENUM('CENTRAL_TO_CENTER', 'CENTER_TO_CENTER'),
    from_center_id INT,
    to_center_id INT,
    transfer_date DATE,
    status ENUM('PENDING', 'APPROVED', 'COMPLETED', 'CANCELLED'),
    total_value DECIMAL(10,2),
    notes TEXT,
    created_by INT,
    approved_by INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

#### 4. Sales Table
```sql
sales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sale_number VARCHAR(50),
    center_id INT,
    patient_id INT,
    patient_name VARCHAR(255),
    sale_date DATE,
    subtotal DECIMAL(10,2),
    tax_amount DECIMAL(10,2),
    total_amount DECIMAL(10,2),
    status ENUM('PENDING', 'CONFIRMED', 'CANCELLED'),
    created_by INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

#### 5. Stock Movements Table
```sql
stock_movements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    batch_id INT,
    movement_type ENUM('PURCHASE', 'TRANSFER_IN', 'TRANSFER_OUT', 'SALE', 'RETURN', 'DISPOSAL'),
    from_location_type ENUM('CENTRAL', 'CENTER', 'VENDOR'),
    from_location_id INT,
    to_location_type ENUM('CENTRAL', 'CENTER', 'SALE'),
    to_location_id INT,
    quantity_change INT,
    quantity_after INT,
    unit_price DECIMAL(10,2),
    total_value DECIMAL(10,2),
    reference_type VARCHAR(50),
    reference_id INT,
    reference_number VARCHAR(100),
    patient_id INT,
    patient_name VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP
)
```

### Supporting Tables
- `medicine_categories` - Medicine categories
- `generic_names` - Generic medicine names
- `medicine_brands` - Medicine brands
- `vendors` - Vendor information
- `centers` - Center/location information
- `central_stocks` - Central warehouse stock
- `center_stocks` - Center-specific stock
- `sale_items` - Individual sale line items
- `stock_transfer_items` - Individual transfer line items
- `vendor_returns` - Vendor return records
- `audit_reports` - Stock audit reports
- `disposal_reports` - Medicine disposal reports

---

## Controller Methods

### Dashboard Methods
- `dashboard()` - Main dashboard with summary statistics
- `get_dashboard_data()` - AJAX endpoint for dashboard data

### Medicine Management
- `medicines()` - List all medicines
- `add_medicine()` - Add new medicine
- `edit_medicine($id)` - Edit existing medicine
- `save_medicine()` - AJAX save medicine

### Batch Management
- `batches()` - List all batches
- `add_batch()` - Add new batch
- `save_batch()` - AJAX save batch

### Stock Level Management
- `stock_levels()` - View current stock levels
- `stock_summary()` - Stock summary by medicine

### Transfer Management
- `transfers()` - List all transfers
- `add_transfer()` - Create new transfer
- `edit_transfer($id)` - Edit transfer
- `approve_transfer($id)` - Approve transfer
- `save_transfer()` - AJAX save transfer

### Sales Management
- `sales()` - List all sales
- `add_sale()` - Create new sale
- `edit_sale($id)` - Edit sale
- `confirm_sale($id)` - Confirm sale
- `save_sale()` - AJAX save sale

### Reporting
- `reports()` - Reports dashboard
- `stock_report()` - Stock reports
- `sales_report()` - Sales reports
- `transfer_report()` - Transfer reports
- `todays_sales()` - Today's sales
- `weekly_sales()` - Weekly sales
- `monthly_sales()` - Monthly sales
- `todays_transfers()` - Today's transfers
- `weekly_transfers()` - Weekly transfers
- `monthly_transfers()` - Monthly transfers

### Alerts & Monitoring
- `low_stock_alerts()` - Low stock alerts
- `expiry_alerts()` - Expiry alerts

### Returns & Disposal
- `medicine_returns()` - Medicine returns
- `process_return()` - Process return
- `returns()` - Returns listing
- `stock_audit()` - Stock audit
- `process_audit()` - Process audit
- `audit_reports()` - Audit reports
- `medicine_disposal()` - Medicine disposal
- `process_disposal()` - Process disposal
- `disposal_reports()` - Disposal reports

### Invoice Management
- `invoices()` - Invoice listing
- `add_invoice()` - Add invoice
- `edit_invoice($invoice_id)` - Edit invoice
- `approve_invoice($invoice_id)` - Approve invoice
- `print_invoice($invoice_id)` - Print invoice
- `save_invoice()` - AJAX save invoice

### Category Management
- `categories()` - Medicine categories
- `add_category()` - Add category
- `edit_category($category_id)` - Edit category
- `activate_category($category_id)` - Activate category
- `deactivate_category($category_id)` - Deactivate category
- `save_category()` - AJAX save category

### Generic Name Management
- `generic_names()` - Generic names
- `add_generic_name()` - Add generic name
- `edit_generic_name($generic_id)` - Edit generic name
- `activate_generic_name($generic_id)` - Activate generic name
- `deactivate_generic_name($generic_id)` - Deactivate generic name
- `save_generic_name()` - AJAX save generic name

### Vendor Management
- `vendors()` - Vendor listing
- `add_vendor()` - Add vendor
- `edit_vendor($id)` - Edit vendor
- `delete_vendor($id)` - Delete vendor
- `get_vendor_details($id)` - Get vendor details
- `view_document($document_type, $vendor_id)` - View vendor document
- `download_document($document_type, $vendor_id)` - Download vendor document

### Vendor Returns
- `vendor_returns()` - Vendor returns
- `vendor_return_reports()` - Vendor return reports
- `add_vendor_return()` - Add vendor return
- `save_vendor_return()` - AJAX save vendor return

### Brand Management
- `brands()` - Brand listing
- `add_brand()` - Add brand
- `edit_brand($id)` - Edit brand
- `delete_brand($id)` - Delete brand

### Stock Tracking
- `stock_tracking_panel()` - Stock tracking panel
- `get_stock_movements()` - Get stock movements
- `get_transfers()` - Get transfers
- `get_sales()` - Get sales
- `get_summary_stats()` - Get summary statistics
- `search_stock_movements()` - Search stock movements
- `export_stock_report()` - Export stock report
- `stock_movements()` - Stock movements listing

### Purchase Order Integration
- `purchase_orders_for_stock()` - Purchase orders for stock
- `add_stock_from_po($po_id)` - Add stock from purchase order
- `process_stock_from_po()` - Process stock from PO
- `po_stock_history()` - PO stock history
- `track_po_batches($po_id)` - Track PO batches

### AJAX Endpoints
- `get_medicine_details()` - Get medicine details
- `get_batch_details()` - Get batch details
- `get_available_stock()` - Get available stock

---

## Model Methods

### Dashboard Functions
- `get_dashboard_summary()` - Get dashboard summary statistics
- `get_low_stock_alerts()` - Get low stock alerts
- `get_expiry_alerts()` - Get expiry alerts
- `get_recent_sales($limit)` - Get recent sales
- `get_recent_transfers($limit)` - Get recent transfers
- `get_sales_analytics($days)` - Get sales analytics
- `get_transfer_analytics($days)` - Get transfer analytics
- `get_top_selling_medicines($limit)` - Get top selling medicines
- `get_center_stock_summary()` - Get center stock summary

### Medicine Brand Functions
- `get_medicine_brands()` - Get all medicine brands
- `add_medicine_brand($data)` - Add medicine brand
- `update_medicine_brand($id, $data)` - Update medicine brand
- `get_medicine_brand_by_id($id)` - Get brand by ID

### Vendor Functions
- `get_vendors()` - Get all vendors
- `add_vendor($data)` - Add vendor
- `update_vendor($id, $data)` - Update vendor
- `generate_vendor_number()` - Generate vendor number
- `handle_vendor_file_upload($file_input_name, $vendor_id)` - Handle file upload
- `get_vendor_by_id($id)` - Get vendor by ID

### Medicine Functions
- `get_all_medicines()` - Get all medicines
- `add_medicine($data)` - Add medicine
- `update_medicine($id, $data)` - Update medicine
- `get_medicine_by_id($id)` - Get medicine by ID

### Batch Functions
- `get_all_batches()` - Get all batches
- `add_batch($data)` - Add batch
- `get_batch_by_id($id)` - Get batch by ID

### Stock Level Functions
- `get_current_stock_levels($center_id)` - Get current stock levels
- `get_stock_levels_from_tables($center_id)` - Get stock levels from tables
- `get_medicine_stock_summary()` - Get medicine stock summary
- `get_available_stock($batch_id, $center_id)` - Get available stock

### Stock Transfer Functions
- `get_all_transfers()` - Get all transfers
- `add_transfer($data)` - Add transfer
- `get_transfer_by_id($id)` - Get transfer by ID
- `get_transfer_items($transfer_id)` - Get transfer items
- `add_transfer_item($data)` - Add transfer item
- `approve_transfer($id, $approved_by)` - Approve transfer
- `get_available_batches_for_transfer($center_id)` - Get available batches for transfer

### Sales Functions
- `get_all_sales()` - Get all sales
- `add_sale($data)` - Add sale
- `get_sale_by_id($id)` - Get sale by ID
- `get_sale_items($sale_id)` - Get sale items
- `add_sale_item($data)` - Add sale item
- `confirm_sale($id)` - Confirm sale
- `get_available_batches_for_sale($center_id)` - Get available batches for sale

### Report Functions
- `get_sales_report($start_date, $end_date, $center_id)` - Get sales report
- `get_transfer_report($start_date, $end_date, $transfer_type, $from_center_id, $to_center_id)` - Get transfer report
- `get_available_batches_for_return()` - Get available batches for return
- `get_available_batches_for_audit()` - Get available batches for audit
- `get_available_batches_for_disposal()` - Get available batches for disposal
- `get_audit_reports()` - Get audit reports
- `get_disposal_reports()` - Get disposal reports
- `get_invoices()` - Get invoices
- `get_available_batches_for_invoice()` - Get available batches for invoice

### Category Functions
- `get_categories()` - Get categories
- `add_category($data)` - Add category
- `update_category($id, $data)` - Update category
- `update_category_status($id, $status)` - Update category status

### Generic Name Functions
- `get_generic_names()` - Get generic names
- `add_generic_name($data)` - Add generic name
- `update_generic_name($id, $data)` - Update generic name
- `update_generic_name_status($id, $status)` - Update generic name status

### Vendor Return Functions
- `get_vendor_returns()` - Get vendor returns
- `get_vendor_return_reports($vendor_id, $status, $from_date, $to_date)` - Get vendor return reports
- `get_vendor_return_summary_stats($vendor_id, $status, $from_date, $to_date)` - Get vendor return summary stats
- `get_available_batches_for_vendor_return()` - Get available batches for vendor return

### Purchase Order Integration Functions
- `get_purchase_order_details($po_id)` - Get purchase order details
- `get_batches_from_purchase_order($po_id)` - Get batches from purchase order
- `get_po_batch_summary($po_id)` - Get PO batch summary
- `get_purchase_orders_for_stock_addition()` - Get purchase orders for stock addition
- `get_purchase_order_items($po_id)` - Get purchase order items
- `add_stock_from_purchase_order($po_id, $stock_items)` - Add stock from purchase order
- `check_existing_stock_item($item_name, $batch_number, $vendor_number)` - Check existing stock item
- `update_stock_quantity($stock_id, $quantity, $stock_data)` - Update stock quantity
- `insert_stock_item($stock_data)` - Insert stock item
- `create_vendor_billing_record($po_id, $item, $batch_id)` - Create vendor billing record
- `get_vendor_name($vendor_number)` - Get vendor name
- `get_purchase_order_for_stock_addition($po_id)` - Get purchase order for stock addition
- `check_purchase_order_items_exist($po_id)` - Check purchase order items exist
- `get_processed_purchase_orders()` - Get processed purchase orders

### Stock Tracking Functions
- `get_stock_movements()` - Get stock movements
- `get_stock_movements_by_batch($batch_id)` - Get stock movements by batch
- `search_stock_movements($filters)` - Search stock movements
- `get_summary_stats()` - Get summary statistics
- `export_stock_report($filters)` - Export stock report

### Medicine Return Functions
- `process_medicine_return($return_data, $return_items)` - Process medicine return
- `get_medicine_returns()` - Get medicine returns

### Center Functions
- `get_all_centers()` - Get all centers
- `add_center($data)` - Add center
- `update_center($id, $data)` - Update center
- `get_center_by_id($id)` - Get center by ID

### Helper Functions
- `calculate_expiry_days($expiry_date)` - Calculate expiry days
- `update_expiry_days()` - Update expiry days for all batches

---

## View Components

### Dashboard (`dashboard.php`)
- **Summary Cards**: Total medicines, batches, low stock count, expiring items
- **Charts**: Sales analytics, transfer analytics, top selling medicines
- **Recent Activity**: Recent sales and transfers
- **Alerts**: Low stock and expiry alerts
- **Quick Actions**: Direct links to common operations

### Medicine Management
- **`medicines.php`**: Medicine listing with search and filter
- **`add_medicine.php`**: Form to add new medicine
- **`edit_medicine.php`**: Form to edit existing medicine

### Batch Management
- **`batches.php`**: Batch listing with expiry information
- **`add_batch.php`**: Form to add new batch

### Stock Management
- **`stock_levels.php`**: Current stock levels by medicine
- **`stock_summary.php`**: Stock summary with FIFO ranking

### Transfer Management
- **`transfers.php`**: Transfer listing with status
- **`add_transfer.php`**: Form to create new transfer
- **`edit_transfer.php`**: Form to edit transfer

### Sales Management
- **`sales.php`**: Sales listing
- **`add_sale.php`**: Form to create new sale
- **`edit_sale.php`**: Form to edit sale

### Reporting
- **`reports.php`**: Reports dashboard
- **`stock_report.php`**: Stock reports
- **`sales_report.php`**: Sales reports
- **`transfer_report.php`**: Transfer reports

### Alerts
- **`low_stock_alerts.php`**: Low stock alerts
- **`expiry_alerts.php`**: Expiry alerts

### Returns & Disposal
- **`medicine_returns.php`**: Medicine returns
- **`stock_audit.php`**: Stock audit
- **`medicine_disposal.php`**: Medicine disposal

### Invoice Management
- **`invoices.php`**: Invoice listing
- **`add_invoice.php`**: Form to add invoice

### Category Management
- **`categories.php`**: Category listing
- **`add_category.php`**: Form to add category

### Generic Name Management
- **`generic_names.php`**: Generic name listing
- **`add_generic_name.php`**: Form to add generic name

### Vendor Management
- **`vendors.php`**: Vendor listing
- **`add_vendor.php`**: Form to add vendor
- **`edit_vendor.php`**: Form to edit vendor

### Vendor Returns
- **`vendor_returns.php`**: Vendor returns
- **`vendor_return_reports.php`**: Vendor return reports
- **`add_vendor_return.php`**: Form to add vendor return

### Brand Management
- **`brands.php`**: Brand listing
- **`add_brand.php`**: Form to add brand
- **`edit_brand.php`**: Form to edit brand

### Stock Tracking
- **`stock_tracking_panel.php`**: Stock tracking panel
- **`stock_movements.php`**: Stock movements listing

### Purchase Order Integration
- **`purchase_orders_for_stock.php`**: Purchase orders for stock
- **`add_stock_from_po.php`**: Form to add stock from PO
- **`po_stock_history.php`**: PO stock history
- **`track_po_batches.php`**: Track PO batches

---

## Key Features

### 1. Dashboard Analytics
- **Real-time Statistics**: Total medicines, batches, stock value
- **Alert System**: Low stock and expiry alerts
- **Performance Metrics**: Sales and transfer analytics
- **Quick Actions**: Direct access to common operations

### 2. Medicine Management
- **Complete CRUD**: Create, read, update, delete medicines
- **Brand Association**: Link medicines to brands
- **Generic Names**: Track generic medicine names
- **Categories**: Organize medicines by categories
- **Regulatory Compliance**: Track narcotic, controlled substances
- **Stock Levels**: Set minimum, maximum, and reorder levels

### 3. Batch Management
- **FIFO Implementation**: First In, First Out inventory management
- **Expiry Tracking**: Automatic expiry date monitoring
- **Quality Control**: Quality status tracking
- **Vendor Integration**: Link batches to vendors
- **Invoice Tracking**: Track purchase invoices

### 4. Stock Transfer System
- **Hub-Spoke Model**: Central to center transfers
- **Center-to-Center**: Direct center transfers
- **Approval Workflow**: Multi-level approval process
- **Real-time Tracking**: Track transfer status
- **Batch-level Tracking**: Track individual batch movements

### 5. Sales Management
- **Patient Integration**: Link sales to patients
- **Center-specific Sales**: Track sales by center
- **Batch Tracking**: Track which batches were sold
- **FIFO Sales**: Automatic FIFO batch selection
- **Confirmation Process**: Sales confirmation workflow

### 6. Purchase Order Integration
- **Seamless Integration**: Add stock from completed POs
- **Batch Creation**: Automatic batch creation from PO items
- **Vendor Billing**: Automatic vendor billing record creation
- **Stock Addition**: Direct stock addition to inventory
- **History Tracking**: Complete PO stock history

### 7. Reporting & Analytics
- **Comprehensive Reports**: Stock, sales, transfer reports
- **Date Range Filtering**: Flexible date range selection
- **Export Capabilities**: CSV export functionality
- **Visual Analytics**: Charts and graphs
- **Performance Metrics**: Key performance indicators

### 8. Alert System
- **Low Stock Alerts**: Automatic low stock notifications
- **Expiry Alerts**: Medicine expiry notifications
- **Configurable Thresholds**: Customizable alert thresholds
- **Priority Levels**: Critical, warning, and info alerts

### 9. Audit Trail
- **Complete Tracking**: All stock movements tracked
- **User Attribution**: Track who performed actions
- **Timestamp Recording**: Precise timing of all actions
- **Reference Linking**: Link movements to source documents

### 10. Security Features
- **Role-based Access**: Different access levels
- **Session Management**: Secure session handling
- **Input Validation**: Comprehensive input validation
- **SQL Injection Prevention**: Parameterized queries

---

## API Endpoints

### Dashboard Endpoints
- `GET /stocks_new/dashboard` - Main dashboard
- `GET /stocks_new/get_dashboard_data` - Dashboard data (AJAX)

### Medicine Endpoints
- `GET /stocks_new/medicines` - List medicines
- `GET /stocks_new/add_medicine` - Add medicine form
- `POST /stocks_new/add_medicine` - Create medicine
- `GET /stocks_new/edit_medicine/{id}` - Edit medicine form
- `POST /stocks_new/edit_medicine/{id}` - Update medicine
- `POST /stocks_new/save_medicine` - Save medicine (AJAX)

### Batch Endpoints
- `GET /stocks_new/batches` - List batches
- `GET /stocks_new/add_batch` - Add batch form
- `POST /stocks_new/add_batch` - Create batch
- `POST /stocks_new/save_batch` - Save batch (AJAX)

### Stock Level Endpoints
- `GET /stocks_new/stock_levels` - Current stock levels
- `GET /stocks_new/stock_summary` - Stock summary

### Transfer Endpoints
- `GET /stocks_new/transfers` - List transfers
- `GET /stocks_new/add_transfer` - Add transfer form
- `POST /stocks_new/add_transfer` - Create transfer
- `GET /stocks_new/edit_transfer/{id}` - Edit transfer form
- `POST /stocks_new/edit_transfer/{id}` - Update transfer
- `POST /stocks_new/approve_transfer/{id}` - Approve transfer
- `POST /stocks_new/save_transfer` - Save transfer (AJAX)

### Sales Endpoints
- `GET /stocks_new/sales` - List sales
- `GET /stocks_new/add_sale` - Add sale form
- `POST /stocks_new/add_sale` - Create sale
- `GET /stocks_new/edit_sale/{id}` - Edit sale form
- `POST /stocks_new/edit_sale/{id}` - Update sale
- `POST /stocks_new/confirm_sale/{id}` - Confirm sale
- `POST /stocks_new/save_sale` - Save sale (AJAX)

### Report Endpoints
- `GET /stocks_new/reports` - Reports dashboard
- `GET /stocks_new/stock_report` - Stock reports
- `GET /stocks_new/sales_report` - Sales reports
- `GET /stocks_new/transfer_report` - Transfer reports
- `GET /stocks_new/todays_sales` - Today's sales
- `GET /stocks_new/weekly_sales` - Weekly sales
- `GET /stocks_new/monthly_sales` - Monthly sales
- `GET /stocks_new/todays_transfers` - Today's transfers
- `GET /stocks_new/weekly_transfers` - Weekly transfers
- `GET /stocks_new/monthly_transfers` - Monthly transfers

### Alert Endpoints
- `GET /stocks_new/low_stock_alerts` - Low stock alerts
- `GET /stocks_new/expiry_alerts` - Expiry alerts

### Return & Disposal Endpoints
- `GET /stocks_new/medicine_returns` - Medicine returns
- `POST /stocks_new/process_return` - Process return
- `GET /stocks_new/returns` - Returns listing
- `GET /stocks_new/stock_audit` - Stock audit
- `POST /stocks_new/process_audit` - Process audit
- `GET /stocks_new/audit_reports` - Audit reports
- `GET /stocks_new/medicine_disposal` - Medicine disposal
- `POST /stocks_new/process_disposal` - Process disposal
- `GET /stocks_new/disposal_reports` - Disposal reports

### Invoice Endpoints
- `GET /stocks_new/invoices` - Invoice listing
- `GET /stocks_new/add_invoice` - Add invoice form
- `POST /stocks_new/add_invoice` - Create invoice
- `GET /stocks_new/edit_invoice/{invoice_id}` - Edit invoice form
- `POST /stocks_new/approve_invoice/{invoice_id}` - Approve invoice
- `GET /stocks_new/print_invoice/{invoice_id}` - Print invoice
- `POST /stocks_new/save_invoice` - Save invoice (AJAX)

### Category Endpoints
- `GET /stocks_new/categories` - Category listing
- `GET /stocks_new/add_category` - Add category form
- `POST /stocks_new/add_category` - Create category
- `GET /stocks_new/edit_category/{category_id}` - Edit category form
- `POST /stocks_new/activate_category/{category_id}` - Activate category
- `POST /stocks_new/deactivate_category/{category_id}` - Deactivate category
- `POST /stocks_new/save_category` - Save category (AJAX)

### Generic Name Endpoints
- `GET /stocks_new/generic_names` - Generic name listing
- `GET /stocks_new/add_generic_name` - Add generic name form
- `POST /stocks_new/add_generic_name` - Create generic name
- `GET /stocks_new/edit_generic_name/{generic_id}` - Edit generic name form
- `POST /stocks_new/activate_generic_name/{generic_id}` - Activate generic name
- `POST /stocks_new/deactivate_generic_name/{generic_id}` - Deactivate generic name
- `POST /stocks_new/save_generic_name` - Save generic name (AJAX)

### Vendor Endpoints
- `GET /stocks_new/vendors` - Vendor listing
- `GET /stocks_new/add_vendor` - Add vendor form
- `POST /stocks_new/add_vendor` - Create vendor
- `GET /stocks_new/edit_vendor/{id}` - Edit vendor form
- `POST /stocks_new/delete_vendor/{id}` - Delete vendor
- `GET /stocks_new/get_vendor_details/{id}` - Get vendor details (AJAX)
- `GET /stocks_new/view_document/{document_type}/{vendor_id}` - View vendor document
- `GET /stocks_new/download_document/{document_type}/{vendor_id}` - Download vendor document

### Vendor Return Endpoints
- `GET /stocks_new/vendor_returns` - Vendor returns
- `GET /stocks_new/vendor_return_reports` - Vendor return reports
- `GET /stocks_new/add_vendor_return` - Add vendor return form
- `POST /stocks_new/save_vendor_return` - Save vendor return (AJAX)

### Brand Endpoints
- `GET /stocks_new/brands` - Brand listing
- `GET /stocks_new/add_brand` - Add brand form
- `POST /stocks_new/add_brand` - Create brand
- `GET /stocks_new/edit_brand/{id}` - Edit brand form
- `POST /stocks_new/delete_brand/{id}` - Delete brand

### Stock Tracking Endpoints
- `GET /stocks_new/stock_tracking_panel` - Stock tracking panel
- `GET /stocks_new/get_stock_movements` - Get stock movements (AJAX)
- `GET /stocks_new/get_transfers` - Get transfers (AJAX)
- `GET /stocks_new/get_sales` - Get sales (AJAX)
- `GET /stocks_new/get_summary_stats` - Get summary stats (AJAX)
- `POST /stocks_new/search_stock_movements` - Search stock movements
- `GET /stocks_new/export_stock_report` - Export stock report
- `GET /stocks_new/stock_movements` - Stock movements listing

### Purchase Order Integration Endpoints
- `GET /stocks_new/purchase_orders_for_stock` - Purchase orders for stock
- `GET /stocks_new/add_stock_from_po/{po_id}` - Add stock from PO form
- `POST /stocks_new/process_stock_from_po` - Process stock from PO
- `GET /stocks_new/po_stock_history` - PO stock history
- `GET /stocks_new/track_po_batches/{po_id}` - Track PO batches

### AJAX Data Endpoints
- `GET /stocks_new/get_medicine_details` - Get medicine details (AJAX)
- `GET /stocks_new/get_batch_details` - Get batch details (AJAX)
- `GET /stocks_new/get_available_stock` - Get available stock (AJAX)

---

## User Interface

### Design Principles
- **Bootstrap 3 Framework**: Consistent, responsive design
- **Mobile-First**: Responsive design for all devices
- **User-Friendly**: Intuitive navigation and workflows
- **Accessibility**: WCAG compliant interface elements

### Layout Structure
- **Header**: Navigation menu with role-based access
- **Sidebar**: Quick navigation menu
- **Main Content**: Dynamic content area
- **Footer**: System information and links

### Color Scheme
- **Primary**: Blue (#337ab7) - Main actions
- **Success**: Green (#5cb85c) - Success states
- **Warning**: Yellow (#f0ad4e) - Warning states
- **Danger**: Red (#d9534f) - Error states
- **Info**: Light Blue (#5bc0de) - Information

### Component Library
- **Panels**: Content containers with headers
- **Forms**: Consistent form styling
- **Tables**: Data tables with sorting and filtering
- **Buttons**: Action buttons with icons
- **Alerts**: Status messages and notifications
- **Modals**: Popup dialogs for confirmations
- **Charts**: Data visualization components

### Responsive Breakpoints
- **Mobile**: < 768px
- **Tablet**: 768px - 992px
- **Desktop**: > 992px

---

## Workflow Processes

### 1. Medicine Addition Workflow
1. **Access Add Medicine Form**: Navigate to add medicine page
2. **Fill Medicine Details**: Enter medicine information
3. **Validation**: Client and server-side validation
4. **Save Medicine**: Store in database
5. **Confirmation**: Success message and redirect

### 2. Batch Addition Workflow
1. **Select Medicine**: Choose from existing medicines
2. **Enter Batch Details**: Batch number, expiry, quantities
3. **Vendor Information**: Link to vendor
4. **Quality Check**: Set quality status
5. **Stock Addition**: Add to central stock
6. **Movement Logging**: Log stock movement

### 3. Stock Transfer Workflow
1. **Create Transfer**: Select transfer type and centers
2. **Add Items**: Select batches and quantities
3. **Submit for Approval**: Transfer status = PENDING
4. **Approval Process**: Authorized user approves
5. **Stock Movement**: Update stock levels
6. **Completion**: Transfer status = COMPLETED

### 4. Sales Process Workflow
1. **Create Sale**: Select center and patient
2. **Add Items**: Select batches and quantities
3. **Calculate Totals**: Automatic calculation
4. **Confirm Sale**: Update stock levels
5. **Movement Logging**: Log sales movement
6. **Patient Billing**: Link to billing system

### 5. Purchase Order Integration Workflow
1. **Select PO**: Choose completed purchase order
2. **Review Items**: Verify PO items
3. **Add to Stock**: Create batches and add stock
4. **Vendor Billing**: Create billing records
5. **Mark Processed**: Update PO status
6. **History Tracking**: Maintain PO stock history

### 6. Return Process Workflow
1. **Initiate Return**: Select reason and items
2. **Approval Process**: Authorize return
3. **Stock Update**: Restore stock levels
4. **Movement Logging**: Log return movement
5. **Documentation**: Generate return documents

### 7. Audit Process Workflow
1. **Schedule Audit**: Set audit parameters
2. **Physical Count**: Count actual stock
3. **System Comparison**: Compare with system records
4. **Variance Analysis**: Identify discrepancies
5. **Adjustment**: Update stock levels
6. **Report Generation**: Create audit report

---

## Integration Points

### 1. Purchase Order System
- **Data Flow**: PO completion → Stock addition
- **API Integration**: Direct database integration
- **Batch Creation**: Automatic batch creation
- **Vendor Billing**: Automatic billing record creation

### 2. Patient Management System
- **Sales Integration**: Link sales to patients
- **Billing Integration**: Connect to billing system
- **Prescription Tracking**: Track prescribed medicines

### 3. Center Management System
- **Location Tracking**: Track stock by center
- **Transfer Management**: Center-to-center transfers
- **Reporting**: Center-specific reports

### 4. Vendor Management System
- **Vendor Information**: Shared vendor data
- **Purchase History**: Track vendor purchases
- **Return Management**: Handle vendor returns

### 5. Billing System
- **Sales Integration**: Automatic billing generation
- **Invoice Management**: Create and manage invoices
- **Payment Tracking**: Track payment status

### 6. Reporting System
- **Data Export**: Export data for external reporting
- **Analytics Integration**: Connect to analytics tools
- **Dashboard Integration**: Share data with main dashboard

---

## Security Features

### 1. Authentication & Authorization
- **Session Management**: Secure session handling
- **Role-based Access**: Different access levels
- **Permission Control**: Granular permissions
- **Login Validation**: Secure login process

### 2. Data Security
- **Input Validation**: Comprehensive input validation
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping
- **CSRF Protection**: Cross-site request forgery protection

### 3. File Security
- **File Upload Validation**: Secure file uploads
- **File Type Restrictions**: Allowed file types
- **File Size Limits**: Maximum file sizes
- **Secure Storage**: Encrypted file storage

### 4. Audit Trail
- **User Tracking**: Track user actions
- **Action Logging**: Log all operations
- **Timestamp Recording**: Precise timing
- **Change History**: Track data changes

### 5. Data Privacy
- **Patient Data Protection**: Secure patient information
- **Vendor Data Security**: Protect vendor information
- **Financial Data Security**: Secure financial data
- **Compliance**: Regulatory compliance

---

## Performance Considerations

### 1. Database Optimization
- **Indexing**: Proper database indexing
- **Query Optimization**: Efficient queries
- **Connection Pooling**: Database connection management
- **Caching**: Data caching strategies

### 2. Application Performance
- **Code Optimization**: Efficient code structure
- **Memory Management**: Proper memory usage
- **Load Balancing**: Distribute load
- **Caching**: Application-level caching

### 3. User Experience
- **Page Load Times**: Fast page loading
- **AJAX Implementation**: Asynchronous operations
- **Progressive Loading**: Load data progressively
- **Responsive Design**: Mobile optimization

### 4. Scalability
- **Horizontal Scaling**: Scale across servers
- **Vertical Scaling**: Scale server resources
- **Database Scaling**: Scale database layer
- **CDN Integration**: Content delivery networks

---

## Troubleshooting

### Common Issues

#### 1. Database Connection Issues
- **Symptoms**: Database connection errors
- **Causes**: Incorrect database credentials, server issues
- **Solutions**: Check database configuration, verify server status

#### 2. Session Management Issues
- **Symptoms**: User logout, session expired
- **Causes**: Session timeout, server restart
- **Solutions**: Check session configuration, implement session persistence

#### 3. File Upload Issues
- **Symptoms**: File upload failures
- **Causes**: File size limits, permission issues
- **Solutions**: Check file permissions, increase upload limits

#### 4. Performance Issues
- **Symptoms**: Slow page loading, timeouts
- **Causes**: Database queries, server resources
- **Solutions**: Optimize queries, increase server resources

#### 5. Integration Issues
- **Symptoms**: Data synchronization problems
- **Causes**: API changes, data format issues
- **Solutions**: Update integration code, verify data formats

### Debugging Tools
- **Error Logging**: Comprehensive error logging
- **Debug Mode**: Development debug mode
- **Performance Monitoring**: Application performance monitoring
- **Database Profiling**: Database query profiling

### Support Resources
- **Documentation**: Complete system documentation
- **Code Comments**: Detailed code comments
- **Error Messages**: Clear error messages
- **User Guides**: User operation guides

---

## Conclusion

The Stocks New Module is a comprehensive, enterprise-grade inventory management system designed specifically for healthcare facilities. It provides complete control over medicine inventory, from procurement to patient delivery, with robust reporting, analytics, and integration capabilities.

### Key Strengths:
- **Comprehensive Coverage**: Complete inventory lifecycle management
- **User-Friendly Interface**: Intuitive, responsive design
- **Robust Security**: Enterprise-grade security features
- **Scalable Architecture**: Designed for growth
- **Integration Ready**: Seamless integration with existing systems
- **Audit Compliant**: Complete audit trail and compliance features

### Future Enhancements:
- **Mobile App**: Native mobile application
- **Advanced Analytics**: Machine learning insights
- **API Expansion**: RESTful API development
- **Cloud Integration**: Cloud-based deployment options
- **IoT Integration**: Internet of Things device integration

This module represents a significant advancement in healthcare inventory management, providing the tools necessary for efficient, compliant, and cost-effective medicine management.

---

*Documentation Version: 1.0*  
*Last Updated: [Current Date]*  
*Module Version: 2.0*
