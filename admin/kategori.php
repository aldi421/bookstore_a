<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");
    exit;
}

$data = mysqli_query(
    $conn,

    "SELECT * FROM kategori"

);

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>

        Kategori Buku

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

            Kategori Buku

        </h1>

        <p>

            Kelola kategori buku pada aplikasi LENTERA

        </p>

        <br>

        <a href="tambah_kategori.php" class="btn">

            + Tambah Kategori

        </a>

        <br><br>

        <table width="100%">

            <tr>

                <th>

                    No

                </th>

                <th>

                    Nama Kategori

                </th>

                <th>

                    Aksi

                </th>

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

                        <?= h($row['nama_kategori']); ?>

                    </td>

                    <td>

                        <a

                            href="edit_kategori.php?id=<?= $row['id_kategori']; ?>"

                            class="action-edit">

                            Edit

                        </a>

                        <form method="POST" action="hapus_kategori.php" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            <?= csrf_input(); ?>
                            <input type="hidden" name="id" value="<?= (int) $row['id_kategori']; ?>">
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