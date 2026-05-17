<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Check and update order status if coming from payment
if (isset($_GET['status']) && isset($_GET['order_id'])) {
    require_once '../includes/midtrans_helper.php';
    $order_number = $_GET['order_id'];

    $stmt = $pdo->prepare("SELECT id, status FROM orders WHERE order_number = ? AND user_id = ?");
    $stmt->execute([$order_number, $user_id]);
    $order_to_check = $stmt->fetch();

    if ($order_to_check && ($order_to_check['status'] == 'waiting_payment' || $order_to_check['status'] == 'pending')) {
        $status_data = MidtransHelper::getTransactionStatus($order_number);
        if ($status_data) {
            $transaction_status = $status_data['transaction_status'];
            $new_status = null;

            if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
                $new_status = 'paid';
            } else if ($transaction_status == 'pending') {
                $new_status = 'waiting_payment';
            } else if ($transaction_status == 'deny' || $transaction_status == 'expire' || $transaction_status == 'cancel') {
                $new_status = 'cancelled';
            }

            if ($new_status) {
                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$new_status, $order_to_check['id']]);
            }
        }
    }
}

$stmt = $pdo->prepare("SELECT o.*, p.name as package_name, p.speed
                       FROM orders o
                       JOIN packages p ON o.package_id = p.id
                       WHERE o.user_id = ?
                       ORDER BY o.created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container" style="padding: 60px 0;">
    <div class="flex justify-between items-center mb-20" style="flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 32px; letter-spacing: -0.025em;">Dashboard Pelanggan</h1>
            <p class="text-muted">Halo, <strong><?= $_SESSION['user_name'] ?></strong>. Selamat datang kembali.</p>
        </div>
        <a href="../packages.php" class="btn btn-primary">Pesan WiFi Baru</a>
    </div>

    <div class="card">
        <h3 class="mb-20">Riwayat Pesanan</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Paket</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 60px;">
                                <div class="text-muted mb-20">Anda belum memiliki pesanan.</div>
                                <a href="../packages.php" class="btn btn-outline">Pesan sekarang!</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td style="font-family: monospace; font-weight: 600; color: var(--primary);"><?= $order['order_number'] ?></td>
                                <td>
                                    <strong><?= $order['package_name'] ?></strong><br>
                                    <small class="text-muted"><?= $order['speed'] ?></small>
                                </td>
                                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                <td><strong>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <?php
                                    $status_map = [
                                        'pending' => ['badge-pending', 'Pending'],
                                        'waiting_payment' => ['badge-pending', 'Menunggu Bayar'],
                                        'paid' => ['badge-info', 'Dibayar'],
                                        'processing' => ['badge-info', 'Proses Pasang'],
                                        'completed' => ['badge-success', 'Selesai'],
                                        'cancelled' => ['badge-error', 'Dibatalkan']
                                    ];
                                    $current_status = $status_map[$order['status']] ?? ['badge-outline', $order['status']];
                                    ?>
                                    <span class="badge <?= $current_status[0] ?>"><?= $current_status[1] ?></span>
                                </td>
                                <td>
                                    <div class="flex gap-10">
                                        <?php if ($order['status'] == 'waiting_payment'): ?>
                                            <a href="../pay.php?order_id=<?= $order['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">Bayar</a>
                                        <?php endif; ?>
                                        <a href="track.php?order_number=<?= $order['order_number'] ?>" class="btn btn-outline" style="padding: 8px 16px; font-size: 12px;">Lacak</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
