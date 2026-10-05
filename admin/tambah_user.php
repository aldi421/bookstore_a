<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

$error = '';

if (isset($_POST['simpan'])) {
    verify_csrf_or_abort();

    $nama = trim((string) ($_POST['nama'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $alamat = trim((string) ($_POST['alamat'] ?? ''));
    $role = (string) ($_POST['role'] ?? 'user');

    if ($nama === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nama atau email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif (!in_array($role, ['admin', 'user'], true)) {
        $error = 'Role tidak valid.';
    } else {
        $check = mysqli_prepare($conn, 'SELECT id_user FROM users WHERE email=? LIMIT 1');
        mysqli_stmt_bind_param($check, 's', $email);
        mysqli_stmt_execute($check);

        if (mysqli_fetch_assoc(mysqli_stmt_get_result($check))) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, 'INSERT INTO users (nama,email,password,alamat,role) VALUES (?,?,?,?,?)');
            mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $hash, $alamat, $role);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: user.php?status=tambah_sukses');
                exit;
            }
            $error = 'Pengguna gagal ditambahkan.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../template/sidebar.php'; ?>
<div class="content">
    <h1>Tambah Pengguna</h1>
    <p>Buat akun user atau administrator baru.</p>
    <div class="form-admin">
        <?php if ($error !== '') { ?><div class="card" style="margin-bottom:16px;"><?= h($error); ?></div><?php } ?>
        <form method="POST" autocomplete="off">
            <?= csrf_input(); ?>
            <label>Nama</label>
            <input type="text" name="nama" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" minlength="8" required>

            <label>Alamat</label>
            <textarea name="alamat"></textarea>

            <label>Role</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button class="btn" type="submit" name="simpan">Simpan Pengguna</button>
            <a class="btn" href="user.php">Kembali</a>
        </form>
    </div>
</div>
<script src="../js/ui.js"></script>
</body>
</html>
