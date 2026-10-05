<?php
session_start();
include "config/koneksi.php";

$error_login = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error_login = "Email dan password wajib diisi.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id_user, nama, email, password, role FROM users WHERE email = ? LIMIT 1");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            // Database project ini masih menggunakan password plain-text.
            // Dibuat kompatibel dengan data lama agar sistem yang sudah ada tidak berubah.
            if ($user && hash_equals((string)$user['password'], (string)$password)) {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: user/index.php");
                }
                exit;
            }
        }

        $error_login = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#082117">
    <title>Masuk | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
    <link rel="stylesheet" href="css/style.css?v=20261005-authfix2">
</head>
<body class="auth-page">
    <main class="login-container auth-shell">
        <section class="auth-editorial-panel" aria-label="Tentang LENTERA">
            <div class="auth-editorial-topline">
                <span>EST. 2026</span><span class="auth-star">✦</span><span>CURATED BOOKSTORE</span>
            </div>

            <div class="auth-editorial-copy">
                <p class="auth-editorial-kicker">LENTERA · BOOKS & STORIES</p>
                <h2>Temukan cerita yang <em>tinggal lebih lama.</em></h2>
                <p class="auth-editorial-desc">Ruang untuk buku, gagasan, dan cerita yang menemani setiap langkahmu. Masuk dan lanjutkan perjalanan membaca bersama LENTERA.</p>
            </div>

            <div class="auth-feature-strip">
                <div class="auth-feature-item"><b>Curated</b><span>Rak pilihan untuk menemukan bacaan yang terasa personal.</span></div>
                <div class="auth-feature-item"><b>Stories</b><span>Novel, inspirasi, komik, teknologi, dan lebih banyak dunia untuk dijelajahi.</span></div>
                <div class="auth-feature-item"><b>Lentera</b><span>Pengalaman toko buku digital yang hangat dan berkarakter.</span></div>
            </div>

            <div class="auth-quote-card">
                <span class="auth-quote-mark">“</span>
                <p>Buku yang tepat tidak sekadar dibaca—ia ikut membentuk cara kita melihat dunia.</p>
                <small>— CATATAN DARI LENTERA</small>
            </div>

            <div class="auth-book-display" aria-hidden="true">
                <div class="auth-book auth-book-1"><span>STORIES</span></div>
                <div class="auth-book auth-book-2"><span>IDEAS</span></div>
                <div class="auth-book auth-book-3"><span>READ</span></div>
                <div class="auth-book auth-book-4"><span>LENTERA</span></div>
            </div>
        </section>

        <section class="login-card auth-form-card">
            <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
            <div class="login-header">
                <h1>LENTERA</h1>
                <h2>Selamat Datang Kembali</h2>
                <p>Masuk untuk melanjutkan perjalanan membaca dan belanja bukumu.</p>
            </div>

            <?php if (isset($_GET['registered']) && $_GET['registered'] === '1') { ?>
                <div class="auth-alert auth-alert-success" role="status">Akun berhasil dibuat. Silakan login.</div>
            <?php } ?>
            <?php if ($error_login !== '') { ?>
                <div class="auth-alert" role="alert"><?= htmlspecialchars($error_login) ?></div>
            <?php } ?>

            <form method="POST" class="auth-form" autocomplete="on">
                <div class="input-group">
                    <label for="login-email">Email</label>
                    <input id="login-email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Masukkan email" autocomplete="email" required>
                </div>

                <div class="input-group">
                    <label for="login-password">Password</label>
                    <input id="login-password" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
                </div>

                <button class="btn-login" type="submit" name="login">MASUK KE LENTERA</button>
                <div class="register-link">Belum punya akun? <a href="register.php">Daftar Sekarang</a></div>
            </form>
        </section>
    </main>
    <script src="js/ui.js?v=20261005-authfix2"></script>
</body>
</html>
