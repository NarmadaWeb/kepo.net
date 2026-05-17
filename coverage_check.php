<?php
require_once 'config/config.php';
require_once 'config/database.php';

$areas = $pdo->query("SELECT * FROM coverage_areas ORDER BY name ASC")->fetchAll();

include 'includes/header.php';
?>

<div class="container" style="padding: 80px 0;">
    <div class="section-title">
        <h1 style="font-size: 48px; letter-spacing: -0.05em;">Cek Coverage Area</h1>
        <p style="max-width: 600px; margin: 0 auto;">Layanan Kepo.net terus berkembang. Periksa apakah lokasi Anda sudah terjangkau oleh jaringan fiber optik kami.</p>
    </div>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <h3 class="mb-20">Area Tercover Saat Ini</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Wilayah / Dusun</th>
                        <th>Kualitas Sinyal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($areas)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data area.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($areas as $area): ?>
                            <tr>
                                <td><strong><?= e($area['name']) ?></strong></td>
                                <td>
                                    <?php if ($area['status'] == 'strong'): ?>
                                        <span class="badge badge-success">Sangat Kuat</span>
                                    <?php elseif ($area['status'] == 'medium'): ?>
                                        <span class="badge badge-info">Kuat</span>
                                    <?php else: ?>
                                        <span class="badge badge-pending">Terbatas</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="flex items-center gap-10" style="color: var(--success); font-weight: 600;">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
                                        Tersedia
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-20 p-20" style="background: #f1f5f9; border-radius: var(--radius-md); text-align: center;">
            <p class="text-muted">Wilayah Anda belum terdaftar? Jangan khawatir, kami terus melakukan ekspansi jaringan.</p>
            <a href="contact.php" class="btn btn-outline mt-20">Request Pasang di Wilayah Saya</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
