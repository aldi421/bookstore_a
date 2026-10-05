<?php

session_start();

include "../config/koneksi.php";

if ($_SESSION['role'] != "admin") {

    header("location:../login.php");
}

if (isset($_POST['simpan'])) {

    $nama_kategori = $_POST['nama_kategori'];

    $query = mysqli_query(
        $conn,

        "INSERT INTO kategori

(nama_kategori)

VALUES

('$nama_kategori')"

    );

    if ($query) {

        echo "

<script>

alert('Kategori berhasil ditambahkan');

window.location='kategori.php';

</script>

";
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