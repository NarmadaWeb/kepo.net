<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

// Add Technician
if (isset($_POST['add_technician'])) {
    $name = $_POST['name'];
    $employee_id = $_POST['employee_id'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO technicians (name, employee_id, phone, status) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $employee_id, $phone, $status])) {
        $message = 'Teknisi berhasil ditambahkan.';
    }
}

// Delete Technician
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM technicians WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'Teknisi berhasil dihapus.';
    }
}

$technicians = $pdo->query("SELECT * FROM technicians ORDER BY name ASC")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h2 style="font-size: 18px;">Manajemen Teknisi</h2>
    </div>

    <div style="padding: 30px 0;">
        <?php if ($message): ?>
            <div class="badge badge-success" style="display: block; padding: 10px; margin-bottom: 20px;"><?= $message ?></div>
        <?php endif; ?>

        <div class="grid-3">
            <div style="grid-column: span 1;">
                <div class="card">
                    <h3>Tambah Teknisi</h3>
                    <form action="" method="POST" style="margin-top: 20px;">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="form-group">
                            <label>ID Pegawai</label>
                            <input type="text" name="employee_id" required placeholder="Contoh: TEK-001">
                        </div>
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <input type="tel" name="phone" required placeholder="0812xxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <button type="submit" name="add_technician" class="btn btn-primary" style="width: 100%;">Simpan Data</button>
                    </form>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="card">
                    <h3>Daftar Teknisi</h3>
                    <div class="table-container" style="margin-top: 20px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Teknisi</th>
                                    <th>ID Pegawai</th>
                                    <th>No. HP</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($technicians as $t): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['name']) ?>" style="width: 32px; height: 32px; border-radius: 50%;">
                                                <strong><?= $t['name'] ?></strong>
                                            </div>
                                        </td>
                                        <td style="font-family: 'JetBrains Mono';"><?= $t['employee_id'] ?></td>
                                        <td><?= $t['phone'] ?></td>
                                        <td>
                                            <span class="badge <?= $t['status'] == 'online' ? 'badge-success' : 'badge-outline' ?>">
                                                <?= $t['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?delete=<?= $t['id'] ?>" class="btn btn-outline btn-delete" style="padding: 5px; color: var(--error-color); border: none;"><span class="material-symbols-outlined">delete</span></a>
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
