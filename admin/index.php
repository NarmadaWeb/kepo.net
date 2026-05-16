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
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'paid' OR status = 'processing' OR status = 'completed'")->fetchColumn();
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
        <h2 style="font-size: 18px;">Dashboard Statistik</h2>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="font-size: 14px; color: var(--text-secondary);"><?= date('l, d F Y') ?></span>
            <button class="btn btn-primary" style="padding: 8px 16px;">Export Laporan</button>
        </div>
    </div>

    <div style="padding: 30px 0;">
        <div class="stats-grid">
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <span class="label">Total Pelanggan</span>
                    <span class="material-symbols-outlined" style="color: var(--primary-color);">groups</span>
                </div>
                <span class="value"><?= number_format($total_customers) ?></span>
            </div>
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <span class="label">Pesanan Hari Ini</span>
                    <span class="material-symbols-outlined" style="color: var(--accent-color);">shopping_cart</span>
                </div>
                <span class="value"><?= $total_orders_today ?></span>
            </div>
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <span class="label">Pendapatan</span>
                    <span class="material-symbols-outlined" style="color: var(--secondary-color);">payments</span>
                </div>
                <span class="value">Rp <?= number_format($total_revenue ?: 0, 0, ',', '.') ?></span>
            </div>
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <span class="label">Menunggu Bayar</span>
                    <span class="material-symbols-outlined" style="color: var(--error-color);">pending_actions</span>
                </div>
                <span class="value"><?= $pending_payments ?></span>
            </div>
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <span class="label">Proses Pasang</span>
                    <span class="material-symbols-outlined" style="color: var(--primary-color);">engineering</span>
                </div>
                <span class="value"><?= $active_installations ?></span>
            </div>
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <span class="label">Teknisi Online</span>
                    <span class="material-symbols-outlined" style="color: var(--secondary-color);">online_prediction</span>
                </div>
                <span class="value"><?= $online_technicians ?></span>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Pesanan Terbaru</h3>
                    <a href="orders.php" style="font-size: 13px; color: var(--primary-color);">Lihat Semua</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td style="font-family: 'JetBrains Mono'; font-size: 12px;">#<?= $order['order_number'] ?></td>
                                    <td><?= $order['user_name'] ?></td>
                                    <td><?= $order['package_name'] ?></td>
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

            <div class="card">
                <h3>Kapasitas Jaringan</h3>
                <div style="margin-top: 20px;">
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                            <span>Utilisasi OLT Utama</span>
                            <span style="font-weight: 700;">78%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #eee; border-radius: 10px; overflow: hidden;">
                            <div style="width: 78%; height: 100%; background: var(--secondary-color);"></div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="padding: 15px; background: var(--surface-color); border-radius: 10px; text-align: center;">
                            <div style="font-size: 12px; color: var(--text-secondary); text-transform: uppercase;">Port Tersedia</div>
                            <div style="font-size: 20px; font-weight: 700; margin-top: 5px;">48</div>
                        </div>
                        <div style="padding: 15px; background: var(--surface-color); border-radius: 10px; text-align: center;">
                            <div style="font-size: 12px; color: var(--text-secondary); text-transform: uppercase;">ODP Aktif</div>
                            <div style="font-size: 20px; font-weight: 700; margin-top: 5px;">24 / 30</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
