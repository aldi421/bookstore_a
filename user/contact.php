<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

$id_user = $_SESSION['id_user'];

// proses kirim pesan

if (isset($_POST['kirim'])) {

    $judul = $_POST['judul_pesan'];

    $isi = $_POST['isi_pesan'];

    $tanggal = date('Y-m-d');

    $status = "Baru";

    $query = mysqli_query(
        $conn,

        "INSERT INTO pesan

    (

    id_user,

    judul_pesan,

    isi_pesan,

    tanggal,

    status

    )

VALUES

(

    '$id_user',

    '$judul',

    '$isi',

    '$tanggal',

    '$status'

    )

    "

    );

    if ($query) {

        echo "

        <script>

        alert('Pesan berhasil dikirim ke admin');

        window.location='contact.php';

        </script>

        ";
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>

        Contact Admin

    </title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <div class="card">

            <h1>

                Contact Admin

            </h1>

            <p>

                Silahkan kirim pertanyaan atau informasi kepada admin RUANG BACA.

            </p>

            <form method="POST">

                <label>

                    Judul Pesan

                </label>

                <input

                    type="text"

                    name="judul_pesan"

                    placeholder="Contoh: Tanya stok buku"

                    required>

                <br><br>

                <label>

                    Isi Pesan

                </label>

                <textarea

                    name="isi_pesan"

                    placeholder="Tuliskan pesan Anda..."

                    required>

</textarea>

                <br><br>

                <button

                    class="btn"

                    name="kirim">

                    Kirim Pesan

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>