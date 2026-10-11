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

<!DOCTYPE html>
<html>

<head>
    <title>
        Daftar Akun RUANG BACA
    </title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
            <div class="register-header">

                <h1>
                    📚 RUANG BACA
                </h1>

                <h2>
                    Daftar Akun Baru
                </h2>

                <p>
                    Buat akun untuk mulai berbelanja buku favorit Anda
                </p>

            </div>

            <form method="POST">
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
                        placeholder="Masukkan password"
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