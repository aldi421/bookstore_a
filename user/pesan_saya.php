<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

$id_user = $_SESSION['id_user'];

$data = mysqli_query(
    $conn,

    "SELECT *

FROM pesan

WHERE id_user='$id_user'

ORDER BY id_pesan DESC"

);

?>

<!DOCTYPE html>

<html>

<head>

    <title>

        Pesan Saya

    </title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <h1>

            Pesan Saya

        </h1>

        <p>

            Riwayat pesan dan balasan dari admin

        </p>

        <?php while ($row = mysqli_fetch_assoc($data)) { ?>

            <div class="message-card">

                <h3>

                    <?= $row['judul_pesan']; ?>

                </h3>

                <p>

                    <b>

                        Pesan Saya:

                    </b>

                </p>

                <p>

                    <?= $row['isi_pesan']; ?>

                </p>

                <hr>

                <p>

                    <b>

                        Balasan Admin:

                    </b>

                </p>

                <?php if ($row['balasan_admin'] == "") { ?>

                    <p>

                        <i>

                            Belum ada balasan dari admin

                        </i>

                    </p>

                <?php } else { ?>

                    <p>

                        <?= $row['balasan_admin']; ?>

                    </p>

                <?php } ?>

                <div class="message-footer">

                    Tanggal:

                    <?= $row['tanggal']; ?>

                    <br>

                    Status:

                    <?php if ($row['status'] == "Baru") { ?>

                        <span class="badge-proses">

                            Baru

                        </span>

                    <?php } else { ?>

                        <span class="badge-selesai">

                            Dibalas

                        </span>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>