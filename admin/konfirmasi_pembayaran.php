<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pesanan.php');
    exit;
}

verify_csrf_or_abort();
$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: pesanan.php');
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE pesanan SET status_pembayaran='Sudah Bayar', status='Diproses' WHERE id_pesanan=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: pesanan.php');
exit;
