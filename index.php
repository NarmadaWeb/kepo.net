<?php
require_once 'config/config.php';
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM packages WHERE status = 'active' LIMIT 3");
$packages = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-flex">
            <div class="hero-content">
                <span class="badge badge-info mb-20">Jaringan Aktif 99.9%</span>
                <h1>Internet Cepat & Stabil untuk Desa Keru</h1>
                <p>
                    Nikmati koneksi internet tanpa batas dengan teknologi fiber optik terbaru. Dirancang untuk kebutuhan belajar, bekerja, dan hiburan keluarga.
                </p>
                <div class="flex gap-10">
                    <a href="packages.php" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="#coverage" class="btn btn-outline">Cek Area Coverage</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80" alt="WiFi Concept">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section>
    <div class="container">
        <div class="section-title">
            <h2>Mengapa Memilih Kami?</h2>
            <p>Keunggulan layanan internet kepo.net</p>
        </div>
        <div class="grid-3">
            <div class="card card-hover text-center">
                <div class="feature-icon" style="margin: 0 auto 24px;">
                    <span class="material-symbols-outlined" style="font-size: 32px;">speed</span>
                </div>
                <h3>Kecepatan Tinggi</h3>
                <p class="text-muted">Akses internet tanpa lemot dengan kecepatan simetris upload dan download.</p>
            </div>
            <div class="card card-hover text-center">
                <div class="feature-icon" style="margin: 0 auto 24px; color: var(--success);">
                    <span class="material-symbols-outlined" style="font-size: 32px;">verified_user</span>
                </div>
                <h3>Reliabel</h3>
                <p class="text-muted">Jaminan uptime 99.9% dengan dukungan tim teknis yang siap siaga.</p>
            </div>
            <div class="card card-hover text-center">
                <div class="feature-icon" style="margin: 0 auto 24px; color: var(--accent);">
                    <span class="material-symbols-outlined" style="font-size: 32px;">support_agent</span>
                </div>
                <h3>Layanan Lokal</h3>
                <p class="text-muted">Kami hadir lebih dekat untuk memberikan pelayanan terbaik bagi warga desa.</p>
            </div>
        </div>
    </div>
</section>

<!-- Packages Teaser -->
<section style="background-color: white;">
    <div class="container">
        <div class="section-title">
            <h2>Pilih Paket Sesuai Kebutuhan</h2>
            <p>Tersedia berbagai pilihan kecepatan untuk Anda</p>
        </div>
        <div class="grid-3">
            <?php foreach ($packages as $p): ?>
                <div class="card card-hover text-center flex" style="flex-direction: column; align-items: center;">
                    <h3 style="color: var(--primary);"><?= $p['name'] ?></h3>
                    <div style="font-size: 40px; font-weight: 800; margin: 16px 0; letter-spacing: -0.025em;">
                        <?= $p['speed'] ?>
                    </div>
                    <div class="mb-20">
                        <span style="font-size: 24px; font-weight: 700;">Rp <?= number_format($p['monthly_price'], 0, ',', '.') ?></span>
                        <span class="text-muted">/bulan</span>
                    </div>
                    <ul class="text-muted mb-20 w-full" style="text-align: left; font-size: 14px;">
                        <li class="mb-20" style="display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 18px; color: var(--success);">check_circle</span>
                            Unlimited Kuota
                        </li>
                        <li class="mb-20" style="display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 18px; color: var(--success);">check_circle</span>
                            Gratis Router WiFi
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 18px; color: var(--success);">check_circle</span>
                            Biaya Pasang: Rp <?= number_format($p['installation_fee'], 0, ',', '.') ?>
                        </li>
                    </ul>
                    <a href="packages.php" class="btn btn-primary w-full mt-20">Pilih Paket</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
