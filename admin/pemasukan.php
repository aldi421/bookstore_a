<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

// total pemasukan

$total = mysqli_query(
    $conn,

    "
SELECT SUM(total) AS pemasukan

FROM pesanan

WHERE status_pembayaran='Sudah Bayar'

"

);

$data_total = mysqli_fetch_assoc($total);

$pemasukan = $data_total['pemasukan'] ?? 0;

// data transaksi

$data = mysqli_query(
    $conn,

    "
SELECT pesanan.*, users.nama

FROM pesanan

INNER JOIN users

ON pesanan.id_user = users.id_user

WHERE status_pembayaran='Sudah Bayar'

ORDER BY id_pesanan DESC

"

);

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Pemasukan

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>

            Laporan Pemasukan

        </h1>

        <p>

            Data transaksi pembayaran berhasil

        </p>

        <div class="dashboard-box">

            <div>

                <h3>

                    Total Pemasukan

                </h3>

                <h1>

                    Rp <?= number_format($pemasukan, 0, ',', '.'); ?>

                </h1>

            </div>

        </div>

        <br>

        <div class="table-box">

            <table width="100%">

                <tr>

                    <th>

                        No

                    </th>

                    <th>

                        Nama User

                    </th>

                    <th>

                        Tanggal

                    </th>

                    <th>

                        Metode Pembayaran

                    </th>

                    <th>

                        Total

                    </th>

                </tr>

                <?php

                $no = 1;

                while ($row = mysqli_fetch_assoc($data)) {

                ?>

                    <tr>

                        <td>

                            <?= $no++; ?>

                        </td>

                        <td>

                            <?= h($row['nama']); ?>

                        </td>

                        <td>

                            <?= h($row['tanggal']); ?>

                        </td>

                        <td>

                            <?= h($row['metode_pembayaran']); ?>

                        </td>

                        <td>

                            Rp <?= number_format($row['total'], 0, ',', '.'); ?>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>