<?php
/**
 * Test Actual Login Issue
 * This script directly tests the login redirect problem
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Test Actual Login Issue</h2>";
echo "<hr>";

// Test 1: Check if we can access the main application directly
echo "<h3>1. Testing Direct Application Access</h3>";

// Test the actual login process by simulating a POST request
echo "<h3>2. Simulating Login POST Request</h3>";

// Set up the POST data as if it came from the login form
$_POST['login'] = 'login';
$_POST['email'] = 'ceo@indiaivf.in';
$_POST['password'] = 'admin';

echo "POST data set:<br>";
echo "Email: " . $_POST['email'] . "<br>";
echo "Password: " . $_POST['password'] . "<br>";

// Test 3: Check session status before
echo "<h3>3. Session Status Before Login</h3>";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "✅ Session started<br>";
} else {
    echo "✅ Session already active<br>";
}

echo "Session ID: " . session_id() . "<br>";
echo "Session data before: " . json_encode($_SESSION) . "<br>";

// Test 4: Simulate the actual CodeIgniter login process
echo "<h3>4. Simulating CodeIgniter Login Process</h3>";

// Set up CodeIgniter environment
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('FCPATH', __DIR__ . '/');
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'production');
}

// Load database config
include_once __DIR__ . '/application/config/database.php';

// Load helper
include_once __DIR__ . '/application/helpers/myhelper_helper.php';

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

echo "✅ Existing sessions cleared<br>";

// Perform login
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM " . $db_prefix . "employees WHERE username = ? AND password = ? AND status = '1'";
$stmt = $mysqli->prepare($sql);
$hashed_password = md5($password);
$stmt->bind_param("ss", $email, $hashed_password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "✅ User found: " . $user['name'] . "<br>";
    
    // Set session
    $_SESSION['logged_administrator'] = array(
        'name' => $user['name'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role'],
        'employee_number' => $user['employee_number']
    );
    
    echo "✅ Session set<br>";
    echo "Session data after login: " . json_encode($_SESSION) . "<br>";
    
    // Test checklogin
    $logg = checklogin();
    echo "checklogin() result: " . json_encode($logg) . "<br>";
    
    if ($logg['status'] === true) {
        echo "✅ Login successful!<br>";
        
        // Test 5: Simulate dashboard access
        echo "<h3>5. Testing Dashboard Access</h3>";
        
        $logg2 = checklogin();
        if ($logg2['status'] === true) {
            echo "✅ Dashboard access should be granted<br>";
            echo "User role: " . $logg2['role'] . "<br>";
            
            // Test 6: Check if redirect would work
            echo "<h3>6. Testing Redirect</h3>";
            
            if (headers_sent($file, $line)) {
                echo "❌ Headers already sent from $file line $line<br>";
                echo "This would prevent redirect from working<br>";
            } else {
                echo "✅ Headers not sent - redirect should work<br>";
                
                // Test the actual redirect
                echo "Testing redirect to dashboard...<br>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
                echo "You should be redirected to the main application in 2 seconds...<br>";
            }
            
        } else {
            echo "❌ Dashboard access would be denied<br>";
            echo "This is why you're getting redirected back to login<br>";
        }
        
    } else {
        echo "❌ checklogin() failed after setting session<br>";
    }
    
} else {
    echo "❌ User not found or password incorrect<br>";
}

$mysqli->close();

// Test 7: Check session persistence
echo "<h3>7. Session Persistence Test</h3>";
echo "Final session data: " . json_encode($_SESSION) . "<br>";
echo "Session ID: " . session_id() . "<br>";

echo "<hr>";
echo "<p><strong>Login test completed!</strong></p>";
echo "<p><a href='index.php'>Try main application now</a></p>";
?>
