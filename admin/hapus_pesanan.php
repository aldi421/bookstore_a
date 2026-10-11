<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

$id = $_GET['id'];

// hapus detail pesanan dulu

$hapus_detail = mysqli_query(
    $conn,

    "DELETE FROM detail_pesanan 
WHERE id_pesanan='$id'"

);

// hapus pesanan

$hapus_pesanan = mysqli_query(
    $conn,

    "DELETE FROM pesanan 
WHERE id_pesanan='$id'"

);

if ($hapus_pesanan) {

    echo "

<script>

alert('Pesanan berhasil dihapus');

window.location='pesanan.php';

</script>

";
} else {

    echo "

<script>

alert('Pesanan gagal dihapus');

window.location='pesanan.php';

</script>

";
}
