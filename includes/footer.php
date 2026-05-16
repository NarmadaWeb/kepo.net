    <footer style="background: white; border-top: 1px solid var(--border); padding: 60px 0; margin-top: 80px;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 40px;">
            <div style="max-width: 300px;">
                <a href="<?= e(BASE_URL) ?>" class="logo">kepo.net</a>
                <p style="color: var(--text-muted); font-size: 14px; margin-top: 16px;">
                    Penyedia layanan internet fiber optik tercepat dan terstabil untuk mendukung aktivitas digital Anda.
                </p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 24px;">
                    &copy; <?= date('Y') ?> kepo.net. All rights reserved.
                </p>
            </div>
            <div style="display: flex; gap: 60px; flex-wrap: wrap;">
                <div>
                    <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 20px;">Layanan</h4>
                    <ul style="display: flex; flex-direction: column; gap: 12px; font-size: 14px; color: var(--text-muted);">
                        <li><a href="<?= e(BASE_URL) ?>packages.php" class="nav-link">Paket Internet</a></li>
                        <li><a href="#" class="nav-link">Internet Bisnis</a></li>
                        <li><a href="#" class="nav-link">Cek Coverage</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 20px;">Bantuan</h4>
                    <ul style="display: flex; flex-direction: column; gap: 12px; font-size: 14px; color: var(--text-muted);">
                        <li><a href="#" class="nav-link">Pusat Bantuan</a></li>
                        <li><a href="<?= e(BASE_URL) ?>user/track.php" class="nav-link">Lacak Pesanan</a></li>
                        <li><a href="#" class="nav-link">Kontak Kami</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
