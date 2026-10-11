<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");
    exit;
}

if (!isset($_GET['id'])) {

    header("location:pesanan.php");
    exit;
}

$id_pesanan = $_GET['id'];

$id_user = $_SESSION['id_user'];

// ambil data pesanan

$pesanan = mysqli_query(
    $conn,

    "
SELECT *

FROM pesanan

WHERE id_pesanan='$id_pesanan'

AND id_user='$id_user'

"

);

$data_pesanan = mysqli_fetch_assoc($pesanan);

if (!$data_pesanan) {

    echo "

    <script>

    alert('Pesanan tidak ditemukan');

    window.location='pesanan.php';

    </script>

    ";

    exit;
}

// ambil detail buku

$detail = mysqli_query(
    $conn,

    "

SELECT 

detail_pesanan.*,

buku.judul_buku,

buku.penulis,

buku.harga,

buku.gambar

FROM detail_pesanan

INNER JOIN buku

ON detail_pesanan.id_buku=buku.id_buku

WHERE detail_pesanan.id_pesanan='$id_pesanan'

"

);

?>

<!DOCTYPE html>

<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Detail Pesanan

    </title>

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

                    <?= $data_pesanan['tanggal']; ?>

                </b>

            </p>

            <p>

                Metode Pembayaran :

                <b>

                    <?= $data_pesanan['metode_pembayaran']; ?>

                </b>

            </p>

            <h3>

                Daftar Buku

            </h3>

            <?php while ($row = mysqli_fetch_assoc($detail)) { ?>

                <div class="book-order">

                    <img src="../images/buku/<?= $row['gambar']; ?>">

                    <div>

                        <h3>

                            <?= $row['judul_buku']; ?>

                        </h3>

                        <p>

                            Penulis :

                            <?= $row['penulis']; ?>

                        </p>

                        <p>

                            Jumlah :

                            <?= $row['jumlah']; ?>

                        </p>

                        <p>

                            Harga :

                            <b>

                                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>

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
<?php include "../template/footer.php"; ?>
</body>

</html>