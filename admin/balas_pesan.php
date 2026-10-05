<?php
session_start();
require '../config/koneksi.php';
require_role('admin');

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: pesan.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT pesan.*, users.nama FROM pesan INNER JOIN users ON pesan.id_user=users.id_user WHERE id_pesan=? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header('Location: pesan.php');
    exit;
}

if (isset($_POST['balas'])) {
    verify_csrf_or_abort();
    $balasan = trim((string) ($_POST['balasan_admin'] ?? ''));
    if ($balasan !== '') {
        $status = 'Dibalas';
        $update = mysqli_prepare($conn, 'UPDATE pesan SET balasan_admin=?, status=? WHERE id_pesan=?');
        mysqli_stmt_bind_param($update, 'ssi', $balasan, $status, $id);
        if (mysqli_stmt_execute($update)) {
            header('Location: pesan.php');
            exit;
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
        Balas Pesan
    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>

            Balas Pesan User

        </h1>

        <div class="card">

            <h3>

                User:

                <?= h($row['nama']); ?>

            </h3>

            <p>

                <b>
                    Judul:
                </b>

                <?= h($row['judul_pesan']); ?>

            </p>

            <p>

                <b>
                    Pesan:
                </b>

            </p>

            <p>

                <?= h($row['isi_pesan']); ?>

            </p>

            <form method="POST">
                <?= csrf_input(); ?>

                <label>

                    Balasan Admin

                </label>

                <textarea

                    name="balasan_admin"

                    required></textarea>

                <br><br>

                <button class="btn" name="balas">

                    Kirim Balasan

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>