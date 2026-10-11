<?php

session_start();

include "../config/koneksi.php";

// cek admin

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

// mengambil id buku

$id = $_GET['id'];

// mengambil data gambar sebelum hapus

$data = mysqli_query(
    $conn,

    "SELECT gambar FROM buku

WHERE id_buku='$id'

"
);

$buku = mysqli_fetch_assoc($data);

// hapus file gambar

if ($buku['gambar'] != "") {

    $gambar = "../images/buku/" . $buku['gambar'];

    if (file_exists($gambar)) {

        unlink($gambar);
    }
}

// hapus data buku

$query = mysqli_query(
    $conn,

    "DELETE FROM buku

WHERE id_buku='$id'

"
);

if ($query) {

    echo "

<script>

alert('Data buku berhasil dihapus');

window.location='buku.php';

</script>

";
} else {

    echo "

<script>

alert('Data buku gagal dihapus');

window.location='buku.php';

</script>

";
}
