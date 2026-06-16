<?php
/**
 * Test the Fix
 * This script tests if the login redirect issue is fixed
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Test the Fix</h2>";
echo "<hr>";

// Test 1: Simulate login first
echo "<h3>1. Simulate Login</h3>";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set up database connection
$db_host = 'localhost';
$db_user = 'hmaadmin';
$db_pass = 'Admin@2025!';
$db_name = 'stagin_hms_db';
$db_prefix = 'hms_';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    echo "❌ Database connection failed: " . $mysqli->connect_error . "<br>";
    exit;
}

// Perform login
$email = 'ceo@indiaivf.in';
$password = 'admin';

$sql = "SELECT * FROM " . $db_prefix . "employees WHERE username = ? AND password = ? AND status = '1'";
$stmt = $mysqli->prepare($sql);
$hashed_password = md5($password);
$stmt->bind_param("ss", $email, $hashed_password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Clear existing sessions
    unset($_SESSION['logged_administrator']);
    unset($_SESSION['logged_accountant']);
    unset($_SESSION['logged_stock_manager']);
    unset($_SESSION['logged_billing_manager']);
    unset($_SESSION['logged_telecaller']);
    unset($_SESSION['logged_central_stock_manager']);
    unset($_SESSION['logged_doctor']);
    unset($_SESSION['logged_investigation_manager']);
    unset($_SESSION['logged_counselor']);
    unset($_SESSION['logged_center_head']);
    unset($_SESSION['logged_liason']);
    unset($_SESSION['logged_mrd']);
    unset($_SESSION['logged_embryologist']);
    unset($_SESSION['logged_viewer']);
    
    // Set session
    $_SESSION['logged_administrator'] = array(
        'name' => $user['name'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role'],
        'employee_number' => $user['employee_number']
    );
    
    echo "✅ Login simulated successfully<br>";
    echo "Session data: " . json_encode($_SESSION) . "<br>";
    
    // Test checklogin
    include_once __DIR__ . '/application/helpers/myhelper_helper.php';
    $logg = checklogin();
    echo "checklogin() result: " . json_encode($logg) . "<br>";
    
    if ($logg['status'] === true) {
        echo "✅ User is logged in<br>";
        
        // Test 2: Test the fixed main application
        echo "<h3>2. Testing Fixed Main Application</h3>";
        echo "Now try accessing the main application:<br>";
        echo "<a href='index.php' target='_blank'>http://139.84.175.208/index.php</a><br>";
        echo "<br>";
        echo "You should be automatically redirected to the dashboard!<br>";
        
    } else {
        echo "❌ checklogin() failed<br>";
    }
} else {
    echo "❌ User not found<br>";
}

$mysqli->close();

// Test 3: Instructions
echo "<h3>3. Instructions</h3>";
echo "<ol>";
echo "<li>Click the link above to test the main application</li>";
echo "<li>You should be automatically redirected to the dashboard</li>";
echo "<li>If you see the login page, the fix didn't work</li>";
echo "<li>If you're redirected to dashboard, the fix worked!</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Test completed!</strong></p>";
echo "<p><a href='index.php' target='_blank'>Test Main Application</a></p>";
?>
