<?php
/**
 * Test Session Issue
 * This script tests if sessions are working properly in the main application
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Test Session Issue</h2>";
echo "<hr>";

// Test 1: Check if we can start a fresh session
echo "<h3>1. Fresh Session Test</h3>";

// Destroy any existing session
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
    echo "✅ Existing session destroyed<br>";
}

// Start a new session
session_start();
echo "✅ New session started<br>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data: " . json_encode($_SESSION) . "<br>";

// Test 2: Set up CodeIgniter environment
echo "<h3>2. CodeIgniter Environment Setup</h3>";

define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('FCPATH', __DIR__ . '/');
define('ENVIRONMENT', 'production');

// Load database config
include_once __DIR__ . '/application/config/database.php';
echo "✅ Database config loaded<br>";

// Load main config
include_once __DIR__ . '/application/config/config.php';
echo "✅ Main config loaded<br>";

// Load helper
include_once __DIR__ . '/application/helpers/myhelper_helper.php';
echo "✅ Helper loaded<br>";

// Test 3: Test checklogin with empty session
echo "<h3>3. Test checklogin with Empty Session</h3>";
$logg = checklogin();
echo "checklogin() result with empty session: " . json_encode($logg) . "<br>";

// Test 4: Simulate login and test checklogin
echo "<h3>4. Simulate Login and Test</h3>";

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
    echo "✅ User found: " . $user['name'] . "<br>";
    
    // Set session exactly like User_model does
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
    
    $_SESSION['logged_administrator'] = array(
        'name' => $user['name'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role'],
        'employee_number' => $user['employee_number']
    );
    
    echo "✅ Session set<br>";
    echo "Session data: " . json_encode($_SESSION) . "<br>";
    
    // Test checklogin immediately
    $logg = checklogin();
    echo "checklogin() result: " . json_encode($logg) . "<br>";
    
    if ($logg['status'] === true) {
        echo "✅ Login successful!<br>";
        
        // Test 5: Simulate what happens in Welcome->index()
        echo "<h3>5. Simulate Welcome->index() Logic</h3>";
        
        $logg2 = checklogin();
        if ($logg2['status'] === true) {
            echo "✅ User should be redirected to dashboard<br>";
            echo "Base URL would be: " . (isset($config['base_url']) ? $config['base_url'] : 'Not set') . "<br>";
            
            // Test 6: Check if redirect would work
            echo "<h3>6. Test Redirect</h3>";
            
            if (headers_sent($file, $line)) {
                echo "❌ Headers already sent from $file line $line<br>";
            } else {
                echo "✅ Headers not sent - redirect should work<br>";
                
                // Test actual redirect
                echo "Testing redirect...<br>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
                echo "You should be redirected to index.php in 2 seconds...<br>";
            }
        } else {
            echo "❌ checklogin() failed in Welcome->index()<br>";
        }
        
    } else {
        echo "❌ checklogin() failed after setting session<br>";
    }
    
} else {
    echo "❌ User not found<br>";
}

$mysqli->close();

// Test 7: Check session persistence
echo "<h3>7. Session Persistence Test</h3>";
echo "Final session data: " . json_encode($_SESSION) . "<br>";
echo "Session ID: " . session_id() . "<br>";

echo "<hr>";
echo "<p><strong>Session test completed!</strong></p>";
echo "<p><a href='index.php'>Try main application now</a></p>";
?>
