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

<div class="container" style="padding: 50px 0; max-width: 800px;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1>Lacak Pemasangan WiFi</h1>
        <p style="color: var(--text-secondary);">Masukkan nomor pesanan Anda untuk melihat status instalasi secara real-time.</p>

        <form action="" method="GET" style="margin-top: 30px; display: flex; gap: 10px; max-width: 500px; margin-left: auto; margin-right: auto;">
            <input type="text" name="order_number" value="<?= $order_number ?>" placeholder="#ORD-..." style="flex-grow: 1;">
            <button type="submit" class="btn btn-primary">Lacak</button>
        </form>
    </div>

    <?php if ($order_number && !$order): ?>
        <div class="card" style="text-align: center; padding: 50px;">
            <span class="material-symbols-outlined" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;">search_off</span>
            <h3>Pesanan Tidak Ditemukan</h3>
            <p style="color: var(--text-secondary);">Mohon periksa kembali nomor pesanan Anda.</p>
        </div>
    <?php elseif ($order): ?>
        <div class="grid-2">
            <div style="grid-column: span 2;">
                <div class="card" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 12px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Status Pesanan #<?= $order['order_number'] ?></div>
                        <h2 style="margin-top: 5px; color: var(--primary-color);">
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
                    <span class="badge <?= $badge_class[$order['status']] ?>" style="font-size: 14px; padding: 8px 16px;"><?= $order['status'] ?></span>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size: 18px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                    <span class="material-symbols-outlined">timeline</span> Timeline Progress
                </h3>

                <div style="position: relative; padding-left: 30px;">
                    <!-- Vertical Line -->
                    <div style="position: absolute; left: 9px; top: 5px; bottom: 5px; width: 2px; background-color: var(--border-color);"></div>

                    <!-- Steps -->
                    <div style="margin-bottom: 30px; position: relative;">
                        <div style="position: absolute; left: -30px; width: 20px; height: 20px; border-radius: 50%; background-color: var(--secondary-color); border: 4px solid white; box-shadow: 0 0 0 1px var(--secondary-color); z-index: 2;"></div>
                        <h4 style="font-size: 14px; margin-bottom: 2px;">Pesanan Dibuat</h4>
                        <p style="font-size: 12px; color: var(--text-secondary);"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                    </div>

                    <?php if (in_array($order['status'], ['paid', 'processing', 'completed'])): ?>
                        <div style="margin-bottom: 30px; position: relative;">
                            <div style="position: absolute; left: -30px; width: 20px; height: 20px; border-radius: 50%; background-color: var(--secondary-color); border: 4px solid white; box-shadow: 0 0 0 1px var(--secondary-color); z-index: 2;"></div>
                            <h4 style="font-size: 14px; margin-bottom: 2px;">Pembayaran Terverifikasi</h4>
                            <p style="font-size: 12px; color: var(--text-secondary);">Area tercover, siap dijadwalkan.</p>
                        </div>
                    <?php endif; ?>

                    <?php if ($order['status'] == 'processing' || $order['status'] == 'completed'): ?>
                        <div style="margin-bottom: 30px; position: relative;">
                            <div style="position: absolute; left: -30px; width: 20px; height: 20px; border-radius: 50%; background-color: var(--primary-color); border: 4px solid white; box-shadow: 0 0 0 1px var(--primary-color); z-index: 2;"></div>
                            <h4 style="font-size: 14px; margin-bottom: 2px;">Teknisi Ditugaskan</h4>
                            <p style="font-size: 12px; color: var(--text-secondary);">Teknisi sedang dalam perjalanan.</p>
                        </div>
                    <?php endif; ?>

                    <div style="position: relative;">
                        <div style="position: absolute; left: -30px; width: 20px; height: 20px; border-radius: 50%; background-color: white; border: 2px solid var(--border-color); z-index: 2;"></div>
                        <h4 style="font-size: 14px; margin-bottom: 2px; color: var(--text-secondary);">Pemasangan Selesai</h4>
                        <p style="font-size: 12px; color: var(--text-secondary);">Menunggu penyelesaian di lokasi.</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <?php if ($order['technician_name']): ?>
                    <h3 style="font-size: 18px; margin-bottom: 20px;">Detail Teknisi</h3>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <img src="<?= $order['technician_image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($order['technician_name']) ?>" alt="Technician" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-color);">
                        <div>
                            <div style="font-weight: 700;"><?= $order['technician_name'] ?></div>
                            <div style="font-size: 12px; color: var(--text-secondary);">ID: <?= $order['technician_id'] ?></div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="tel:<?= $order['technician_phone'] ?>" class="btn btn-outline" style="width: 100%;"><span class="material-symbols-outlined" style="font-size: 18px;">call</span> Hubungi</a>
                        <a href="https://wa.me/<?= $order['technician_phone'] ?>" class="btn btn-outline" style="width: 100%;"><span class="material-symbols-outlined" style="font-size: 18px;">chat</span> WhatsApp</a>
                    </div>
                <?php else: ?>
                    <h3 style="font-size: 18px; margin-bottom: 20px;">Detail Paket</h3>
                    <div style="background: var(--surface-color); padding: 15px; border-radius: 8px;">
                        <div style="font-weight: 700; color: var(--primary-color);"><?= $order['package_name'] ?></div>
                        <div style="font-size: 14px; color: var(--text-secondary);"><?= $order['speed'] ?> Fiber Internet</div>
                    </div>
                    <p style="font-size: 12px; color: var(--text-secondary); margin-top: 15px;">
                        Teknisi akan ditugaskan segera setelah jadwal pemasangan tersedia di area Anda.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
