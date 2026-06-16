<?php
/**
 * Clear Redirect Loop
 * This script clears the redirect loop and tests the actual issue
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


echo "<h2>HMS India - Clear Redirect Loop</h2>";
echo "<hr>";

// Test 1: Clear all sessions and cookies
echo "<h3>1. Clearing All Sessions and Cookies</h3>";

// Destroy current session
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
    echo "✅ Current session destroyed<br>";
}

// Clear all cookies
if (isset($_COOKIE)) {
    foreach ($_COOKIE as $name => $value) {
        setcookie($name, '', time() - 3600, '/');
    }
    echo "✅ All cookies cleared<br>";
}

// Start fresh session
session_start();
echo "✅ Fresh session started<br>";
echo "New session ID: " . session_id() . "<br>";

// Test 2: Test the actual login process step by step
echo "<h3>2. Testing Login Process Step by Step</h3>";

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
    
    // Test checklogin
    include_once __DIR__ . '/application/helpers/myhelper_helper.php';
    $logg = checklogin();
    echo "checklogin() result: " . json_encode($logg) . "<br>";
    
    if ($logg['status'] === true) {
        echo "✅ Login successful!<br>";
        
        // Test 3: Test dashboard access directly
        echo "<h3>3. Testing Dashboard Access</h3>";
        
        // Simulate what happens in Welcome->dashboard()
        $logg2 = checklogin();
        if ($logg2['status'] === true) {
            echo "✅ Dashboard access should work<br>";
            echo "User role: " . $logg2['role'] . "<br>";
            
            // Test 4: Check if we can access the main application now
            echo "<h3>4. Testing Main Application Access</h3>";
            echo "Now try accessing the main application:<br>";
            echo "<a href='index.php' target='_blank'>http://139.84.175.208/index.php</a><br>";
            echo "<br>";
            echo "You should see the login page (since we're not logged in via the main app)<br>";
            echo "But if you log in through the form, it should work!<br>";
            
        } else {
            echo "❌ Dashboard access would fail - this is the problem!<br>";
            echo "checklogin() is returning false in dashboard<br>";
        }
        
    } else {
        echo "❌ checklogin() failed after setting session<br>";
    }
} else {
    echo "❌ User not found<br>";
}

$mysqli->close();

// Test 5: Instructions for testing
echo "<h3>5. Testing Instructions</h3>";
echo "<ol>";
echo "<li>Click the link above to access the main application</li>";
echo "<li>You should see the login page (not a redirect loop)</li>";
echo "<li>Enter credentials: ceo@indiaivf.in / admin</li>";
echo "<li>Click Submit</li>";
echo "<li>You should be redirected to the dashboard</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Redirect loop cleared!</strong></p>";
echo "<p><a href='index.php' target='_blank'>Test Main Application</a></p>";
?>
