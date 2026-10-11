<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("location:../login.php");
    exit;
}

$error = '';

if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $role = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';

    if ($nama === '' || $email === '' || $password === '' || $alamat === '') {
        $error = 'Semua data wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $cek = mysqli_prepare($conn, "SELECT id_user FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($cek, "s", $email);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {
            $error = 'Email sudah digunakan. Gunakan email lain.';
        } else {
            /* Login project ini masih menggunakan password biasa, jadi format lama dipertahankan agar kompatibel. */
            $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, alamat, role) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $password, $alamat, $role);

            if (mysqli_stmt_execute($stmt)) {
                header("location:user.php?status=tambah_sukses");
                exit;
            } else {
                $error = 'Pengguna gagal ditambahkan. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna | RUANG BACA</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .user-form-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:25px}.user-form-head h1{margin:0;color:#572624;font-size:clamp(36px,4vw,50px);line-height:1}.user-form-head p{margin:8px 0 0;color:#786f63;font:italic 14px/1.6 Georgia,serif}.user-form-back{display:inline-flex;align-items:center;gap:8px;padding:10px 15px;border:1px solid #d5c5aa;border-radius:8px;background:#fffaf1;color:#612b29;font-size:12px;font-weight:700}.user-form-card{max-width:760px;background:#fffdf8;border:1px solid #ddcfb9;border-radius:14px;box-shadow:0 12px 30px rgba(62,45,31,.07);overflow:hidden}.user-form-title{padding:20px 25px;border-bottom:1px solid #e7dccb;background:linear-gradient(90deg,#f4ead9,#fffdf8)}.user-form-title span{display:block;color:#9b7847;font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;margin-bottom:4px}.user-form-title h2{margin:0;color:#3d3028;font-size:23px;font-weight:600}.user-form-body{padding:26px}.user-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:19px}.user-field.full{grid-column:1/-1}.user-field label{display:block;margin-bottom:7px;color:#51463d;font-size:11px;font-weight:700;letter-spacing:.04em}.user-field input,.user-field select,.user-field textarea{width:100%;padding:12px 13px;border:1px solid #d5c7b1;border-radius:8px;background:#fffefa;color:#40372f;outline:none;transition:.2s}.user-field textarea{min-height:105px;resize:vertical}.user-field input:focus,.user-field select:focus,.user-field textarea:focus{border-color:#9f7447;box-shadow:0 0 0 3px rgba(159,116,71,.09)}.role-note{margin-top:7px;color:#8a7d6f;font-size:10px;line-height:1.5}.user-form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:25px;padding-top:20px;border-top:1px solid #eee4d5}.btn-user-cancel,.btn-user-save{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 18px;border-radius:8px;font-size:11px;font-weight:700;border:0;cursor:pointer}.btn-user-cancel{border:1px solid #d5c7b1;background:#fffaf1;color:#5d5045}.btn-user-save{background:#6b2d2c;color:#fff8ec;box-shadow:0 7px 17px rgba(107,45,44,.18)}.user-alert{max-width:760px;margin-bottom:16px;padding:12px 15px;border:1px solid #e0bdb6;border-radius:8px;background:#f8e9e5;color:#7b3834;font-size:12px}@media(max-width:700px){.user-form-head{align-items:flex-start;flex-direction:column}.user-form-grid{grid-template-columns:1fr}.user-field.full{grid-column:auto}.user-form-body{padding:20px}.user-form-actions{flex-direction:column}.btn-user-cancel,.btn-user-save{width:100%}}
    </style>
</head>
<body>
<?php include "../template/sidebar.php"; ?>
<div class="content">
    <div class="user-form-head">
        <div><h1>Tambah Pengguna</h1><p>Buat akun baru sebagai user atau administrator RUANG BACA.</p></div>
        <a class="user-form-back" href="user.php">← Kembali</a>
    </div>

    <?php if ($error !== '') { ?><div class="user-alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>

    <div class="user-form-card">
        <div class="user-form-title"><span>Manajemen Akun</span><h2>Informasi Pengguna</h2></div>
        <form method="POST" class="user-form-body" autocomplete="off">
            <div class="user-form-grid">
                <div class="user-field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="user-field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="nama@email.com" required>
                </div>
                <div class="user-field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <div class="user-field">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" placeholder="Ulangi password" required>
                </div>
                <div class="user-field">
                    <label>Role / Hak Akses</label>
                    <select name="role" required>
                        <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <div class="role-note">Admin dapat mengakses dashboard pengelolaan. User menggunakan halaman pelanggan.</div>
                </div>
                <div class="user-field full">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Masukkan alamat pengguna" required><?= htmlspecialchars($_POST['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
            </div>
            <div class="user-form-actions">
                <a href="user.php" class="btn-user-cancel">Batal</a>
                <button type="submit" name="simpan" class="btn-user-save">+ Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
<script src="../js/ui.js"></script>
</body>
</html>
