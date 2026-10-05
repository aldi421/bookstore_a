<?php
session_start();
require '../config/koneksi.php';
require_role('user');

$id_pesanan = (int) ($_GET['id'] ?? 0);
$id_user = (int) ($_SESSION['id_user'] ?? 0);
if ($id_pesanan <= 0) {
    header('Location: pesanan.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT * FROM pesanan WHERE id_pesanan=? AND id_user=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'ii', $id_pesanan, $id_user);
mysqli_stmt_execute($stmt);
$data_pesanan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data_pesanan) {
    header('Location: pesanan.php');
    exit;
}

$detailStmt = mysqli_prepare($conn, 'SELECT detail_pesanan.*, buku.judul_buku, buku.penulis, buku.gambar, COALESCE(detail_pesanan.harga, buku.harga) AS harga_tampil FROM detail_pesanan INNER JOIN buku ON detail_pesanan.id_buku=buku.id_buku WHERE detail_pesanan.id_pesanan=?');
mysqli_stmt_bind_param($detailStmt, 'i', $id_pesanan);
mysqli_stmt_execute($detailStmt);
$detail = mysqli_stmt_get_result($detailStmt);
?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Detail Pesanan

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <h1>

            Detail Pesanan #<?= $id_pesanan; ?>

        </h1>

        <div class="order-card">

            <p>

                Tanggal :

                <b>

                    <?= h($data_pesanan['tanggal']); ?>

                </b>

            </p>

            <p>

                Metode Pembayaran :

                <b>

                    <?= h($data_pesanan['metode_pembayaran']); ?>

                </b>

            </p>

            <h3>

                Daftar Buku

            </h3>

            <?php while ($row = mysqli_fetch_assoc($detail)) { ?>

                <div class="book-order">

                    <img src="../images/buku/<?= h($row['gambar']); ?>">

                    <div>

                        <h3>

                            <?= h($row['judul_buku']); ?>

                        </h3>

                        <p>

                            Penulis :

                            <?= h($row['penulis']); ?>

                        </p>

                        <p>

                            Jumlah :

                            <?= $row['jumlah']; ?>

                        </p>

                        <p>

                            Harga :

                            <b>

                                Rp <?= number_format($row['harga_tampil'], 0, ',', '.'); ?>

                            </b>

                        </p>

                    </div>

                </div>

            <?php } ?>

            <br>

            <a href="pesanan.php" class="btn">

                Kembali

            </a>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>