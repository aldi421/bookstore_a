<?php
session_start();
if(!isset($_SESSION['role'])||$_SESSION['role']!=='user'){header('location:../login.php');exit;}
include '../config/koneksi.php';
$id=(int)($_SESSION['id_user']??0);
$q=mysqli_query($conn,"SELECT nama,email,alamat FROM users WHERE id_user=$id LIMIT 1");
$profile=$q?mysqli_fetch_assoc($q):null;
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Profil Saya — LENTERA</title><link rel="stylesheet" href="../css/style.css"></head><body><?php include '../template/navbar.php'; ?><header class="page-masthead compact"><span class="section-kicker">MEMBER AREA / PROFIL</span><h1>Profil Saya</h1><p>Informasi akun yang terdaftar di LENTERA.</p></header><main class="content-user"><div class="profile-panel"><div class="profile-avatar" aria-hidden="true">L</div><div><span class="section-kicker">AKUN MEMBER</span><h2><?= htmlspecialchars($profile['nama']??($_SESSION['nama']??'Pembaca'),ENT_QUOTES,'UTF-8') ?></h2><dl><dt>Nama lengkap</dt><dd><?= htmlspecialchars($profile['nama']??'-',ENT_QUOTES,'UTF-8') ?></dd><dt>Email</dt><dd><?= htmlspecialchars($profile['email']??'-',ENT_QUOTES,'UTF-8') ?></dd><dt>Alamat</dt><dd><?= nl2br(htmlspecialchars($profile['alamat']??'-',ENT_QUOTES,'UTF-8')) ?></dd></dl><p class="profile-note">Informasi ini diambil langsung dari database akunmu.</p><a class="btn" href="contact.php">Hubungi Admin ↗</a></div></div></main><?php include '../template/footer.php'; ?><script src="../js/ui.js" defer></script></body></html>
