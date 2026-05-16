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

// Handle Assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $technician_id = $_POST['technician_id'];
    $installation_date = $_POST['installation_date'];

    $stmt = $pdo->prepare("UPDATE orders SET technician_id = ?, status = 'processing', installation_date = ? WHERE id = ?");
    if ($stmt->execute([$technician_id, $installation_date, $order_id])) {
        header('Location: orders.php?assigned=1');
        exit;
    }
}

// Fetch Order Info
$stmt = $pdo->prepare("SELECT o.*, u.name as user_name, p.name as package_name FROM orders o JOIN users u ON o.user_id = u.id JOIN packages p ON o.package_id = p.id WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

// Fetch Online Technicians
$technicians = $pdo->query("SELECT * FROM technicians WHERE status = 'online'")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h2 style="font-size: 18px;">Penugasan Teknisi</h2>
    </div>

    <div style="padding: 30px 0; max-width: 600px;">
        <div class="card">
            <h3 style="margin-bottom: 20px;">Detail Pesanan #<?= $order['order_number'] ?></h3>
            <div style="background: var(--surface-color); padding: 15px; border-radius: 8px; margin-bottom: 30px; font-size: 14px;">
                <p><strong>Pelanggan:</strong> <?= $order['user_name'] ?></p>
                <p><strong>Paket:</strong> <?= $order['package_name'] ?></p>
                <p><strong>Alamat:</strong> <?= $order['address'] ?></p>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Pilih Teknisi (Online)</label>
                    <select name="technician_id" required>
                        <option value="">-- Pilih Teknisi --</option>
                        <?php foreach ($technicians as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['name'] ?> (<?= $t['employee_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal & Waktu Instalasi</label>
                    <input type="datetime-local" name="installation_date" required>
                </div>
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <a href="orders.php" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Konfirmasi Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
