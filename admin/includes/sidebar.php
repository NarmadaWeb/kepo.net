<aside class="sidebar">
    <div style="padding: 0 16px 40px;">
        <span class="logo">kepo.net</span>
        <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; margin-top: 4px;">Admin Portal</div>
    </div>

    <nav>
        <ul class="flex" style="flex-direction: column; gap: 8px;">
            <li>
                <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/orders.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">shopping_cart</span> Pesanan
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/bills.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'bills.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'bills.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">payments</span> Tagihan Bulanan
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/technicians.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'technicians.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'technicians.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">engineering</span> Teknisi
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/packages.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'packages.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'packages.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">inventory_2</span> Paket Layanan
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/coverage.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'coverage.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'coverage.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">map</span> Coverage
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/reports.php" class="btn btn-outline <?= (basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '') ?>" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'background: #eff6ff; color: var(--primary);' : '') ?>">
                    <span class="material-symbols-outlined">insights</span> Laporan
                </a>
            </li>
            <li style="margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border);">
                <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; color: var(--danger);">
                    <span class="material-symbols-outlined">logout</span> Keluar Sistem
                </a>
            </li>
        </ul>
    </nav>
</aside>
