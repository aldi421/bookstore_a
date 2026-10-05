<?php
session_start();
include "config/koneksi.php";
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
$buku_unggulan = mysqli_query($conn, "SELECT buku.*, kategori.nama_kategori FROM buku LEFT JOIN kategori ON buku.id_kategori=kategori.id_kategori ORDER BY buku.id_buku DESC LIMIT 8");
$hero_books = mysqli_query($conn, "SELECT id_buku, judul_buku, gambar FROM buku ORDER BY id_buku DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#10251d">
<title>LENTERA | Books & Stories</title>
<link rel="icon" type="image/svg+xml" href="images/favicon.svg">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <a class="logo" href="index.php" aria-label="LENTERA Beranda"><span class="logo-mark">L</span><span>LENTERA<small>BOOKS & STORIES</small></span></a>
    <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false">☰</button>
    <ul class="nav-menu">
        <li><a class="active" href="index.php">Beranda</a></li>
        <li><a href="katalog.php">Koleksi</a></li>
        <?php if(isset($_SESSION['role'])){ ?>
            <li><a href="<?= $_SESSION['role']=='admin'?'admin/index.php':'user/index.php' ?>">Dashboard</a></li>
            <li><a href="logout.php">Keluar</a></li>
        <?php } else { ?>
            <li><a href="login.php">Masuk</a></li>
            <li><a class="nav-cta" href="register.php">Daftar</a></li>
        <?php } ?>
    </ul>
</nav>

<header class="hero vintage-hero">
    <div class="hero-inner">
        <p class="eyebrow">LENTERA · BOOKS & STORIES</p>
        <h1>Menerangi setiap<br><em>halaman.</em></h1>
        <p class="hero-copy">Ruang untuk menemukan cerita yang menetap lebih lama dari halaman terakhir—dikurasi untuk pembaca, pemimpi, dan siapa pun yang masih percaya pada keajaiban sebuah buku.</p>
        <div class="hero-actions">
            <a href="katalog.php" class="btn">Jelajahi Koleksi</a>
            <a href="#koleksi" class="text-link">Pilihan terbaru →</a>
        </div>
    </div>

    <div class="hero-visual" aria-label="Pilihan buku terbaru LENTERA">
        <div class="hero-orbit" aria-hidden="true"></div>
        <div class="hero-book-stack">
            <?php $hero_count=0; while($hb=mysqli_fetch_assoc($hero_books)){ $hero_count++; ?>
                <a class="hero-book" href="<?= isset($_SESSION['role'])&&$_SESSION['role']=='user'?'user/detail_buku.php?id='.$hb['id_buku']:'login.php' ?>" title="<?= htmlspecialchars($hb['judul_buku']) ?>">
                    <?php if(!empty($hb['gambar'])){ ?>
                        <img src="images/buku/<?= htmlspecialchars($hb['gambar']) ?>" alt="<?= htmlspecialchars($hb['judul_buku']) ?>">
                    <?php } else { ?>
                        <span class="hero-book-fallback">L</span>
                    <?php } ?>
                </a>
            <?php } ?>
            <?php while($hero_count<3){ $hero_count++; ?><span class="hero-book"><span class="hero-book-fallback">L</span></span><?php } ?>
        </div>
        <div class="hero-seal"><span>Curated<br>Reading<br>2026</span></div>
    </div>
</header>

<main>
<section class="home-section intro-strip">
    <div><span class="section-kicker">Koleksi Kurasi</span><h2>Buku yang layak tinggal lebih lama.</h2></div>
    <p>Kami percaya menemukan buku seharusnya terasa personal. LENTERA menghadirkan rak yang tenang, visual yang hangat, dan pilihan bacaan untuk menemani rasa ingin tahu.</p>
</section>

<section class="home-section" id="koleksi">
    <div class="section-heading">
        <div><span class="section-kicker">BARU DI RAK</span><h2>Pilihan Terbaru</h2></div>
        <a class="text-link" href="katalog.php">Lihat seluruh koleksi →</a>
    </div>
    <div class="book-container home-books">
    <?php while($row=mysqli_fetch_assoc($buku_unggulan)){ ?>
        <article class="book-card">
            <a class="cover-wrap" href="<?= isset($_SESSION['role'])&&$_SESSION['role']=='user'?'user/detail_buku.php?id='.$row['id_buku']:'login.php' ?>">
                <img src="images/buku/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul_buku']) ?>">
            </a>
            <div class="book-meta">
                <span class="book-category"><?= htmlspecialchars($row['nama_kategori']??'Koleksi') ?></span>
                <h3><?= htmlspecialchars($row['judul_buku']) ?></h3>
                <p class="author"><?= htmlspecialchars($row['penulis']) ?></p>
                <div class="book-bottom"><strong>Rp <?= number_format($row['harga'],0,',','.') ?></strong><a class="mini-link" href="<?= isset($_SESSION['role'])&&$_SESSION['role']=='user'?'user/detail_buku.php?id='.$row['id_buku']:'login.php' ?>">Detail →</a></div>
            </div>
        </article>
    <?php } ?>
    </div>
</section>

<section class="category-band">
    <div class="home-section">
        <div class="section-heading light"><div><span class="section-kicker">TELUSURI RAK</span><h2>Temukan duniamu.</h2></div></div>
        <div class="category-container">
            <?php while($row=mysqli_fetch_assoc($kategori)){ ?>
                <a class="category-card" href="katalog.php?kategori=<?= (int)$row['id_kategori'] ?>"><span>✦</span><h3><?= htmlspecialchars($row['nama_kategori']) ?></h3><p>Temukan koleksi pilihan</p><b>Lihat rak →</b></a>
            <?php } ?>
        </div>
    </div>
</section>

<section class="manifesto"><span class="ornament">❦</span><p>“Sebuah buku yang baik tidak berhenti saat ditutup. Ia ikut pulang bersama pembacanya.”</p><small>— LENTERA BOOKS & STORIES</small></section>
</main>

<footer><div><b>LENTERA</b><p>Menerangi Setiap Halaman.</p></div><p>© 2026 LENTERA · Books & Stories</p></footer>
<script src="js/ui.js"></script>
</body>
</html>
