<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: buku.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT * FROM buku WHERE id_buku=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$buku) {
    header('Location: buku.php');
    exit;
}

$kategori = mysqli_query($conn, 'SELECT * FROM kategori ORDER BY nama_kategori ASC');
$error = '';

if (isset($_POST['update'])) {
    verify_csrf_or_abort();

    $id_kategori = (int) ($_POST['id_kategori'] ?? 0);
    $judul = trim((string) ($_POST['judul_buku'] ?? ''));
    $penulis = trim((string) ($_POST['penulis'] ?? ''));
    $penerbit = trim((string) ($_POST['penerbit'] ?? ''));
    $tahun = (int) ($_POST['tahun'] ?? 0);
    $harga = (int) ($_POST['harga'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);
    $deskripsi = trim((string) ($_POST['deskripsi'] ?? ''));
    $gambar = (string) $buku['gambar'];
    $gambarBaru = null;

    if ($id_kategori <= 0 || $judul === '' || $penulis === '' || $harga < 0 || $stok < 0) {
        $error = 'Data buku belum valid.';
    } else {
        try {
            if (isset($_FILES['gambar']) && ($_FILES['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $gambarBaru = upload_book_image($_FILES['gambar'], '../images/buku');
                $gambar = $gambarBaru;
            }

            $update = mysqli_prepare($conn, 'UPDATE buku SET id_kategori=?, judul_buku=?, penulis=?, penerbit=?, tahun=?, harga=?, stok=?, gambar=?, deskripsi=? WHERE id_buku=?');
            mysqli_stmt_bind_param($update, 'isssiiissi', $id_kategori, $judul, $penulis, $penerbit, $tahun, $harga, $stok, $gambar, $deskripsi, $id);

            if (!mysqli_stmt_execute($update)) {
                throw new RuntimeException('Gagal memperbarui data buku.');
            }

            if ($gambarBaru !== null && !empty($buku['gambar'])) {
                $old = '../images/buku/' . basename((string) $buku['gambar']);
                if (is_file($old)) @unlink($old);
            }

            header('Location: buku.php');
            exit;
        } catch (Throwable $e) {
            if ($gambarBaru !== null) {
                $newPath = '../images/buku/' . basename($gambarBaru);
                if (is_file($newPath)) @unlink($newPath);
            }
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>Edit Buku</title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>

            Edit Data Buku

        </h1>

        <div class="form-admin">

            <form method="POST" enctype="multipart/form-data">
                <?= csrf_input(); ?>

                <label>

                    Kategori

                </label>

                <select name="id_kategori">

                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                        <option

                            value="<?= $k['id_kategori']; ?>"

                            <?= ($k['id_kategori'] == $buku['id_kategori']) ? 'selected' : ''; ?>>

                            <?= h($k['nama_kategori']); ?>

                        </option>

                    <?php } ?>

                </select>

                <label>

                    Judul Buku

                </label>

                <input

                    type="text"

                    name="judul_buku"

                    value="<?= h($buku['judul_buku']); ?>">

                <label>

                    Penulis

                </label>

                <input

                    type="text"

                    name="penulis"

                    value="<?= h($buku['penulis']); ?>">

                <label>

                    Penerbit

                </label>

                <input

                    type="text"

                    name="penerbit"

                    value="<?= h($buku['penerbit']); ?>">

                <label>

                    Tahun

                </label>

                <input

                    type="number"

                    name="tahun"

                    value="<?= $buku['tahun']; ?>">

                <label>

                    Harga

                </label>

                <input

                    type="number"

                    name="harga"

                    value="<?= $buku['harga']; ?>">

                <label>

                    Stok

                </label>

                <input

                    type="number"

                    name="stok"

                    value="<?= $buku['stok']; ?>">

                <label>

                    Deskripsi

                </label>

                <textarea

                    name="deskripsi">

<?= h($buku['deskripsi']); ?>

</textarea>

                <label>

                    Gambar Lama

                </label>

                <br>

                <img src="../images/buku/<?= h($buku['gambar']); ?>"

                    width="100">

                <label>

                    Ganti Gambar

                </label>

                <input

                    type="file"

                    name="gambar">

                <button

                    class="btn"

                    name="update">

                    Update Buku

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>