<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: buku.php');
    exit;
}

verify_csrf_or_abort();
$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: buku.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT gambar FROM buku WHERE id_buku=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$delete = mysqli_prepare($conn, 'DELETE FROM buku WHERE id_buku=?');
mysqli_stmt_bind_param($delete, 'i', $id);

if (mysqli_stmt_execute($delete)) {
    if ($buku && !empty($buku['gambar'])) {
        $path = '../images/buku/' . basename((string) $buku['gambar']);
        if (is_file($path)) {
            @unlink($path);
        }
    }
    header('Location: buku.php?status=hapus_sukses');
} else {
    header('Location: buku.php?status=hapus_gagal');
}
exit;
