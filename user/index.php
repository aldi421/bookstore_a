<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        LENTERA User

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php

    include "../template/navbar.php";

    ?>

    <div class="user-container">

        <span class="section-kicker">MEMBER READING ROOM</span>

        <h1>

            Halo,

            <?= h($_SESSION['nama']); ?>

            👋

        </h1>

        <p>

            Selamat datang di LENTERA.

            Pilih bacaan, lanjutkan belanja, dan temukan cerita berikutnya.

        </p>

        <div class="user-card-container">

            <div class="user-card">

                <div class="user-icon">

                    📚

                </div>

                <h3>

                    Katalog Buku

                </h3>

                <p>

                    Cari dan pilih buku favorit

                </p>

                <a href="katalog.php" class="btn">

                    Lihat Buku

                </a>

            </div>

            <div class="user-card">

                <div class="user-icon">

                    🛒

                </div>

                <h3>

                    Keranjang

                </h3>

                <p>

                    Buku yang ingin dibeli

                </p>

                <a href="keranjang.php" class="btn">

                    Lihat Keranjang

                </a>

            </div>

            <div class="user-card">

                <div class="user-icon">

                    📦

                </div>

                <h3>

                    Pesanan

                </h3>

                <p>

                    Riwayat pembelian

                </p>

                <a href="pesanan.php" class="btn">

                    Pesanan Saya

                </a>

            </div>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>