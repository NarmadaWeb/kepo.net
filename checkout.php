<?php
require_once 'config/config.php';
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php&package_id=' . ($_GET['package_id'] ?? ''));
    exit;
}

$package_id = $_GET['package_id'] ?? null;
if (!$package_id) {
    header('Location: packages.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch();

if (!$package) {
    header('Location: packages.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $order_number = 'ORD-' . time() . rand(100, 999);
    $total_amount = $package['monthly_price'] + $package['installation_fee'];

    // Handle KTP Upload
    $ktp_image = '';
    if (isset($_FILES['ktp_image']) && $_FILES['ktp_image']['error'] == 0) {
        $target_dir = "assets/img/ktp/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $file_info = pathinfo($_FILES["ktp_image"]["name"]);
        $file_ext = strtolower($file_info['extension']);

        if (in_array($file_ext, $allowed_ext)) {
            $ktp_image = $order_number . '.' . $file_ext;
            move_uploaded_file($_FILES["ktp_image"]["tmp_name"], $target_dir . $ktp_image);
        } else {
            $error = "Format file KTP tidak didukung. Gunakan JPG atau PNG.";
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO orders (order_number, user_id, package_id, address, city, district, total_amount, ktp_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$order_number, $user_id, $package_id, $address, $city, $district, $total_amount, $ktp_image])) {
            $order_id = $pdo->lastInsertId();
            header("Location: pay.php?order_id=$order_id");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}

include 'includes/header.php';
?>

<div class="container" style="padding: 60px 0;">
    <div class="flex gap-10" style="align-items: flex-start; flex-wrap: wrap;">
        <!-- Left side: Form -->
        <div style="flex: 2; min-width: 350px;">
            <div class="card">
                <h2 class="mb-20">Lengkapi Data Pemasangan</h2>
                <p class="text-muted mb-20">Silakan isi formulir di bawah ini untuk proses registrasi dan instalasi.</p>

                <?php if ($error): ?>
                    <div class="badge badge-error mb-20 w-full" style="display: block; padding: 12px;"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-20">
                        <h3 class="mb-20" style="font-size: 18px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">Data Personal</h3>
                        <div class="form-group">
                            <label>Nama Lengkap (Sesuai KTP)</label>
                            <input type="text" value="<?= $_SESSION['user_name'] ?>" disabled style="background-color: var(--bg-main);">
                        </div>
                    </div>

                    <div class="mb-20">
                        <h3 class="mb-20" style="font-size: 18px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">Alamat Instalasi</h3>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea name="address" required placeholder="Nama jalan, nomor rumah, RT/RW..." style="height: 120px;"></textarea>
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Kota / Kabupaten</label>
                                <input type="text" name="city" required placeholder="Contoh: Jakarta Selatan">
                            </div>
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input type="text" name="district" required placeholder="Contoh: Kebayoran Baru">
                            </div>
                        </div>
                    </div>

                    <div class="mb-20">
                        <h3 class="mb-20" style="font-size: 18px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">Dokumen Pendukung</h3>
                        <div class="form-group">
                            <label>Foto KTP</label>
                            <input type="file" name="ktp_image" accept="image/*" required style="padding: 8px;">
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                    </div>

                    <div class="flex gap-10 mt-20" style="margin-top: 40px;">
                        <a href="packages.php" class="btn btn-outline" style="flex: 1;">Kembali</a>
                        <button type="submit" class="btn btn-primary" style="flex: 2;">Lanjut ke Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right side: Summary -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card" style="position: sticky; top: 100px;">
                <h3 class="mb-20 flex items-center gap-10" style="font-size: 18px;">
                    <span class="material-symbols-outlined" style="color: var(--primary);">receipt_long</span> Ringkasan Pesanan
                </h3>

                <div class="mb-20" style="background-color: var(--bg-main); padding: 20px; border-radius: var(--radius-md);">
                    <div style="font-weight: 700; color: var(--primary); font-size: 18px;"><?= $package['name'] ?></div>
                    <div class="text-muted" style="font-size: 14px;"><?= $package['speed'] ?> Fiber Internet</div>
                </div>

                <div class="flex mb-20" style="flex-direction: column; gap: 12px; font-size: 14px;">
                    <div class="flex justify-between">
                        <span class="text-muted">Biaya Berlangganan (Bulan 1)</span>
                        <span style="font-weight: 600;">Rp <?= number_format($package['monthly_price'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Biaya Instalasi</span>
                        <span style="font-weight: 600;">Rp <?= number_format($package['installation_fee'], 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-20 py-20" style="border-top: 1px solid var(--border); margin-top: 20px;">
                    <span style="font-weight: 600;">Total Pembayaran</span>
                    <span style="font-size: 24px; font-weight: 800; color: var(--primary); letter-spacing: -0.025em;">Rp <?= number_format($package['monthly_price'] + $package['installation_fee'], 0, ',', '.') ?></span>
                </div>

                <div style="background-color: #fffbeb; border: 1px solid #fef3c7; padding: 16px; border-radius: var(--radius-md); display: flex; gap: 12px;">
                    <span class="material-symbols-outlined" style="color: var(--accent); font-size: 20px;">info</span>
                    <p style="font-size: 12px; color: #92400e; line-height: 1.5;">
                        Tagihan selanjutnya adalah Rp <?= number_format($package['monthly_price'], 0, ',', '.') ?> per bulan. Biaya instalasi hanya dibayarkan satu kali di awal.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
