<?php

session_start();

include "../config/koneksi.php";

// cek login admin

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("location:../login.php");
    exit;
}

// =======================
// HITUNG TOTAL DATA
// =======================

// total buku

$q_buku = mysqli_query(
    $conn,

    "SELECT COUNT(*) as total FROM buku"

);

$data_buku = mysqli_fetch_assoc($q_buku);

$total_buku = $data_buku['total'];

// total user

$q_user = mysqli_query(
    $conn,

    "SELECT COUNT(*) as total FROM users"

);

$data_user = mysqli_fetch_assoc($q_user);

$total_user = $data_user['total'];

// total pesanan

$q_pesanan = mysqli_query(
    $conn,

    "SELECT COUNT(*) as total FROM pesanan"

);

$data_pesanan = mysqli_fetch_assoc($q_pesanan);

$total_pesanan = $data_pesanan['total'];

?>

<!DOCTYPE html>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <title>
        Dashboard Admin
    </title>

    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include "../template/sidebar.php"; ?>

    <div class="content">

        <h1>
            Dashboard Admin
        </h1>

        <p>
            Selamat datang, <b>Administrator</b> 👋
        </p>

        <div class="dashboard-container">

            <div class="dashboard-box">

                <div class="dashboard-icon">
                    📚
                </div>

                <div>
                    <h3>
                        Total Buku
                    </h3>

                    <h1>
                        <?= $total_buku; ?>
                    </h1>

                </div>

            </div>

            <div class="dashboard-box">

                <div class="dashboard-icon">
                    👥
                </div>

                <div>

                    <h3>
                        Total User
                    </h3>

                    <h1>
                        <?= $total_user; ?>
                    </h1>

                </div>

            </div>

            <div class="dashboard-box">

                <div class="dashboard-icon">
                    🛒
                </div>

                <div>

                    <h3>
                        Pesanan
                    </h3>

                    <h1>
                        <?= $total_pesanan; ?>
                    </h1>

                </div>

            </div>

        </div>

<script src="../js/ui.js"></script>
</body>

</html>