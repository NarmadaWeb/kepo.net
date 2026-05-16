<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

// Add Package
if (isset($_POST['add_package'])) {
    $name = $_POST['name'];
    $speed = $_POST['speed'];
    $description = $_POST['description'];
    $monthly_price = $_POST['monthly_price'];
    $installation_fee = $_POST['installation_fee'];

    $stmt = $pdo->prepare("INSERT INTO packages (name, speed, description, monthly_price, installation_fee) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $speed, $description, $monthly_price, $installation_fee])) {
        $message = 'Paket berhasil ditambahkan.';
    }
}

// Delete Package
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'Paket berhasil dihapus.';
    }
}

$packages = $pdo->query("SELECT * FROM packages ORDER BY created_at DESC")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h2 style="font-size: 18px;">Manajemen Paket</h2>
    </div>

    <div style="padding: 30px 0;">
        <?php if ($message): ?>
            <div class="badge badge-success" style="display: block; padding: 10px; margin-bottom: 20px;"><?= $message ?></div>
        <?php endif; ?>

        <div class="grid-3">
            <div style="grid-column: span 1;">
                <div class="card">
                    <h3>Tambah Paket Baru</h3>
                    <form action="" method="POST" style="margin-top: 20px;">
                        <div class="form-group">
                            <label>Nama Paket</label>
                            <input type="text" name="name" required placeholder="Contoh: Kepo Pro">
                        </div>
                        <div class="form-group">
                            <label>Kecepatan</label>
                            <input type="text" name="speed" required placeholder="Contoh: 50 Mbps">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" placeholder="Penjelasan singkat paket..." style="height: 80px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Harga Bulanan (Rp)</label>
                            <input type="number" name="monthly_price" required placeholder="150000">
                        </div>
                        <div class="form-group">
                            <label>Biaya Instalasi (Rp)</label>
                            <input type="number" name="installation_fee" required placeholder="200000">
                        </div>
                        <button type="submit" name="add_package" class="btn btn-primary" style="width: 100%;">Simpan Paket</button>
                    </form>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="card">
                    <h3>Daftar Paket</h3>
                    <div class="table-container" style="margin-top: 20px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Paket</th>
                                    <th>Kecepatan</th>
                                    <th>Harga / Bln</th>
                                    <th>Biaya Pasang</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($packages as $p): ?>
                                    <tr>
                                        <td><strong><?= $p['name'] ?></strong></td>
                                        <td><span style="color: var(--primary-color); font-weight: 600;"><?= $p['speed'] ?></span></td>
                                        <td>Rp <?= number_format($p['monthly_price'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($p['installation_fee'], 0, ',', '.') ?></td>
                                        <td><span class="badge <?= $p['status'] == 'active' ? 'badge-success' : 'badge-error' ?>"><?= $p['status'] ?></span></td>
                                        <td>
                                            <a href="?delete=<?= $p['id'] ?>" class="btn btn-outline btn-delete" style="padding: 5px; color: var(--error-color); border: none;"><span class="material-symbols-outlined">delete</span></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
