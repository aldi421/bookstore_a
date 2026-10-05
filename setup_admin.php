<?php
session_start();
require __DIR__ . '/config/koneksi.php';

$setupKey = (string) ($db_config['setup_key'] ?? '');
$message = '';
$messageType = 'warning';

$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='admin'");
$adminCount = $countResult ? (int) mysqli_fetch_assoc($countResult)['total'] : 0;

if ($adminCount > 0) {
    $message = 'Setup admin sudah terkunci karena akun admin sudah tersedia.';
    $messageType = 'success';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_abort();

    $key = trim((string) ($_POST['setup_key'] ?? ''));
    $nama = trim((string) ($_POST['nama'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if ($setupKey === '' || $setupKey === 'GANTI_DENGAN_KUNCI_ACAK_PANJANG') {
        $message = 'Atur setup_key di config/database.php terlebih dahulu.';
    } elseif (!hash_equals($setupKey, $key)) {
        $message = 'Setup key salah.';
    } elseif ($nama === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Nama atau email tidak valid.';
    } elseif (strlen($password) < 8) {
        $message = 'Password admin minimal 8 karakter.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, alamat, role) VALUES (?, ?, ?, '', 'admin')");
        mysqli_stmt_bind_param($stmt, 'sss', $nama, $email, $hash);

        if (mysqli_stmt_execute($stmt)) {
            $adminCount = 1;
            $message = 'Admin berhasil dibuat. Setup sekarang terkunci. Silakan login.';
            $messageType = 'success';
        } else {
            $message = mysqli_errno($conn) === 1062
                ? 'Email tersebut sudah terdaftar.'
                : 'Admin gagal dibuat.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
<div class="login-container">
    <div class="login-card" style="margin:auto;max-width:520px;">
        <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
        <div class="login-header">
            <h1>LENTERA</h1>
            <h2>Setup Admin Pertama</h2>
            <p>Halaman ini otomatis terkunci setelah akun admin pertama dibuat.</p>
        </div>

        <?php if ($message !== '') { ?>
            <div class="card" style="margin-bottom:16px;"><?= h($message); ?></div>
        <?php } ?>

        <?php if ($adminCount === 0) { ?>
            <form method="POST" autocomplete="off">
                <?= csrf_input(); ?>
                <div class="input-group">
                    <label>Setup Key</label>
                    <input type="password" name="setup_key" required>
                </div>
                <div class="input-group">
                    <label>Nama Admin</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" minlength="8" required>
                </div>
                <button class="btn-login" type="submit">BUAT ADMIN</button>
            </form>
        <?php } else { ?>
            <a href="login.php" class="btn-login" style="display:block;text-align:center;">LOGIN</a>
        <?php } ?>
    </div>
</div>
</body>
</html>
