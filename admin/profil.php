<?php
session_start();
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){header('location:../login.php');exit;}
include '../config/koneksi.php';
$id=(int)($_SESSION['id_user']??0);
$q=mysqli_query($conn,"SELECT nama,email,alamat FROM users WHERE id_user=$id LIMIT 1");
$profile=$q?mysqli_fetch_assoc($q):null;
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Profil Administrator — LENTERA</title><link rel="stylesheet" href="../css/style.css"></head><body><?php include '../template/sidebar.php'; ?><main class="content"><span class="section-kicker">ADMINISTRATOR / PROFIL</span><h1>Profil Administrator</h1><p>Informasi akun yang sedang masuk ke LENTERA.</p><section class="profile-panel"><div class="profile-avatar">L</div><div><span class="section-kicker">AKUN ADMIN</span><h2><?= htmlspecialchars($profile['nama']??($_SESSION['nama']??'Administrator'),ENT_QUOTES,'UTF-8') ?></h2><dl><dt>Nama lengkap</dt><dd><?= htmlspecialchars($profile['nama']??'-',ENT_QUOTES,'UTF-8') ?></dd><dt>Email</dt><dd><?= htmlspecialchars($profile['email']??'-',ENT_QUOTES,'UTF-8') ?></dd><dt>Alamat</dt><dd><?= nl2br(htmlspecialchars($profile['alamat']??'-',ENT_QUOTES,'UTF-8')) ?></dd></dl><p class="profile-note">Informasi diambil langsung dari tabel users. Hubungi administrator sistem untuk perubahan data akun.</p></div></section></main><script src="../js/ui.js" defer></script></body></html>