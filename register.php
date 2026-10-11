<?php
include "config/koneksi.php";
if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $query = mysqli_query(
        $conn,

        "INSERT INTO users 
    (nama,email,password,alamat,role)
    ('$nama',
    '$email',
    '$password',
    '$alamat',
    'user')"

    );
    if ($query) {
        echo "
        <script>
        alert('Registrasi berhasil');
        window.location='login.php';
        </script>
        ";
    }
}


?>

<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Daftar — LENTERA</title><link rel="stylesheet" href="css/style.css"></head><body><div class="register-container"><section class="auth-feature"><div class="auth-brand"><span class="logo-mark">L</span><span>LENTERA<small>BOOKS &amp; STORIES</small></span></div><div class="auth-feature-content"><span class="section-kicker">YOUR NEXT CHAPTER / 02</span><h2>Mulai kisah <em>barumu.</em></h2><p>Buat ruang untuk cerita yang menginspirasi. Bergabung bersama LENTERA dan temukan buku yang tepat.</p></div><div class="auth-feature-foot">✦ TEMUKAN BUKU. NYALAKAN INSPIRASI.</div></section><main class="auth-form-column"><div class="register-card"><a class="auth-home-link" href="index.php">← Kembali ke Beranda</a><div class="register-header"><h1>GABUNG LENTERA</h1><h2>Buat akun baru.</h2><p>Daftarkan diri untuk mulai menjelajahi koleksi kami.</p></div><form method="POST"><div class="input-group"><label for="nama">Nama Lengkap</label><input id="nama" type="text" name="nama" autocomplete="name" placeholder="Nama lengkap Anda" required></div><div class="input-group"><label for="email">Alamat Email</label><input id="email" type="email" name="email" autocomplete="email" placeholder="nama@email.com" required></div><div class="input-group"><label for="password">Password</label><input id="password" type="password" name="password" autocomplete="new-password" placeholder="Buat password akun" required></div><div class="input-group"><label for="alamat">Alamat Lengkap</label><textarea id="alamat" name="alamat" autocomplete="street-address" placeholder="Alamat pengiriman buku" required></textarea></div><button class="btn-register" type="submit" name="register">Daftar Sekarang <span aria-hidden="true">↗</span></button><p class="login-link">Sudah punya akun? <a href="login.php">Masuk di sini</a></p></form></div></main></div><script src="js/ui.js" defer></script></body></html>