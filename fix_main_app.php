<?php
/**
 * Fix Main Application
 * This script fixes the main application to properly check sessions before outputting HTML
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Fix Main Application</h2>";
echo "<hr>";

// Test 1: Check what's happening in the main application
echo "<h3>1. Analyzing Main Application Issue</h3>";

// The problem is that index.php is outputting HTML immediately
// We need to ensure session checking happens BEFORE any output

echo "The issue is that index.php outputs HTML before checking sessions.<br>";
echo "We need to ensure session checking happens first.<br>";

// Test 2: Check if we can fix this by modifying the flow
echo "<h3>2. Testing Session Check Before Output</h3>";

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set up CodeIgniter environment
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('FCPATH', __DIR__ . '/');
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'production');
}

// Load helper
include_once __DIR__ . '/application/helpers/myhelper_helper.php';

// Check if user is already logged in
$logg = checklogin();
echo "checklogin() result: " . json_encode($logg) . "<br>";

if ($logg['status'] === true) {
    echo "✅ User is logged in - should redirect to dashboard<br>";
    echo "This is what should happen in the main application<br>";
} else {
    echo "ℹ️ User not logged in - should show login page<br>";
}

// Test 3: Create a fixed version of the main application
echo "<h3>3. Creating Fixed Main Application</h3>";

$fixed_index_content = '<?php
/**
 * Fixed index.php for HMS India
 * This version ensures session checking happens before any output
 */

// Set error reporting
error_reporting(E_ALL);
ini_set(\'display_errors\', 1);

// Start session FIRST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set up CodeIgniter environment
define(\'BASEPATH\', __DIR__ . \'/system/\');
define(\'APPPATH\', __DIR__ . \'/application/\');
define(\'FCPATH\', __DIR__ . \'/\');

// Environment detection
$environment = \'development\';
if (isset($_SERVER[\'CI_ENV\'])) {
    $environment = $_SERVER[\'CI_ENV\'];
} elseif (getenv(\'CI_ENV\')) {
    $environment = getenv(\'CI_ENV\');
} elseif (isset($_SERVER[\'HTTP_HOST\'])) {
    $host = $_SERVER[\'HTTP_HOST\'];
    if (strpos($host, \'139.84.175.208\') !== false || 
        strpos($host, \'indiaivf.website\') !== false ||
        strpos($host, \'hmsindia\') !== false) {
        $environment = \'production\';
    }
}
define(\'ENVIRONMENT\', $environment);

// Load helper
include_once __DIR__ . \'/application/helpers/myhelper_helper.php\';

// Check if user is already logged in BEFORE any output
$logg = checklogin();
if ($logg[\'status\'] === true) {
    // User is logged in, redirect to dashboard
    header("location: " . \'http://139.84.175.208/\' . "dashboard");
    exit;
}

// If we get here, user is not logged in, so load the normal CodeIgniter application
// This will show the login page

// Include the original CodeIgniter bootstrap
require_once BASEPATH.\'core/CodeIgniter.php\';
';

// Write the fixed index.php
if (file_put_contents(__DIR__ . '/index_fixed.php', $fixed_index_content)) {
    echo "✅ Created fixed index.php as index_fixed.php<br>";
    echo "<a href='index_fixed.php'>Test the fixed version</a><br>";
} else {
    echo "❌ Failed to create fixed index.php<br>";
}

// Test 4: Check current session state
echo "<h3>4. Current Session State</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data: " . json_encode($_SESSION) . "<br>";

// Test 5: Simulate login and test the fixed version
echo "<h3>5. Simulate Login for Testing</h3>";

if (isset($_GET['simulate_login'])) {
    // Set up database connection
    $db_host = 'localhost';
    $db_user = 'hmaadmin';
    $db_pass = 'Admin@2025!';
    $db_name = 'stagin_hms_db';
    $db_prefix = 'hms_';
    
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if (!$mysqli->connect_error) {
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
            $logg = checklogin();
            echo "checklogin() result: " . json_encode($logg) . "<br>";
            
            if ($logg['status'] === true) {
                echo "✅ Now try the fixed version: <a href='index_fixed.php'>index_fixed.php</a><br>";
                echo "You should be redirected to the dashboard!<br>";
            }
        }
        
        $mysqli->close();
    }
} else {
    echo "<a href='?simulate_login=1'>Click here to simulate login</a><br>";
}

echo "<hr>";
echo "<p><strong>Fix completed!</strong></p>";
echo "<p><a href='index_fixed.php'>Test fixed version</a> | <a href='index.php'>Original version</a></p>";
?>
