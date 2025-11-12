<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Email Settings
| -------------------------------------------------------------------
| This file tells CodeIgniter's built-in Email library how to log in.
*/

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com';        // From your file
$config['smtp_port']   = 587;                     // From your file
$config['smtp_user']   = 'ranjeet.kumar@indiaivf.in'; // From your file
$config['smtp_pass']   = 'fqojlbmhnetadgcz'; // Your NEW App Password
$config['smtp_crypto'] = 'tls';                     // From your file
$config['mailtype']    = 'html';
$config['charset']     = 'iso-8859-1';
$config['wordwrap']    = TRUE;
$config['newline']     = "\r\n";