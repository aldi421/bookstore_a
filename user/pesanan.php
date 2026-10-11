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

    "
SELECT * 
FROM pesanan
WHERE id_user='$id_user'
ORDER BY id_pesanan DESC
"

);

?>

<!DOCTYPE html>

<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Pesanan Saya
    </title>

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
                        <?= $row['tanggal']; ?>
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
                        <?= $row['metode_pembayaran']; ?>
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

                            <?= $row['status']; ?>

                        </span>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>

    </div>

<script src="../js/ui.js"></script>
<?php include "../template/footer.php"; ?>
</body>

</html>