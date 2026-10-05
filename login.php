<?php
session_start();
require __DIR__ . "/config/koneksi.php";

$error = '';

if (isset($_POST['login'])) {
    verify_csrf_or_abort();

    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Email atau password salah.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id_user, nama, email, password, role FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        $passwordValid = false;
        $legacyPlaintext = false;

        if ($user) {
            $stored = (string) $user['password'];
            $passwordValid = password_verify($password, $stored);

            // Migrasi otomatis untuk database lama yang masih menyimpan password plaintext.
            if (!$passwordValid && !preg_match('/^\$(2y|argon2)/', $stored) && hash_equals($stored, $password)) {
                $passwordValid = true;
                $legacyPlaintext = true;
            }
        }

        if ($user && $passwordValid) {
            if ($legacyPlaintext || password_needs_rehash((string) $user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $update = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id_user = ?");
                mysqli_stmt_bind_param($update, 'si', $newHash, $user['id_user']);
                mysqli_stmt_execute($update);
            }

            session_regenerate_id(true);
            $_SESSION['id_user'] = (int) $user['id_user'];
            $_SESSION['nama'] = (string) $user['nama'];
            $_SESSION['role'] = (string) $user['role'];
            unset($_SESSION['csrf_token']);

            if ($user['role'] === 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: user/index.php');
            }
            exit;
        }

        $error = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>
        Login LENTERA


    </title>

    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
<link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">
    <div class="login-container">
        <section class="auth-editorial-panel" aria-label="Tentang Lentera">
            <div class="auth-editorial-topline">
                <span>EST. 2026</span>
                <span class="auth-star">✦</span>
                <span>CURATED BOOKSTORE</span>
            </div>

            <div class="auth-editorial-copy">
                <p class="auth-editorial-kicker">LENTERA · BOOKS & STORIES</p>
                <h2>Temukan cerita yang <em>tinggal lebih lama.</em></h2>
                <p class="auth-editorial-desc">
                    Ruang untuk buku, gagasan, dan cerita yang menemani setiap langkahmu.
                    Masuk dan lanjutkan perjalanan membaca bersama LENTERA.
                </p>
            </div>

            <div class="auth-mini-ornament">A curated reading experience</div>

            <div class="auth-feature-strip" aria-hidden="true">
                <div class="auth-feature-item">
                    <b>Curated</b>
                    <span>Rak pilihan berisi judul yang terasa hangat, estetik, dan berkesan.</span>
                </div>
                <div class="auth-feature-item">
                    <b>Stories</b>
                    <span>Dari novel, inspirasi, hingga buku pengetahuan—semuanya terasa dekat.</span>
                </div>
                <div class="auth-feature-item">
                    <b>Quiet Luxury</b>
                    <span>Nuansa toko buku premium dengan sentuhan editorial khas LENTERA.</span>
                </div>
            </div>

            <div class="auth-quote-card">
                <span class="auth-quote-mark">“</span>
                <p>Buku yang tepat tidak sekadar dibaca—ia ikut membentuk cara kita melihat dunia.</p>
                <small>— CATATAN DARI LENTERA</small>
            </div>

            <div class="auth-book-display" aria-hidden="true">
                <div class="auth-book auth-book-1"><span>STORIES</span></div>
                <div class="auth-book auth-book-2"><span>IDEAS</span></div>
                <div class="auth-book auth-book-3"><span>LITERATURE</span></div>
                <div class="auth-book auth-book-4"><span>LENTERA</span></div>
            </div>
        </section>

        <div class="login-card">
            <a class="auth-home-link" href="index.php">← Kembali ke Beranda</a>
            <div class="login-header">


                <h1>
                    LENTERA


                </h1>

                <h2>
                    Selamat Datang Kembali
                </h2>

                <p>
                    Login untuk melanjutkan belanja buku
                </p>

            </div>

            <form method="POST">
                <?= csrf_input(); ?>
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

                <?php if ($error !== '') { ?>
                    <div class="card" style="margin-top:14px;padding:10px 12px;">
                        <?= h($error); ?>
                    </div>
                <?php } ?>

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