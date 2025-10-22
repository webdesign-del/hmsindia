<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hmsindia';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING TABLES ===\n";
    $tables = ['hms_medicine_batches', 'medicine_batches', 'central_stocks', 'center_stocks', 'stock_transfers', 'stock_transfer_items'];
    
    foreach($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if($stmt->rowCount() > 0) {
            echo "✓ Table '$table' EXISTS\n";
        } else {
            echo "✗ Table '$table' NOT FOUND\n";
        }
    }
    
    echo "\n=== CHECKING STOCK DATA ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM hms_medicine_batches WHERE batch_status = 'ACTIVE'");
    $result = $stmt->fetch();
    echo "Active batches in hms_medicine_batches: " . $result['count'] . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM central_stocks");
    $result = $stmt->fetch();
    echo "Records in central_stocks: " . $result['count'] . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM center_stocks");
    $result = $stmt->fetch();
    echo "Records in center_stocks: " . $result['count'] . "\n";
    
    echo "\n=== SAMPLE DATA FROM HMS_MEDICINE_BATCHES ===\n";
    $stmt = $pdo->query("SELECT ID, medicine_id, batch_number, current_quantity, center_id FROM hms_medicine_batches LIMIT 5");
    $results = $stmt->fetchAll();
    foreach($results as $row) {
        echo "ID: {$row['ID']}, Medicine: {$row['medicine_id']}, Batch: {$row['batch_number']}, Qty: {$row['current_quantity']}, Center: {$row['center_id']}\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
