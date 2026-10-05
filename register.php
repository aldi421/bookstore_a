<?php
session_start();
require __DIR__ . "/config/koneksi.php";

$error = '';

if (isset($_POST['register'])) {
    verify_csrf_or_abort();

    $nama = trim((string) ($_POST['nama'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $alamat = trim((string) ($_POST['alamat'] ?? ''));

    if ($nama === '' || $alamat === '') {
        $error = 'Nama dan alamat wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } else {
        $check = mysqli_prepare($conn, "SELECT id_user FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($check, 's', $email);
        mysqli_stmt_execute($check);
        $existing = mysqli_stmt_get_result($check);

        if (mysqli_fetch_assoc($existing)) {
            $error = 'Email sudah terdaftar. Silakan login.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, alamat, role) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $hash, $alamat, $role);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Registrasi berhasil'); window.location='login.php';</script>";
                exit;
            }

            $error = 'Registrasi gagal. Silakan coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>
        Daftar Akun LENTERA
    </title>

    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
<link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
            <div class="register-header">

                <h1>
                    LENTERA
                </h1>

                <h2>
                    Daftar Akun Baru
                </h2>

                <p>
                    Buat akun untuk mulai berbelanja buku favorit Anda
                </p>

            </div>

            <form method="POST">
                <?= csrf_input(); ?>
                <div class="input-group">
                    <label>
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        required>
                </div>

                <div class="input-group">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Masukkan email"
                        required>
                </div>

                <div class="input-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        minlength="8"
                        required>
                </div>

                <div class="input-group">

                    <label>
                        Alamat Lengkap
                    </label>

                    <textarea
                        name="alamat"
                        placeholder="Masukkan alamat lengkap"
                        required>
</textarea>
                </div>

                <button
                    class="btn-register"
                    name="register">
                    Daftar Sekarang
                </button>

                <?php if ($error !== '') { ?>
                    <div class="card" style="margin-top:14px;padding:10px 12px;">
                        <?= h($error); ?>
                    </div>
                <?php } ?>

                <div class="login-link">

                    Sudah punya akun?
                    <a href="login.php">
                        Login disini
                    </a>
                </div>
            </form>
        </div>
    </div>
<script src="js/ui.js"></script>
</body>

</html>