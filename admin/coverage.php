<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

// Add Area
if (isset($_POST['add_area'])) {
    $name = $_POST['name'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO coverage_areas (name, status) VALUES (?, ?)");
    if ($stmt->execute([$name, $status])) {
        $message = 'Area berhasil ditambahkan.';
    }
}

// Add ODP
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
        <h2 style="font-size: 18px;">Manajemen Coverage</h2>
    </div>

    <div style="padding: 30px 0;">
        <?php if ($message): ?>
            <div class="badge badge-success" style="display: block; padding: 10px; margin-bottom: 20px;"><?= $message ?></div>
        <?php endif; ?>

        <div class="grid-2">
            <!-- Left: Add Forms -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="card">
                    <h3>Tambah Area Coverage</h3>
                    <form action="" method="POST" style="margin-top: 15px;">
                        <div class="form-group">
                            <label>Nama Area / Dusun</label>
                            <input type="text" name="name" required placeholder="Contoh: Dusun Krajan">
                        </div>
                        <div class="form-group">
                            <label>Status Sinyal</label>
                            <select name="status">
                                <option value="strong">Kuat (Strong)</option>
                                <option value="medium">Sedang (Medium)</option>
                                <option value="low">Lemah (Low)</option>
                            </select>
                        </div>
                        <button type="submit" name="add_area" class="btn btn-primary" style="width: 100%;">Tambah Area</button>
                    </form>
                </div>

                <div class="card">
                    <h3>Tambah Titik ODP</h3>
                    <form action="" method="POST" style="margin-top: 15px;">
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
                            <input type="text" name="name" required placeholder="Contoh: ODP-KRJ-01">
                        </div>
                        <div class="form-group">
                            <label>Kapasitas Port</label>
                            <input type="number" name="capacity" value="16" required>
                        </div>
                        <button type="submit" name="add_odp" class="btn btn-primary" style="width: 100%;">Tambah ODP</button>
                    </form>
                </div>
            </div>

            <!-- Right: Display List -->
            <div class="card">
                <h3>Status Titik ODP</h3>
                <div class="table-container" style="margin-top: 20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>ODP</th>
                                <th>Area</th>
                                <th>Kapasitas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($odps as $o): ?>
                                <tr>
                                    <td><strong><?= $o['name'] ?></strong></td>
                                    <td><?= $o['area_name'] ?></td>
                                    <td>
                                        <div style="font-size: 12px; margin-bottom: 4px;"><?= $o['used'] ?> / <?= $o['capacity'] ?> Ports</div>
                                        <div style="width: 100%; height: 4px; background: #eee; border-radius: 4px; overflow: hidden;">
                                            <div style="width: <?= ($o['used']/$o['capacity'])*100 ?>%; height: 100%; background: var(--primary-color);"></div>
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
    </div>
</main>

<?php include 'includes/footer.php'; ?>
