<?php
session_start();
include "config/koneksi.php";
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = mysqli_query(
        $conn,

        "SELECT * FROM users
WHERE email='$email'
AND password='$password'"
    );

    $user = mysqli_fetch_assoc($query);
    if ($user) {

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == "admin") {
            header("location:admin/index.php");
        } else {
            header("location:user/index.php");
        }
    } else {
        echo "
<script>
alert('Email atau password salah');
</script>
";
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Masuk — LENTERA</title><link rel="stylesheet" href="css/style.css"></head><body><div class="login-container"><section class="auth-feature"><div class="auth-brand"><span class="logo-mark">L</span><span>LENTERA<small>BOOKS &amp; STORIES</small></span></div><div class="auth-feature-content"><span class="section-kicker">WELCOME BACK / 01</span><h2>Selamat datang <em>kembali.</em></h2><p>Setiap halaman menyimpan cerita. Kembali ke ruang bacamu dan lanjutkan petualangan berikutnya.</p></div><div class="auth-feature-foot">✦ READ • DISCOVER • GROW / EST. 2026</div></section><main class="auth-form-column"><div class="login-card"><a class="auth-home-link" href="index.php">← Kembali ke Beranda</a><div class="login-header"><h1>AKSES MEMBER</h1><h2>Masuk ke LENTERA.</h2><p>Masukkan informasi akun untuk melanjutkan.</p></div><form method="POST"><div class="input-group"><label for="email">Alamat Email</label><input id="email" type="email" name="email" autocomplete="email" placeholder="nama@email.com" required></div><div class="input-group"><label for="password">Password</label><input id="password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required></div><button class="btn-login" type="submit" name="login">Masuk <span aria-hidden="true">↗</span></button><p class="register-link">Belum punya akun? <a href="register.php">Daftar Sekarang</a></p></form></div></main></div><script src="js/ui.js" defer></script></body></html>