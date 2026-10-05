<?php
session_start();
require '../config/koneksi.php';
require_role('user');

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: katalog.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT buku.*, kategori.nama_kategori FROM buku INNER JOIN kategori ON buku.id_kategori=kategori.id_kategori WHERE id_buku=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$buku) {
    header('Location: katalog.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Detail Buku

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php

    include "../template/navbar.php";

    ?>

    <div class="detail-container">

        <div class="detail-card">

            <div class="detail-image">

                <img src="../images/buku/<?= h($buku['gambar']); ?>">

            </div>

            <div class="detail-info">

                <h1>

                    <?= h($buku['judul_buku']); ?>

                </h1>

                <p>

                    <b>Kategori :</b>

                    <?= h($buku['nama_kategori']); ?>

                </p>

                <p>

                    <b>Penulis :</b>

                    <?= h($buku['penulis']); ?>

                </p>

                <p>

                    <b>Penerbit :</b>

                    <?= h($buku['penerbit']); ?>

                </p>

                <p>

                    <b>Tahun :</b>

                    <?= $buku['tahun']; ?>

                </p>

                <h2>

                    Rp <?= number_format($buku['harga'], 0, ',', '.'); ?>

                </h2>

                <p>

                    <?= h($buku['deskripsi']); ?>

                </p>

                <form method="POST" action="keranjang.php" style="display:inline">
                    <?= csrf_input(); ?>
                    <input type="hidden" name="cart_action" value="add">
                    <input type="hidden" name="id" value="<?= (int) $buku['id_buku']; ?>">
                    <button type="submit" class="btn" style="border:0;cursor:pointer;">+ Tambah Keranjang</button>
                </form>

            </div>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>