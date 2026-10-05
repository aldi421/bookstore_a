<?php
include "config/koneksi.php";

$error_register = "";

if (isset($_POST['register'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');

    if ($nama === '' || $email === '' || $password === '' || $alamat === '') {
        $error_register = "Semua data wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_register = "Format email belum benar.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id_user FROM users WHERE email = ? LIMIT 1");
        if ($check) {
            mysqli_stmt_bind_param($check, "s", $email);
            mysqli_stmt_execute($check);
            $existing = mysqli_stmt_get_result($check);
            $email_exists = mysqli_num_rows($existing) > 0;
            mysqli_stmt_close($check);
        } else {
            $email_exists = false;
        }

        if ($email_exists) {
            $error_register = "Email ini sudah terdaftar. Silakan login.";
        } else {
            // Tetap memakai format password project lama agar kompatibel dengan sistem yang ada.
            $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, alamat, role) VALUES (?, ?, ?, ?, 'user')");

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $password, $alamat);
                $ok = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                if ($ok) {
                    header("Location: login.php?registered=1");
                    exit;
                }
            }

            $error_register = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#082117">
    <title>Daftar | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
    <link rel="stylesheet" href="css/style.css?v=20261005-authfix2">
</head>
<body class="auth-page">
    <main class="register-container auth-shell">
        <section class="auth-editorial-panel" aria-label="Tentang LENTERA">
            <div class="auth-editorial-topline">
                <span>EST. 2026</span><span class="auth-star">✦</span><span>READ · COLLECT · DISCOVER</span>
            </div>

            <div class="auth-editorial-copy">
                <p class="auth-editorial-kicker">LENTERA · BOOKS & STORIES</p>
                <h2>Mulai perjalanan membaca yang <em>lebih indah.</em></h2>
                <p class="auth-editorial-desc">Buat akunmu dan masuk ke ruang membaca yang hangat, berkarakter, dan dibuat untuk menemukan cerita berikutnya.</p>
            </div>

            <div class="auth-feature-strip">
                <div class="auth-feature-item"><b>Collect</b><span>Temukan dan kumpulkan buku favoritmu.</span></div>
                <div class="auth-feature-item"><b>Discover</b><span>Jelajahi kategori dan judul yang terus berkembang.</span></div>
                <div class="auth-feature-item"><b>Belong</b><span>Punya ruang sendiri untuk pesanan dan perjalanan belanjamu.</span></div>
            </div>

            <div class="auth-quote-card">
                <span class="auth-quote-mark">“</span>
                <p>Setiap rak menyimpan kemungkinan. Setiap halaman bisa menjadi awal yang baru.</p>
                <small>— LENTERA BOOKS & STORIES</small>
            </div>

            <div class="auth-book-display" aria-hidden="true">
                <div class="auth-book auth-book-1"><span>COLLECT</span></div>
                <div class="auth-book auth-book-2"><span>READ</span></div>
                <div class="auth-book auth-book-3"><span>STORIES</span></div>
                <div class="auth-book auth-book-4"><span>LENTERA</span></div>
            </div>
        </section>

        <section class="register-card auth-form-card">
            <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
            <div class="register-header">
                <h1>LENTERA</h1>
                <h2>Daftar Akun Baru</h2>
                <p>Buat akun untuk mulai menjelajahi dan berbelanja buku favoritmu.</p>
            </div>

            <?php if ($error_register !== '') { ?>
                <div class="auth-alert" role="alert"><?= htmlspecialchars($error_register) ?></div>
            <?php } ?>

            <form method="POST" class="auth-form" autocomplete="on">
                <div class="input-group">
                    <label for="register-name">Nama Lengkap</label>
                    <input id="register-name" type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" autocomplete="name" required>
                </div>

                <div class="input-group">
                    <label for="register-email">Email</label>
                    <input id="register-email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Masukkan email" autocomplete="email" required>
                </div>

                <div class="input-group">
                    <label for="register-password">Password</label>
                    <input id="register-password" type="password" name="password" placeholder="Masukkan password" autocomplete="new-password" required>
                </div>

                <div class="input-group">
                    <label for="register-address">Alamat Lengkap</label>
                    <textarea id="register-address" name="alamat" placeholder="Masukkan alamat lengkap" required><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                </div>

                <button class="btn-register" type="submit" name="register">BUAT AKUN LENTERA</button>
                <div class="login-link">Sudah punya akun? <a href="login.php">Login di sini</a></div>
            </form>
        </section>
    </main>
    <script src="js/ui.js?v=20261005-authfix2"></script>
</body>
</html>
