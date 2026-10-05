<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("location:../login.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM users ORDER BY CASE WHEN role='admin' THEN 0 ELSE 1 END, id_user DESC");
$id_login = (int) ($_SESSION['id_user'] ?? 0);

$pesan = '';
$tipe = 'success';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'tambah_sukses': $pesan = 'Pengguna baru berhasil ditambahkan.'; break;
        case 'hapus_sukses': $pesan = 'Pengguna berhasil dihapus.'; break;
        case 'self_delete': $pesan = 'Akun admin yang sedang digunakan tidak dapat dihapus.'; $tipe = 'warning'; break;
        case 'admin_terakhir': $pesan = 'Admin terakhir tidak dapat dihapus. Tambahkan admin lain terlebih dahulu.'; $tipe = 'warning'; break;
        case 'masih_terpakai': $pesan = 'Akun tidak dapat dihapus karena masih terhubung dengan data pesanan atau keranjang.'; $tipe = 'warning'; break;
        case 'tidak_ditemukan': $pesan = 'Data pengguna tidak ditemukan.'; $tipe = 'warning'; break;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .users-head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:26px}.users-head h1{margin:0;color:#572624;font-size:clamp(38px,4vw,52px);line-height:1}.users-head p{margin:8px 0 0;color:#786f63;font:italic 14px/1.6 Georgia,serif}.add-user-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:43px;padding:0 18px;border-radius:8px;background:#682a28;color:#fff8ed;font-size:11px;font-weight:700;box-shadow:0 7px 18px rgba(104,42,40,.17);transition:.2s}.add-user-btn:hover{transform:translateY(-2px);background:#54201f}.users-alert{margin-bottom:18px;padding:12px 15px;border-radius:8px;font-size:12px;border:1px solid #cdd8c6;background:#edf2e9;color:#4b6546}.users-alert.warning{border-color:#dfcba6;background:#f8f0df;color:#805f2c}.users-table-wrap{padding:20px;background:#fffdf8;border:1px solid #ddcfb9;border-radius:14px;box-shadow:0 12px 30px rgba(62,45,31,.07);overflow-x:auto}.users-table-wrap table{margin:0!important}.role-pill{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:9px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.role-pill:before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor}.role-admin{background:#f0e3cf;color:#805a2c}.role-user{background:#eee7dc;color:#62584d}.current-account{display:block;margin-top:4px;color:#a17c48;font-size:9px;font-weight:700}.user-delete{display:inline-flex;align-items:center;justify-content:center;padding:7px 11px;border-radius:7px;background:#783936;color:#fff;font-size:10px;font-weight:700;transition:.2s}.user-delete:hover{background:#5f2927;transform:translateY(-1px)}.user-protected{display:inline-block;color:#998b7a;font-size:10px;font-style:italic}.users-name{font-weight:700;color:#392f29}.users-address{max-width:300px;color:#756b60}.users-id{font-size:10px;color:#9b8e80}@media(max-width:760px){.users-head{align-items:flex-start;flex-direction:column}.add-user-btn{width:100%}.users-table-wrap{padding:12px}}
    </style>
</head>
<body>
<?php include "../template/sidebar.php"; ?>
<div class="content">
    <div class="users-head">
        <div><h1>Pengguna</h1><p>Kelola akun user dan administrator LENTERA dalam satu halaman.</p></div>
        <a href="tambah_user.php" class="add-user-btn">＋ Tambah Pengguna</a>
    </div>

    <?php if ($pesan !== '') { ?><div class="users-alert <?= $tipe === 'warning' ? 'warning' : ''; ?>"><?= h($pesan); ?></div><?php } ?>

    <div class="users-table-wrap">
        <table width="100%">
            <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Alamat</th><th>Role</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><span class="users-id"><?= $no++; ?></span></td>
                    <td><span class="users-name"><?= h($row['nama']); ?></span><?php if ((int)$row['id_user'] === $id_login) { ?><span class="current-account">Akun Anda</span><?php } ?></td>
                    <td><?= h($row['email']); ?></td>
                    <td><div class="users-address"><?= h($row['alamat']); ?></div></td>
                    <td><span class="role-pill <?= $row['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>"><?= h($row['role']); ?></span></td>
                    <td>
                        <?php if ((int)$row['id_user'] === $id_login) { ?>
                            <span class="user-protected">Sedang digunakan</span>
                        <?php } else { ?>
                            <form method="POST" action="hapus_user.php" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="id" value="<?= (int) $row['id_user']; ?>">
                                <button type="submit" class="user-delete" style="border:0;cursor:pointer;">Hapus</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="../js/ui.js"></script>
</body>
</html>
