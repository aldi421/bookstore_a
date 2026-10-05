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

        About Us

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <div class="about-card">

            <h1>

                Tentang LENTERA

            </h1>

            <p>

                LENTERA BOOKS & STORIES adalah toko buku modern berbasis web yang menghadirkan
                koleksi bacaan untuk menemani rasa ingin tahu, imajinasi, dan perjalanan belajar setiap pembaca.

            </p>

            <p>

                Dengan semangat “Menerangi Setiap Halaman.”, LENTERA dirancang untuk memberikan pengalaman
                menemukan dan membeli buku secara mudah, tenang, dan nyaman.

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

                Layanan LENTERA

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