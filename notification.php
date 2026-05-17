<?php
require_once 'config/config.php';
require_once 'config/database.php';

// Midtrans Notification Handler (Webhook)
// This file would be called by Midtrans server

$json_result = file_get_contents('php://input');
$result = json_decode($json_result, true);

if ($result) {
    $order_id_raw = $result['order_id']; // This is order_number in our DB
    $transaction_status = $result['transaction_status'];
    $payment_type = $result['payment_type'];
    $transaction_id = $result['transaction_id'];
    $gross_amount = $result['gross_amount'];
    $status_code = $result['status_code'];
    $signature_key = $result['signature_key'];

    // Verify signature
    $server_key = MIDTRANS_SERVER_KEY;
    $hashed = hash("sha512", $order_id_raw . $status_code . $gross_amount . $server_key);
    if ($hashed !== $signature_key) {
        error_log("Midtrans Notification: Invalid signature");
        http_response_code(403);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM orders WHERE order_number = ?");
    $stmt->execute([$order_id_raw]);
    $order = $stmt->fetch();

    if ($order) {
        $db_order_id = $order['id'];

        // Update payment status
        if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
            $stmt->execute([$db_order_id]);
        } else if ($transaction_status == 'pending') {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'waiting_payment' WHERE id = ?");
            $stmt->execute([$db_order_id]);
        } else if ($transaction_status == 'deny' || $transaction_status == 'expire' || $transaction_status == 'cancel') {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$db_order_id]);
        }

        // Log to payments table
        $stmt = $pdo->prepare("INSERT INTO payments (order_id, transaction_id, payment_type, gross_amount, transaction_status, transaction_time)
                               VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$db_order_id, $transaction_id, $payment_type, $gross_amount, $transaction_status]);

        // Also update snap_token in orders if not already there (optional, but good for consistency)
        if (isset($result['snap_token'])) {
            $stmt = $pdo->prepare("UPDATE orders SET snap_token = ? WHERE id = ? AND (snap_token IS NULL OR snap_token = '')");
            $stmt->execute([$result['snap_token'], $db_order_id]);
        }
    }
}

http_response_code(200);
?>
