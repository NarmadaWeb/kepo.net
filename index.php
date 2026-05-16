<?php
require_once 'config/config.php';
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM packages WHERE status = 'active' LIMIT 3");
$packages = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section style="padding: 100px 0; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <span class="badge badge-info" style="margin-bottom: 15px;">Jaringan Aktif 99.9%</span>
            <h1 style="font-size: 48px; margin-bottom: 20px;">Internet Cepat & Stabil untuk Desa Keru</h1>
            <p style="font-size: 18px; color: var(--text-secondary); margin-bottom: 30px;">
                Nikmati koneksi internet tanpa batas dengan teknologi fiber optik terbaru. Dirancang untuk kebutuhan belajar, bekerja, dan hiburan keluarga.
            </p>
            <div style="display: flex; gap: 15px;">
                <a href="packages.php" class="btn btn-primary" style="padding: 15px 30px; font-size: 16px;">Daftar Sekarang</a>
                <a href="#coverage" class="btn btn-outline" style="padding: 15px 30px; font-size: 16px;">Cek Area Coverage</a>
            </div>
        </div>
        <div style="flex: 1; min-width: 300px; text-align: center;">
            <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80" alt="WiFi Concept" style="max-width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
        </div>
    </div>
</section>

<!-- Features Section -->
<section style="padding: 80px 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2>Mengapa Memilih Kami?</h2>
            <p style="color: var(--text-secondary);">Keunggulan layanan internet kepo.net</p>
        </div>
        <div class="grid-3">
            <div class="card" style="text-align: center;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--primary-color);">speed</span>
                <h3 style="margin-top: 15px;">Kecepatan Tinggi</h3>
                <p style="font-size: 14px; color: var(--text-secondary);">Akses internet tanpa lemot dengan kecepatan simetris upload dan download.</p>
            </div>
            <div class="card" style="text-align: center;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--secondary-color);">verified_user</span>
                <h3 style="margin-top: 15px;">Reliabel</h3>
                <p style="font-size: 14px; color: var(--text-secondary);">Jaminan uptime 99.9% dengan dukungan tim teknis yang siap siaga.</p>
            </div>
            <div class="card" style="text-align: center;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--accent-color);">support_agent</span>
                <h3 style="margin-top: 15px;">Layanan Lokal</h3>
                <p style="font-size: 14px; color: var(--text-secondary);">Kami hadir lebih dekat untuk memberikan pelayanan terbaik bagi warga desa.</p>
            </div>
        </div>
    </div>
</section>

<!-- Packages Teaser -->
<section style="padding: 80px 0; background-color: #f8f9fa;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2>Pilih Paket Sesuai Kebutuhan</h2>
            <p style="color: var(--text-secondary);">Tersedia berbagai pilihan kecepatan untuk Anda</p>
        </div>
        <div class="grid-3">
            <?php foreach ($packages as $p): ?>
                <div class="card" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <h3 style="color: var(--primary-color);"><?= $p['name'] ?></h3>
                    <div style="font-size: 32px; font-weight: 800; margin: 15px 0;">
                        <?= $p['speed'] ?>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: 700;">Rp <?= number_format($p['monthly_price'], 0, ',', '.') ?></span>
                        <span style="color: var(--text-secondary);">/bulan</span>
                    </div>
                    <ul style="margin-bottom: 30px; text-align: left; font-size: 14px; color: var(--text-secondary);">
                        <li style="margin-bottom: 8px;"><span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: var(--secondary-color);">check_circle</span> Unlimited Kuota</li>
                        <li style="margin-bottom: 8px;"><span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: var(--secondary-color);">check_circle</span> Gratis Router WiFi</li>
                        <li style="margin-bottom: 8px;"><span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: var(--secondary-color);">check_circle</span> Biaya Pasang: Rp <?= number_format($p['installation_fee'], 0, ',', '.') ?></li>
                    </ul>
                    <a href="packages.php" class="btn btn-primary" style="width: 100%;">Pilih Paket</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
