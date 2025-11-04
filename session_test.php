<?php
/**
 * Session Test Script for HMS India
 * This script helps diagnose session issues on the live server
 * Access it via: http://yourdomain.com/session_test.php
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Session Diagnostic Test</h2>";
echo "<hr>";

// Test 1: Environment Detection
echo "<h3>1. Environment Detection</h3>";
$environment = 'development';
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
echo "Detected Environment: <strong>" . $environment . "</strong><br>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "<br>";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "<br>";

// Test 2: Session Configuration
echo "<h3>2. Session Configuration</h3>";
echo "Session Status: " . session_status() . " (1=disabled, 2=active, 3=none)<br>";
echo "Session Save Path: " . session_save_path() . "<br>";
echo "Session Name: " . session_name() . "<br>";
echo "Session ID: " . session_id() . "<br>";

// Test 3: Session Directory
echo "<h3>3. Session Directory Test</h3>";
$session_path = __DIR__ . '/application/cache/sessions/';
echo "Session Path: " . $session_path . "<br>";
echo "Directory Exists: " . (is_dir($session_path) ? 'Yes' : 'No') . "<br>";
if (is_dir($session_path)) {
    echo "Directory Writable: " . (is_writable($session_path) ? 'Yes' : 'No') . "<br>";
    echo "Directory Permissions: " . substr(sprintf('%o', fileperms($session_path)), -4) . "<br>";
} else {
    echo "Creating directory...<br>";
    if (mkdir($session_path, 0755, true)) {
        echo "Directory created successfully!<br>";
        chmod($session_path, 0755);
        echo "Permissions set to 755<br>";
    } else {
        echo "<span style='color: red;'>Failed to create directory!</span><br>";
    }
}

// Test 4: Start Session and Test
echo "<h3>4. Session Test</h3>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "Session started successfully<br>";
} else {
    echo "Session already active<br>";
}

// Set a test session variable
$_SESSION['test_variable'] = 'Session working at ' . date('Y-m-d H:i:s');
echo "Test session variable set: " . $_SESSION['test_variable'] . "<br>";

// Test 5: Cookie Configuration
echo "<h3>5. Cookie Configuration</h3>";
$cookie_params = session_get_cookie_params();
echo "Cookie Lifetime: " . $cookie_params['lifetime'] . " seconds<br>";
echo "Cookie Path: " . $cookie_params['path'] . "<br>";
echo "Cookie Domain: " . ($cookie_params['domain'] ?: 'Not set') . "<br>";
echo "Cookie Secure: " . ($cookie_params['secure'] ? 'Yes' : 'No') . "<br>";
echo "Cookie HttpOnly: " . ($cookie_params['httponly'] ? 'Yes' : 'No') . "<br>";

// Test 6: Login Simulation
echo "<h3>6. Login Simulation Test</h3>";
if (isset($_GET['test_login'])) {
    // Simulate setting session variables like the login process
    $_SESSION['logged_administrator'] = array(
        'name' => 'Test User',
        'username' => 'test@example.com',
        'email' => 'test@example.com',
        'role' => 'administrator',
        'employee_number' => 'TEST001'
    );
    echo "Test login session set successfully!<br>";
    echo "Session data: " . print_r($_SESSION, true) . "<br>";
} else {
    echo "<a href='?test_login=1'>Click here to test login simulation</a><br>";
}

// Test 7: Check Login Function
echo "<h3>7. Check Login Function Test</h3>";
if (file_exists(__DIR__ . '/application/helpers/myhelper_helper.php')) {
    include_once __DIR__ . '/application/helpers/myhelper_helper.php';
    $logg = checklogin();
    echo "Check Login Result: " . print_r($logg, true) . "<br>";
} else {
    echo "Helper file not found!<br>";
}


echo "<hr>";
echo "<p><strong>If you see any errors or issues above, please share this output for further diagnosis.</strong></p>";
echo "<p><a href='index.php'>Go to main application</a></p>";
?>
