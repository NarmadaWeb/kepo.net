<?php
require_once 'config/config.php';
require_once 'config/database.php';

include 'includes/header.php';
?>

<div class="container" style="padding: 80px 0;">
    <div class="section-title">
        <h1 style="font-size: 48px; letter-spacing: -0.05em;">Internet Bisnis Kepo.net</h1>
        <p style="max-width: 600px; margin: 0 auto;">Solusi internet dedicated dengan SLA 99.9% untuk mendukung pertumbuhan bisnis Anda. Koneksi stabil, aman, dan didukung tim ahli.</p>
    </div>

    <div class="grid-2 mb-20" style="margin-bottom: 60px; align-items: center;">
        <div>
            <h2 style="font-size: 32px; margin-bottom: 20px;">Kenapa Memilih Bisnis Kepo?</h2>
            <div class="flex" style="flex-direction: column; gap: 20px;">
                <div class="flex gap-10">
                    <span class="material-symbols-outlined" style="color: var(--primary); font-size: 28px;">verified_user</span>
                    <div>
                        <strong>SLA 99.9% Guarantee</strong>
                        <p class="text-muted" style="font-size: 14px;">Jaminan ketersediaan layanan untuk memastikan bisnis Anda tetap online tanpa gangguan.</p>
                    </div>
                </div>
                <div class="flex gap-10">
                    <span class="material-symbols-outlined" style="color: var(--primary); font-size: 28px;">speed</span>
                    <div>
                        <strong>Dedicated Bandwidth</strong>
                        <p class="text-muted" style="font-size: 14px;">Bandwidth simetris 1:1 tanpa FUP, memberikan kecepatan upload dan download yang sama cepatnya.</p>
                    </div>
                </div>
                <div class="flex gap-10">
                    <span class="material-symbols-outlined" style="color: var(--primary); font-size: 28px;">support_agent</span>
                    <div>
                        <strong>24/7 Priority Support</strong>
                        <p class="text-muted" style="font-size: 14px;">Tim support teknis khusus yang siap membantu menangani kendala Anda kapan saja.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="background: #eff6ff; border: none; display: flex; align-items: center; justify-content: center; padding: 40px;">
             <span class="material-symbols-outlined" style="font-size: 160px; color: var(--primary); opacity: 0.2;">apartment</span>
        </div>
    </div>

    <div class="grid-3">
        <div class="card card-hover flex" style="flex-direction: column;">
            <h3 class="mb-20" style="font-size: 18px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Business SOHO</h3>
            <div style="color: var(--primary); font-size: 40px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.025em;">50 Mbps</div>
            <div class="mb-20 pb-20" style="border-bottom: 1px solid var(--border);">
                <div style="font-size: 28px; font-weight: 800;">Rp 500.000<span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">/bulan</span></div>
            </div>
            <div style="flex-grow: 1;">
                <p class="mb-20" style="font-size: 15px; color: var(--text-muted);">Ideal untuk usaha kecil dan kantor cabang.</p>
                <ul class="mb-20 flex" style="flex-direction: column; gap: 12px; font-size: 14px;">
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Dedicated Bandwidth 1:1</li>
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> SLA 99.9%</li>
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Support Prioritas 24/7</li>
                </ul>
            </div>
            <a href="https://wa.me/628123456789" class="btn btn-primary w-full mt-20">Hubungi Sales</a>
        </div>

        <div class="card card-hover flex" style="flex-direction: column; border-color: var(--primary); position: relative;">
            <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 800;">POPULER</div>
            <h3 class="mb-20" style="font-size: 18px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Business Pro</h3>
            <div style="color: var(--primary); font-size: 40px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.025em;">100 Mbps</div>
            <div class="mb-20 pb-20" style="border-bottom: 1px solid var(--border);">
                <div style="font-size: 28px; font-weight: 800;">Rp 900.000<span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">/bulan</span></div>
            </div>
            <div style="flex-grow: 1;">
                <p class="mb-20" style="font-size: 15px; color: var(--text-muted);">Performa tinggi untuk perusahaan menengah.</p>
                <ul class="mb-20 flex" style="flex-direction: column; gap: 12px; font-size: 14px;">
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Dedicated Bandwidth 1:1</li>
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Static IP Public</li>
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> SLA 99.9%</li>
                </ul>
            </div>
            <a href="https://wa.me/628123456789" class="btn btn-primary w-full mt-20">Hubungi Sales</a>
        </div>

        <div class="card card-hover flex" style="flex-direction: column;">
            <h3 class="mb-20" style="font-size: 18px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Enterprise</h3>
            <div style="color: var(--primary); font-size: 40px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.025em;">Custom</div>
            <div class="mb-20 pb-20" style="border-bottom: 1px solid var(--border);">
                <div style="font-size: 28px; font-weight: 800;">Custom Quote</div>
            </div>
            <div style="flex-grow: 1;">
                <p class="mb-20" style="font-size: 15px; color: var(--text-muted);">Solusi infrastruktur skala besar dan custom.</p>
                <ul class="mb-20 flex" style="flex-direction: column; gap: 12px; font-size: 14px;">
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Managed Services</li>
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Multiple Uplink</li>
                    <li class="flex items-center gap-10"><span class="material-symbols-outlined" style="font-size: 20px; color: var(--success);">check_circle</span> Account Manager Khusus</li>
                </ul>
            </div>
            <a href="https://wa.me/628123456789" class="btn btn-outline w-full mt-20">Konsultasi Gratis</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
