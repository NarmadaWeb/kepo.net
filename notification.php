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
    }
}

http_response_code(200);
?>
