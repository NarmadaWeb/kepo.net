<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get Order History
$stmt = $pdo->prepare("SELECT o.*, p.name as package_name, p.speed
                       FROM orders o
                       JOIN packages p ON o.package_id = p.id
                       WHERE o.user_id = ?
                       ORDER BY o.created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container" style="padding: 50px 0;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1>Dashboard Pelanggan</h1>
            <p style="color: var(--text-secondary);">Halo, <strong><?= $_SESSION['user_name'] ?></strong>. Selamat datang kembali.</p>
        </div>
        <a href="../packages.php" class="btn btn-primary">Pesan WiFi Baru</a>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 20px;">Riwayat Pesanan</h3>
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
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                Anda belum memiliki pesanan. <a href="../packages.php" style="color: var(--primary-color);">Pesan sekarang!</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td style="font-family: 'JetBrains Mono', monospace; font-size: 13px;"><?= $order['order_number'] ?></td>
                                <td>
                                    <strong><?= $order['package_name'] ?></strong><br>
                                    <small style="color: var(--text-secondary);"><?= $order['speed'] ?></small>
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
                                    <div style="display: flex; gap: 10px;">
                                        <?php if ($order['status'] == 'waiting_payment'): ?>
                                            <a href="../pay.php?order_id=<?= $order['id'] ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Bayar</a>
                                        <?php endif; ?>
                                        <a href="track.php?order_number=<?= $order['order_number'] ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">Lacak</a>
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
