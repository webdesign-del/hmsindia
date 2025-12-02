<?php
/**
 * Production Mail Send Test Script
 * 
 * Usage from PowerShell:
 *   php test_mail_production.php
 *   php test_mail_production.php your-email@example.com
 * 
 * This script tests email sending functionality for production environment
 */

// Set execution time limit
set_time_limit(120);

// ANSI color codes for PowerShell output
define('COLOR_GREEN', "\033[32m");
define('COLOR_RED', "\033[31m");
define('COLOR_YELLOW', "\033[33m");
define('COLOR_BLUE', "\033[34m");
define('COLOR_RESET', "\033[0m");

// Enable colored output
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Enable ANSI colors on Windows
    system('');
}

// Helper functions for colored output
function print_success($message) {
    echo COLOR_GREEN . "✓ " . $message . COLOR_RESET . "\n";
}

function print_error($message) {
    echo COLOR_RED . "✗ " . $message . COLOR_RESET . "\n";
}

function print_warning($message) {
    echo COLOR_YELLOW . "⚠ " . $message . COLOR_RESET . "\n";
}

function print_info($message) {
    echo COLOR_BLUE . "ℹ " . $message . COLOR_RESET . "\n";
}

function print_header($message) {
    echo "\n" . COLOR_BLUE . str_repeat("=", 60) . COLOR_RESET . "\n";
    echo COLOR_BLUE . $message . COLOR_RESET . "\n";
    echo COLOR_BLUE . str_repeat("=", 60) . COLOR_RESET . "\n\n";
}

// Set environment to production
define('ENVIRONMENT', 'production');

print_header("PRODUCTION MAIL SEND TEST");

// Get test email from command line argument or use default
$test_email = $argv[1] ?? 'ranjeet.kumar@indiaivf.in';
print_info("Test email address: $test_email");

// Step 1: Check dependencies
print_header("Step 1: Checking Dependencies");

// Check PHPMailer
$phpmailer_path = 'application/smtpmailer/class.phpmailer.php';
if (file_exists($phpmailer_path)) {
    require_once($phpmailer_path);
    print_success("PHPMailer found: $phpmailer_path");
} else {
    print_error("PHPMailer not found: $phpmailer_path");
    exit(1);
}

// Check if PHPMailer class exists
if (class_exists('PHPMailer')) {
    print_success("PHPMailer class loaded successfully");
} else {
    print_error("PHPMailer class not available");
    exit(1);
}

// Check OpenSSL for TLS
if (extension_loaded('openssl')) {
    print_success("OpenSSL extension available (required for TLS/SSL)");
} else {
    print_error("OpenSSL extension not found (required for secure SMTP)");
    exit(1);
}

// Check sockets
if (extension_loaded('sockets')) {
    print_success("Sockets extension available");
} else {
    print_warning("Sockets extension not found (may affect SMTP)");
}

// Step 2: Load email configuration
print_header("Step 2: Loading Email Configuration");

$config_file = 'application/config/production/email.php';
if (!file_exists($config_file)) {
    print_error("Production email config not found: $config_file");
    exit(1);
}

print_success("Config file found: $config_file");

// Read and parse configuration
$config_content = file_get_contents($config_file);

// Extract config values using regex
preg_match('/\$config\[\'mail_host\'\]\s*=\s*[\'"]([^\'"]+)[\'"];/', $config_content, $host_match);
preg_match('/\$config\[\'mail_username\'\]\s*=\s*[\'"]([^\'"]+)[\'"];/', $config_content, $username_match);
preg_match('/\$config\[\'mail_password\'\]\s*=\s*[\'"]([^\'"]+)[\'"];/', $config_content, $password_match);
preg_match('/\$config\[\'mail_from_emailid\'\]\s*=\s*[\'"]([^\'"]+)[\'"];/', $config_content, $from_email_match);
preg_match('/\$config\[\'mail_from_name\'\]\s*=\s*[\'"]([^\'"]+)[\'"];/', $config_content, $from_name_match);
preg_match('/\$config\[\'mail_port\'\]\s*=\s*(\d+);/', $config_content, $port_match);
preg_match('/\$config\[\'mail_encryption\'\]\s*=\s*[\'"]([^\'"]+)[\'"];/', $config_content, $encryption_match);

$config = array(
    'mail_host' => $host_match[1] ?? '',
    'mail_username' => $username_match[1] ?? '',
    'mail_password' => $password_match[1] ?? '',
    'mail_from_emailid' => $from_email_match[1] ?? '',
    'mail_from_name' => $from_name_match[1] ?? '',
    'mail_port' => (int)($port_match[1] ?? 587),
    'mail_encryption' => $encryption_match[1] ?? 'tls'
);

// Validate configuration
$config_valid = true;

if (empty($config['mail_host'])) {
    print_error("SMTP host not configured");
    $config_valid = false;
} else {
    print_success("SMTP Host: " . $config['mail_host']);
}

if (empty($config['mail_username'])) {
    print_error("SMTP username not configured");
    $config_valid = false;
} else {
    print_success("SMTP Username: " . $config['mail_username']);
}

if (empty($config['mail_password'])) {
    print_error("SMTP password not configured");
    $config_valid = false;
} else {
    $masked_password = str_repeat('*', strlen($config['mail_password']));
    print_success("SMTP Password: $masked_password (configured)");
}

