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

<div class="container" style="max-width: 450px; margin-top: 80px;">
    <div class="card">
        <h2 style="text-align: center;">Daftar Akun</h2>
        <p style="text-align: center; color: var(--text-secondary); margin-bottom: 20px;">Mulai berlangganan internet cepat hari ini</p>

        <?php if ($error): ?>
            <div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
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
            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 14px; color: var(--text-secondary);">
            Sudah punya akun? <a href="login.php" style="color: var(--primary-color);">Masuk</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
