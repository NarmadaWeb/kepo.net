<?php
require_once 'config/config.php';
require_once 'config/database.php';

include 'includes/header.php';
?>

<div class="container" style="padding: 80px 0;">
    <div class="section-title">
        <h1 style="font-size: 48px; letter-spacing: -0.05em;">Pusat Bantuan</h1>
        <p style="max-width: 600px; margin: 0 auto;">Temukan jawaban atas pertanyaan Anda atau hubungi tim dukungan kami.</p>
    </div>

    <div class="grid-2">
        <div>
            <h3 class="mb-20">Pertanyaan Umum (FAQ)</h3>
            <div class="flex" style="flex-direction: column; gap: 16px;">
                <div class="card" style="padding: 24px;">
                    <h4 style="margin-bottom: 8px;">Berapa lama proses pemasangan?</h4>
                    <p class="text-muted" style="font-size: 14px;">Biasanya proses pemasangan dilakukan 1-3 hari kerja setelah pembayaran biaya instalasi dikonfirmasi.</p>
                </div>
                <div class="card" style="padding: 24px;">
                    <h4 style="margin-bottom: 8px;">Apakah ada batasan kuota (FUP)?</h4>
                    <p class="text-muted" style="font-size: 14px;">Tidak, semua paket Kepo.net bersifat Unlimited tanpa FUP. Anda bisa internetan sepuasnya tanpa takut kecepatan menurun.</p>
                </div>
                <div class="card" style="padding: 24px;">
                    <h4 style="margin-bottom: 8px;">Bagaimana cara membayar tagihan bulanan?</h4>
                    <p class="text-muted" style="font-size: 14px;">Anda dapat membayar melalui dashboard pelanggan menggunakan berbagai metode pembayaran yang tersedia melalui Midtrans.</p>
                </div>
                <div class="card" style="padding: 24px;">
                    <h4 style="margin-bottom: 8px;">Apa yang harus dilakukan jika internet mati?</h4>
                    <p class="text-muted" style="font-size: 14px;">Pastikan router Anda menyala. Jika masih bermasalah, silakan hubungi layanan pelanggan kami melalui WhatsApp.</p>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="background: var(--primary); color: white;">
                <h3 class="mb-20" style="color: white;">Butuh Bantuan Lebih Lanjut?</h3>
                <p class="mb-20" style="opacity: 0.9;">Tim teknis kami siap membantu Anda 24/7. Jangan ragu untuk menghubungi kami.</p>

                <div class="flex" style="flex-direction: column; gap: 16px; margin-top: 32px;">
                    <div class="flex items-center gap-10">
                        <span class="material-symbols-outlined">call</span>
                        <div>
                            <div style="font-size: 12px; opacity: 0.7;">Hotline</div>
                            <div style="font-weight: 700;">(021) 1234-5678</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-10">
                        <span class="material-symbols-outlined">chat</span>
                        <div>
                            <div style="font-size: 12px; opacity: 0.7;">WhatsApp Support</div>
                            <div style="font-weight: 700;">+62 812-3456-7890</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-10">
                        <span class="material-symbols-outlined">mail</span>
                        <div>
                            <div style="font-size: 12px; opacity: 0.7;">Email Support</div>
                            <div style="font-weight: 700;">support@kepo.net</div>
                        </div>
                    </div>
                </div>

                <a href="contact.php" class="btn btn-outline w-full mt-20" style="color: var(--primary); font-weight: 700;">Kirim Tiket Bantuan</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
