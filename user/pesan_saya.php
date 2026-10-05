<?php
session_start();
require '../config/koneksi.php';
require_role('user');

$id_user = (int) ($_SESSION['id_user'] ?? 0);
$stmt = mysqli_prepare($conn, 'SELECT * FROM pesan WHERE id_user=? ORDER BY id_pesan DESC');
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

        Pesan Saya

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
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

                    <?= h($row['judul_pesan']); ?>

                </h3>

                <p>

                    <b>

                        Pesan Saya:

                    </b>

                </p>

                <p>

                    <?= h($row['isi_pesan']); ?>

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

                        <?= h($row['balasan_admin']); ?>

                    </p>

                <?php } ?>

                <div class="message-footer">

                    Tanggal:

                    <?= h($row['tanggal']); ?>

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