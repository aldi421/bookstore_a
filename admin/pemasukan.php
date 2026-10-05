<?php
session_start();
include "../config/koneksi.php";
require_once "../config/admin_helpers.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$pemasukan=admin_revenue($conn);
$data=admin_query($conn, "SELECT p.*,COALESCE(u.nama,'User tidak ditemukan') AS nama
                          FROM pesanan p LEFT JOIN users u ON u.id_user=p.id_user
                          WHERE LOWER(TRIM(COALESCE(p.status_pembayaran,''))) IN ('sudah bayar','lunas','paid')
                          ORDER BY p.id_pesanan DESC");
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pemasukan | LENTERA</title><link rel="icon" type="image/svg+xml" href="../images/favicon.svg"><link rel="stylesheet" href="../css/style.css"></head><body>
<?php include "../template/sidebar.php"; ?><div class="content"><span class="section-kicker">LAPORAN</span><h1>Laporan Pemasukan</h1><p>Total hanya menghitung pesanan berstatus pembayaran Sudah Bayar/Lunas.</p>
<div class="dashboard-container"><div class="dashboard-box"><div class="dashboard-icon">Rp</div><div><h3>Total Pemasukan</h3><h1><?= h(rupiah($pemasukan)) ?></h1></div></div><div class="dashboard-box"><div class="dashboard-icon">✓</div><div><h3>Transaksi Terbayar</h3><h1><?= $data ? mysqli_num_rows($data) : 0 ?></h1></div></div></div><br>
<div class="table-box"><table width="100%"><thead><tr><th>No</th><th>User</th><th>Tanggal</th><th>Metode</th><th>Total</th></tr></thead><tbody>
<?php if($data && mysqli_num_rows($data)>0): $no=1; while($row=mysqli_fetch_assoc($data)): ?><tr><td><?= $no++ ?></td><td><?= h($row['nama']) ?></td><td><?= h($row['tanggal']) ?></td><td><?= h($row['metode_pembayaran'] ?: '-') ?></td><td><?= h(rupiah($row['total'])) ?></td></tr><?php endwhile; else: ?><tr><td colspan="5" style="text-align:center;padding:35px">Belum ada transaksi yang sudah dibayar.</td></tr><?php endif; ?>
</tbody></table></div></div><script src="../js/ui.js"></script></body></html>
