<?php
session_start();
require '../config/koneksi.php';
require_role('user');

$id_user = (int) ($_SESSION['id_user'] ?? 0);
$error = '';

if (isset($_POST['kirim'])) {
    verify_csrf_or_abort();
    $judul = trim((string) ($_POST['judul_pesan'] ?? ''));
    $isi = trim((string) ($_POST['isi_pesan'] ?? ''));
    $tanggal = date('Y-m-d');
    $status = 'Baru';

    if ($judul === '' || $isi === '') {
        $error = 'Judul dan isi pesan wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO pesan (id_user,judul_pesan,isi_pesan,tanggal,status) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($stmt, 'issss', $id_user, $judul, $isi, $tanggal, $status);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: contact.php?status=terkirim');
            exit;
        }
        $error = 'Pesan gagal dikirim.';
    }
}
?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Contact Admin

    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/navbar.php"; ?>

    <div class="content-user">

        <div class="card">

            <h1>

                Contact Admin

            </h1>

            <p>

                Silahkan kirim pertanyaan atau informasi kepada admin LENTERA.

            </p>

            <form method="POST">
                <?= csrf_input(); ?>

                <label>

                    Judul Pesan

                </label>

                <input

                    type="text"

                    name="judul_pesan"

                    placeholder="Contoh: Tanya stok buku"

                    required>

                <br><br>

                <label>

                    Isi Pesan

                </label>

                <textarea

                    name="isi_pesan"

                    placeholder="Tuliskan pesan Anda..."

                    required>

</textarea>

                <br><br>

                <button

                    class="btn"

                    name="kirim">

                    Kirim Pesan

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>