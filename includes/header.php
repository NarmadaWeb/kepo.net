<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kepo.net - Internet Cepat & Stabil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container nav-container">
            <a href="<?= e(BASE_URL) ?>" class="logo">kepo.net</a>
            <nav class="nav-links">
                <a href="<?= e(BASE_URL) ?>" class="nav-link">Beranda</a>
                <a href="<?= e(BASE_URL) ?>packages.php" class="nav-link">Paket</a>
                <a href="<?= e(BASE_URL) ?>user/track.php" class="nav-link">Cek Status</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= e(BASE_URL) ?>user/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="<?= e(BASE_URL) ?>logout.php" class="btn btn-outline">Keluar</a>
                <?php else: ?>
                    <a href="<?= e(BASE_URL) ?>login.php" class="nav-link">Masuk</a>
                    <a href="<?= e(BASE_URL) ?>register.php" class="btn btn-primary">Daftar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
