<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

?>

<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tentang LENTERA</title><link rel="stylesheet" href="../css/style.css"></head><body><?php include "../template/navbar.php"; ?><header class="page-masthead compact"><span class="section-kicker">OUR STORY / LENTERA</span><h1>Tentang LENTERA</h1><p>Ruang digital untuk membaca, menemukan, dan tumbuh bersama cerita.</p></header><main class="content-user"><article class="about-card about-editorial"><span class="section-kicker">BOOKS & STORIES</span><h2>Temukan Buku.<br><em>Nyalakan Inspirasi.</em></h2><p>LENTERA adalah toko buku digital yang menyediakan katalog buku untuk memudahkan pencarian, pemesanan, dan pembelian bacaan pilihan.</p><div class="about-pillars"><section><span>01 / EXPLORE</span><h3>Visi</h3><p>Membuka akses terhadap berbagai pilihan bacaan melalui pengalaman digital yang nyaman.</p></section><section><span>02 / DISCOVER</span><h3>Misi</h3><p>Membantu pembaca mencari buku, memahami detailnya, serta memesan dengan lebih mudah.</p></section><section><span>03 / CONNECT</span><h3>Komunikasi</h3><p>Kirim pertanyaan atau masukan kepada administrator melalui layanan pesan.</p></section></div><a class="btn" href="contact.php">Hubungi Admin ↗</a></article></main><?php include "../template/footer.php"; ?><script src="../js/ui.js" defer></script></body></html>