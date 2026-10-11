<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$lenteraPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
$lenteraLoggedIn = isset($_SESSION['role']);
$lenteraUser = ($lenteraLoggedIn && $_SESSION['role'] === 'user');
?>
<nav class="navbar" aria-label="Navigasi utama">
  <a class="logo" href="../index.php" aria-label="LENTERA — Beranda"><span class="logo-mark" aria-hidden="true">L</span><span>LENTERA<small>BOOKS &amp; STORIES</small></span></a>
  <button class="mobile-nav-toggle" type="button" aria-label="Buka navigasi" aria-controls="main-menu" aria-expanded="false"><span></span><span></span><span></span></button>
  <ul class="nav-menu" id="main-menu">
    <li><a href="../index.php">Beranda</a></li>
    <li><a href="katalog.php" <?= in_array($lenteraPage,['katalog.php','detail_buku.php'],true)?'class="active"':'' ?>>Koleksi</a></li>
    <li><a href="about.php" <?= $lenteraPage==='about.php'?'class="active"':'' ?>>Tentang</a></li>
    <?php if ($lenteraUser): ?>
      <li><a href="pesanan.php">Pesanan</a></li><li><a href="pesan_saya.php">Pesan</a></li><li><a href="keranjang.php">Keranjang</a></li>
      <li><a href="profil.php">Profil</a></li><li><a href="index.php">Akun Saya</a></li><li><a class="nav-cta" href="../logout.php">Keluar</a></li>
    <?php elseif ($lenteraLoggedIn): ?>
      <li><a href="../admin/index.php">Dashboard Admin</a></li><li><a href="../logout.php">Keluar</a></li>
    <?php else: ?>
      <li><a href="../login.php">Masuk</a></li><li><a class="nav-cta" href="../register.php">Daftar</a></li>
    <?php endif; ?>
  </ul>
</nav>
