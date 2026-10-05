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
    $sql = "SELECT id_pesanan, tanggal, total, metode_pembayaran, status_pembayaran, status
            FROM pesanan
            WHERE id_user = $id_user
            ORDER BY id_pesanan DESC";
    $data = mysqli_query($conn, $sql);
    if (!$data) {
        $error = 'Data pesanan belum bisa dimuat. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css?v=20261005-userfix2">
</head>
<body>
<?php include '../template/navbar.php'; ?>

<main class="content-user">
    <span class="section-kicker">ORDER JOURNAL</span>
    <h1>Pesanan Saya</h1>
    <p>Pantau status pembayaran dan perjalanan pesanan bukumu.</p>

    <?php if ($error !== '') { ?>
        <div class="cart-alert"><?= h($error); ?></div>
    <?php } elseif ($data && mysqli_num_rows($data) === 0) { ?>
        <div class="empty-cart">
            <h3>Belum ada pesanan</h3>
            <p>Pesanan yang sudah dibuat akan muncul di halaman ini.</p>
            <br>
            <a class="btn" href="katalog.php">Jelajahi Koleksi</a>
        </div>
    <?php } elseif ($data) { ?>
        <?php while ($row = mysqli_fetch_assoc($data)) { ?>
            <article class="order-card">
                <span class="section-kicker">ORDER #<?= (int) $row['id_pesanan']; ?></span>
                <h2>Pesanan #<?= (int) $row['id_pesanan']; ?></h2>
                <p>Tanggal: <b><?= h($row['tanggal']); ?></b></p>
                <p>Total: <b>Rp <?= number_format((int) $row['total'], 0, ',', '.'); ?></b></p>
                <p>Metode Pembayaran: <b><?= h($row['metode_pembayaran'] ?: '-'); ?></b></p>

                <div class="order-status">
                    <p>Status Pembayaran</p>
                    <?php if (($row['status_pembayaran'] ?? '') === 'Sudah Bayar') { ?>
                        <span class="badge-selesai">Sudah Bayar</span>
                    <?php } else { ?>
                        <span class="badge-proses"><?= h($row['status_pembayaran'] ?: 'Belum Bayar'); ?></span>
                    <?php } ?>
                </div>

                <div class="order-status">
                    <p>Status Pesanan</p>
                    <?php $status = (string) ($row['status'] ?? 'Diproses'); ?>
                    <?php if ($status === 'Selesai') { ?>
                        <span class="badge-selesai">Selesai</span>
                    <?php } elseif ($status === 'Dikirim') { ?>
                        <span class="badge-kirim">Dikirim</span>
                    <?php } else { ?>
                        <span class="badge-proses"><?= h($status); ?></span>
                    <?php } ?>
                    <br><br>
                    <a href="detail_pesanan.php?id=<?= (int) $row['id_pesanan']; ?>" class="btn">Lihat Detail</a>
                </div>
            </article>
        <?php } ?>
    <?php } ?>
</main>

<script src="../js/ui.js?v=20261005-userfix2"></script>
</body>
</html>
