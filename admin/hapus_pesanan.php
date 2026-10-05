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

mysqli_begin_transaction($conn);
try {
    $detail = mysqli_prepare($conn, 'DELETE FROM detail_pesanan WHERE id_pesanan=?');
    mysqli_stmt_bind_param($detail, 'i', $id);
    if (!mysqli_stmt_execute($detail)) {
        throw new RuntimeException('Gagal menghapus detail pesanan.');
    }

    $order = mysqli_prepare($conn, 'DELETE FROM pesanan WHERE id_pesanan=?');
    mysqli_stmt_bind_param($order, 'i', $id);
    if (!mysqli_stmt_execute($order)) {
        throw new RuntimeException('Gagal menghapus pesanan.');
    }

    mysqli_commit($conn);
    header('Location: pesanan.php?status=hapus_sukses');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log('LENTERA delete order failed: ' . $e->getMessage());
    header('Location: pesanan.php?status=hapus_gagal');
}
exit;
