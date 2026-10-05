<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kategori.php');
    exit;
}

verify_csrf_or_abort();
$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: kategori.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM kategori WHERE id_kategori=?');
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: kategori.php?status=hapus_sukses');
} else {
    header('Location: kategori.php?status=masih_digunakan');
}
exit;
