<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

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
        <h1 style="font-size: 24px; letter-spacing: -0.025em;">Manajemen Teknisi</h1>
    </div>

    <?php if ($message): ?>
        <div class="badge badge-success mb-20 w-full" style="display: block; padding: 12px;"><?= $message ?></div>
    <?php endif; ?>

    <div class="grid-3" style="grid-template-columns: 1fr 2fr;">
        <div class="card">
            <h3 class="mb-20">Tambah Teknisi</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Budi Santoso">
                </div>
                <div class="form-group">
                    <label>ID Pegawai</label>
                    <input type="text" name="employee_id" required placeholder="TEK-001">
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="tel" name="phone" required placeholder="0812xxxxxxxx">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="online">Online (Siap Tugas)</option>
                        <option value="offline">Offline (Istirahat)</option>
                    </select>
                </div>
                <button type="submit" name="add_technician" class="btn btn-primary w-full">Simpan Data</button>
            </form>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-container" style="border: none; border-radius: 0;">
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
                                    <div class="flex items-center gap-10">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['name']) ?>&background=random" style="width: 32px; height: 32px; border-radius: 50%;">
                                        <span style="font-weight: 600;"><?= $t['name'] ?></span>
                                    </div>
                                </td>
                                <td style="font-family: monospace;"><?= $t['employee_id'] ?></td>
                                <td><?= $t['phone'] ?></td>
                                <td>
                                    <span class="badge <?= $t['status'] == 'online' ? 'badge-success' : 'badge-outline' ?>">
                                        <?= $t['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?delete=<?= $t['id'] ?>" class="btn btn-outline" style="padding: 6px; color: var(--danger); border: none;"><span class="material-symbols-outlined">delete</span></a>
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
