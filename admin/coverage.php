<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

if (isset($_POST['add_area'])) {
    $name = $_POST['name'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO coverage_areas (name, status) VALUES (?, ?)");
    if ($stmt->execute([$name, $status])) {
        $message = 'Area berhasil ditambahkan.';
    }
}

if (isset($_POST['add_odp'])) {
    $area_id = $_POST['area_id'];
    $name = $_POST['name'];
    $capacity = $_POST['capacity'];

    $stmt = $pdo->prepare("INSERT INTO odp_points (area_id, name, capacity) VALUES (?, ?, ?)");
    if ($stmt->execute([$area_id, $name, $capacity])) {
        $message = 'Titik ODP berhasil ditambahkan.';
    }
}

$areas = $pdo->query("SELECT * FROM coverage_areas ORDER BY name ASC")->fetchAll();
$odps = $pdo->query("SELECT o.*, a.name as area_name FROM odp_points o JOIN coverage_areas a ON o.area_id = a.id ORDER BY a.name ASC")->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="admin-topbar">
        <h1 style="font-size: 24px; letter-spacing: -0.025em;">Manajemen Coverage</h1>
    </div>

    <?php if ($message): ?>
        <div class="badge badge-success mb-20 w-full" style="display: block; padding: 12px;"><?= $message ?></div>
    <?php endif; ?>

    <div class="grid-2">
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="card">
                <h3>Tambah Area Coverage</h3>
                <form action="" method="POST" class="mt-20">
                    <div class="form-group">
                        <label>Nama Area / Dusun</label>
                        <input type="text" name="name" required placeholder="Dusun Krajan">
                    </div>
                    <div class="form-group">
                        <label>Status Sinyal</label>
                        <select name="status">
                            <option value="strong">Kuat (Strong)</option>
                            <option value="medium">Sedang (Medium)</option>
                            <option value="low">Lemah (Low)</option>
                        </select>
                    </div>
                    <button type="submit" name="add_area" class="btn btn-primary w-full">Tambah Area</button>
                </form>
            </div>

            <div class="card">
                <h3>Tambah Titik ODP</h3>
                <form action="" method="POST" class="mt-20">
                    <div class="form-group">
                        <label>Pilih Area</label>
                        <select name="area_id" required>
                            <option value="">-- Pilih Area --</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= $area['id'] ?>"><?= $area['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama ODP</label>
                        <input type="text" name="name" required placeholder="ODP-KRJ-01">
                    </div>
                    <div class="form-group">
                        <label>Kapasitas Port</label>
                        <input type="number" name="capacity" value="16" required>
                    </div>
                    <button type="submit" name="add_odp" class="btn btn-primary w-full">Tambah ODP</button>
                </form>
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-container" style="border: none; border-radius: 0;">
                <div style="padding: 24px; border-bottom: 1px solid var(--border);">
                    <h3>Daftar Titik ODP</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ODP</th>
                            <th>Area</th>
                            <th>Penggunaan Port</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($odps as $o): ?>
                            <tr>
                                <td><strong><?= $o['name'] ?></strong></td>
                                <td><?= $o['area_name'] ?></td>
                                <td>
                                    <div class="flex justify-between" style="font-size: 11px; margin-bottom: 6px;">
                                        <span class="text-muted"><?= $o['used'] ?> / <?= $o['capacity'] ?> Ports</span>
                                        <span style="font-weight: 700;"><?= round(($o['used']/$o['capacity'])*100) ?>%</span>
                                    </div>
                                    <div style="width: 100%; height: 6px; background: var(--bg-main); border-radius: 10px; overflow: hidden;">
                                        <div style="width: <?= ($o['used']/$o['capacity'])*100 ?>%; height: 100%; background: var(--primary);"></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?= $o['status'] == 'active' ? 'badge-success' : 'badge-error' ?>"><?= $o['status'] ?></span>
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
