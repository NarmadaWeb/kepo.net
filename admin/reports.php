<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$monthly_revenue = $pdo->query("SELECT DATE_FORMAT(created_at, '%M %Y') as month, SUM(total_amount) as total
                                FROM orders
                                WHERE status IN ('paid', 'processing', 'completed')
                                GROUP BY month
                                ORDER BY created_at ASC")->fetchAll();

$package_stats = $pdo->query("SELECT p.name, COUNT(o.id) as total
                              FROM packages p
                              LEFT JOIN orders o ON p.id = o.package_id
                              GROUP BY p.id")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h1 style="font-size: 24px; letter-spacing: -0.025em;">Laporan & Analitik</h1>
    </div>

    <div class="grid-2">
        <div class="card">
            <h3 class="mb-20">Pendapatan Bulanan</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthly_revenue as $rev): ?>
                            <tr>
                                <td><strong><?= $rev['month'] ?></strong></td>
                                <td style="color: var(--success); font-weight: 700;">Rp <?= number_format($rev['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3 class="mb-20">Popularitas Paket</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Jumlah Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($package_stats as $stat): ?>
                            <tr>
                                <td><strong><?= $stat['name'] ?></strong></td>
                                <td><span class="badge badge-info"><?= $stat['total'] ?> Pesanan</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
