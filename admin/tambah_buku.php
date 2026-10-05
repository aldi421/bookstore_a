<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

$kategori = mysqli_query($conn, 'SELECT * FROM kategori ORDER BY nama_kategori ASC');
$error = '';

if (isset($_POST['simpan'])) {
    verify_csrf_or_abort();

    $id_kategori = (int) ($_POST['id_kategori'] ?? 0);
    $judul = trim((string) ($_POST['judul_buku'] ?? ''));
    $penulis = trim((string) ($_POST['penulis'] ?? ''));
    $penerbit = trim((string) ($_POST['penerbit'] ?? ''));
    $tahun = (int) ($_POST['tahun'] ?? 0);
    $harga = (int) ($_POST['harga'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);
    $deskripsi = trim((string) ($_POST['deskripsi'] ?? ''));

    if ($id_kategori <= 0 || $judul === '' || $penulis === '' || $harga < 0 || $stok < 0) {
        $error = 'Data buku belum valid.';
    } elseif (!isset($_FILES['gambar']) || ($_FILES['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        $error = 'Cover buku wajib diunggah.';
    } else {
        $gambar = null;
        try {
            $gambar = upload_book_image($_FILES['gambar'], '../images/buku');
            $stmt = mysqli_prepare($conn, 'INSERT INTO buku (id_kategori,judul_buku,penulis,penerbit,tahun,harga,stok,gambar,deskripsi) VALUES (?,?,?,?,?,?,?,?,?)');
            mysqli_stmt_bind_param($stmt, 'isssiiiss', $id_kategori, $judul, $penulis, $penerbit, $tahun, $harga, $stok, $gambar, $deskripsi);
            if (!mysqli_stmt_execute($stmt)) {
                throw new RuntimeException('Gagal menyimpan buku.');
            }
            header('Location: buku.php');
            exit;
        } catch (Throwable $e) {
            if ($gambar) {
                $path = '../images/buku/' . basename($gambar);
                if (is_file($path)) @unlink($path);
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

    <title>

        Tambah Buku

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php

    include "../template/sidebar.php";

    ?>

    <div class="content">

        <h1>

            Tambah Data Buku

        </h1>

        <p>

            Tambahkan koleksi buku baru ke LENTERA

        </p>

        <div class="form-admin">

            <form method="POST" enctype="multipart/form-data">
                <?= csrf_input(); ?>

                <label>

                    Kategori Buku

                </label>

                <select name="id_kategori" required>

                    <option value="">

                        -- Pilih Kategori --

                    </option>

                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                        <option value="<?= $k['id_kategori']; ?>">

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

                    placeholder="Masukkan judul buku"

                    required>

                <label>

                    Penulis

                </label>

                <input

                    type="text"

                    name="penulis"

                    placeholder="Nama penulis"

                    required>

                <label>

                    Penerbit

                </label>

                <input

                    type="text"

                    name="penerbit"

                    placeholder="Nama penerbit"

                    required>

                <label>

                    Tahun Terbit

                </label>

                <input

                    type="number"

                    name="tahun"

                    placeholder="Contoh: 2025"

                    required>

                <label>

                    Harga Buku

                </label>

                <input

                    type="number"

                    name="harga"

                    placeholder="Contoh: 50000"

                    required>

                <label>

                    Stok Buku

                </label>

                <input

                    type="number"

                    name="stok"

                    placeholder="Jumlah stok"

                    required>

                <label>

                    Deskripsi Buku

                </label>

                <textarea

                    name="deskripsi"

                    placeholder="Masukkan deskripsi buku"

                    required></textarea>

                <label>

                    Gambar Buku

                </label>

                <input

                    type="file"

                    name="gambar"

                    required>

                <button

                    type="submit"

                    class="btn"

                    name="simpan">

                    Simpan Buku

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>