<?php

include "../config/koneksi.php";

$id = $_GET['id'];

$query = mysqli_query(
    $conn,

    "UPDATE pesanan

SET

status_pembayaran='Sudah Bayar',

status='Diproses'

WHERE id_pesanan='$id'

"

);

if ($query) {

    echo "

<script>

alert('Pembayaran berhasil dikonfirmasi');

window.location='pesanan.php';

</script>

";
}
