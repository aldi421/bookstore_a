<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

$data = mysqli_query(
    $conn,

    "
SELECT pesanan.*, users.nama

FROM pesanan

INNER JOIN users

ON pesanan.id_user = users.id_user

ORDER BY id_pesanan DESC

"

);

?>

<!DOCTYPE html>

<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Data Pesanan
    </title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>
            Data Pesanan
        </h1>

        <p>
            Kelola transaksi pembelian user
        </p>

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
                    Pembayaran
                </th>

                <th>
                    Status Pesanan
                </th>

                <th>
                    Aksi
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

                        <?= $row['nama']; ?>

                    </td>

                    <td>

                        <?= $row['tanggal']; ?>

                    </td>

                    <td>

                        <?= $row['metode_pembayaran']; ?>

                    </td>

                    <td>

                        <?php if ($row['status_pembayaran'] == "Belum Bayar") { ?>

                            <span class="badge-proses">

                                Belum Bayar

                            </span>

                            <br><br>

                            <a

                                class="btn-kirim"

                                href="konfirmasi_pembayaran.php?id=<?= $row['id_pesanan']; ?>">

                                Konfirmasi Bayar

                            </a>

                        <?php } else { ?>

                            <span class="badge-selesai">

                                Sudah Bayar

                            </span>

                        <?php } ?>

                    </td>

                    <td>

                        <?php if ($row['status'] == "Menunggu Pembayaran") { ?>

                            <span class="badge-proses">

                                Menunggu Pembayaran

                            </span>

                        <?php } elseif ($row['status'] == "Diproses") { ?>

                            <span class="badge-proses">

                                Diproses

                            </span>

                        <?php } elseif ($row['status'] == "Dikirim") { ?>

                            <span class="badge-kirim">

                                Dikirim

                            </span>

                        <?php } else { ?>

                            <span class="badge-selesai">

                                Selesai

                            </span>

                        <?php } ?>

                    </td>

                    <td>

                        <a

                            class="btn-detail"

                            href="detail_pesanan.php?id=<?= $row['id_pesanan']; ?>">

                            Detail

                        </a>

                        <?php if ($row['status_pembayaran'] == "Sudah Bayar" && $row['status'] == "Diproses") { ?>

                            <a

                                class="btn-kirim"

                                href="update_status.php?id=<?= $row['id_pesanan']; ?>&status=Dikirim">

                                Kirim

                            </a>

                        <?php } ?>

                        <?php if ($row['status'] == "Dikirim") { ?>

                            <a

                                class="btn-selesai"

                                href="update_status.php?id=<?= $row['id_pesanan']; ?>&status=Selesai">

                                Selesai

                            </a>

                        <?php } ?>

                        <a

                            class="btn-hapus"

                            href="hapus_pesanan.php?id=<?= $row['id_pesanan']; ?>">

                            Hapus

                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>