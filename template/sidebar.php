<?php
$lenteraAdminPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$lenteraAdminMenu = [
    ['index.php','◈','Dashboard'], ['buku.php','▤','Buku'], ['kategori.php','▦','Kategori'],
    ['pesanan.php','◇','Pesanan'], ['user.php','♙','Pengguna'], ['pesan.php','✉','Pesan'],
    ['pemasukan.php','↗','Pemasukan'], ['profil.php','◉','Profil']
];
$lenteraParent = [
 'tambah_buku.php'=>'buku.php','edit_buku.php'=>'buku.php', 'hapus_buku.php'=>'buku.php',
 'tambah_kategori.php'=>'kategori.php','edit_kategori.php'=>'kategori.php','hapus_kategori.php'=>'kategori.php',
 'tambah_user.php'=>'user.php','hapus_user.php'=>'user.php','balas_pesan.php'=>'pesan.php',
 'detail_pesanan.php'=>'pesanan.php','update_status.php'=>'pesanan.php',
 'konfirmasi_pembayaran.php'=>'pesanan.php','hapus_pesanan.php'=>'pesanan.php'
];
$lenteraCurrentMenu = $lenteraParent[$lenteraAdminPage] ?? $lenteraAdminPage;
?>
<button type="button" class="admin-menu-toggle" aria-label="Buka menu administrator" aria-controls="lentera-admin-sidebar" aria-expanded="false">☰ <span>Menu</span></button>
<aside class="sidebar" id="lentera-admin-sidebar">
  <a class="sidebar-logo" href="index.php"><span class="logo-mark">L</span><span>LENTERA<small>ADMINISTRATOR</small></span></a>
  <p class="sidebar-heading">WORKSPACE / 2026</p>
  <nav class="menu" aria-label="Menu administrator">
    <?php foreach ($lenteraAdminMenu as [$url,$symbol,$label]): ?>
      <a href="<?= htmlspecialchars($url,ENT_QUOTES,'UTF-8') ?>" <?= $lenteraCurrentMenu===$url?'class="is-active" aria-current="page"':'' ?>><span class="menu-icon" aria-hidden="true"><?= $symbol ?></span><span><?= $label ?></span></a>
    <?php endforeach; ?>
    <a href="../index.php"><span class="menu-icon" aria-hidden="true">↖</span><span>Lihat Website</span></a>
    <a class="logout-link" href="../logout.php"><span class="menu-icon" aria-hidden="true">↪</span><span>Keluar</span></a>
  </nav>
  <div class="sidebar-foot"><span>✦ READ • DISCOVER • GROW</span><small>LENTERA BOOKS &amp; STORIES</small></div>
</aside><div class="sidebar-overlay" aria-hidden="true"></div>
