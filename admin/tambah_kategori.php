<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

$error = '';
if (isset($_POST['simpan'])) {
    verify_csrf_or_abort();
    $nama_kategori = trim((string) ($_POST['nama_kategori'] ?? ''));

    if ($nama_kategori === '') {
        $error = 'Nama kategori wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO kategori (nama_kategori) VALUES (?)');
        mysqli_stmt_bind_param($stmt, 's', $nama_kategori);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: kategori.php');
            exit;
        }
        $error = 'Kategori gagal ditambahkan.';
    }
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>
        Tambah Kategori
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

            Tambah Kategori Buku

        </h1>

        <div class="form-admin">

            <form method="POST">
                <?= csrf_input(); ?>

                <label>

                    Nama Kategori

                </label>

                <input

                    type="text"

                    name="nama_kategori"

                    placeholder="Contoh: Novel"

                    required>

                <button

                    class="btn"

                    name="simpan">

                    Simpan Data

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>