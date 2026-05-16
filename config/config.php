<?php
// Dynamic BASE_URL detection
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_dir = str_replace(basename($script_name), "", $script_name);

// If we are in admin/ or user/ directory, we need to go up one level
if (strpos($base_dir, '/admin/') !== false) {
    $base_dir = str_replace('/admin/', '/', $base_dir);
} elseif (strpos($base_dir, '/user/') !== false) {
    $base_dir = str_replace('/user/', '/', $base_dir);
}

define('BASE_URL', $protocol . "://" . $host . $base_dir);

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
