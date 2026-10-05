<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

$id = $_GET['id'];

// mengambil data buku

$data = mysqli_query(
    $conn,

    "SELECT buku.*, kategori.nama_kategori

FROM buku

INNER JOIN kategori

ON buku.id_kategori = kategori.id_kategori

WHERE id_buku='$id'

"
);

$buku = mysqli_fetch_assoc($data);

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

                <img src="../images/buku/<?= $buku['gambar']; ?>">

            </div>

            <div class="detail-info">

                <h1>

                    <?= $buku['judul_buku']; ?>

                </h1>

                <p>

                    <b>Kategori :</b>

                    <?= $buku['nama_kategori']; ?>

                </p>

                <p>

                    <b>Penulis :</b>

                    <?= $buku['penulis']; ?>

                </p>

                <p>

                    <b>Penerbit :</b>

                    <?= $buku['penerbit']; ?>

                </p>

                <p>

                    <b>Tahun :</b>

                    <?= $buku['tahun']; ?>

                </p>

                <h2>

                    Rp <?= number_format($buku['harga'], 0, ',', '.'); ?>

                </h2>

                <p>

                    <?= $buku['deskripsi']; ?>

                </p>

                <a href="keranjang.php?id=<?= $buku['id_buku']; ?>" class="btn">

                    + Tambah Keranjang

                </a>

            </div>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>