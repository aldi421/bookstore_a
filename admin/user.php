<?php
session_start();
include "../config/koneksi.php";
require_once "../config/admin_helpers.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$data = admin_query($conn, "SELECT id_user,nama,email,alamat,role FROM users ORDER BY CASE WHEN role='admin' THEN 0 ELSE 1 END,id_user DESC");
$id_login=(int)($_SESSION['id_user']??0);
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pengguna | LENTERA</title><link rel="icon" type="image/svg+xml" href="../images/favicon.svg"><link rel="stylesheet" href="../css/style.css"></head><body>
<?php include "../template/sidebar.php"; ?><div class="content"><span class="section-kicker">MANAJEMEN AKUN</span><h1>Data Pengguna</h1><p>Semua akun yang tersimpan di tabel <code>users</code>.</p><br>
<div class="table-box"><table width="100%"><thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Alamat</th><th>Role</th><th>Aksi</th></tr></thead><tbody>
<?php if($data && mysqli_num_rows($data)>0): $no=1; while($row=mysqli_fetch_assoc($data)): ?>
<tr><td><?= $no++ ?></td><td><b><?= h($row['nama']) ?></b><?= (int)$row['id_user']===$id_login?' <small>(Akun Anda)</small>':'' ?></td><td><?= h($row['email']) ?></td><td><?= h($row['alamat']) ?></td><td><?= h($row['role']) ?></td><td><?php if((int)$row['id_user']===$id_login): ?>—<?php else: ?><a href="hapus_user.php?id=<?= (int)$row['id_user'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus pengguna ini?')">Hapus</a><?php endif; ?></td></tr>
<?php endwhile; else: ?><tr><td colspan="6" style="text-align:center;padding:35px">Tidak ada akun pada tabel users.</td></tr><?php endif; ?>
</tbody></table></div></div><script src="../js/ui.js"></script></body></html>
