<?php
require_once 'config/config.php';
require_once 'config/database.php';

$order_id = $_GET['order_id'] ?? null;
if (!$order_id || !isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT o.*, p.name as package_name, u.email as user_email, u.name as user_name, u.phone as user_phone
                       FROM orders o
                       JOIN packages p ON o.package_id = p.id
                       JOIN users u ON o.user_id = u.id
                       WHERE o.id = ? AND o.user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Midtrans integration (Dummy for now as I don't have the real SDK/keys)
// In a real scenario, you'd use Midtrans Snap API to get a snap_token
$snap_token = $order['snap_token'];

if (!$snap_token) {
    // Generate snap token via Midtrans API
    // $params = [...];
    // $snap_token = \Midtrans\Snap::getSnapToken($params);

    // For this simulation, we'll generate a dummy token if not exists
    $snap_token = 'dummy-token-' . uniqid();
    $stmt = $pdo->prepare("UPDATE orders SET snap_token = ?, status = 'waiting_payment' WHERE id = ?");
    $stmt->execute([$snap_token, $order_id]);
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 600px; padding: 100px 0;">
    <div class="card" style="text-align: center;">
        <div style="background-color: #e3f2fd; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <span class="material-symbols-outlined" style="color: var(--primary-color); font-size: 32px;">payments</span>
        </div>
        <h2>Selesaikan Pembayaran</h2>
        <p style="color: var(--text-secondary); margin-bottom: 30px;">Hampir selesai! Silakan lakukan pembayaran untuk pesanan <strong>#<?= $order['order_number'] ?></strong></p>

        <div style="background-color: var(--surface-color); padding: 20px; border-radius: 12px; margin-bottom: 30px; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: var(--text-secondary);">Paket</span>
                <span style="font-weight: 600;"><?= $order['package_name'] ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; border-top: 1px solid var(--border-color); padding-top: 10px; margin-top: 10px;">
                <span>Total Tagihan</span>
                <span style="color: var(--primary-color);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <button id="pay-button" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">Bayar Sekarang</button>

        <p style="font-size: 12px; color: var(--text-secondary); margin-top: 20px;">
            <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">lock</span> Keamanan transaksi Anda terjamin oleh Midtrans.
        </p>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // In simulation, we just redirect to dashboard with success
        alert('Ini adalah simulasi Midtrans Snap. Di sistem nyata, Anda perlu menginstal library Midtrans-PHP dan mengonfigurasi Server Key yang valid.');
        window.location.href = 'user/dashboard.php?status=success';

        /*
        Contoh Implementasi Backend (pay.php):

        require_once 'vendor/autoload.php';
        \Midtrans\Config::$serverKey = MIDTRANS_SERVER_KEY;
        \Midtrans\Config::$isProduction = MIDTRANS_IS_PRODUCTION;
        \Midtrans\Config::$isSanitized = MIDTRANS_IS_SANITIZED;
        \Midtrans\Config::$is3ds = MIDTRANS_IS_3DS;

        $params = [
            'transaction_details' => [
                'order_id' => $order['order_number'],
                'gross_amount' => (int)$order['total_amount'],
            ],
            'customer_details' => [
                'first_name' => $order['user_name'],
                'email' => $order['user_email'],
                'phone' => $order['user_phone'],
            ],
        ];

        $snap_token = \Midtrans\Snap::getSnapToken($params);

        --------------------------------------------------

        Real code for JS:
        snap.pay('<?= $snap_token ?>', {
            onSuccess: function(result){ window.location.href = 'user/dashboard.php?status=success'; },
            onPending: function(result){ window.location.href = 'user/dashboard.php?status=pending'; },
            onError: function(result){ alert("Pembayaran gagal!"); },
            onClose: function(){ alert('Anda menutup popup tanpa menyelesaikan pembayaran'); }
        });
        */
    };
</script>

<?php include 'includes/footer.php'; ?>
