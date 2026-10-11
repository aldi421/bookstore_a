<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("location:../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location:user.php");
    exit;
}

$id = (int) $_GET['id'];
$id_login = (int) ($_SESSION['id_user'] ?? 0);

if ($id <= 0) {
    header("location:user.php?status=tidak_ditemukan");
    exit;
}

/* Admin tidak boleh menghapus akun yang sedang dipakai. */
if ($id === $id_login) {
    header("location:user.php?status=self_delete");
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT role FROM users WHERE id_user = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("location:user.php?status=tidak_ditemukan");
    exit;
}

/* Minimal satu admin harus tetap ada. */
if ($user['role'] === 'admin') {
    $cek_admin = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='admin'");
    $jumlah_admin = (int) mysqli_fetch_assoc($cek_admin)['total'];

    if ($jumlah_admin <= 1) {
        header("location:user.php?status=admin_terakhir");
        exit;
    }
}

$hapus = mysqli_prepare($conn, "DELETE FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($hapus, "i", $id);

if (mysqli_stmt_execute($hapus)) {
    header("location:user.php?status=hapus_sukses");
} else {
    /* Biasanya terjadi jika akun masih terhubung ke pesanan/keranjang. */
    header("location:user.php?status=masih_terpakai");
}
exit;
