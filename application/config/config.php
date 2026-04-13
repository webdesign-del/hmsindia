<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to $_SERVER['SERVER_ADDR'] if available, or localhost if the IP address
| is not available.
|
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/

// Get environment from environment variable or default to development
$environment = defined('ENVIRONMENT') ? ENVIRONMENT : 'development';    
switch ($environment) {
    case 'production':
        $config['base_url'] = getenv('BASE_URL') ?: 'https://indiaivf.website/';
        $config['index_page'] = '';
        $config['uri_protocol'] = 'REQUEST_URI';
        $config['url_suffix'] = '';
        $config['language'] = 'english';
        $config['charset'] = 'UTF-8';
        $config['enable_hooks'] = TRUE;
        $config['subclass_prefix'] = 'MY_';
        $config['composer_autoload'] = FALSE;
        $config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
        $config['enable_query_strings'] = FALSE;
        $config['controller_trigger'] = 'c';
        $config['function_trigger'] = 'm';
        $config['directory_trigger'] = 'd';
        $config['allow_get_array'] = TRUE;
        $config['log_threshold'] = 1; // Only errors in production
        $config['log_path'] = '';
        $config['log_file_extension'] = '';
        $config['log_file_permissions'] = 0644;
        $config['log_date_format'] = 'Y-m-d H:i:s';
        $config['error_views_path'] = '';
        $config['cache_path'] = '';
        $config['cache_query_string'] = FALSE;
        $config['encryption_key'] = 'K3zPq8VnR4uS7bX2aQ9fL0dG5wY1mT6Z';
        $config['sess_driver'] = 'files';
        $config['sess_cookie_name'] = 'ci_session';
        $config['sess_expiration'] = 7200; // 2 hours in production
        $config['sess_save_path'] = APPPATH . 'cache/sessions';
        $config['sess_match_ip'] = FALSE;
        $config['sess_time_to_update'] = 300;
        $config['sess_regenerate_destroy'] = FALSE;
        $config['sess_use_database'] = FALSE;
        $config['sess_table_name'] = 'ci_sessions';
        $config['cookie_prefix'] = '';
        $config['cookie_domain'] = '';
        $config['cookie_path'] = '/';
        $config['cookie_secure'] = FALSE; // Disabled for HTTP access
        $config['cookie_httponly'] = TRUE;
        $config['standardize_newlines'] = FALSE;
        $config['global_xss_filtering'] = FALSE;
        $config['csrf_protection'] = FALSE;
        $config['csrf_token_name'] = 'csrf_test_name';
        $config['csrf_cookie_name'] = 'csrf_cookie_name';
        $config['csrf_expire'] = 7200;
        $config['csrf_regenerate'] = TRUE;
        $config['csrf_exclude_uris'] = array('', 'welcome/index');
        $config['compress_output'] = TRUE;
        $config['time_reference'] = 'local';
        $config['rewrite_short_tags'] = FALSE;
        $config['proxy_ips'] = '';
        break;
    case 'staging':
        $config['base_url'] = getenv('BASE_URL') ?: 'https://staging.indiaivf.website/';
        $config['index_page'] = '';
        $config['uri_protocol'] = 'REQUEST_URI';
        $config['url_suffix'] = '';
        $config['language'] = 'english';
        $config['charset'] = 'UTF-8';
        $config['enable_hooks'] = TRUE;
        $config['subclass_prefix'] = 'MY_';
        $config['composer_autoload'] = FALSE;
        $config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
        $config['enable_query_strings'] = FALSE;
        $config['controller_trigger'] = 'c';
        $config['function_trigger'] = 'm';
        $config['directory_trigger'] = 'd';
        $config['allow_get_array'] = TRUE;
        $config['log_threshold'] = 1; // Only errors in staging
        $config['log_path'] = '';
        $config['log_file_extension'] = '';
        $config['log_file_permissions'] = 0644;
        $config['log_date_format'] = 'Y-m-d H:i:s';
        $config['error_views_path'] = '';
        $config['cache_path'] = '';
        $config['cache_query_string'] = FALSE;
        $config['encryption_key'] = 'your-32-character-secret-key-here';
        $config['sess_driver'] = 'files';
        $config['sess_cookie_name'] = 'ci_session';
        $config['sess_expiration'] = 7200; // 2 hours in development
        $config['sess_save_path'] = APPPATH . 'cache/sessions';
        $config['sess_match_ip'] = FALSE;
        $config['sess_time_to_update'] = 300;
        $config['sess_regenerate_destroy'] = FALSE;
        $config['cookie_prefix'] = '';
        $config['cookie_domain'] = '';
        $config['cookie_path'] = '/';
        $config['cookie_secure'] = FALSE; // HTTP allowed in development
        $config['cookie_httponly'] = FALSE;
        $config['standardize_newlines'] = FALSE;
        $config['global_xss_filtering'] = FALSE;
        $config['csrf_protection'] = FALSE; // Disabled in development
        $config['csrf_token_name'] = 'csrf_test_name';
        $config['csrf_cookie_name'] = 'csrf_cookie_name';
        $config['csrf_expire'] = 7200;
        $config['csrf_regenerate'] = TRUE;
        $config['csrf_exclude_uris'] = array();
        $config['compress_output'] = FALSE;
        $config['time_reference'] = 'local';
        $config['rewrite_short_tags'] = FALSE;
        $config['proxy_ips'] = '';
        break;
    case 'development':
    default:
        $config['base_url'] = getenv('BASE_URL') ?: 'http://localhost/hmsindia/';
        $config['index_page'] = '';
        $config['uri_protocol'] = 'REQUEST_URI';
        $config['url_suffix'] = '';
        $config['language'] = 'english';
        $config['charset'] = 'UTF-8';
        $config['enable_hooks'] = TRUE;
        $config['subclass_prefix'] = 'MY_';
        $config['composer_autoload'] = FALSE;
       // $config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
        $config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-+=';
        $config['enable_query_strings'] = FALSE;
        $config['controller_trigger'] = 'c';
        $config['function_trigger'] = 'm';
        $config['directory_trigger'] = 'd';
        $config['allow_get_array'] = TRUE;
        $config['log_threshold'] = 1; // Only errors in staging
        $config['log_path'] = '';
        $config['log_file_extension'] = '';
        $config['log_file_permissions'] = 0644;
        $config['log_date_format'] = 'Y-m-d H:i:s';
        $config['error_views_path'] = '';
        $config['cache_path'] = '';
        $config['cache_query_string'] = FALSE;
        $config['encryption_key'] = 'your-32-character-secret-key-here';
        $config['sess_driver'] = 'files';
        $config['sess_cookie_name'] = 'ci_session';
        $config['sess_expiration'] = 7200; // 2 hours in development
        $config['sess_save_path'] = APPPATH . 'cache/sessions';
        $config['sess_match_ip'] = FALSE;
        $config['sess_time_to_update'] = 300;
        $config['sess_regenerate_destroy'] = FALSE;
        $config['cookie_prefix'] = '';
        $config['cookie_domain'] = '';
        $config['cookie_path'] = '/';
        $config['cookie_secure'] = FALSE; // HTTP allowed in development
        $config['cookie_httponly'] = FALSE;
        $config['standardize_newlines'] = FALSE;
        $config['global_xss_filtering'] = FALSE;
        $config['csrf_protection'] = FALSE; // Disabled in development
        $config['csrf_token_name'] = 'csrf_test_name';
        $config['csrf_cookie_name'] = 'csrf_cookie_name';
        $config['csrf_expire'] = 7200;
        $config['csrf_regenerate'] = TRUE;
        $config['csrf_exclude_uris'] = array();
        $config['compress_output'] = FALSE;
        $config['time_reference'] = 'local';
        $config['rewrite_short_tags'] = FALSE;
        $config['proxy_ips'] = '';
        break;
}

