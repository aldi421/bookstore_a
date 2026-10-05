<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: user.php');
    exit;
}

verify_csrf_or_abort();
$id = (int) ($_POST['id'] ?? 0);
$id_login = (int) ($_SESSION['id_user'] ?? 0);

if ($id <= 0) {
    header('Location: user.php?status=tidak_ditemukan');
    exit;
}

if ($id === $id_login) {
    header('Location: user.php?status=self_delete');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT role FROM users WHERE id_user=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$user) {
    header('Location: user.php?status=tidak_ditemukan');
    exit;
}

if ($user['role'] === 'admin') {
    $cek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='admin'");
    $jumlahAdmin = (int) mysqli_fetch_assoc($cek)['total'];
    if ($jumlahAdmin <= 1) {
        header('Location: user.php?status=admin_terakhir');
        exit;
    }
}

$hapus = mysqli_prepare($conn, 'DELETE FROM users WHERE id_user=?');
mysqli_stmt_bind_param($hapus, 'i', $id);

if (mysqli_stmt_execute($hapus)) {
    header('Location: user.php?status=hapus_sukses');
} else {
    header('Location: user.php?status=masih_terpakai');
}
exit;
