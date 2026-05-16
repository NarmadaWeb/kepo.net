<?php
require_once 'config/config.php';
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: user/dashboard.php');
        }
        exit;
    } else {
        $error = 'Email atau password salah.';
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 450px; padding: 100px 0;">
    <div class="card">
        <h2 class="text-center">Masuk</h2>
        <p class="text-center text-muted mb-20">Selamat datang kembali di kepo.net</p>

        <?php if ($error): ?>
            <div class="badge badge-error mb-20 w-full text-center" style="display: block; padding: 12px; border-radius: var(--radius-md);">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registered'])): ?>
            <div class="badge badge-success mb-20 w-full text-center" style="display: block; padding: 12px; border-radius: var(--radius-md);">
                Pendaftaran berhasil! Silakan masuk.
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-full">Masuk</button>
        </form>

        <p class="text-center mt-20" style="font-size: 14px; color: var(--text-muted);">
            Belum punya akun? <a href="register.php" style="color: var(--primary); font-weight: 600;">Daftar sekarang</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
