<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Monthly Revenue Report Data
$monthly_revenue = $pdo->query("SELECT DATE_FORMAT(created_at, '%M %Y') as month, SUM(total_amount) as total
                                FROM orders
                                WHERE status IN ('paid', 'processing', 'completed')
                                GROUP BY month
                                ORDER BY created_at ASC")->fetchAll();

// Package Popularity
$package_stats = $pdo->query("SELECT p.name, COUNT(o.id) as total
                              FROM packages p
                              LEFT JOIN orders o ON p.id = o.package_id
                              GROUP BY p.id")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h2 style="font-size: 18px;">Laporan & Analitik</h2>
    </div>

    <div style="padding: 30px 0;">
        <div class="grid-2">
            <div class="card">
                <h3>Pendapatan Bulanan</h3>
                <div class="table-container" style="margin-top: 20px;">
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
                                    <td><?= $rev['month'] ?></td>
                                    <td><strong>Rp <?= number_format($rev['total'], 0, ',', '.') ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3>Popularitas Paket</h3>
                <div class="table-container" style="margin-top: 20px;">
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
                                    <td><?= $stat['name'] ?></td>
                                    <td><?= $stat['total'] ?> Pesanan</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
