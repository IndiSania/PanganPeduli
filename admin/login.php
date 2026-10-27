<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
if (is_admin()) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admins WHERE email = :email LIMIT 1');
    $stmt->execute(['email'=>$email]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        set_flash('success', 'Berhasil masuk ke dashboard admin.');
        redirect('dashboard.php');
    }
    $error = 'Email atau kata sandi tidak sesuai.';
}
$pageTitle = 'Login Admin';
require __DIR__ . '/../includes/header.php';
?>
<section class="login-section"><div class="login-card"><a class="brand login-brand" href="../index.php"><span class="brand-mark">P</span><span>Pangan<span class="brand-light">Peduli</span></span></a><span class="eyebrow">AREA ADMINISTRATOR</span><h1>Selamat datang kembali.</h1><p>Masuk untuk mengelola donasi makanan.</p>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="login-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Email<input type="email" name="email" required autocomplete="username" placeholder="admin@panganpeduli.local"></label><label>Kata sandi<input type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"></label><button class="btn btn-primary btn-full" type="submit">Masuk ke Dashboard <span>↗</span></button></form><div class="demo-credentials"><strong>Akun demo lokal</strong><span>Email: admin@panganpeduli.local</span><span>Password: password</span></div><a class="back-link" href="../index.php">← Kembali ke beranda</a></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
