<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Development Environment Configuration
|--------------------------------------------------------------------------
|
| This file contains configuration settings specific to the development
| environment. These settings override the main config.php settings when
| ENVIRONMENT is set to 'development'.
|
*/

// Development-specific overrides
$config['base_url'] = getenv('BASE_URL') ?: 'http://localhost/hmsindia/';
$config['log_threshold'] = 4; // All messages in development
$config['cookie_secure'] = FALSE; // HTTP allowed in development
$config['cookie_httponly'] = FALSE;
$config['csrf_protection'] = FALSE; // Disabled in development
$config['compress_output'] = FALSE;
$config['sess_expiration'] = 7200; // 2 hours in development
$config['sess_save_path'] = APPPATH . 'cache/sessions';

// Development email settings (if not already set in email.php)
$config['mail_debug'] = true;
