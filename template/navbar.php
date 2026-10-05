<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/helpers.php';
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
?>
<nav class="navbar">
    <a class="logo" href="../user/index.php" aria-label="LENTERA Beranda">
        <span class="logo-mark">L</span>
        <span>LENTERA<small>BOOKS & STORIES</small></span>
    </a>
    <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false">☰</button>
    <ul class="nav-menu">
        <li><a class="<?= $current_page === 'index.php' ? 'active' : '' ?>" href="../user/index.php">Beranda</a></li>
        <li><a class="<?= in_array($current_page, ['katalog.php','detail_buku.php'], true) ? 'active' : '' ?>" href="../user/katalog.php">Koleksi</a></li>
        <li><a class="<?= $current_page === 'keranjang.php' ? 'active' : '' ?>" href="../user/keranjang.php">Keranjang</a></li>
        <li><a class="<?= in_array($current_page, ['pesanan.php','detail_pesanan.php'], true) ? 'active' : '' ?>" href="../user/pesanan.php">Pesanan</a></li>
        <li><a class="<?= $current_page === 'contact.php' ? 'active' : '' ?>" href="../user/contact.php">Kirim Pesan</a></li>
        <li><a class="<?= $current_page === 'pesan_saya.php' ? 'active' : '' ?>" href="../user/pesan_saya.php">Pesan Saya</a></li>
        <li><a class="<?= $current_page === 'about.php' ? 'active' : '' ?>" href="../user/about.php">Tentang</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') { ?>
            <li><a href="../logout.php">Keluar</a></li>
        <?php } else { ?>
            <li><a class="nav-cta" href="../login.php">Masuk</a></li>
        <?php } ?>
    </ul>
</nav>
