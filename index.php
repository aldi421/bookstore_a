<?php
session_start();
include "config/koneksi.php";
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
$buku_unggulan = mysqli_query($conn, "SELECT buku.*, kategori.nama_kategori FROM buku LEFT JOIN kategori ON buku.id_kategori=kategori.id_kategori ORDER BY buku.id_buku DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RUANG BACA | Toko Buku</title><link rel="stylesheet" href="css/style.css">
</head><body>
<nav class="navbar"><a class="logo" href="index.php"><span class="logo-mark">RB</span><span>RUANG BACA<small>Toko Buku & Cerita</small></span></a><ul class="nav-menu"><li><a class="active" href="index.php">Beranda</a></li><li><a href="katalog.php">Koleksi</a></li><?php if(isset($_SESSION['role'])){ ?><li><a href="<?= $_SESSION['role']=='admin'?'admin/index.php':'user/index.php' ?>">Dashboard</a></li><li><a href="logout.php">Keluar</a></li><?php } else { ?><li><a href="login.php">Masuk</a></li><li><a class="nav-cta" href="register.php">Daftar</a></li><?php } ?></ul></nav>
<header class="hero vintage-hero"><div class="hero-inner"><p class="eyebrow">EST. 2026 · BOGOR, INDONESIA</p><h1>Temukan cerita yang<br><em>layak disimpan.</em></h1><p class="hero-copy">Koleksi pilihan untuk pembaca yang percaya bahwa setiap buku memiliki rumah, dan setiap halaman menyimpan perjalanan.</p><div class="hero-actions"><a href="katalog.php" class="btn">Jelajahi Koleksi</a><a href="#koleksi" class="text-link">Lihat pilihan terbaru →</a></div></div><div class="hero-quote"><span>“</span><p>Sebuah ruang untuk membaca,<br>menemukan, dan mengingat.</p></div></header>
<main>
<section class="home-section intro-strip"><div><span class="section-kicker">Koleksi Kurasi</span><h2>Buku dari rak kami</h2></div><p>Judul-judul yang tersedia langsung dari koleksi RUANG BACA. Temukan bacaan berikutnya untuk menemani waktu tenangmu.</p></section>
<section class="home-section" id="koleksi"><div class="section-heading"><div><span class="section-kicker">BARU DI RAK</span><h2>Pilihan Terbaru</h2></div><a class="text-link" href="katalog.php">Lihat seluruh koleksi →</a></div><div class="book-container home-books">
<?php while($row=mysqli_fetch_assoc($buku_unggulan)){ ?><article class="book-card"><a class="cover-wrap" href="<?= isset($_SESSION['role'])&&$_SESSION['role']=='user'?'user/detail_buku.php?id='.$row['id_buku']:'login.php' ?>"><img src="images/buku/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul_buku']) ?>"></a><div class="book-meta"><span class="book-category"><?= htmlspecialchars($row['nama_kategori']??'Koleksi') ?></span><h3><?= htmlspecialchars($row['judul_buku']) ?></h3><p class="author"><?= htmlspecialchars($row['penulis']) ?></p><div class="book-bottom"><strong>Rp <?= number_format($row['harga'],0,',','.') ?></strong><a class="mini-link" href="<?= isset($_SESSION['role'])&&$_SESSION['role']=='user'?'user/detail_buku.php?id='.$row['id_buku']:'login.php' ?>">Detail →</a></div></div></article><?php } ?>
</div></section>
<section class="category-band"><div class="home-section"><div class="section-heading light"><div><span class="section-kicker">TELUSURI RAK</span><h2>Koleksi berdasarkan kategori</h2></div></div><div class="category-container"><?php while($row=mysqli_fetch_assoc($kategori)){ ?><a class="category-card" href="katalog.php?kategori=<?= (int)$row['id_kategori'] ?>"><span>✦</span><h3><?= htmlspecialchars($row['nama_kategori']) ?></h3><p>Temukan koleksi pilihan</p><b>Lihat rak →</b></a><?php } ?></div></div></section>
<section class="manifesto"><span class="ornament">❦</span><p>“Buku yang baik tidak hanya selesai dibaca.<br>Ia tinggal bersama kita.”</p><small>— RUANG BACA</small></section>
</main><footer><div><b>RUANG BACA</b><p>Temukan Cerita, Temukan Pengetahuan.</p></div><p>© 2026 RUANG BACA · Toko Buku</p></footer><script src="js/ui.js"></script>
</body></html>