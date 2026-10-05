<?php

session_start();

include "../config/koneksi.php";

// cek login admin

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");
    exit;
}

// mengambil data kategori

$kategori = mysqli_query(
    $conn,

    "SELECT * FROM kategori"

);

// proses simpan buku

if (isset($_POST['simpan'])) {

    $id_kategori = $_POST['id_kategori'];
    $judul       = $_POST['judul_buku'];
    $penulis     = $_POST['penulis'];
    $penerbit    = $_POST['penerbit'];
    $tahun       = $_POST['tahun'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $deskripsi   = $_POST['deskripsi'];

    // cek kategori

    if ($id_kategori == "") {

        echo "

        <script>

        alert('Silahkan pilih kategori buku');

        </script>

        ";
    } else {

        // upload gambar

        $gambar = $_FILES['gambar']['name'];

        $tmp = $_FILES['gambar']['tmp_name'];

        // folder penyimpanan

        $folder = "../images/buku/";

        // buat folder otomatis

        if (!is_dir($folder)) {

            mkdir($folder, 0777, true);
        }

        // pindahkan gambar

        move_uploaded_file(

            $tmp,

            $folder . $gambar

        );

        // simpan data ke database

        $query = mysqli_query(
            $conn,

            "INSERT INTO buku

        (

        id_kategori,

        judul_buku,

        penulis,

        penerbit,

        tahun,

        harga,

        stok,

        gambar,

        deskripsi

        )

VALUES

(

        '$id_kategori',

        '$judul',

        '$penulis',

        '$penerbit',

        '$tahun',

        '$harga',

        '$stok',

        '$gambar',

        '$deskripsi'

        )

        "
        );

        if ($query) {

            echo "

            <script>

            alert('Data buku berhasil ditambahkan');

            window.location='buku.php';

            </script>

            ";
        } else {

            echo "

            <script>

            alert('Gagal menyimpan data : " . mysqli_error($conn) . "');

            </script>

            ";
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

                <label>

                    Kategori Buku

                </label>

                <select name="id_kategori" required>

                    <option value="">

                        -- Pilih Kategori --

                    </option>

                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                        <option value="<?= $k['id_kategori']; ?>">

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