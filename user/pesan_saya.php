<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/helpers.php';
lentera_require_user();

$id_user = (int) ($_SESSION['id_user'] ?? 0);
$data = false;
$error = '';

if ($id_user <= 0) {
    $error = 'Sesi pengguna tidak valid. Silakan login ulang.';
} else {
    $data = mysqli_query(
        $conn,
        "SELECT id_pesan, judul_pesan, isi_pesan, balasan_admin, tanggal, status
         FROM pesan
         WHERE id_user = $id_user
         ORDER BY id_pesan DESC"
    );
    if (!$data) {
        $error = 'Riwayat pesan belum bisa dimuat. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Saya | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css?v=20261005-userfix2">
</head>
<body>
<?php include '../template/navbar.php'; ?>

<main class="content-user">
    <span class="section-kicker">MESSAGE JOURNAL</span>
    <h1>Pesan Saya</h1>
    <p>Riwayat pertanyaan dan balasan dari admin LENTERA.</p>

    <?php if (isset($_GET['sent']) && $_GET['sent'] === '1') { ?>
        <div class="cart-alert">Pesan berhasil dikirim ke admin.</div>
    <?php } ?>

    <p style="margin:20px 0"><a class="btn" href="contact.php">+ Kirim Pesan Baru</a></p>

    <?php if ($error !== '') { ?>
        <div class="cart-alert"><?= h($error); ?></div>
    <?php } elseif ($data && mysqli_num_rows($data) === 0) { ?>
        <div class="empty-cart">
            <h3>Belum ada pesan</h3>
            <p>Pesan yang kamu kirim ke admin akan muncul di sini.</p>
        </div>
    <?php } elseif ($data) { ?>
        <?php while ($row = mysqli_fetch_assoc($data)) { ?>
            <article class="message-card">
                <span class="section-kicker">MESSAGE #<?= (int) $row['id_pesan']; ?></span>
                <h3><?= h($row['judul_pesan']); ?></h3>

                <p><b>Pesan Saya:</b></p>
                <p><?= nl2br(h($row['isi_pesan'])); ?></p>

                <hr style="margin:22px 0;border:0;border-top:1px solid rgba(80,70,55,.14)">

                <p><b>Balasan Admin:</b></p>
                <?php if (trim((string) $row['balasan_admin']) === '') { ?>
                    <p><i>Belum ada balasan dari admin.</i></p>
                <?php } else { ?>
                    <p><?= nl2br(h($row['balasan_admin'])); ?></p>
                <?php } ?>

                <div class="message-footer" style="margin-top:20px">
                    Tanggal: <?= h($row['tanggal']); ?><br>
                    Status:
                    <?php if (($row['status'] ?? '') === 'Baru') { ?>
                        <span class="badge-proses">Baru</span>
                    <?php } else { ?>
                        <span class="badge-selesai"><?= h($row['status'] ?: 'Dibalas'); ?></span>
                    <?php } ?>
                </div>
            </article>
        <?php } ?>
    <?php } ?>
</main>

<script src="../js/ui.js?v=20261005-userfix2"></script>
</body>
</html>
