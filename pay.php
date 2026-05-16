<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/midtrans_helper.php';

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

$snap_token = $order['snap_token'];
if (!$snap_token) {
    $order_details = [
        'order_id' => $order['order_number'],
        'gross_amount' => (int)$order['total_amount'],
    ];

    $customer_details = [
        'first_name' => $order['user_name'],
        'email' => $order['user_email'],
        'phone' => $order['user_phone'],
    ];

    $snap_token = MidtransHelper::getSnapToken($order_details, $customer_details);

    if ($snap_token) {
        $stmt = $pdo->prepare("UPDATE orders SET snap_token = ?, status = 'waiting_payment' WHERE id = ?");
        $stmt->execute([$snap_token, $order_id]);
    } else {
        $error = "Gagal mendapatkan token pembayaran. Silakan coba lagi nanti.";
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 600px; padding: 100px 0;">
    <div class="card text-center">
        <div style="background-color: #eff6ff; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 32px;">
            <span class="material-symbols-outlined" style="color: var(--primary); font-size: 40px;">payments</span>
        </div>
        <h2 class="mb-20">Selesaikan Pembayaran</h2>
        <p class="text-muted mb-20">Hampir selesai! Silakan lakukan pembayaran untuk pesanan <strong>#<?= $order['order_number'] ?></strong></p>

        <div class="mb-20" style="background-color: var(--bg-main); padding: 32px; border-radius: var(--radius-lg); text-align: left;">
            <div class="flex justify-between mb-20">
                <span class="text-muted">Paket Internet</span>
                <span style="font-weight: 700; color: var(--text-main);"><?= $order['package_name'] ?></span>
            </div>
            <div class="flex justify-between py-20" style="font-size: 20px; font-weight: 800; border-top: 1px solid var(--border); margin-top: 20px;">
                <span>Total Tagihan</span>
                <span style="color: var(--primary); letter-spacing: -0.025em;">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <button id="pay-button" class="btn btn-primary w-full" style="padding: 16px; font-size: 16px; box-shadow: var(--shadow-lg);">Bayar Sekarang</button>

        <p class="mt-20" style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">lock</span> Keamanan transaksi Anda terjamin oleh Midtrans.
        </p>
    </div>
</div>

<script src="<?= MIDTRANS_IS_PRODUCTION ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('<?= $snap_token ?>', {
            // Optional
            onSuccess: function(result){
                /* You may add your own js here, this is just example */
                window.location.href = 'user/dashboard.php?status=success&order_id=<?= $order['order_number'] ?>';
            },
            // Optional
            onPending: function(result){
                /* You may add your own js here, this is just example */
                window.location.href = 'user/dashboard.php?status=pending&order_id=<?= $order['order_number'] ?>';
            },
            // Optional
            onError: function(result){
                /* You may add your own js here, this is just example */
                window.location.href = 'user/dashboard.php?status=error&order_id=<?= $order['order_number'] ?>';
            }
        });
    };
</script>

<?php include 'includes/footer.php'; ?>
