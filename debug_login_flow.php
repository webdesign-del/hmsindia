<?php
/**
 * Debug Login Flow for HMS India
 * This script debugs the actual CodeIgniter login flow
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Debug Login Flow</h2>";
echo "<hr>";

// Test 1: Check if CodeIgniter is working
echo "<h3>1. CodeIgniter Bootstrap Test</h3>";

// Set up CodeIgniter environment
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('FCPATH', __DIR__ . '/');
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'production');
}

// Include CodeIgniter bootstrap
if (file_exists(__DIR__ . '/index.php')) {
    // Get the environment detection from index.php
    $environment = 'production';
    if (isset($_SERVER['CI_ENV'])) {
        $environment = $_SERVER['CI_ENV'];
    } elseif (getenv('CI_ENV')) {
        $environment = getenv('CI_ENV');
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
        if (strpos($host, '139.84.175.208') !== false || 
            strpos($host, 'indiaivf.website') !== false ||
            strpos($host, 'hmsindia') !== false) {
            $environment = 'production';
        }
    }
    define('ENVIRONMENT', $environment);
    
    echo "✅ Environment set to: " . $environment . "<br>";
    
    // Load database config
    include_once __DIR__ . '/application/config/database.php';
    echo "✅ Database config loaded<br>";
    
    // Load session config
    include_once __DIR__ . '/application/config/config.php';
    echo "✅ Main config loaded<br>";
    
    // Load helper
    include_once __DIR__ . '/application/helpers/myhelper_helper.php';
    echo "✅ Helper loaded<br>";
    
} else {
    echo "❌ CodeIgniter index.php not found<br>";
    exit;
}

// Test 2: Simulate the actual login process
echo "<h3>2. Simulating CodeIgniter Login Process</h3>";

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "✅ Session started<br>";
} else {
    echo "✅ Session already active<br>";
}

// Clear any existing sessions
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

echo "✅ Existing sessions cleared<br>";

// Test 3: Simulate user login
echo "<h3>3. Simulating User Login</h3>";

$test_email = 'ceo@indiaivf.in';
$test_password = 'admin';

// Simulate the User_model->userlogin() process
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

// Step 1: Check if user exists
$sql1 = "SELECT * FROM " . $db_prefix . "employees WHERE username = ? AND status = '1'";
$stmt1 = $mysqli->prepare($sql1);
$stmt1->bind_param("s", $test_email);
$stmt1->execute();
$result1 = $stmt1->get_result();
$user_result = $result1->fetch_all(MYSQLI_ASSOC);

echo "User lookup result: " . count($user_result) . " users found<br>";

if (count($user_result) > 0) {
    $user = $user_result[0];
    echo "User: " . $user['name'] . " (Role: " . $user['role'] . ")<br>";
    
    // Step 2: Check password
    $hashed_password = md5($test_password);
    if ($user['password'] === $hashed_password) {
        echo "✅ Password matches<br>";
        
        // Step 3: Set session (simulate User_model logic)
        $role = $user['role'];
        
        if ($role == 'administrator') {
            $_SESSION['logged_administrator'] = array(
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'employee_number' => $user['employee_number']
            );
            echo "✅ Administrator session set<br>";
        }
        
        // Step 4: Test checklogin()
        echo "<h3>4. Testing checklogin() Function</h3>";
        $logg = checklogin();
        echo "checklogin() result: " . json_encode($logg) . "<br>";
        
        if ($logg['status'] === true) {
            echo "✅ checklogin() returns true - login should succeed<br>";
            
            // Step 5: Test dashboard access simulation
            echo "<h3>5. Testing Dashboard Access Simulation</h3>";
            
            // This simulates what happens in Welcome->dashboard()
            $logg2 = checklogin();
            if ($logg2['status'] === true) {
                echo "✅ Dashboard access should be granted<br>";
                echo "User role: " . $logg2['role'] . "<br>";
                echo "Session data: " . json_encode($_SESSION) . "<br>";
            } else {
                echo "❌ Dashboard access would be denied<br>";
                echo "This is why you're getting redirected to login<br>";
            }
            
        } else {
            echo "❌ checklogin() returns false - this is the problem!<br>";
        }
        
    } else {
        echo "❌ Password does not match<br>";
    }
} else {
    echo "❌ User not found<br>";
}

$mysqli->close();

// Test 6: Check session persistence
echo "<h3>6. Session Persistence Test</h3>";
echo "Current session ID: " . session_id() . "<br>";
echo "Session data: " . json_encode($_SESSION) . "<br>";

// Test 7: Check if there are any redirect issues
echo "<h3>7. Redirect Test</h3>";
echo "Current URL: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Base URL should be: http://139.84.175.208/<br>";

// Test 8: Check for any output before redirect
echo "<h3>8. Output Buffer Test</h3>";
if (ob_get_level() > 0) {
    echo "⚠️ Output buffer is active - this might cause redirect issues<br>";
    echo "Buffer contents: " . ob_get_contents() . "<br>";
} else {
    echo "✅ No output buffer issues<br>";
}

echo "<hr>";
echo "<p><strong>Debug completed!</strong></p>";
echo "<p><a href='index.php'>Try main application</a> | <a href='test_login_simple.php'>Back to login test</a></p>";
?>
