<?php
session_start();
require_once '../config/helpers.php';
lentera_require_user();

$nama = $_SESSION['nama'] ?? 'Pembaca';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda User | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css?v=20261005-userfix2">
</head>
<body>
<?php include '../template/navbar.php'; ?>

<main class="user-container">
    <span class="section-kicker">MEMBER READING ROOM</span>
    <h1>Halo, <?= h($nama); ?> 👋</h1>
    <p>Selamat datang di LENTERA. Pilih bacaan, lanjutkan belanja, dan temukan cerita berikutnya.</p>

    <div class="user-card-container">
        <div class="user-card">
            <div class="user-icon">📚</div>
            <h3>Katalog Buku</h3>
            <p>Telusuri koleksi dan temukan buku favoritmu.</p>
            <a href="katalog.php" class="btn">Lihat Buku</a>
        </div>

        <div class="user-card">
            <div class="user-icon">🛒</div>
            <h3>Keranjang</h3>
            <p>Cek kembali buku yang sudah kamu pilih sebelum checkout.</p>
            <a href="keranjang.php" class="btn">Lihat Keranjang</a>
        </div>

        <div class="user-card">
            <div class="user-icon">📦</div>
            <h3>Pesanan</h3>
            <p>Pantau status pembayaran dan proses pesananmu.</p>
            <a href="pesanan.php" class="btn">Pesanan Saya</a>
        </div>

        <div class="user-card">
            <div class="user-icon">✉️</div>
            <h3>Hubungi Admin</h3>
            <p>Kirim pertanyaan, informasi, atau permintaan bantuan.</p>
            <a href="contact.php" class="btn">Kirim Pesan</a>
        </div>

        <div class="user-card">
            <div class="user-icon">💬</div>
            <h3>Pesan Saya</h3>
            <p>Lihat riwayat pesan dan balasan yang diberikan admin.</p>
            <a href="pesan_saya.php" class="btn">Lihat Pesan</a>
        </div>

        <div class="user-card">
            <div class="user-icon">✦</div>
            <h3>Tentang LENTERA</h3>
            <p>Kenali visi, misi, dan pengalaman membaca LENTERA.</p>
            <a href="about.php" class="btn">Tentang Kami</a>
        </div>
    </div>
</main>

<script src="../js/ui.js?v=20261005-userfix2"></script>
</body>
</html>
