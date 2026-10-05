<?php
session_start();
include "../config/koneksi.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$id=(int)($_GET['id']??0); $status=trim((string)($_GET['status']??''));
$allowed=['Menunggu Pembayaran','Diproses','Dikirim','Selesai','Dibatalkan'];
if($id<=0 || !in_array($status,$allowed,true)){ header('Location: pesanan.php'); exit; }
$stmt=mysqli_prepare($conn,'UPDATE pesanan SET status=? WHERE id_pesanan=?');
mysqli_stmt_bind_param($stmt,'si',$status,$id); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
header('Location: pesanan.php'); exit;
