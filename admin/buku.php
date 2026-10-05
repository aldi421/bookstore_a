<?php
session_start();
include "../config/koneksi.php";
require_once "../config/admin_helpers.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$data = admin_query($conn, "SELECT b.*, COALESCE(k.nama_kategori,'Tanpa Kategori') AS nama_kategori
                            FROM buku b LEFT JOIN kategori k ON k.id_kategori=b.id_kategori
                            ORDER BY b.id_buku DESC");
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Data Buku | LENTERA</title><link rel="icon" type="image/svg+xml" href="../images/favicon.svg"><link rel="stylesheet" href="../css/style.css"></head><body>
<?php include "../template/sidebar.php"; ?>
<div class="content">
<span class="section-kicker">MANAJEMEN KOLEKSI</span><h1>Data Buku</h1><p>Kelola seluruh koleksi buku pada LENTERA. Data di bawah dibaca langsung dari tabel <code>buku</code>.</p><br>
<a href="tambah_buku.php" class="btn">+ Tambah Buku</a><br><br>
<div class="table-box"><table width="100%"><thead><tr><th>No</th><th>Gambar</th><th>Judul Buku</th><th>Kategori</th><th>Penulis</th><th>Penerbit</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
<?php if($data && mysqli_num_rows($data)>0): $no=1; while($row=mysqli_fetch_assoc($data)): ?>
<tr><td><?= $no++ ?></td><td><?php if(!empty($row['gambar'])): ?><img src="../images/buku/<?= h($row['gambar']) ?>" class="book-image" alt=""><?php else: ?>—<?php endif; ?></td><td><b><?= h($row['judul_buku']) ?></b></td><td><?= h($row['nama_kategori']) ?></td><td><?= h($row['penulis']) ?></td><td><?= h($row['penerbit']) ?></td><td><span class="price"><?= h(rupiah($row['harga'])) ?></span></td><td><span class="stock"><?= (int)$row['stok'] ?></span></td><td><a href="edit_buku.php?id=<?= (int)$row['id_buku'] ?>" class="action-edit">Edit</a> <a href="hapus_buku.php?id=<?= (int)$row['id_buku'] ?>" class="action-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a></td></tr>
<?php endwhile; else: ?><tr><td colspan="9" style="text-align:center;padding:35px">Belum ada data buku. Jika seharusnya ada data contoh, cek apakah <b>bookstore.sql</b> sudah di-import ke database yang aktif.</td></tr><?php endif; ?>
</tbody></table></div></div><script src="../js/ui.js"></script></body></html>
