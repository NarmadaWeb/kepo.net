<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/midtrans_helper.php';

$bill_id = $_GET['bill_id'] ?? null;
if (!$bill_id || !isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT mb.*, p.name as package_name, u.email as user_email, u.name as user_name, u.phone as user_phone, o.order_number
                       FROM monthly_bills mb
                       JOIN orders o ON mb.order_id = o.id
                       JOIN packages p ON o.package_id = p.id
                       JOIN users u ON o.user_id = u.id
                       WHERE mb.id = ? AND o.user_id = ?");
$stmt->execute([$bill_id, $_SESSION['user_id']]);
$bill = $stmt->fetch();

if (!$bill || $bill['status'] == 'paid') {
    header('Location: user/dashboard.php');
    exit;
}

$snap_token = $bill['snap_token'];
if (!$snap_token) {
    $order_details = [
        'order_id' => $bill['bill_number'],
        'gross_amount' => (int)$bill['amount'],
    ];

    $customer_details = [
        'first_name' => $bill['user_name'],
        'email' => $bill['user_email'],
        'phone' => $bill['user_phone'],
    ];

    $snap_token = MidtransHelper::getSnapToken($order_details, $customer_details);

    if ($snap_token) {
        $stmt = $pdo->prepare("UPDATE monthly_bills SET snap_token = ? WHERE id = ?");
        $stmt->execute([$snap_token, $bill_id]);
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
        <h2 class="mb-20">Bayar Tagihan Bulanan</h2>
        <p class="text-muted mb-20">Pembayaran untuk periode <strong><?= date('F Y', mktime(0, 0, 0, $bill['bill_month'], 10, $bill['bill_year'])) ?></strong></p>

        <div class="mb-20" style="background-color: var(--bg-main); padding: 32px; border-radius: var(--radius-lg); text-align: left;">
            <div class="flex justify-between mb-20">
                <span class="text-muted">No. Tagihan</span>
                <span style="font-weight: 700; color: var(--text-main);"><?= $bill['bill_number'] ?></span>
            </div>
            <div class="flex justify-between mb-20">
                <span class="text-muted">Paket Internet</span>
                <span style="font-weight: 700; color: var(--text-main);"><?= $bill['package_name'] ?></span>
            </div>
            <div class="flex justify-between py-20" style="font-size: 20px; font-weight: 800; border-top: 1px solid var(--border); margin-top: 20px;">
                <span>Total Tagihan</span>
                <span style="color: var(--primary); letter-spacing: -0.025em;">Rp <?= number_format($bill['amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <button id="pay-button" class="btn btn-primary w-full" style="padding: 16px; font-size: 16px; box-shadow: var(--shadow-lg);">Bayar Sekarang</button>

        <a href="user/dashboard.php" class="btn btn-outline w-full mt-20">Kembali ke Dashboard</a>
    </div>
</div>

<script src="<?= MIDTRANS_IS_PRODUCTION ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('<?= $snap_token ?>', {
            onSuccess: function(result){
                window.location.href = 'user/dashboard.php?status=success&bill_id=<?= $bill['bill_number'] ?>';
            },
            onPending: function(result){
                window.location.href = 'user/dashboard.php?status=pending&bill_id=<?= $bill['bill_number'] ?>';
            },
            onError: function(result){
                window.location.href = 'user/dashboard.php?status=error&bill_id=<?= $bill['bill_number'] ?>';
            }
        });
    };
</script>

<?php include 'includes/footer.php'; ?>
