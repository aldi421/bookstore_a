<?php
session_start();
include "../config/koneksi.php";
require_once "../config/admin_helpers.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$total_buku     = admin_count($conn, 'buku');
$total_user     = admin_count($conn, 'users', "role='user'");
$total_pesanan  = admin_count($conn, 'pesanan');
$total_kategori = admin_count($conn, 'kategori');
$pemasukan      = admin_revenue($conn);

$recent = admin_query($conn, "SELECT p.id_pesanan,p.tanggal,p.total,p.status,p.status_pembayaran,
                                     COALESCE(u.nama,'User tidak ditemukan') AS nama
                              FROM pesanan p
                              LEFT JOIN users u ON u.id_user=p.id_user
                              ORDER BY p.id_pesanan DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard Admin | LENTERA</title>
<link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
<link rel="stylesheet" href="../css/style.css">
<style>
.admin-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-top:34px}.admin-summary .dashboard-box{min-width:0}.admin-money{grid-column:span 2}.admin-db-note{margin:22px 0 0;padding:14px 16px;border-left:3px solid #b08d57;background:#fff9ef;color:#675d51;font-size:12px}.admin-section-title{display:flex;justify-content:space-between;align-items:end;margin:42px 0 16px}.admin-section-title h2{margin:0;font-size:28px}.admin-empty{padding:34px;text-align:center;color:#7d746a}.admin-recent{overflow:auto;background:#fffdf8;border:1px solid #ddd0b8}.admin-recent table{width:100%;border-collapse:collapse}.admin-recent th{background:#17372c;color:#fff8ed;padding:13px;font-size:9px;letter-spacing:.1em;text-transform:uppercase}.admin-recent td{padding:13px;border-bottom:1px solid #eee3d4;font-size:12px}@media(max-width:1100px){.admin-summary{grid-template-columns:repeat(2,1fr)}.admin-money{grid-column:span 2}}@media(max-width:640px){.admin-summary{grid-template-columns:1fr}.admin-money{grid-column:auto}}
</style>
</head>
<body>
<?php include "../template/sidebar.php"; ?>
<div class="content">
    <span class="section-kicker">LENTERA ADMINISTRATOR</span>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <b><?= h($_SESSION['nama'] ?? 'Administrator') ?></b>. Ringkasan ini dibaca langsung dari database aktif.</p>

    <div class="admin-summary">
        <div class="dashboard-box"><div class="dashboard-icon">📚</div><div><h3>Total Buku</h3><h1><?= $total_buku ?></h1></div></div>
        <div class="dashboard-box"><div class="dashboard-icon">👥</div><div><h3>Pengguna</h3><h1><?= $total_user ?></h1></div></div>
        <div class="dashboard-box"><div class="dashboard-icon">🛒</div><div><h3>Pesanan</h3><h1><?= $total_pesanan ?></h1></div></div>
        <div class="dashboard-box"><div class="dashboard-icon">▤</div><div><h3>Kategori</h3><h1><?= $total_kategori ?></h1></div></div>
        <div class="dashboard-box admin-money"><div class="dashboard-icon">Rp</div><div><h3>Total Pemasukan Terbayar</h3><h1><?= h(rupiah($pemasukan)) ?></h1></div></div>
    </div>

    <?php if ($total_buku===0 && $total_user===0 && $total_pesanan===0): ?>
    <div class="admin-db-note"><b>Database berhasil terhubung, tetapi data utama masih kosong.</b> Jika kamu mengharapkan data contoh dari <code>bookstore.sql</code>, data tersebut harus benar-benar di-import ke database yang sedang dipakai.</div>
    <?php endif; ?>

    <div class="admin-section-title"><div><span class="section-kicker">AKTIVITAS TERBARU</span><h2>Pesanan Terakhir</h2></div><a class="btn" href="pesanan.php">Lihat Semua</a></div>
    <div class="admin-recent">
    <?php if ($recent && mysqli_num_rows($recent)>0): ?>
        <table><thead><tr><th>ID</th><th>User</th><th>Tanggal</th><th>Total</th><th>Pembayaran</th><th>Status</th></tr></thead><tbody>
        <?php while($row=mysqli_fetch_assoc($recent)): ?>
            <tr><td>#<?= (int)$row['id_pesanan'] ?></td><td><?= h($row['nama']) ?></td><td><?= h($row['tanggal']) ?></td><td><?= h(rupiah($row['total'])) ?></td><td><?= h($row['status_pembayaran'] ?: '-') ?></td><td><span class="<?= h(order_status_class($row['status'])) ?>"><?= h($row['status'] ?: '-') ?></span></td></tr>
        <?php endwhile; ?>
        </tbody></table>
    <?php else: ?>
        <div class="admin-empty">Belum ada pesanan di database.</div>
    <?php endif; ?>
    </div>
</div>
<script src="../js/ui.js"></script>
</body></html>
