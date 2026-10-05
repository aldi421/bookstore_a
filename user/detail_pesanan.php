<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/helpers.php';
lentera_require_user();

$id_pesanan = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$id_user = (int) ($_SESSION['id_user'] ?? 0);

if ($id_pesanan <= 0 || $id_user <= 0) {
    header('Location: pesanan.php');
    exit;
}

$pesananQuery = mysqli_query(
    $conn,
    "SELECT id_pesanan, tanggal, total, metode_pembayaran, status_pembayaran, status
     FROM pesanan
     WHERE id_pesanan = $id_pesanan AND id_user = $id_user
     LIMIT 1"
);

$data_pesanan = $pesananQuery ? mysqli_fetch_assoc($pesananQuery) : null;
if (!$data_pesanan) {
    header('Location: pesanan.php');
    exit;
}

$detail = mysqli_query(
    $conn,
    "SELECT dp.id_detail, dp.id_buku, dp.jumlah,
            COALESCE(dp.harga, b.harga) AS harga_item,
            b.judul_buku, b.penulis, b.gambar
     FROM detail_pesanan dp
     INNER JOIN buku b ON dp.id_buku = b.id_buku
     WHERE dp.id_pesanan = $id_pesanan
     ORDER BY dp.id_detail ASC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $id_pesanan; ?> | LENTERA</title>
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css?v=20261005-userfix2">
</head>
<body>
<?php include '../template/navbar.php'; ?>

<main class="content-user">
    <span class="section-kicker">ORDER DETAIL</span>
    <h1>Detail Pesanan #<?= $id_pesanan; ?></h1>

    <div class="order-card">
        <p>Tanggal: <b><?= h($data_pesanan['tanggal']); ?></b></p>
        <p>Metode Pembayaran: <b><?= h($data_pesanan['metode_pembayaran'] ?: '-'); ?></b></p>
        <p>Status Pembayaran: <b><?= h($data_pesanan['status_pembayaran'] ?: '-'); ?></b></p>
        <p>Status Pesanan: <b><?= h($data_pesanan['status'] ?: '-'); ?></b></p>
        <p>Total: <b>Rp <?= number_format((int) $data_pesanan['total'], 0, ',', '.'); ?></b></p>

        <br>
        <h3>Daftar Buku</h3>

        <?php if (!$detail || mysqli_num_rows($detail) === 0) { ?>
            <div class="empty-cart">
                <p>Detail buku untuk pesanan ini belum tersedia.</p>
            </div>
        <?php } else { ?>
            <?php while ($row = mysqli_fetch_assoc($detail)) { ?>
                <div class="book-order">
                    <?php if (!empty($row['gambar'])) { ?>
                        <img src="../images/buku/<?= h($row['gambar']); ?>" alt="<?= h($row['judul_buku']); ?>">
                    <?php } ?>
                    <div>
                        <h3><?= h($row['judul_buku']); ?></h3>
                        <p>Penulis: <?= h($row['penulis']); ?></p>
                        <p>Jumlah: <?= (int) $row['jumlah']; ?></p>
                        <p>Harga: <b>Rp <?= number_format((int) $row['harga_item'], 0, ',', '.'); ?></b></p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <br>
        <a href="pesanan.php" class="btn">Kembali ke Pesanan</a>
    </div>
</main>

<script src="../js/ui.js?v=20261005-userfix2"></script>
</body>
</html>
