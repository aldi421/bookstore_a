<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");

    exit;
}

$id = $_GET['id'];

// mengambil data buku

$data = mysqli_query(
    $conn,

    "SELECT * FROM buku

WHERE id_buku='$id'

"
);

$buku = mysqli_fetch_assoc($data);

// mengambil kategori

$kategori = mysqli_query(
    $conn,

    "SELECT * FROM kategori"

);

// proses update

if (isset($_POST['update'])) {

    $id_kategori = $_POST['id_kategori'];

    $judul = $_POST['judul_buku'];

    $penulis = $_POST['penulis'];

    $penerbit = $_POST['penerbit'];

    $tahun = $_POST['tahun'];

    $harga = $_POST['harga'];

    $stok = $_POST['stok'];

    $deskripsi = $_POST['deskripsi'];

    // cek apakah upload gambar baru

    if ($_FILES['gambar']['name'] != "") {

        $gambar = $_FILES['gambar']['name'];

        $tmp = $_FILES['gambar']['tmp_name'];

        $folder = "../images/buku/";

        move_uploaded_file(

            $tmp,

            $folder . $gambar

        );
    } else {

        $gambar = $buku['gambar'];
    }

    $query = mysqli_query(
        $conn,

        "UPDATE buku SET

id_kategori='$id_kategori',

judul_buku='$judul',

penulis='$penulis',

penerbit='$penerbit',

tahun='$tahun',

harga='$harga',

stok='$stok',

gambar='$gambar',

deskripsi='$deskripsi'

WHERE id_buku='$id'

"
    );

    if ($query) {

        echo "

<script>

alert('Data buku berhasil diperbarui');

window.location='buku.php';

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

                <label>

                    Kategori

                </label>

                <select name="id_kategori">

                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                        <option

                            value="<?= $k['id_kategori']; ?>"

                            <?= ($k['id_kategori'] == $buku['id_kategori']) ? 'selected' : ''; ?>>

                            <?= $k['nama_kategori']; ?>

                        </option>

                    <?php } ?>

                </select>

                <label>

                    Judul Buku

                </label>

                <input

                    type="text"

                    name="judul_buku"

                    value="<?= $buku['judul_buku']; ?>">

                <label>

                    Penulis

                </label>

                <input

                    type="text"

                    name="penulis"

                    value="<?= $buku['penulis']; ?>">

                <label>

                    Penerbit

                </label>

                <input

                    type="text"

                    name="penerbit"

                    value="<?= $buku['penerbit']; ?>">

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

<?= $buku['deskripsi']; ?>

</textarea>

                <label>

                    Gambar Lama

                </label>

                <br>

                <img src="../images/buku/<?= $buku['gambar']; ?>"

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