if (empty($config['mail_from_emailid'])) {
    print_error("From email not configured");
    $config_valid = false;
} else {
    print_success("From Email: " . $config['mail_from_emailid']);
}

if (empty($config['mail_from_name'])) {
    print_warning("From name not configured");
} else {
    print_success("From Name: " . $config['mail_from_name']);
}

print_success("Port: " . $config['mail_port']);
print_success("Encryption: " . strtoupper($config['mail_encryption']));

if (!$config_valid) {
    print_error("Email configuration is incomplete. Please check config file.");
    exit(1);
}

// Step 3: Test SMTP connection
print_header("Step 3: Testing SMTP Connection");

try {
    $smtp = new PHPMailer(true);
    $smtp->isSMTP();
    $smtp->Host = $config['mail_host'];
    $smtp->SMTPAuth = true;
    $smtp->Username = $config['mail_username'];
    $smtp->Password = $config['mail_password'];
    $smtp->SMTPSecure = $config['mail_encryption'];
    $smtp->Port = $config['mail_port'];
    $smtp->Timeout = 30;
    
    // Set SMTP options for better compatibility
    $smtp->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    print_info("Connecting to SMTP server...");
    $smtp->smtpConnect();
    print_success("SMTP connection established successfully");
    $smtp->smtpClose();
} catch (Exception $e) {
    print_error("SMTP connection failed: " . $e->getMessage());
    print_warning("Will attempt to send email anyway...");
}

// Step 4: Send test email
print_header("Step 4: Sending Test Email");

$subject = 'Production Mail Test - ' . date('Y-m-d H:i:s');
$message = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Production Mail Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .info-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .info-table td:first-child { font-weight: bold; width: 40%; }
        .success { color: #4CAF50; font-weight: bold; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Production Mail Test</h1>
        </div>
        <div class="content">
            <p class="success">Congratulations! Your production email system is working correctly.</p>
            
            <h2>Test Details</h2>
            <table class="info-table">
                <tr>
                    <td>Environment</td>
                    <td>' . ENVIRONMENT . '</td>
                </tr>
                <tr>
                    <td>Test Date/Time</td>
                    <td>' . date('Y-m-d H:i:s T') . '</td>
                </tr>
                <tr>
                    <td>Server</td>
                    <td>' . ($_SERVER['HTTP_HOST'] ?? gethostname()) . '</td>
                </tr>
                <tr>
                    <td>SMTP Host</td>
                    <td>' . $config['mail_host'] . '</td>
                </tr>
                <tr>
                    <td>SMTP Port</td>
                    <td>' . $config['mail_port'] . '</td>
                </tr>
                <tr>
                    <td>Encryption</td>
                    <td>' . strtoupper($config['mail_encryption']) . '</td>
                </tr>
                <tr>
                    <td>From Address</td>
                    <td>' . $config['mail_from_name'] . ' &lt;' . $config['mail_from_emailid'] . '&gt;</td>
                </tr>
                <tr>
                    <td>PHP Version</td>
                    <td>' . phpversion() . '</td>
                </tr>
            </table>
            
            <h2>What This Means</h2>
            <p>If you are reading this email, it confirms that:</p>
            <ul>
                <li>✓ SMTP configuration is correct</li>
                <li>✓ Authentication credentials are valid</li>
                <li>✓ Network connectivity is working</li>
                <li>✓ SSL/TLS encryption is functioning</li>
                <li>✓ Email delivery is operational</li>
            </ul>
            
            <p><strong>Your production email system is ready to send emails!</strong></p>
        </div>
        <div class="footer">
            <p>This is an automated test email from HMS India Production System</p>
            <p>Generated by test_mail_production.php</p>
        </div>
    </div>
</body>
</html>
';

try {
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = $config['mail_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['mail_username'];
    $mail->Password = $config['mail_password'];
    $mail->SMTPSecure = $config['mail_encryption'];
    $mail->Port = $config['mail_port'];
    $mail->Timeout = 30;
    
    // Set SMTP options
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    // Recipients
    $mail->setFrom($config['mail_from_emailid'], $config['mail_from_name']);
    $mail->addAddress($test_email);
    
    // Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);
    
    print_info("Preparing email...");
    print_info("To: $test_email");
    print_info("Subject: $subject");
    print_info("Sending email...");
    
    $result = $mail->send();
    
    if ($result) {
        print_success("EMAIL SENT SUCCESSFULLY!");
        print_success("Check inbox: $test_email");
        
        print_header("Test Result: SUCCESS");
        print_success("Production mail system is working correctly");
        print_info("Date/Time: " . date('Y-m-d H:i:s T'));
        print_info("From: " . $config['mail_from_emailid']);
        print_info("To: $test_email");
        
        exit(0);
    } else {
        print_error("Failed to send email (no exception thrown)");
        exit(1);
    }
    
} catch (Exception $e) {
    print_error("EMAIL SEND FAILED!");
    print_error("Error: " . $e->getMessage());
    
    print_header("Troubleshooting Tips");
    print_info("1. Verify SMTP credentials in: $config_file");
    print_info("2. Check if SMTP server allows connections from this IP");
    print_info("3. Verify firewall rules allow outbound SMTP connections");
    print_info("4. Check if less secure apps are enabled (for Gmail)");
    print_info("5. Review application logs for detailed error messages");
    
    exit(1);
}
?>

