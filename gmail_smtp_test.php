<?php
/**
 * Gmail SMTP Test - Simple and Working
 * Tests Gmail SMTP with proper configuration
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Gmail SMTP Test</h2>";
echo "<hr>";

// Production Mail SMTP Configuration (from application/config/production/email.php)
$gmail_config = [
    'host' => 'smtp.gmail.com',
    'username' => 'digital@indiaivf.in',
    'password' => 'dmnjvdgzdqdsfsmv',
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'digital@indiaivf.in',
    'from_name' => 'IndiaIVF (DEV)'
];

echo "<h3>Configuration</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>Host</td><td>{$gmail_config['host']}</td></tr>";
echo "<tr><td>Port</td><td>{$gmail_config['port']}</td></tr>";
echo "<tr><td>Encryption</td><td>{$gmail_config['encryption']}</td></tr>";
echo "<tr><td>Username</td><td>{$gmail_config['username']}</td></tr>";
echo "<tr><td>Password</td><td>***" . substr($gmail_config['password'], -3) . "</td></tr>";
echo "</table>";

echo "<hr>";

// Test 1: Basic Connection
echo "<h3>Test 1: Basic Connection</h3>";
$socket = @fsockopen($gmail_config['host'], $gmail_config['port'], $errno, $errstr, 10);
if ($socket) {
    echo "✅ Connected to {$gmail_config['host']}:{$gmail_config['port']}<br>";
    
    // Read server greeting
    $response = fgets($socket, 1024);
    echo "Server greeting: " . trim($response) . "<br>";
    
    fclose($socket);
} else {
    echo "❌ Connection failed: {$errstr} ({$errno})<br>";
    exit;
}

// Test 2: PHPMailer Test
echo "<h3>Test 2: PHPMailer Test</h3>";

if (file_exists('application/smtpmailer/class.phpmailer.php')) {
    require_once 'application/smtpmailer/class.phpmailer.php';
    require_once 'application/smtpmailer/class.smtp.php';
    
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $gmail_config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $gmail_config['username'];
    $mail->Password = $gmail_config['password'];
    $mail->SMTPSecure = $gmail_config['encryption'];
    $mail->Port = $gmail_config['port'];
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->Timeout = 30;
    
    // Set from address
    $mail->setFrom($gmail_config['from_email'], $gmail_config['from_name']);
    
    // Add recipient
    $test_email = isset($_GET['email']) ? $_GET['email'] : 'test@example.com';
    $mail->addAddress($test_email);
    
    // Set email content
    $mail->isHTML(true);
    $mail->Subject = 'Gmail SMTP Test - ' . date('Y-m-d H:i:s');
    $mail->Body = '<h3>Gmail SMTP Test Successful!</h3><p>This email was sent using Gmail SMTP from HMS India system.</p><p>Test time: ' . date('Y-m-d H:i:s') . '</p>';
    
    echo "<form method='GET'>";
    echo "Test Email Address: <input type='email' name='email' value='{$test_email}' required><br><br>";
    echo "<input type='submit' value='Send Test Email'>";
    echo "</form>";
    
    if (isset($_GET['email']) && !empty($_GET['email'])) {
        echo "<br><strong>Attempting to send test email...</strong><br>";
        
        try {
            if ($mail->send()) {
                echo "✅ Email sent successfully!<br>";
                echo "Check your inbox at: {$test_email}<br>";
            } else {
                echo "❌ Email sending failed!<br>";
                echo "Error: " . $mail->ErrorInfo . "<br>";
            }
        } catch (Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "<br>";
        }
    }
    
} else {
    echo "❌ PHPMailer not found<br>";
}

// Test 3: Alternative Gmail Configuration (SSL on port 465)
echo "<h3>Test 3: Alternative Gmail Configuration (SSL on port 465)</h3>";

$gmail_ssl_config = $gmail_config;
$gmail_ssl_config['port'] = 465;
$gmail_ssl_config['encryption'] = 'ssl';

echo "Testing Gmail with SSL on port 465...<br>";

$socket_ssl = @fsockopen($gmail_ssl_config['host'], $gmail_ssl_config['port'], $errno, $errstr, 10);
if ($socket_ssl) {
    echo "✅ Connected to {$gmail_ssl_config['host']}:{$gmail_ssl_config['port']}<br>";
    
    // Read server greeting
    $response = fgets($socket_ssl, 1024);
    echo "Server greeting: " . trim($response) . "<br>";
    
    fclose($socket_ssl);
} else {
    echo "❌ SSL connection failed: {$errstr} ({$errno})<br>";
}

// Test 4: Check Gmail App Password Requirements
echo "<h3>Test 4: Gmail App Password Check</h3>";
echo "<p><strong>Important:</strong> Gmail requires App Passwords for SMTP authentication.</p>";
echo "<p>Make sure you're using an App Password, not your regular Gmail password.</p>";
echo "<p>To generate an App Password:</p>";
echo "<ol>";
echo "<li>Go to your Google Account settings</li>";
echo "<li>Security → 2-Step Verification → App passwords</li>";
echo "<li>Generate a new app password for 'Mail'</li>";
echo "<li>Use that password in the configuration</li>";
echo "</ol>";

echo "<hr>";

// Test 5: Network Diagnostics
echo "<h3>Test 5: Network Diagnostics</h3>";

// Check if we can reach Gmail
echo "Checking Gmail connectivity...<br>";
$gmail_ip = gethostbyname('smtp.gmail.com');
echo "Gmail IP: {$gmail_ip}<br>";

// Test both ports
$ports = [587, 465];
foreach ($ports as $port) {
    $socket = @fsockopen('smtp.gmail.com', $port, $errno, $errstr, 5);
    if ($socket) {
        echo "✅ Port {$port} is accessible<br>";
        fclose($socket);
    } else {
        echo "❌ Port {$port} is not accessible: {$errstr}<br>";
    }
}

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p><strong>Gmail SMTP Configuration:</strong></p>";
echo "<ul>";
echo "<li>Host: smtp.gmail.com</li>";
echo "<li>Port: 587 (TLS) or 465 (SSL)</li>";
echo "<li>Encryption: TLS or SSL</li>";
echo "<li>Authentication: Required (App Password)</li>";
echo "</ul>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Verify you're using an App Password (not regular password)</li>";
echo "<li>Test with the form above to send a real email</li>";
echo "<li>Check your email configuration files</li>";
echo "</ol>";

echo "<p><strong>Test completed at:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>
