<?php
define('BASE_URL', 'http://localhost/kepo_net/');

// Midtrans Configuration
define('MIDTRANS_SERVER_KEY', 'YOUR_SERVER_KEY');
define('MIDTRANS_CLIENT_KEY', 'YOUR_CLIENT_KEY');
define('MIDTRANS_IS_PRODUCTION', false);
define('MIDTRANS_IS_SANITIZED', true);
define('MIDTRANS_IS_3DS', true);

session_start();

function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
?>
