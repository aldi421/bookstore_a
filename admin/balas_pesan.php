<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

$id = $_GET['id'];

$data = mysqli_query(
    $conn,

    "SELECT pesan.*, users.nama

FROM pesan

INNER JOIN users

ON pesan.id_user = users.id_user

WHERE id_pesan='$id'

"
);

$row = mysqli_fetch_assoc($data);

if (isset($_POST['balas'])) {

    $balasan = $_POST['balasan_admin'];

    $status = "Dibalas";

    $query = mysqli_query(
        $conn,

        "UPDATE pesan SET

balasan_admin='$balasan',

status='$status'

WHERE id_pesan='$id'

"

    );

    if ($query) {

        echo "

<script>

alert('Balasan berhasil dikirim');

window.location='pesan.php';

</script>

";
    }
}

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>
        Balas Pesan
    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>

            Balas Pesan User

        </h1>

        <div class="card">

            <h3>

                User:

                <?= $row['nama']; ?>

            </h3>

            <p>

                <b>
                    Judul:
                </b>

                <?= $row['judul_pesan']; ?>

            </p>

            <p>

                <b>
                    Pesan:
                </b>

            </p>

            <p>

                <?= $row['isi_pesan']; ?>

            </p>

            <form method="POST">

                <label>

                    Balasan Admin

                </label>

                <textarea

                    name="balasan_admin"

                    required></textarea>

                <br><br>

                <button class="btn" name="balas">

                    Kirim Balasan

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>