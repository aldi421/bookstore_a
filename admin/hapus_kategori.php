<?php

session_start();

include "../config/koneksi.php";

if ($_SESSION['role'] != "admin") {

    header("location:../login.php");
}

$id = $_GET['id'];

$query = mysqli_query(
    $conn,

    "DELETE FROM kategori

WHERE id_kategori='$id'

"

);

if ($query) {

    echo "

<script>

alert('Kategori berhasil dihapus');

window.location='kategori.php';

</script>

";
}
