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

<div class="container" style="max-width: 400px; margin-top: 100px;">
    <div class="card">
        <h2 style="text-align: center;">Masuk</h2>
        <p style="text-align: center; color: var(--text-secondary); margin-bottom: 20px;">Selamat datang kembali di kepo.net</p>

        <?php if ($error): ?>
            <div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                <?= $error ?>
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
            <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 14px; color: var(--text-secondary);">
            Belum punya akun? <a href="register.php" style="color: var(--primary-color);">Daftar sekarang</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