// Additional configuration
$config['db_prefix'] = 'hms_';
$config['upload_path'] = FCPATH . 'assets/';

/**** SEND MAIL ********/
// Load environment-specific email configuration
$env_config_file = APPPATH . 'config/' . ENVIRONMENT . '/email.php';
if (file_exists($env_config_file)) {
    // Load environment-specific email config
    include($env_config_file);
} else {
    // Fallback to production settings if environment config doesn't exist
    $config['mail_host'] = 'mail.indiaivf.website';
    $config['mail_username'] = 'billings@indiaivf.website';
    $config['mail_password'] = 'qYlSbaXXsn&9';
    $config['mail_from_emailid'] = 'info@indiaivf.website';
    $config['mail_from_name'] = 'IndiaIVF';
    $config['mail_port'] = 587;
    $config['mail_encryption'] = 'tls';
    $config['mail_debug'] = false;
}

// Ensure all email config keys are set with defaults
$config['mail_host'] = $config['mail_host'] ?? 'localhost';
$config['mail_username'] = $config['mail_username'] ?? '';
$config['mail_password'] = $config['mail_password'] ?? '';
$config['mail_from_emailid'] = $config['mail_from_emailid'] ?? 'noreply@localhost';
$config['mail_from_name'] = $config['mail_from_name'] ?? 'System';
$config['mail_port'] = $config['mail_port'] ?? 587;
$config['mail_encryption'] = $config['mail_encryption'] ?? 'tls';
$config['mail_debug'] = $config['mail_debug'] ?? false;

/**** SEND MAIL ********/