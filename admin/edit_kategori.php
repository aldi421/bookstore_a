<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: kategori.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT id_kategori, nama_kategori FROM kategori WHERE id_kategori=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header('Location: kategori.php');
    exit;
}

$error = '';
if (isset($_POST['update'])) {
    verify_csrf_or_abort();
    $nama_kategori = trim((string) ($_POST['nama_kategori'] ?? ''));

    if ($nama_kategori === '') {
        $error = 'Nama kategori wajib diisi.';
    } else {
        $update = mysqli_prepare($conn, 'UPDATE kategori SET nama_kategori=? WHERE id_kategori=?');
        mysqli_stmt_bind_param($update, 'si', $nama_kategori, $id);
        if (mysqli_stmt_execute($update)) {
            header('Location: kategori.php');
            exit;
        }
        $error = 'Kategori gagal diperbarui.';
    }
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Edit Kategori

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

            Edit Kategori Buku

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

                    value="<?= h($row['nama_kategori']); ?>"

                    required>

                <button

                    class="btn"

                    name="update">

                    Update Data

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>