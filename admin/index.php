<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Stats
$total_customers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_orders_today = $pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$pending_payments = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'waiting_payment'")->fetchColumn();
$active_installations = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'processing'")->fetchColumn();
$order_revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status IN ('paid', 'processing', 'completed')")->fetchColumn() ?: 0;
$bill_revenue = $pdo->query("SELECT SUM(amount) FROM monthly_bills WHERE status = 'paid'")->fetchColumn() ?: 0;
$total_revenue = $order_revenue + $bill_revenue;
$unpaid_bills = $pdo->query("SELECT COUNT(*) FROM monthly_bills WHERE status = 'unpaid'")->fetchColumn();
$online_technicians = $pdo->query("SELECT COUNT(*) FROM technicians WHERE status = 'online'")->fetchColumn();

// Recent Orders
$stmt = $pdo->query("SELECT o.*, u.name as user_name, p.name as package_name
                     FROM orders o
                     JOIN users u ON o.user_id = u.id
                     JOIN packages p ON o.package_id = p.id
                     ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <div>
            <h1 style="font-size: 24px; letter-spacing: -0.025em;">Ringkasan Sistem</h1>
            <p class="text-muted" style="font-size: 14px;"><?= date('l, d F Y') ?></p>
        </div>
        <div class="flex gap-10">
            <button class="btn btn-outline"><span class="material-symbols-outlined">refresh</span></button>
            <button class="btn btn-primary">Export Laporan</button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="flex justify-between items-center mb-20">
                <span class="label">Total Pelanggan</span>
                <span class="material-symbols-outlined" style="color: var(--primary);">groups</span>
            </div>
            <span class="value"><?= number_format($total_customers) ?></span>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center mb-20">
                <span class="label">Pesanan Hari Ini</span>
                <span class="material-symbols-outlined" style="color: var(--accent);">shopping_cart</span>
            </div>
            <span class="value"><?= $total_orders_today ?></span>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center mb-20">
                <span class="label">Total Pendapatan</span>
                <span class="material-symbols-outlined" style="color: var(--success);">payments</span>
            </div>
            <span class="value">Rp <?= number_format($total_revenue ?: 0, 0, ',', '.') ?></span>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center mb-20">
                <span class="label">Proses Pasang</span>
                <span class="material-symbols-outlined" style="color: var(--info);">engineering</span>
            </div>
            <span class="value"><?= $active_installations ?></span>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center mb-20">
                <span class="label">Tagihan Belum Bayar</span>
                <span class="material-symbols-outlined" style="color: var(--danger);">pending_actions</span>
            </div>
            <span class="value"><?= $unpaid_bills ?></span>
        </div>
    </div>

    <div class="grid-2">
        <div class="card" style="grid-column: span 2;">
            <div class="flex justify-between items-center mb-20">
                <h3>Pesanan Terbaru</h3>
                <a href="orders.php" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">Lihat Semua</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Paket Internet</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: var(--primary);">#<?= $order['order_number'] ?></td>
                                <td><strong><?= $order['user_name'] ?></strong></td>
                                <td><?= $order['package_name'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <?php
                                    $status_badges = [
                                        'pending' => 'badge-pending',
                                        'waiting_payment' => 'badge-pending',
                                        'paid' => 'badge-info',
                                        'processing' => 'badge-info',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-error'
                                    ];
                                    ?>
                                    <span class="badge <?= $status_badges[$order['status']] ?>"><?= $order['status'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
