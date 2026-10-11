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
<!DOCTYPE html>
<html>

<head>
    <title>
        Login RUANG BACA


    </title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
            <div class="login-header">


                <h1>
                    📚 RUANG BACA


                </h1>

                <h2>
                    Selamat Datang Kembali
                </h2>

                <p>
                    Login untuk melanjutkan belanja buku
                </p>

            </div>

            <form method="POST">
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

                <button
                    class="btn-login"
                    name="login">
                    LOGIN
                </button>

                <div class="register-link">
                    Belum punya akun?
                    <a href="register.php">

                        Daftar Sekarang
                    </a>

                </div>
            </form>
        </div>
    </div>
<script src="js/ui.js"></script>
</body>

</html>