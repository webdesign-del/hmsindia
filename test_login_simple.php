<?php
/**
 * Simple Login Test for HMS India
 * This script tests the login process step by step
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>HMS India - Simple Login Test</h2>";
echo "<hr>";

// Test 1: Environment Detection
$environment = 'development';
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
    if (strpos($host, '139.84.175.208') !== false || 
        strpos($host, 'indiaivf.website') !== false ||
        strpos($host, 'hmsindia') !== false) {
        $environment = 'production';
    }
}

echo "<h3>1. Environment: " . $environment . "</h3>";

// Test 2: Database Connection
$db_host = ($environment === 'production') ? 'localhost' : 'localhost';
$db_user = ($environment === 'production') ? 'hmaadmin' : 'root';
$db_pass = ($environment === 'production') ? 'Admin@2025!' : '';
$db_name = ($environment === 'production') ? 'stagin_hms_db' : 'hmsindiaivf';
$db_prefix = 'hms_';

echo "<h3>2. Database Connection</h3>";
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    echo "❌ Database connection failed: " . $mysqli->connect_error . "<br>";
    exit;
} else {
    echo "✅ Database connected successfully<br>";
}

// Test 3: Check if login form is submitted
if (isset($_POST['test_login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<h3>3. Testing Login for: " . htmlspecialchars($email) . "</h3>";
    
    // Step 1: Check if user exists
    $sql1 = "SELECT * FROM " . $db_prefix . "employees WHERE username = ? AND status = '1'";
    $stmt1 = $mysqli->prepare($sql1);
    $stmt1->bind_param("s", $email);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $user_result = $result1->fetch_all(MYSQLI_ASSOC);
    
    echo "Step 1 - User lookup: " . count($user_result) . " users found<br>";
    
    if (count($user_result) > 0) {
        $user = $user_result[0];
        echo "User found: " . htmlspecialchars($user['name']) . "<br>";
        echo "Role: " . htmlspecialchars($user['role']) . "<br>";
        echo "Center ID: " . htmlspecialchars($user['center_id']) . "<br>";
        
        // Step 2: Check password
        $hashed_password = md5($password);
        echo "Step 2 - Password check:<br>";
        echo "Input password (MD5): " . $hashed_password . "<br>";
        echo "Stored password: " . $user['password'] . "<br>";
        
        if ($user['password'] === $hashed_password) {
            echo "✅ Password matches!<br>";
            
            // Step 3: Check center relationship (if needed)
            if ($user['center_id'] != 0) {
                echo "Step 3 - Center relationship check:<br>";
                $sql2 = "SELECT * FROM " . $db_prefix . "centers WHERE center_number = ? AND status = '1'";
                $stmt2 = $mysqli->prepare($sql2);
                $stmt2->bind_param("s", $user['center_id']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $center_result = $result2->fetch_all(MYSQLI_ASSOC);
                
                echo "Centers found: " . count($center_result) . "<br>";
                if (count($center_result) > 0) {
                    echo "✅ Center relationship valid<br>";
                } else {
                    echo "❌ Center relationship invalid - this might cause login failure<br>";
                }
            } else {
                echo "Step 3 - No center relationship needed (center_id = 0)<br>";
            }
            
            // Step 4: Simulate session creation
            echo "Step 4 - Session simulation:<br>";
            session_start();
            
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
            
            // Set session based on role
            $role = $user['role'];
            $session_key = 'logged_' . $role;
            
            if ($role == 'administrator') {
                $_SESSION[$session_key] = array(
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'employee_number' => $user['employee_number']
                );
            } else {
                // For other roles, we need center data
                $center_data = array('center_number' => 0, 'type' => '');
                if ($user['center_id'] != 0) {
                    $sql3 = "SELECT center_number, type FROM " . $db_prefix . "centers WHERE center_number = ?";
                    $stmt3 = $mysqli->prepare($sql3);
                    $stmt3->bind_param("s", $user['center_id']);
                    $stmt3->execute();
                    $result3 = $stmt3->get_result();
                    if ($center_row = $result3->fetch_assoc()) {
                        $center_data = $center_row;
                    }
                }
                
                $_SESSION[$session_key] = array(
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'employee_number' => $user['employee_number'],
                    'center' => $center_data['center_number'],
                    'center_type' => $center_data['type']
                );
            }
            
            echo "✅ Session created for role: " . $role . "<br>";
            echo "Session data: " . json_encode($_SESSION[$session_key]) . "<br>";
            
            // Step 5: Test checklogin function
            echo "Step 5 - Testing checklogin function:<br>";
            if (file_exists(__DIR__ . '/application/helpers/myhelper_helper.php')) {
                include_once __DIR__ . '/application/helpers/myhelper_helper.php';
                $logg = checklogin();
                echo "checklogin() result: " . json_encode($logg) . "<br>";
                
                if ($logg['status'] === true) {
                    echo "✅ Login successful! User should be able to access dashboard<br>";
                } else {
                    echo "❌ checklogin() failed - this is why login redirects back<br>";
                }
            } else {
                echo "❌ Helper file not found<br>";
            }
            
        } else {
            echo "❌ Password does not match<br>";
        }
    } else {
        echo "❌ User not found or inactive<br>";
    }
} else {
    // Show login form
    echo "<h3>3. Test Login Form</h3>";
    echo "<form method='post'>";
    echo "Email: <input type='text' name='email' value='admin@test.com' required><br><br>";
    echo "Password: <input type='password' name='password' value='password' required><br><br>";
    echo "<input type='hidden' name='test_login' value='1'>";
    echo "<input type='submit' value='Test Login'>";
    echo "</form>";
    
    // Show available users
    echo "<h3>4. Available Users</h3>";
    $result = $mysqli->query("SELECT username, name, role, status FROM " . $db_prefix . "employees WHERE status = '1' LIMIT 10");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Username</th><th>Name</th><th>Role</th><th>Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

$mysqli->close();

echo "<hr>";
echo "<p><strong>Login test completed!</strong></p>";
echo "<p><a href='index.php'>Go to main application</a> | <a href='test_database.php'>Run database test</a></p>";
?>
