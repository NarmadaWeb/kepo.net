<?php
require_once 'config/config.php';
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $phone, $hashed_password])) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                $error = 'Terjadi kesalahan, silakan coba lagi.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 500px; padding: 80px 0;">
    <div class="card">
        <h2 class="text-center">Daftar Akun</h2>
        <p class="text-center text-muted mb-20">Mulai berlangganan internet cepat hari ini</p>

        <?php if ($error): ?>
            <div class="badge badge-error mb-20 w-full text-center" style="display: block; padding: 12px; border-radius: var(--radius-md);">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Masukkan nama lengkap">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="tel" name="phone" required placeholder="0812xxxxxxxx">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-full">Daftar</button>
        </form>

        <p class="text-center mt-20" style="font-size: 14px; color: var(--text-muted);">
            Sudah punya akun? <a href="login.php" style="color: var(--primary); font-weight: 600;">Masuk</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
