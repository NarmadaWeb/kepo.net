<?php
require_once 'config/config.php';
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM packages WHERE status = 'active' ORDER BY monthly_price ASC");
$packages = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container" style="padding: 80px 0;">
    <div class="section-title">
        <h1 style="font-size: 48px; letter-spacing: -0.05em;">Paket Internet Kepo Fiber</h1>
        <p style="max-width: 600px; margin: 0 auto;">Pilih paket internet yang paling sesuai dengan kebutuhan digital Anda dan keluarga.</p>
    </div>

    <div class="grid-3">
        <?php foreach ($packages as $p): ?>
            <div class="card card-hover flex" style="flex-direction: column; position: relative;">
                <?php if ($p['name'] == 'Pro'): ?>
                    <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 800; box-shadow: var(--shadow-md);">REKOMENDASI</div>
                <?php endif; ?>

                <h3 class="mb-20" style="font-size: 18px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;"><?= $p['name'] ?></h3>
                <div style="color: var(--primary); font-size: 40px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.025em;"><?= $p['speed'] ?></div>

                <div class="mb-20 pb-20" style="border-bottom: 1px solid var(--border);">
                    <div style="font-size: 28px; font-weight: 800;">Rp <?= number_format($p['monthly_price'], 0, ',', '.') ?><span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">/bulan</span></div>
                    <div style="font-size: 13px; color: var(--text-muted); margin-top: 8px;">Biaya Pasang: Rp <?= number_format($p['installation_fee'], 0, ',', '.') ?></div>
                </div>

                <div style="flex-grow: 1;">
                    <p class="mb-20" style="font-size: 15px; color: var(--text-muted);"><?= $p['description'] ?></p>
                    <ul class="mb-20 flex" style="flex-direction: column; gap: 12px; font-size: 14px;">
                        <li class="flex items-center gap-10">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span>
                            Unlimited Quota (Tanpa FUP)
                        </li>
                        <li class="flex items-center gap-10">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span>
                            Router WiFi Dual-Band
                        </li>
                        <li class="flex items-center gap-10">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span>
                            Support 24/7
                        </li>
                    </ul>
                </div>

                <a href="checkout.php?package_id=<?= $p['id'] ?>" class="btn btn-primary w-full mt-20">Daftar Sekarang</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card mt-20" style="background-color: #f1f5f9; border: 2px dashed var(--primary); padding: 40px;">
        <div class="flex items-center justify-between" style="flex-wrap: wrap; gap: 20px;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="font-size: 24px;">Butuh Paket Khusus untuk Bisnis?</h3>
                <p class="text-muted">Kami menyediakan layanan internet dedicated dengan SLA 99.9% untuk kebutuhan kantor, sekolah, atau instansi Anda.</p>
            </div>
            <a href="https://wa.me/628123456789" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary); padding: 16px 32px;">Hubungi Sales</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
