<?php
session_start();
include "../config/koneksi.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$id=(int)($_GET['id']??0);
if($id<=0){ header('Location: pesanan.php'); exit; }
$stmt=mysqli_prepare($conn,"UPDATE pesanan SET status_pembayaran='Sudah Bayar', status=CASE WHEN status IN ('Menunggu Pembayaran','') OR status IS NULL THEN 'Diproses' ELSE status END WHERE id_pesanan=?");
mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
header('Location: pesanan.php'); exit;
