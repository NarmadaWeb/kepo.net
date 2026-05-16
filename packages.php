<?php
require_once 'config/config.php';
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM packages WHERE status = 'active' ORDER BY monthly_price ASC");
$packages = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container" style="padding-top: 50px; padding-bottom: 80px;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 36px;">Paket Internet Kepo Fiber</h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">Pilih paket internet yang paling sesuai dengan kebutuhan digital Anda dan keluarga.</p>
    </div>

    <div class="grid-3">
        <?php foreach ($packages as $p): ?>
            <div class="card" style="display: flex; flex-direction: column; position: relative;">
                <?php if ($p['name'] == 'Pro'): ?>
                    <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent-color); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">REKOMENDASI</div>
                <?php endif; ?>

                <h3 style="margin-bottom: 5px;"><?= $p['name'] ?></h3>
                <div style="color: var(--primary-color); font-size: 28px; font-weight: 800; margin-bottom: 15px;"><?= $p['speed'] ?></div>

                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color);">
                    <div style="font-size: 24px; font-weight: 700;">Rp <?= number_format($p['monthly_price'], 0, ',', '.') ?><span style="font-size: 14px; font-weight: 400; color: var(--text-secondary);">/bulan</span></div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">Biaya Pasang: Rp <?= number_format($p['installation_fee'], 0, ',', '.') ?></div>
                </div>

                <div style="flex-grow: 1;">
                    <p style="font-size: 14px; margin-bottom: 20px;"><?= $p['description'] ?></p>
                    <ul style="font-size: 14px; color: var(--text-secondary); display: flex; flex-direction: column; gap: 10px; margin-bottom: 30px;">
                        <li><span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--secondary-color);">check</span> Unlimited Quota (Tanpa FUP)</li>
                        <li><span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--secondary-color);">check</span> Router WiFi Dual-Band</li>
                        <li><span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle; color: var(--secondary-color);">check</span> Support 24/7</li>
                    </ul>
                </div>

                <a href="checkout.php?package_id=<?= $p['id'] ?>" class="btn btn-primary" style="width: 100%;">Daftar Sekarang</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card" style="margin-top: 50px; background-color: var(--surface-color); border: 1px dashed var(--primary-color);">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="flex-grow: 1;">
                <h3>Butuh Paket Khusus untuk Bisnis?</h3>
                <p style="color: var(--text-secondary); font-size: 14px;">Kami menyediakan layanan internet dedicated dengan SLA 99.9% untuk kebutuhan kantor, sekolah, atau instansi Anda.</p>
            </div>
            <a href="https://wa.me/628123456789" class="btn btn-outline" style="border-color: var(--primary-color); color: var(--primary-color);">Hubungi Sales</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
