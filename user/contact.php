<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/helpers.php';
lentera_require_user();

$id_user = (int) ($_SESSION['id_user'] ?? 0);
$error = '';

if (isset($_POST['kirim'])) {
    $judul = trim($_POST['judul_pesan'] ?? '');
    $isi = trim($_POST['isi_pesan'] ?? '');

    if ($id_user <= 0) {
        $error = 'Sesi pengguna tidak valid. Silakan login ulang.';
    } elseif ($judul === '' || $isi === '') {
        $error = 'Judul dan isi pesan wajib diisi.';
    } elseif (strlen($judul) > 100) {
        $error = 'Judul pesan maksimal 100 karakter.';
    } else {
        $tanggal = date('Y-m-d');
        $status = 'Baru';
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO pesan (id_user, judul_pesan, isi_pesan, tanggal, status) VALUES (?, ?, ?, ?, ?)'
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'issss', $id_user, $judul, $isi, $tanggal, $status);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: pesan_saya.php?sent=1');
                exit;
            }
            mysqli_stmt_close($stmt);
        }

        $error = 'Pesan belum berhasil dikirim. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pesan | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css?v=20261005-userfix2">
</head>
<body>
<?php include '../template/navbar.php'; ?>

<main class="content-user">
    <div class="card">
        <span class="section-kicker">READER SUPPORT</span>
        <h1>Hubungi Admin</h1>
        <p>Silakan kirim pertanyaan atau informasi kepada admin LENTERA.</p>

        <?php if ($error !== '') { ?>
            <div class="cart-alert"><?= h($error); ?></div>
            <br>
        <?php } ?>

        <form method="POST" action="contact.php">
            <label for="judul_pesan">Judul Pesan</label>
            <input id="judul_pesan" type="text" name="judul_pesan" maxlength="100" value="<?= h($_POST['judul_pesan'] ?? ''); ?>" placeholder="Contoh: Tanya stok buku" required>
            <br><br>

            <label for="isi_pesan">Isi Pesan</label>
            <textarea id="isi_pesan" name="isi_pesan" placeholder="Tuliskan pesan Anda..." required><?= h($_POST['isi_pesan'] ?? ''); ?></textarea>
            <br><br>

            <button class="btn" type="submit" name="kirim">Kirim Pesan</button>
            <a class="mini-link" href="pesan_saya.php" style="margin-left:14px">Lihat Pesan Saya →</a>
        </form>
    </div>
</main>

<script src="../js/ui.js?v=20261005-userfix2"></script>
</body>
</html>
