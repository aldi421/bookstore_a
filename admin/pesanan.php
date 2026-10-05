<?php
session_start();
include "../config/koneksi.php";
require_once "../config/admin_helpers.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$data = admin_query($conn, "SELECT p.*, COALESCE(u.nama,'User tidak ditemukan') AS nama
                            FROM pesanan p LEFT JOIN users u ON u.id_user=p.id_user
                            ORDER BY p.id_pesanan DESC");
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pesanan | LENTERA</title><link rel="icon" type="image/svg+xml" href="../images/favicon.svg"><link rel="stylesheet" href="../css/style.css"></head><body>
<?php include "../template/sidebar.php"; ?><div class="content"><span class="section-kicker">TRANSAKSI</span><h1>Data Pesanan</h1><p>Pesanan tetap tampil meskipun akun user terkait sudah tidak ditemukan.</p><br>
<div class="table-box"><table width="100%"><thead><tr><th>No</th><th>User</th><th>Tanggal</th><th>Total</th><th>Metode</th><th>Pembayaran</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php if($data && mysqli_num_rows($data)>0): $no=1; while($row=mysqli_fetch_assoc($data)): $paid=paid_status($row['status_pembayaran']); ?>
<tr><td><?= $no++ ?></td><td><?= h($row['nama']) ?></td><td><?= h($row['tanggal']) ?></td><td><?= h(rupiah($row['total'])) ?></td><td><?= h($row['metode_pembayaran'] ?: '-') ?></td><td><span class="<?= $paid?'badge-selesai':'badge-proses' ?>"><?= h($row['status_pembayaran'] ?: 'Belum Bayar') ?></span><?php if(!$paid): ?><br><br><a class="btn-kirim" href="konfirmasi_pembayaran.php?id=<?= (int)$row['id_pesanan'] ?>">Konfirmasi Bayar</a><?php endif; ?></td><td><span class="<?= h(order_status_class($row['status'])) ?>"><?= h($row['status'] ?: '-') ?></span></td><td><a class="btn-detail" href="detail_pesanan.php?id=<?= (int)$row['id_pesanan'] ?>">Detail</a> <?php if($paid && strtolower(trim((string)$row['status']))==='diproses'): ?><a class="btn-kirim" href="update_status.php?id=<?= (int)$row['id_pesanan'] ?>&status=Dikirim">Kirim</a><?php endif; ?> <?php if(strtolower(trim((string)$row['status']))==='dikirim'): ?><a class="btn-selesai" href="update_status.php?id=<?= (int)$row['id_pesanan'] ?>&status=Selesai">Selesai</a><?php endif; ?> <a class="btn-hapus" href="hapus_pesanan.php?id=<?= (int)$row['id_pesanan'] ?>" onclick="return confirm('Yakin hapus pesanan ini?')">Hapus</a></td></tr>
<?php endwhile; else: ?><tr><td colspan="8" style="text-align:center;padding:35px">Belum ada data pesanan pada database.</td></tr><?php endif; ?>
</tbody></table></div></div><script src="../js/ui.js"></script></body></html>
