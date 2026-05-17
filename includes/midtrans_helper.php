<?php

class MidtransHelper {
    public static function getSnapToken($order_details, $customer_details) {
        $server_key = MIDTRANS_SERVER_KEY;
        $is_production = MIDTRANS_IS_PRODUCTION;

        $url = $is_production
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => $order_details,
            'customer_details' => $customer_details,
        ];

        $json_payload = json_encode($payload);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code == 201 || $http_code == 200) {
            $result = json_decode($response, true);
            return $result['token'];
        } else {
            // Log error or handle it
            error_log("Midtrans API Error: " . $response);
            return null;
        }
    }

    public static function getTransactionStatus($order_id) {
        $server_key = MIDTRANS_SERVER_KEY;
        $is_production = MIDTRANS_IS_PRODUCTION;

        $url = $is_production
            ? "https://api.midtrans.com/v2/$order_id/status"
            : "https://api.sandbox.midtrans.com/v2/$order_id/status";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code == 200) {
            return json_decode($response, true);
        } else {
            error_log("Midtrans Status Check Error: " . $response);
            return null;
        }
    }
}
