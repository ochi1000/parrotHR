<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'smtp.yandex.com', 
    'smtp_port' => 465,
    'smtp_user' => 'admin@zercomsystems.com',
    'smtp_pass' => 'zercom123',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '5', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE,
    'newline' => "\r\n"

);