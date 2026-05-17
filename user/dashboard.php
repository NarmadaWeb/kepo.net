<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Check and update order/bill status if coming from payment
if (isset($_GET['status'])) {
    require_once '../includes/midtrans_helper.php';

    if (isset($_GET['order_id'])) {
        $order_number = $_GET['order_id'];
        $stmt = $pdo->prepare("SELECT id, status FROM orders WHERE order_number = ? AND user_id = ?");
        $stmt->execute([$order_number, $user_id]);
        $order_to_check = $stmt->fetch();

        if ($order_to_check && ($order_to_check['status'] == 'waiting_payment' || $order_to_check['status'] == 'pending')) {
            $status_data = MidtransHelper::getTransactionStatus($order_number);
            if ($status_data) {
                $transaction_status = $status_data['transaction_status'];
                $new_status = null;
                if ($transaction_status == 'settlement' || $transaction_status == 'capture') $new_status = 'paid';
                else if ($transaction_status == 'pending') $new_status = 'waiting_payment';
                else if (in_array($transaction_status, ['deny', 'expire', 'cancel'])) $new_status = 'cancelled';

                if ($new_status) {
                    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                    $stmt->execute([$new_status, $order_to_check['id']]);
                }
            }
        }
    } elseif (isset($_GET['bill_id'])) {
        $bill_number = $_GET['bill_id'];
        $stmt = $pdo->prepare("SELECT mb.id, mb.status FROM monthly_bills mb JOIN orders o ON mb.order_id = o.id WHERE mb.bill_number = ? AND o.user_id = ?");
        $stmt->execute([$bill_number, $user_id]);
        $bill_to_check = $stmt->fetch();

        if ($bill_to_check && $bill_to_check['status'] == 'unpaid') {
            $status_data = MidtransHelper::getTransactionStatus($bill_number);
            if ($status_data && ($status_data['transaction_status'] == 'settlement' || $status_data['transaction_status'] == 'capture')) {
                $stmt = $pdo->prepare("UPDATE monthly_bills SET status = 'paid', paid_at = NOW() WHERE id = ?");
                $stmt->execute([$bill_to_check['id']]);
            }
        }
    }
}

// Check for unpaid bills
$stmt = $pdo->prepare("SELECT COUNT(*) FROM monthly_bills mb JOIN orders o ON mb.order_id = o.id WHERE o.user_id = ? AND mb.status = 'unpaid'");
$stmt->execute([$user_id]);
$unpaid_bills_count = $stmt->fetchColumn();

// Fetch Bills
$stmt = $pdo->prepare("SELECT mb.*, p.name as package_name
                       FROM monthly_bills mb
                       JOIN orders o ON mb.order_id = o.id
                       JOIN packages p ON o.package_id = p.id
                       WHERE o.user_id = ?
                       ORDER BY mb.created_at DESC");
$stmt->execute([$user_id]);
$bills = $stmt->fetchAll();

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

    <?php if ($unpaid_bills_count > 0): ?>
        <div class="card mb-20" style="background-color: #fee2e2; border-color: #f87171; display: flex; align-items: center; justify-content: space-between; padding: 24px;">
            <div class="flex items-center gap-10">
                <span class="material-symbols-outlined" style="color: var(--danger); font-size: 32px;">error</span>
                <div>
                    <h3 style="color: #991b1b; font-size: 18px;">Belum Bayar Bulanan</h3>
                    <p style="color: #b91c1c; font-size: 14px;">Ada <?= $unpaid_bills_count ?> tagihan yang belum dibayar. Silakan segera lakukan pembayaran.</p>
                </div>
            </div>
            <a href="#tagihan" class="btn btn-danger">Lihat Tagihan</a>
        </div>
    <?php endif; ?>

    <div class="card mb-20" id="tagihan" style="border-left: 4px solid var(--primary);">
        <div class="flex justify-between items-center mb-20">
            <h3 class="flex items-center gap-10">
                <span class="material-symbols-outlined" style="color: var(--primary);">receipt_long</span> Tagihan Bulanan
            </h3>
            <span class="text-muted" style="font-size: 13px;">Kelola biaya langganan rutin Anda</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. Tagihan</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bills)): ?>
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 40px;">Belum ada tagihan bulanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bills as $bill): ?>
                            <tr>
                                <td style="font-family: monospace; font-weight: 600; color: var(--primary);"><?= $bill['bill_number'] ?></td>
                                <td><?= date('F Y', mktime(0, 0, 0, $bill['bill_month'], 10, $bill['bill_year'])) ?></td>
                                <td><strong>Rp <?= number_format($bill['amount'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <?php if ($bill['status'] == 'paid'): ?>
                                        <span class="badge badge-success">Lunas</span>
                                    <?php else: ?>
                                        <span class="badge badge-error">Belum Bayar</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($bill['status'] == 'unpaid'): ?>
                                        <a href="../pay_bill.php?bill_id=<?= $bill['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">Bayar Sekarang</a>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="border-left: 4px solid var(--accent);">
        <div class="flex justify-between items-center mb-20">
            <h3 class="flex items-center gap-10">
                <span class="material-symbols-outlined" style="color: var(--accent);">potted_plant</span> Riwayat Pesanan Pemasangan
            </h3>
            <span class="text-muted" style="font-size: 13px;">Status instalasi WiFi baru Anda</span>
        </div>
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
