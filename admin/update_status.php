<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

$id = $_GET['id'];

$status = $_GET['status'];

$query = mysqli_query(
    $conn,

    "UPDATE pesanan SET

status='$status'

WHERE id_pesanan='$id'

"

);

if ($query) {

    echo "

<script>

alert('Status pesanan berhasil diperbarui');

window.location='pesanan.php';

</script>

";
} else {

    echo "

<script>

alert('Gagal memperbarui status');

window.location='pesanan.php';

</script>

";
}
