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

    <title>

        About Us

    </title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <div class="about-card">

            <h1>

                Tentang RUANG BACA

            </h1>

            <p>

                RUANG BACA merupakan aplikasi penjualan buku SMK Informatika RUANG BACA berbasis web
                yang menyediakan berbagai macam koleksi buku untuk
                memudahkan pengguna dalam mencari dan membeli buku.

            </p>

            <p>

                Aplikasi ini dibuat untuk memberikan pengalaman belanja
                buku secara mudah, cepat, dan nyaman melalui sistem
                digital.

            </p>

            <h2>

                Visi

            </h2>

            <p>

                Menjadi platform penjualan buku digital yang memberikan
                akses mudah terhadap berbagai sumber bacaan berkualitas.

            </p>

            <h2>

                Misi

            </h2>

            <ul>

                <li>

                    Menyediakan katalog buku yang lengkap.

                </li>

                <li>

                    Memberikan kemudahan dalam proses pemesanan buku.

                </li>

                <li>

                    Membangun pelayanan yang cepat dan terpercaya.

                </li>

            </ul>

            <h2>

                Layanan RUANG BACA

            </h2>

            <ul>

                <li>

                    Pencarian buku.

                </li>

                <li>

                    Pemesanan buku online.

                </li>

                <li>

                    Pembayaran melalui berbagai metode.

                </li>

                <li>

                    Layanan komunikasi dengan admin.

                </li>

            </ul>

            <h2>

                Hubungi Admin : 0857-6688-9900

            </h2>

            <h3>

            </h3>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>