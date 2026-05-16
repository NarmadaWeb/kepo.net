<aside class="sidebar">
    <div style="padding: 0 20px 30px;">
        <span class="logo">kepo.net</span>
        <p style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase; margin-top: 5px;">Admin Panel</p>
    </div>

    <nav>
        <ul style="display: flex; flex-direction: column; gap: 5px; padding: 0 10px;">
            <li>
                <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'background: #e3f2fd; color: var(--primary-color);' : '') ?>">
                    <span class="material-symbols-outlined">dashboard</span> Overview
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/orders.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'background: #e3f2fd; color: var(--primary-color);' : '') ?>">
                    <span class="material-symbols-outlined">shopping_cart</span> Pesanan
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/technicians.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'technicians.php' ? 'background: #e3f2fd; color: var(--primary-color);' : '') ?>">
                    <span class="material-symbols-outlined">engineering</span> Teknisi
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/packages.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'packages.php' ? 'background: #e3f2fd; color: var(--primary-color);' : '') ?>">
                    <span class="material-symbols-outlined">inventory_2</span> Paket
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/coverage.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; <?= (basename($_SERVER['PHP_SELF']) == 'coverage.php' ? 'background: #e3f2fd; color: var(--primary-color);' : '') ?>">
                    <span class="material-symbols-outlined">map</span> Coverage
                </a>
            </li>
            <li style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline" style="width: 100%; justify-content: flex-start; border: none; color: var(--error-color);">
                    <span class="material-symbols-outlined">logout</span> Keluar
                </a>
            </li>
        </ul>
    </nav>
</aside>
