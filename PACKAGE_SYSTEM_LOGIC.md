# 📦 Medicine Package System - Complete Logic

## 🎯 **System Overview**
A comprehensive box/package-based medicine selling system where packages are assembled from central stocks and can be transferred and sold.

## 🔄 **Complete Workflow**

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  CENTRAL STOCKS │ -> │ PACKAGE ASSEMBLY │ -> │ PACKAGE STOCKS  │ -> │    TRANSFER    │
│                 │    │                  │    │                 │    │                 │
│ • Individual    │    │ • Deduct from    │    │ • Ready boxes   │    │ • Between       │
│ • Medicines     │    │ • Central stocks │    │ • At centers    │    │ • Centers       │
│ • With batches  │    │ • FIFO logic     │    │ • Transferable  │    │ • Departments   │
└─────────────────┘    └──────────────────┘    └─────────────────┘    └─────────────────┘
                                                                 │
                                                                 ▼
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   PACKAGE SALE  │ -> │ MEDICINE RETURN  │ -> │ CENTRAL STOCKS  │
│                 │    │                  │    │                 │
│ • Sell boxes    │    │ • Deduct from    │    │ • Individual     │
│ • Auto-deduct   │    │ • Medicine stock │    │ • Medicines      │
│ • FIFO batches  │    │ • Update sales   │    │ • Updated       │
└─────────────────┘    └──────────────────┘    └─────────────────┘
```

## 📊 **Database Tables**

### 1. **medicine_packages**
- Package definitions (name, code, price, GST)
- Status: active/inactive

### 2. **package_items**
- Links medicines to packages
- Quantity of each medicine in package

### 3. **package_stocks**
- Package inventory at each center/department
- Quantity available for sale/transfer

### 4. **package_stock_movements**
- All package stock movements
- Transfer, sale, adjustment history

## ⚙️ **Key Functions Logic**

### **1. Package Assembly (`add_package_stock`)**
```php
// Check all required medicines available in central stocks
foreach ($package_items as $item) {
    $needed = $item->quantity * $packages_to_create;
    // Verify stock availability
}

// Deduct medicines using FIFO
foreach ($batches as $batch) {
    deduct_from_center_stocks();
    log_medicine_movement('PACKAGE_ASSEMBLY');
}

// Create package stock
insert_package_stock();
log_package_movement('PURCHASE');
```

### **2. Package Transfer (`transfer_package_stock`)**
```php
// Deduct from source center
update_package_stocks(source: -quantity);

// Add to destination center
update_package_stocks(destination: +quantity);

// Log movements
log_package_movement('TRANSFER_OUT');
log_package_movement('TRANSFER_IN');
```

### **3. Package Sale (`process_package_sale`)**
```php
// Get package contents
$package_items = get_package_items();

// For each medicine in package
foreach ($package_items as $item) {
    $needed = $item->quantity * $packages_sold;

    // Find batches using FIFO
    $batches = get_available_batches_fifo();

    // Deduct from medicine stocks
    foreach ($batches as $batch) {
        deduct_from_center_stocks();
        add_to_sale_items();
        log_medicine_movement('SALE');
    }
}

// Deduct from package stocks
update_package_stocks(-packages_sold);
log_package_movement('SALE');
```

## 🔄 **Movement Types**

### **Medicine Movements (stock_movements table)**
- `PURCHASE` - Adding medicine stock
- `SALE` - Selling medicines
- `PACKAGE_ASSEMBLY` - Medicines used for package creation
- `TRANSFER_IN/OUT` - Medicine transfers
- `ADJUSTMENT` - Stock adjustments

### **Package Movements (package_stock_movements table)**
- `PURCHASE` - Package assembly/stock creation
- `SALE` - Package sales
- `TRANSFER_IN/OUT` - Package transfers
- `ADJUSTMENT` - Package adjustments

## 📈 **Business Rules**

### **Package Assembly**
1. Check medicine availability in central stocks
2. Use FIFO for batch selection
3. Deduct exact quantities from medicine stocks
4. Create package stock at specified center
5. Log all movements for traceability

### **Package Sales**
1. Break down package to component medicines
2. Deduct medicines using FIFO from available batches
3. Record sale items for each medicine batch
4. Update package stock levels
5. Maintain complete audit trail

### **Package Transfers**
1. Move packages between centers/departments
2. Maintain separate inventory tracking
3. Log transfer movements
4. Update stock levels at both ends

## 🎯 **Key Benefits**

1. **Accurate Inventory** - Packages assembled from actual stock
2. **Flexible Selling** - Sell individual medicines OR packages
3. **Complete Tracking** - Every movement logged and traceable
4. **FIFO Compliance** - Proper batch management
5. **Multi-Center Support** - Transfer packages between locations

## 🔧 **Implementation Status**

- ✅ Package definition tables
- ✅ Stock management tables
- ✅ Package assembly logic (from central stocks)
- ✅ Package selling logic (breaks to medicines)
- ✅ Package transfer logic
- ✅ Movement logging
- ✅ UI components
- ✅ Database migrations

The system now provides complete box-wise selling with proper inventory management! 📦💊</contents>
</xai:function_call xmlns="http://www.w3.org/1999/xhtml">Wrote contents to PACKAGE_SYSTEM_LOGIC.md.

