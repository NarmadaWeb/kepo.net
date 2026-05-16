<?php
require_once 'config/config.php';
require_once 'includes/header.php';

$password = $_POST['password'] ?? '';
$hash = '';

if ($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
}
?>

<div class="container" style="max-width: 600px; padding: 100px 0;">
    <div class="card">
        <h2>Generate Password Hash</h2>
        <p class="text-muted mb-20">Gunakan tool ini untuk membuat hash password yang bisa dimasukkan ke database.</p>

        <form action="" method="POST">
            <div class="form-group">
                <label>Password Polos</label>
                <input type="text" name="password" required value="<?= e($password) ?>" placeholder="Masukkan password...">
            </div>
            <button type="submit" class="btn btn-primary">Generate Hash</button>
        </form>

        <?php if ($hash): ?>
            <div class="mt-20">
                <label>Hash (Copy ini ke database):</label>
                <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; word-break: break-all; font-family: monospace; border: 1px solid #ddd;">
                    <?= $hash ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-20" style="padding: 15px; background: #eff6ff; border-radius: 5px; color: #1e40af; font-size: 14px;">
            <strong>Catatan:</strong> Default admin email adalah <code>admin@kepo.net</code>. Update kolom <code>password</code> di tabel <code>users</code> dengan hasil hash di atas.
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
