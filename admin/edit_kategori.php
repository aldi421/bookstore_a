<?php

session_start();

include "../config/koneksi.php";

if ($_SESSION['role'] != "admin") {

    header("location:../login.php");
}

// mengambil id dari URL

$id = $_GET['id'];

// mengambil data kategori

$data = mysqli_query(
    $conn,

    "SELECT * FROM kategori

WHERE id_kategori='$id'"

);

$row = mysqli_fetch_assoc($data);

// proses update

if (isset($_POST['update'])) {

    $nama_kategori = $_POST['nama_kategori'];

    $query = mysqli_query(
        $conn,

        "UPDATE kategori

SET nama_kategori='$nama_kategori'

WHERE id_kategori='$id'

"

    );

    if ($query) {

        echo "

<script>

alert('Kategori berhasil diperbarui');

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

                <label>

                    Nama Kategori

                </label>

                <input

                    type="text"

                    name="nama_kategori"

                    value="<?= $row['nama_kategori']; ?>"

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