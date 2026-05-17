<?php
require_once 'config/config.php';
require_once 'config/database.php';

$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // In a real app, you'd save this to DB or send an email
    $message_sent = true;
}

include 'includes/header.php';
?>

<div class="container" style="padding: 80px 0;">
    <div class="section-title">
        <h1 style="font-size: 48px; letter-spacing: -0.05em;">Hubungi Kami</h1>
        <p style="max-width: 600px; margin: 0 auto;">Punya pertanyaan atau ingin berlangganan? Tim kami siap melayani Anda.</p>
    </div>

    <div class="grid-2">
        <div class="card">
            <h3 class="mb-20">Kirim Pesan</h3>
            <?php if ($message_sent): ?>
                <div class="badge badge-success mb-20 w-full" style="display: block; padding: 16px;">
                    Terima kasih! Pesan Anda telah terkirim. Tim kami akan segera menghubungi Anda.
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama lengkap Anda">
                </div>
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" required placeholder="nama@email.com">
                </div>
                <div class="form-group">
                    <label>Subjek</label>
                    <select name="subject">
                        <option>Informasi Pemasangan Baru</option>
                        <option>Gangguan Teknis</option>
                        <option>Pembayaran & Tagihan</option>
                        <option>Kemitraan</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pesan Anda</label>
                    <textarea name="message" required placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." style="height: 150px;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Kirim Pesan</button>
            </form>
        </div>

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="card">
                <h3 class="mb-20">Informasi Kontak</h3>
                <div class="flex" style="flex-direction: column; gap: 20px;">
                    <div class="flex gap-10">
                        <span class="material-symbols-outlined" style="color: var(--primary);">location_on</span>
                        <div>
                            <div style="font-weight: 700;">Kantor Pusat</div>
                            <div class="text-muted" style="font-size: 14px;">Jl. Fiber Optik No. 123, Jakarta Selatan, 12345</div>
                        </div>
                    </div>
                    <div class="flex gap-10">
                        <span class="material-symbols-outlined" style="color: var(--primary);">call</span>
                        <div>
                            <div style="font-weight: 700;">Telepon</div>
                            <div class="text-muted" style="font-size: 14px;">(021) 1234-5678</div>
                        </div>
                    </div>
                    <div class="flex gap-10">
                        <span class="material-symbols-outlined" style="color: var(--primary);">mail</span>
                        <div>
                            <div style="font-weight: 700;">Email</div>
                            <div class="text-muted" style="font-size: 14px;">info@kepo.net</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="background: #f1f5f9;">
                <h3 class="mb-20">Jam Operasional</h3>
                <div class="flex" style="flex-direction: column; gap: 12px; font-size: 14px;">
                    <div class="flex justify-between">
                        <span>Senin - Jumat</span>
                        <strong>08:00 - 17:00</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Sabtu</span>
                        <strong>09:00 - 15:00</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Minggu & Libur Nasional</span>
                        <strong style="color: var(--danger);">Tutup</strong>
                    </div>
                    <div class="mt-20 p-10" style="background: white; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <p style="font-size: 12px;"><strong>Catatan:</strong> Untuk dukungan teknis (gangguan), layanan tersedia 24/7 melalui WhatsApp.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
