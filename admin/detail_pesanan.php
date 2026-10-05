<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("location:../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location:pesanan.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil ID Pesanan
|--------------------------------------------------------------------------
*/
$id_pesanan = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Ambil Data Pesanan
|--------------------------------------------------------------------------
*/
$data = mysqli_query(
    $conn,
    "
    SELECT 
        pesanan.*,
        users.nama
    FROM pesanan
    INNER JOIN users
        ON pesanan.id_user = users.id_user
    WHERE pesanan.id_pesanan = $id_pesanan
    LIMIT 1
    "
);

$pesanan = mysqli_fetch_assoc($data);

/* Jika pesanan tidak ditemukan */
if (!$pesanan) {
    header("location:pesanan.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil Detail Buku
|--------------------------------------------------------------------------
*/
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
        ON detail_pesanan.id_buku = buku.id_buku
    WHERE detail_pesanan.id_pesanan = $id_pesanan
    "
);

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/
function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function statusClass($status)
{
    $status = strtolower(trim($status));

    if (
        strpos($status, 'selesai') !== false ||
        strpos($status, 'sudah bayar') !== false ||
        strpos($status, 'dibayar') !== false
    ) {
        return 'success';
    }

    if (
        strpos($status, 'batal') !== false ||
        strpos($status, 'gagal') !== false
    ) {
        return 'danger';
    }

    if (
        strpos($status, 'proses') !== false ||
        strpos($status, 'dikirim') !== false ||
        strpos($status, 'dikemas') !== false
    ) {
        return 'info';
    }

    return 'warning';
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Detail Pesanan #<?= $id_pesanan; ?> | LENTERA
    </title>

    <!-- CSS utama project -->
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

    <!-- CSS khusus halaman Detail Pesanan -->
    <style>

        /* ================================================================
           DETAIL PESANAN
           ================================================================ */

        .order-detail-page {
            padding-bottom: 50px;
        }

        /* ---------- Header ---------- */

        .order-detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 26px;
        }

        .order-detail-heading {
            margin: 0;
        }

        .order-eyebrow {
            display: block;
            margin-bottom: 7px;

            color: #9a7340;

            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .order-detail-heading h1 {
            margin: 0;

            color: #57201f;

            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(30px, 3vw, 44px);
            font-weight: 500;
            line-height: 1.05;
        }

        .order-detail-heading p {
            max-width: 650px;
            margin: 9px 0 0;

            color: #7c6d5d;

            font-size: 14px;
            line-height: 1.7;
        }

        /* ---------- Tombol kembali ---------- */

        .order-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            min-height: 43px;
            padding: 0 18px;

            border: 1px solid #d8c7a9;
            border-radius: 8px;

            background: #fffaf0;
            color: #57201f;

            font-size: 13px;
            font-weight: 700;
            text-decoration: none;

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease,
                background .25s ease;
        }

        .order-back-btn:hover {
            transform: translateY(-2px);

            border-color: #b99255;

            background: #ffffff;

            box-shadow: 0 8px 20px rgba(74, 46, 27, .10);
        }

        /* ================================================================
           INFORMATION CARD
           ================================================================ */

        .order-information {
            overflow: hidden;

            margin-bottom: 25px;

            border: 1px solid #dfd0b8;
            border-radius: 14px;

            background: rgba(255, 252, 246, .95);

            box-shadow: 0 12px 35px rgba(71, 45, 26, .06);
        }

        .order-information-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;

            padding: 20px 24px;

            border-bottom: 1px solid #e5d9c5;

            background:
                linear-gradient(
                    90deg,
                    rgba(247, 237, 218, .75),
                    rgba(255, 252, 246, .9)
                );
        }

        .order-information-top h2 {
            margin: 0;

            color: #38281f;

            font-family: Georgia, "Times New Roman", serif;
            font-size: 21px;
            font-weight: 500;
        }

        .order-number {
            color: #a17b43;

            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        /* ---------- Grid informasi ---------- */

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .order-info-item {
            position: relative;

            min-height: 105px;
            padding: 21px 23px;

            border-right: 1px solid #eadfce;
        }

        .order-info-item:last-child {
            border-right: none;
        }

        .order-info-label {
            display: block;
            margin-bottom: 10px;

            color: #9b8975;

            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .order-info-value {
            display: block;

            color: #35261f;

            font-size: 14px;
            font-weight: 700;
            line-height: 1.5;
        }

        /* ================================================================
           BADGE
           ================================================================ */

        .order-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 7px 11px;

            border-radius: 999px;

            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
        }

        .order-status::before {
            content: "";

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;
        }

        .order-status.success {
            background: #e8efe6;
            color: #456345;
        }

        .order-status.warning {
            background: #f6ecd8;
            color: #956b29;
        }

        .order-status.danger {
            background: #f4dfdc;
            color: #8a3c35;
        }

        .order-status.info {
            background: #e7e9df;
            color: #53614d;
        }

        /* ================================================================
           BOOK SECTION
           ================================================================ */

        .ordered-books-section {
            padding: 24px;

            border: 1px solid #dfd0b8;
            border-radius: 14px;

            background: #fffdf8;

            box-shadow: 0 12px 35px rgba(71, 45, 26, .05);
        }

        .ordered-books-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;

            margin-bottom: 20px;
            padding-bottom: 17px;

            border-bottom: 1px solid #e5d9c5;
        }

        .ordered-books-header h2 {
            margin: 0 0 5px;

            color: #38281f;

            font-family: Georgia, "Times New Roman", serif;
            font-size: 23px;
            font-weight: 500;
        }

        .ordered-books-header p {
            margin: 0;

            color: #8a7968;

            font-size: 12px;
        }

        /* ---------- Item buku ---------- */

        .ordered-book {
            display: grid;
            grid-template-columns: 92px minmax(220px, 1fr) 130px 120px 150px;
            align-items: center;
            gap: 22px;

            padding: 18px;

            border: 1px solid #eadfce;
            border-radius: 11px;

            background: #fffcf6;

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
        }

        .ordered-book + .ordered-book {
            margin-top: 12px;
        }

        .ordered-book:hover {
            transform: translateY(-2px);

            border-color: #d5bd96;

            box-shadow: 0 10px 26px rgba(72, 44, 26, .08);
        }

        /* ---------- Cover ---------- */

        .ordered-book-cover {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 92px;
            height: 122px;
            padding: 5px;

            overflow: hidden;

            border: 1px solid #d9c7aa;
            border-radius: 5px;

            background: #eee3cf;
        }

        .ordered-book-cover img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;
            object-position: center;

            border-radius: 2px;
        }

        /* ---------- Informasi buku ---------- */

        .ordered-book-category {
            display: block;
            margin-bottom: 7px;

            color: #a27a3e;

            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .ordered-book-info h3 {
            margin: 0 0 7px;

            color: #39281f;

            font-family: Georgia, "Times New Roman", serif;
            font-size: 18px;
            font-weight: 500;
            line-height: 1.35;
        }

        .ordered-book-info p {
            margin: 0;

            color: #817160;

            font-size: 12px;
            font-style: italic;
        }

        /* ---------- Kolom angka ---------- */

        .book-stat {
            padding-left: 18px;

            border-left: 1px solid #eadfce;
        }

        .book-stat-label {
            display: block;
            margin-bottom: 7px;

            color: #9c8b77;

            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .book-stat-value {
            color: #39281f;

            font-size: 13px;
            font-weight: 700;
        }

        .book-subtotal .book-stat-value {
            color: #6c2826;

            font-family: Georgia, "Times New Roman", serif;
            font-size: 17px;
        }

        /* ================================================================
           EMPTY STATE
           ================================================================ */

        .order-empty {
            padding: 45px 20px;

            border: 1px dashed #d7c5a6;
            border-radius: 10px;

            color: #8c7964;

            text-align: center;
        }

        /* ================================================================
           FOOTER ACTION
           ================================================================ */

        .order-footer-action {
            display: flex;
            justify-content: flex-end;

            margin-top: 22px;
        }

        .order-primary-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            min-height: 44px;
            padding: 0 21px;

            border-radius: 8px;

            background: #682a28;
            color: #fff9ef;

            font-size: 12px;
            font-weight: 700;
            text-decoration: none;

            box-shadow: 0 7px 18px rgba(104, 42, 40, .15);

            transition:
                transform .25s ease,
                background .25s ease,
                box-shadow .25s ease;
        }

        .order-primary-back:hover {
            transform: translateY(-2px);

            background: #54201f;

            box-shadow: 0 10px 23px rgba(104, 42, 40, .22);
        }

        /* ================================================================
           ANIMATION
           ================================================================ */

        @keyframes orderFadeUp {

            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-detail-header {
            animation: orderFadeUp .45s ease both;
        }

        .order-information {
            animation: orderFadeUp .5s .05s ease both;
        }

        .ordered-books-section {
            animation: orderFadeUp .55s .10s ease both;
        }

        /* ================================================================
           RESPONSIVE
           ================================================================ */

        @media (max-width: 1200px) {

            .order-info-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .order-info-item {
                border-bottom: 1px solid #eadfce;
            }

            .ordered-book {
                grid-template-columns: 82px minmax(200px, 1fr) 110px 90px 130px;
                gap: 15px;
            }

            .ordered-book-cover {
                width: 82px;
                height: 110px;
            }
        }

        @media (max-width: 900px) {

            .order-detail-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .ordered-book {
                grid-template-columns: 75px 1fr;
            }

            .ordered-book-cover {
                width: 75px;
                height: 100px;

                grid-row: span 2;
            }

            .book-stat {
                padding: 12px 0 0;

                border-top: 1px solid #eadfce;
                border-left: none;
            }

            .ordered-book {
                align-items: start;
            }
        }

        @media (max-width: 600px) {

            .order-detail-page {
                padding-bottom: 25px;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
            }

            .order-info-item {
                min-height: auto;

                padding: 17px 20px;

                border-right: none;
            }

            .order-information-top,
            .ordered-books-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .ordered-books-section {
                padding: 16px;
            }

            .ordered-book {
                grid-template-columns: 64px 1fr;
                padding: 14px;
            }

            .ordered-book-cover {
                width: 64px;
                height: 88px;
            }

            .ordered-book-info h3 {
                font-size: 16px;
            }

            .order-footer-action {
                justify-content: stretch;
            }

            .order-primary-back {
                width: 100%;
            }
        }

    </style>

</head>

<body>

<?php include "../template/sidebar.php"; ?>

<div class="content order-detail-page">

    <!-- ================================================================
         PAGE HEADER
         ================================================================ -->

    <div class="order-detail-header">

        <div class="order-detail-heading">

            <span class="order-eyebrow">
                Manajemen Pesanan
            </span>

            <h1>
                Detail Pesanan #<?= $id_pesanan; ?>
            </h1>

            <p>
                Informasi transaksi, status pembayaran, dan daftar buku
                yang terdapat dalam pesanan ini.
            </p>

        </div>

        <a href="pesanan.php" class="order-back-btn">
            <span>←</span>
            Kembali ke Pesanan
        </a>

    </div>


    <!-- ================================================================
         INFORMASI PESANAN
         ================================================================ -->

    <section class="order-information">

        <div class="order-information-top">

            <h2>Informasi Pesanan</h2>

            <span class="order-number">
                Order #<?= $id_pesanan; ?>
            </span>

        </div>

        <div class="order-info-grid">

            <!-- Pembeli -->

            <div class="order-info-item">

                <span class="order-info-label">
                    Nama Pembeli
                </span>

                <span class="order-info-value">
                    <?= e($pesanan['nama']); ?>
                </span>

            </div>


            <!-- Tanggal -->

            <div class="order-info-item">

                <span class="order-info-label">
                    Tanggal Pesanan
                </span>

                <span class="order-info-value">
                    <?= e($pesanan['tanggal']); ?>
                </span>

            </div>


            <!-- Metode -->

            <div class="order-info-item">

                <span class="order-info-label">
                    Metode Pembayaran
                </span>

                <span class="order-info-value">
                    <?= e($pesanan['metode_pembayaran']); ?>
                </span>

            </div>


            <!-- Pembayaran -->

            <div class="order-info-item">

                <span class="order-info-label">
                    Status Pembayaran
                </span>

                <span
                    class="order-status <?= statusClass($pesanan['status_pembayaran']); ?>"
                >
                    <?= e($pesanan['status_pembayaran']); ?>
                </span>

            </div>


            <!-- Pesanan -->

            <div class="order-info-item">

                <span class="order-info-label">
                    Status Pesanan
                </span>

                <span
                    class="order-status <?= statusClass($pesanan['status']); ?>"
                >
                    <?= e($pesanan['status']); ?>
                </span>

            </div>

        </div>

    </section>


    <!-- ================================================================
         DAFTAR BUKU
         ================================================================ -->

    <section class="ordered-books-section">

        <div class="ordered-books-header">

            <div>

                <h2>Daftar Buku</h2>

                <p>
                    Buku yang dibeli dalam pesanan #<?= $id_pesanan; ?>.
                </p>

            </div>

        </div>


        <?php

        $ada_buku = false;

        while ($row = mysqli_fetch_assoc($detail)) {

            $ada_buku = true;

            $jumlah = (int) $row['jumlah'];

            $harga = (float) $row['harga'];

            $subtotal = $jumlah * $harga;

            $gambar = !empty($row['gambar'])
                ? "../images/buku/" . rawurlencode($row['gambar'])
                : "../images/no-image.png";

        ?>

            <article class="ordered-book">

                <!-- Cover -->

                <div class="ordered-book-cover">

                    <img
                        src="<?= e($gambar); ?>"
                        alt="<?= e($row['judul_buku']); ?>"
                        onerror="this.onerror=null; this.src='../images/no-image.png';"
                    >

                </div>


                <!-- Judul -->

                <div class="ordered-book-info">

                    <span class="ordered-book-category">
                        Buku Pesanan
                    </span>

                    <h3>
                        <?= e($row['judul_buku']); ?>
                    </h3>

                    <p>
                        <?= e($row['penulis']); ?>
                    </p>

                </div>


                <!-- Harga -->

                <div class="book-stat">

                    <span class="book-stat-label">
                        Harga Satuan
                    </span>

                    <span class="book-stat-value">
                        Rp <?= number_format($harga, 0, ',', '.'); ?>
                    </span>

                </div>


                <!-- Jumlah -->

                <div class="book-stat">

                    <span class="book-stat-label">
                        Jumlah
                    </span>

                    <span class="book-stat-value">
                        <?= $jumlah; ?> buku
                    </span>

                </div>


                <!-- Subtotal -->

                <div class="book-stat book-subtotal">

                    <span class="book-stat-label">
                        Subtotal
                    </span>

                    <span class="book-stat-value">
                        Rp <?= number_format($subtotal, 0, ',', '.'); ?>
                    </span>

                </div>

            </article>

        <?php } ?>


        <?php if (!$ada_buku) { ?>

            <div class="order-empty">

                Tidak ada buku pada pesanan ini.

            </div>

        <?php } ?>


        <div class="order-footer-action">

            <a
                href="pesanan.php"
                class="order-primary-back"
            >
                ← Kembali ke Daftar Pesanan
            </a>

        </div>

    </section>

</div>

<script src="../js/ui.js"></script>

</body>
</html>