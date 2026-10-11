<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

?>

<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ruang Member — LENTERA</title><link rel="stylesheet" href="../css/style.css"></head><body><?php include "../template/navbar.php"; ?><main class="user-container"><div class="member-banner"><div><div class="member-eyebrow">MEMBER AREA / LENTERA</div><h1>Halo, <?= htmlspecialchars($_SESSION['nama']??'Pembaca',ENT_QUOTES,'UTF-8') ?>.</h1><p>Selamat datang di rumah cerita. Ada buku-buku menarik yang menantimu.</p></div><a class="btn" href="katalog.php">Jelajahi Koleksi ↗</a></div><span class="section-kicker">QUICK ACCESS / 01</span><h2 style="font-size:32px;font-weight:500;margin-top:9px;color:var(--green-dark)">Apa yang ingin kamu lakukan?</h2><div class="user-card-container"><article class="user-card"><div class="user-icon">▤</div><h3>Koleksi Buku</h3><p>Jelajahi pilihan judul, penulis, dan genre untukmu.</p><a href="katalog.php" class="btn">Lihat Koleksi ↗</a></article><article class="user-card"><div class="user-icon">♧</div><h3>Keranjang</h3><p>Lihat dan kelola buku yang akan kamu beli.</p><a href="keranjang.php" class="btn">Keranjang ↗</a></article><article class="user-card"><div class="user-icon">◇</div><h3>Pesanan Saya</h3><p>Ikuti proses pesanan dan cek riwayat transaksi.</p><a href="pesanan.php" class="btn">Lihat Pesanan ↗</a></article><article class="user-card"><div class="user-icon">✉</div><h3>Pesan Admin</h3><p>Tinjau pesan dan balasan dari tim LENTERA.</p><a href="pesan_saya.php" class="btn">Lihat Pesan ↗</a></article></div><p class="dashboard-profile-link">Ingin melihat informasi akun? <a href="profil.php">Buka Profil Saya ↗</a></p></main><?php include "../template/footer.php"; ?><script src="../js/ui.js" defer></script></body></html>