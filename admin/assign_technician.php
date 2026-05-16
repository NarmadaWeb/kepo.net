<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    header('Location: orders.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $technician_id = $_POST['technician_id'];
    $installation_date = $_POST['installation_date'];

    $stmt = $pdo->prepare("UPDATE orders SET technician_id = ?, status = 'processing', installation_date = ? WHERE id = ?");
    if ($stmt->execute([$technician_id, $installation_date, $order_id])) {
        header('Location: orders.php?assigned=1');
        exit;
    }
}

$stmt = $pdo->prepare("SELECT o.*, u.name as user_name, p.name as package_name FROM orders o JOIN users u ON o.user_id = u.id JOIN packages p ON o.package_id = p.id WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

$technicians = $pdo->query("SELECT * FROM technicians WHERE status = 'online'")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h1 style="font-size: 24px; letter-spacing: -0.025em;">Penugasan Teknisi</h1>
    </div>

    <div class="container" style="max-width: 600px; padding: 0;">
        <div class="card">
            <h3 class="mb-20">Detail Pesanan #<?= $order['order_number'] ?></h3>
            <div style="background: var(--bg-main); padding: 24px; border-radius: var(--radius-md); margin-bottom: 32px; font-size: 14px; border-left: 4px solid var(--primary);">
                <div class="mb-20"><strong>Pelanggan:</strong> <?= $order['user_name'] ?></div>
                <div class="mb-20"><strong>Paket Internet:</strong> <?= $order['package_name'] ?></div>
                <div><strong>Alamat Pemasangan:</strong> <?= $order['address'] ?></div>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Pilih Teknisi (Status: Online)</label>
                    <select name="technician_id" required>
                        <option value="">-- Pilih Teknisi --</option>
                        <?php foreach ($technicians as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['name'] ?> (<?= $t['employee_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jadwal Instalasi</label>
                    <input type="datetime-local" name="installation_date" required>
                </div>
                <div class="flex gap-10 mt-20" style="margin-top: 40px;">
                    <a href="orders.php" class="btn btn-outline" style="flex: 1;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="flex: 2;">Konfirmasi Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
