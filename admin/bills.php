<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';
$error = '';

// Generate bills for the current month
if (isset($_POST['generate_bills'])) {
    $month = (int)date('m');
    $year = (int)date('Y');

    // Find all completed orders that don't have a bill for this month
    $stmt = $pdo->prepare("SELECT o.id, o.order_number, p.monthly_price
                           FROM orders o
                           JOIN packages p ON o.package_id = p.id
                           WHERE o.status = 'completed'
                           AND o.id NOT IN (
                               SELECT order_id FROM monthly_bills WHERE bill_month = ? AND bill_year = ?
                           )");
    $stmt->execute([$month, $year]);
    $pending_bills = $stmt->fetchAll();

    if (empty($pending_bills)) {
        $message = "Semua tagihan bulan ini sudah digenerate.";
    } else {
        $count = 0;
        foreach ($pending_bills as $pb) {
            $bill_number = 'BILL-' . $pb['id'] . '-' . $month . $year . '-' . rand(100, 999);
            $stmt = $pdo->prepare("INSERT INTO monthly_bills (bill_number, order_id, amount, bill_month, bill_year) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$bill_number, $pb['id'], $pb['monthly_price'], $month, $year])) {
                $count++;
            }
        }
        $message = "$count tagihan berhasil digenerate.";
    }
}

// Fetch all bills
$stmt = $pdo->query("SELECT mb.*, o.order_number, u.name as user_name, u.phone as user_phone, p.name as package_name
                     FROM monthly_bills mb
                     JOIN orders o ON mb.order_id = o.id
                     JOIN users u ON o.user_id = u.id
                     JOIN packages p ON o.package_id = p.id
                     ORDER BY mb.created_at DESC");
$bills = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h1 style="font-size: 24px; letter-spacing: -0.025em;">Tagihan Bulanan</h1>
        <form action="" method="POST">
            <button type="submit" name="generate_bills" class="btn btn-primary">
                <span class="material-symbols-outlined">sync</span> Generate Tagihan <?= date('F Y') ?>
            </button>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="badge badge-success mb-20 w-full" style="display: block; padding: 12px;"><?= $message ?></div>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-container" style="border: none; border-radius: 0;">
            <table>
                <thead>
                    <tr>
                        <th>No. Tagihan</th>
                        <th>Pelanggan</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bills)): ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 40px;">Belum ada data tagihan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bills as $bill): ?>
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= $bill['bill_number'] ?></td>
                                <td>
                                    <div style="font-weight: 600;"><?= e($bill['user_name']) ?></div>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?= e($bill['user_phone']) ?></div>
                                </td>
                                <td><?= date('F Y', mktime(0, 0, 0, $bill['bill_month'], 10, $bill['bill_year'])) ?></td>
                                <td><strong>Rp <?= number_format($bill['amount'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <?php if ($bill['status'] == 'paid'): ?>
                                        <span class="badge badge-success">Lunas</span>
                                    <?php else: ?>
                                        <span class="badge badge-error">Belum Bayar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $bill['paid_at'] ? date('d/m/Y H:i', strtotime($bill['paid_at'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
