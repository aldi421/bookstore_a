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
$status = trim((string) ($_POST['status'] ?? ''));
$allowed = ['Diproses', 'Dikirim', 'Selesai'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    header('Location: pesanan.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'UPDATE pesanan SET status=? WHERE id_pesanan=?');
mysqli_stmt_bind_param($stmt, 'si', $status, $id);
mysqli_stmt_execute($stmt);

header('Location: pesanan.php');
exit;
