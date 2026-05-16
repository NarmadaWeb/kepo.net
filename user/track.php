<?php
require_once '../config/config.php';
require_once '../config/database.php';

$order_number = $_GET['order_number'] ?? '';
$order = null;

if ($order_number) {
    $stmt = $pdo->prepare("SELECT o.*, p.name as package_name, p.speed, t.name as technician_name, t.phone as technician_phone, t.image as technician_image, t.employee_id as technician_id
                           FROM orders o
                           JOIN packages p ON o.package_id = p.id
                           LEFT JOIN technicians t ON o.technician_id = t.id
                           WHERE o.order_number = ?");
    $stmt->execute([$order_number]);
    $order = $stmt->fetch();
}

include '../includes/header.php';
?>

<div class="container" style="padding: 60px 0; max-width: 900px;">
    <div class="text-center mb-20">
        <h1 style="font-size: 36px; letter-spacing: -0.05em;">Lacak Pemasangan WiFi</h1>
        <p class="text-muted">Masukkan nomor pesanan Anda untuk melihat status instalasi secara real-time.</p>

        <form action="" method="GET" class="flex gap-10 mt-20" style="max-width: 500px; margin: 32px auto;">
            <input type="text" name="order_number" value="<?= $order_number ?>" placeholder="#ORD-..." style="flex: 1;">
            <button type="submit" class="btn btn-primary">Lacak Pesanan</button>
        </form>
    </div>

    <?php if ($order_number && !$order): ?>
        <div class="card text-center" style="padding: 80px 0;">
            <span class="material-symbols-outlined" style="font-size: 64px; color: var(--text-muted); margin-bottom: 24px;">search_off</span>
            <h3>Pesanan Tidak Ditemukan</h3>
            <p class="text-muted">Mohon periksa kembali nomor pesanan Anda.</p>
        </div>
    <?php elseif ($order): ?>
        <div class="grid-2">
            <div style="grid-column: 1 / -1;">
                <div class="card flex justify-between items-center" style="padding: 24px 32px;">
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">Status Pesanan #<?= $order['order_number'] ?></div>
                        <h2 style="margin-top: 8px; color: var(--primary); font-size: 24px;">
                            <?php
                            $status_text = [
                                'pending' => 'Sedang Diverifikasi',
                                'waiting_payment' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'processing' => 'Teknisi Sedang Menuju Lokasi',
                                'completed' => 'Pemasangan Selesai',
                                'cancelled' => 'Pesanan Dibatalkan'
                            ];
                            echo $status_text[$order['status']] ?? $order['status'];
                            ?>
                        </h2>
                    </div>
                    <?php
                    $badge_class = [
                        'pending' => 'badge-pending',
                        'waiting_payment' => 'badge-pending',
                        'paid' => 'badge-info',
                        'processing' => 'badge-info',
                        'completed' => 'badge-success',
                        'cancelled' => 'badge-error'
                    ];
                    ?>
                    <span class="badge <?= $badge_class[$order['status']] ?>" style="font-size: 14px; padding: 10px 20px;"><?= $order['status'] ?></span>
                </div>
            </div>

            <div class="card">
                <h3 class="mb-20 flex items-center gap-10" style="font-size: 18px;">
                    <span class="material-symbols-outlined" style="color: var(--primary);">timeline</span> Timeline Progress
                </h3>

                <div style="position: relative; padding-left: 32px; margin-top: 24px;">
                    <div style="position: absolute; left: 7px; top: 0; bottom: 0; width: 2px; background-color: var(--border);"></div>

                    <div class="mb-20" style="position: relative;">
                        <div style="position: absolute; left: -32px; width: 16px; height: 16px; border-radius: 50%; background-color: var(--success); border: 3px solid white; box-shadow: 0 0 0 1px var(--success); z-index: 2;"></div>
                        <h4 style="font-size: 14px; font-weight: 700;">Pesanan Dibuat</h4>
                        <p style="font-size: 12px; color: var(--text-muted);"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                    </div>

                    <?php if (in_array($order['status'], ['paid', 'processing', 'completed'])): ?>
                        <div class="mb-20" style="position: relative;">
                            <div style="position: absolute; left: -32px; width: 16px; height: 16px; border-radius: 50%; background-color: var(--success); border: 3px solid white; box-shadow: 0 0 0 1px var(--success); z-index: 2;"></div>
                            <h4 style="font-size: 14px; font-weight: 700;">Pembayaran Terverifikasi</h4>
                            <p style="font-size: 12px; color: var(--text-muted);">Pesanan Anda sedang dalam antrean penjadwalan teknisi.</p>
                        </div>
                    <?php endif; ?>

                    <?php if ($order['status'] == 'processing' || $order['status'] == 'completed'): ?>
                        <div class="mb-20" style="position: relative;">
                            <div style="position: absolute; left: -32px; width: 16px; height: 16px; border-radius: 50%; background-color: var(--primary); border: 3px solid white; box-shadow: 0 0 0 1px var(--primary); z-index: 2;"></div>
                            <h4 style="font-size: 14px; font-weight: 700;">Teknisi Ditugaskan</h4>
                            <p style="font-size: 12px; color: var(--text-muted);">Teknisi sedang dalam perjalanan ke lokasi Anda.</p>
                        </div>
                    <?php endif; ?>

                    <div style="position: relative;">
                        <div style="position: absolute; left: -32px; width: 16px; height: 16px; border-radius: 50%; background-color: white; border: 2px solid var(--border); z-index: 2;"></div>
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text-muted);">Pemasangan Selesai</h4>
                        <p style="font-size: 12px; color: var(--text-muted);">Pemasangan dan aktivasi layanan di lokasi.</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <?php if ($order['technician_name']): ?>
                    <h3 class="mb-20" style="font-size: 18px;">Informasi Teknisi</h3>
                    <div class="flex items-center gap-10 mb-20" style="padding: 16px; background: var(--bg-main); border-radius: var(--radius-md);">
                        <img src="<?= $order['technician_image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($order['technician_name']) ?>" alt="Technician" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                        <div>
                            <div style="font-weight: 700; color: var(--text-main);"><?= $order['technician_name'] ?></div>
                            <div style="font-size: 12px; color: var(--text-muted);">ID Pegawai: <?= $order['technician_id'] ?></div>
                        </div>
                    </div>
                    <div class="flex" style="flex-direction: column; gap: 12px;">
                        <a href="tel:<?= $order['technician_phone'] ?>" class="btn btn-outline w-full"><span class="material-symbols-outlined" style="font-size: 20px;">call</span> Hubungi Teknisi</a>
                        <a href="https://wa.me/<?= $order['technician_phone'] ?>" class="btn btn-outline w-full" style="border-color: #25d366; color: #128c7e;"><span class="material-symbols-outlined" style="font-size: 20px;">chat</span> Chat WhatsApp</a>
                    </div>
                <?php else: ?>
                    <h3 class="mb-20" style="font-size: 18px;">Detail Paket Layanan</h3>
                    <div style="background: var(--bg-main); padding: 24px; border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
                        <div style="font-weight: 800; color: var(--primary); font-size: 18px;"><?= $order['package_name'] ?></div>
                        <div class="text-muted" style="font-size: 14px; font-weight: 500;"><?= $order['speed'] ?> Fiber Optic Internet</div>
                    </div>
                    <p class="mt-20" style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                        Kami akan menugaskan teknisi terbaik kami segera setelah jadwal tersedia di area Anda. Anda akan menerima notifikasi melalui WhatsApp.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
