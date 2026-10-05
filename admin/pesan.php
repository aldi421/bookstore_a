<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

$data = mysqli_query(
    $conn,

    "SELECT pesan.*, users.nama

FROM pesan

INNER JOIN users

ON pesan.id_user = users.id_user

ORDER BY id_pesan DESC"

);

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Pesan User

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>

            Pesan User

        </h1>

        <p>

            Daftar pesan yang dikirim oleh pengguna

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

                    Judul Pesan

                </th>

                <th>

                    Isi Pesan

                </th>

                <th>

                    Tanggal

                </th>

                <th>

                    Status

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

                        <?= h($row['judul_pesan']); ?>

                    </td>

                    <td>

                        <?= h($row['isi_pesan']); ?>

                    </td>

                    <td>

                        <?= h($row['tanggal']); ?>

                    </td>

                    <td>

                        <?php if ($row['status'] == "Baru") { ?>

                            <span class="badge-proses">

                                Baru

                            </span>

                        <?php } else { ?>

                            <span class="badge-selesai">

                                Dibalas

                            </span>

                        <?php } ?>

                    </td>

                    <td>

                        <a

                            href="balas_pesan.php?id=<?= $row['id_pesan']; ?>"

                            class="btn-detail">

                            Balas

                        </a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>