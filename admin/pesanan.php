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
    <meta charset="UTF-8">

    <title>
        Data Pesanan
    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
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

                        <?= h($row['nama']); ?>

                    </td>

                    <td>

                        <?= h($row['tanggal']); ?>

                    </td>

                    <td>

                        <?= h($row['metode_pembayaran']); ?>

                    </td>

                    <td>

                        <?php if ($row['status_pembayaran'] == "Belum Bayar") { ?>

                            <span class="badge-proses">

                                Belum Bayar

                            </span>

                            <br><br>

                            <form method="POST" action="konfirmasi_pembayaran.php" style="display:inline">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="id" value="<?= (int) $row['id_pesanan']; ?>">
                                <button type="submit" class="btn-kirim" style="border:0;cursor:pointer;">Konfirmasi Bayar</button>
                            </form>

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

                            <form method="POST" action="update_status.php" style="display:inline">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="id" value="<?= (int) $row['id_pesanan']; ?>">
                                <input type="hidden" name="status" value="Dikirim">
                                <button type="submit" class="btn-kirim" style="border:0;cursor:pointer;">Kirim</button>
                            </form>

                        <?php } ?>

                        <?php if ($row['status'] == "Dikirim") { ?>

                            <form method="POST" action="update_status.php" style="display:inline">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="id" value="<?= (int) $row['id_pesanan']; ?>">
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="btn-selesai" style="border:0;cursor:pointer;">Selesai</button>
                            </form>

                        <?php } ?>

                        <form method="POST" action="hapus_pesanan.php" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                            <?= csrf_input(); ?>
                            <input type="hidden" name="id" value="<?= (int) $row['id_pesanan']; ?>">
                            <button type="submit" class="btn-hapus" style="border:0;cursor:pointer;">Hapus</button>
                        </form>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>