<?php
session_start();
require '../config/koneksi.php';
require_role('user');

$id_user = (int) ($_SESSION['id_user'] ?? 0);
$stmt = mysqli_prepare($conn, 'SELECT * FROM pesanan WHERE id_user=? ORDER BY id_pesanan DESC');
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>
        Pesanan Saya
    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <h1>
            Pesanan Saya
        </h1>

        <p>
            Pantau status pesanan buku kamu
        </p>

        <?php while ($row = mysqli_fetch_assoc($data)) { ?>

            <div class="order-card">

                <h2>
                    Pesanan #<?= $row['id_pesanan']; ?>
                </h2>

                <p>
                    Tanggal :

                    <b>
                        <?= h($row['tanggal']); ?>
                    </b>

                </p>

                <p>
                    Total :

                    <b>
                        Rp <?= number_format($row['total'], 0, ',', '.'); ?>
                    </b>

                </p>

                <p>
                    Metode Pembayaran :

                    <b>
                        <?= h($row['metode_pembayaran']); ?>
                    </b>

                </p>

                <div class="order-status">

                    <p>
                        Status Pembayaran
                    </p>

                    <?php if ($row['status_pembayaran'] == "Sudah Bayar") { ?>

                        <span class="badge-selesai">

                            Sudah Bayar

                        </span>

                    <?php } else { ?>

                        <span class="badge-proses">

                            Belum Bayar

                        </span>

                    <?php } ?>

                </div>

                <div class="order-status">

                    <p>
                        Status Pesanan
                    </p>

                    <br>

                    <a href="detail_pesanan.php?id=<?= $row['id_pesanan']; ?>"
                        class="btn">

                        Lihat Detail

                    </a>

                    <?php if ($row['status'] == "Selesai") { ?>

                        <span class="badge-selesai">

                            Selesai

                        </span>

                    <?php } elseif ($row['status'] == "Dikirim") { ?>

                        <span class="badge-kirim">

                            Dikirim

                        </span>

                    <?php } else { ?>

                        <span class="badge-proses">

                            <?= h($row['status']); ?>

                        </span>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>