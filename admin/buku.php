<?php

session_start();

include "../config/koneksi.php";

// cek admin

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

// mengambil data buku + kategori

$data = mysqli_query(
    $conn,

    "SELECT buku.*, kategori.nama_kategori

FROM buku

INNER JOIN kategori

ON buku.id_kategori = kategori.id_kategori

ORDER BY id_buku DESC"

);

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Data Buku

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

            Data Buku

        </h1>

        <p>

            Kelola seluruh koleksi buku pada LENTERA

        </p>

        <br>

        <a href="tambah_buku.php" class="btn">

            + Tambah Buku

        </a>

        <br><br>

        <table width="100%">

            <tr>

                <th>No</th>

                <th>Gambar</th>

                <th>Judul Buku</th>

                <th>Kategori</th>

                <th>Penulis</th>

                <th>Penerbit</th>

                <th>Harga</th>

                <th>Stok</th>

                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            while ($row = mysqli_fetch_assoc($data)) {

            ?>

                <tr>

                    <td>

                        <?= $no++; ?>

                    </td>

                    <td>

                        <img

                            src="../images/buku/<?= h($row['gambar']); ?>"

                            class="book-image">

                    </td>

                    <td>

                        <b>

                            <?= h($row['judul_buku']); ?>

                        </b>

                    </td>

                    <td>

                        <?= h($row['nama_kategori']); ?>

                    </td>

                    <td>

                        <?= h($row['penulis']); ?>

                    </td>

                    <td>

                        <?= h($row['penerbit']); ?>

                    </td>

                    <td>

                        <span class="price">

                            Rp <?= number_format($row['harga'], 0, ',', '.'); ?>

                        </span>

                    </td>

                    <td>

                        <span class="stock">

                            <?= $row['stok']; ?>

                        </span>

                    </td>

                    <td>

                        <a

                            href="edit_buku.php?id=<?= $row['id_buku']; ?>"

                            class="action-edit">

                            Edit

                        </a>

                        <form method="POST" action="hapus_buku.php" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                            <?= csrf_input(); ?>
                            <input type="hidden" name="id" value="<?= (int) $row['id_buku']; ?>">
                            <button type="submit" class="action-delete" style="border:0;cursor:pointer;">Hapus</button>
                        </form>

                    </td>

                </tr>

            <?php

            }

            ?>

        </table>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>