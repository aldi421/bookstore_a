<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "user") {

    header("location:../login.php");

    exit;
}

// ==========================================
// MEMBUAT SESSION CART JIKA BELUM ADA
// ==========================================

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {

    $_SESSION['cart'] = [];
}

// ==========================================
// MIGRASI FORMAT CART LAMA
// ==========================================

// Cart lama:
// [1, 2, 3]
//
// Cart baru:
// [
//     id_buku => jumlah
// ]

if (!empty($_SESSION['cart'])) {

    $keys = array_keys($_SESSION['cart']);

    $isSequential = (
        $keys === range(0, count($keys) - 1)
    );

    if ($isSequential) {

        $cartBaru = [];

        foreach ($_SESSION['cart'] as $idLama) {

            $idLama = (int) $idLama;

            if ($idLama > 0) {

                if (!isset($cartBaru[$idLama])) {

                    $cartBaru[$idLama] = 1;
                } else {

                    $cartBaru[$idLama]++;
                }
            }
        }

        $_SESSION['cart'] = $cartBaru;
    }
}

// ==========================================
// PROSES TOMBOL + DAN -
// ==========================================

if (
    isset($_GET['action'])
    &&
    isset($_GET['id'])
) {

    $action = $_GET['action'];

    $id = (int) $_GET['id'];

    if (
        $id > 0
        &&
        isset($_SESSION['cart'][$id])
    ) {

        $cekBuku = mysqli_query(

            $conn,

            "SELECT id_buku, stok
            FROM buku
            WHERE id_buku='$id'
            LIMIT 1"

        );

        $bukuCek = mysqli_fetch_assoc($cekBuku);

        if ($bukuCek) {

            $stok = (int) $bukuCek['stok'];

            // ==========================
            // TAMBAH JUMLAH
            // ==========================

            if ($action === 'plus') {

                if (
                    $_SESSION['cart'][$id] < $stok
                ) {

                    $_SESSION['cart'][$id]++;
                } else {

                    $_SESSION['cart_message']
                        =
                        "Jumlah buku sudah mencapai stok yang tersedia.";
                }
            }

            // ==========================
            // KURANGI JUMLAH
            // ==========================

            if ($action === 'minus') {

                $_SESSION['cart'][$id]--;

                // Jika jumlah 0
                // otomatis hapus dari cart

                if (
                    $_SESSION['cart'][$id] <= 0
                ) {

                    unset(
                        $_SESSION['cart'][$id]
                    );
                }
            }
        }
    }

    header("location:keranjang.php");

    exit;
}

// ==========================================
// TAMBAH BUKU DARI DETAIL BUKU
// ==========================================

// Link lama tetap bisa digunakan:
//
// keranjang.php?id=1
//
// Jika buku yang sama ditambahkan lagi,
// jumlah akan bertambah otomatis.

if (
    isset($_GET['id'])
    &&
    !isset($_GET['action'])
) {

    $id = (int) $_GET['id'];

    if ($id > 0) {

        $cekBuku = mysqli_query(

            $conn,

            "SELECT id_buku, stok
            FROM buku
            WHERE id_buku='$id'
            LIMIT 1"

        );

        $bukuCek = mysqli_fetch_assoc($cekBuku);

        if ($bukuCek) {

            $stok = (int) $bukuCek['stok'];

            if ($stok <= 0) {

                $_SESSION['cart_message']
                    =
                    "Maaf, stok buku sedang habis.";
            } else {

                // Jika belum ada di keranjang

                if (
                    !isset($_SESSION['cart'][$id])
                ) {

                    $_SESSION['cart'][$id] = 1;
                }

                // Jika sudah ada
                // jumlah ditambahkan

                elseif (
                    $_SESSION['cart'][$id] < $stok
                ) {

                    $_SESSION['cart'][$id]++;
                } else {

                    $_SESSION['cart_message']
                        =
                        "Jumlah buku sudah mencapai stok yang tersedia.";
                }
            }
        }
    }

    header("location:keranjang.php");

    exit;
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        Keranjang Belanja

    </title>

    <link
        rel="stylesheet"
        href="../css/style.css">

</head>

<body>

    <?php

    include "../template/navbar.php";

    ?>

    <div class="content-user">

        <h1>

            Keranjang Belanja

        </h1>

        <!-- ===============================
    PESAN STOK
    =============================== -->

        <?php if (
            isset($_SESSION['cart_message'])
        ) { ?>

            <div class="cart-alert">

                <?= htmlspecialchars(
                    $_SESSION['cart_message']
                ); ?>

            </div>

            <?php

            unset($_SESSION['cart_message']);

            ?>

        <?php } ?>

        <!-- ===============================
    TABEL KERANJANG
    =============================== -->

        <div class="cart-wrapper">

            <table class="cart-table">

                <thead>

                    <tr>

                        <th>

                            Judul Buku

                        </th>

                        <th>

                            Harga Satuan

                        </th>

                        <th>

                            Jumlah

                        </th>

                        <th>

                            Subtotal

                        </th>

                        <th>

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $total = 0;

                    if (
                        count($_SESSION['cart']) > 0
                    ) {

                        foreach (
                            $_SESSION['cart']
                            as
                            $id => $jumlah
                        ) {

                            $id = (int) $id;

                            $jumlah = (int) $jumlah;

                            $data = mysqli_query(

                                $conn,

                                "SELECT *
            FROM buku
            WHERE id_buku='$id'
            LIMIT 1"

                            );

                            $buku = mysqli_fetch_assoc($data);

                            // Jika buku sudah dihapus
                            // dari database

                            if (!$buku) {

                                unset(
                                    $_SESSION['cart'][$id]
                                );

                                continue;
                            }

                            $harga = (int) $buku['harga'];

                            // Harga x jumlah

                            $subtotal
                                =
                                $harga * $jumlah;

                            // Total semua buku

                            $total
                                +=
                                $subtotal;

                    ?>

                            <tr>

                                <!-- JUDUL -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $buku['judul_buku']
                                        ); ?>

                                    </strong>

                                    <div class="cart-stock">

                                        Stok tersedia:

                                        <?= (int) $buku['stok']; ?>

                                    </div>

                                </td>

                                <!-- HARGA SATUAN -->

                                <td>

                                    Rp <?= number_format(
                                            $harga,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                </td>

                                <!-- JUMLAH -->

                                <td>

                                    <div class="qty-control">

                                        <!-- KURANG -->

                                        <a
                                            href="keranjang.php?action=minus&id=<?= $id; ?>"
                                            class="qty-btn"
                                            title="Kurangi jumlah">

                                            −

                                        </a>

                                        <!-- JUMLAH -->

                                        <span class="qty-number">

                                            <?= $jumlah; ?>

                                        </span>

                                        <!-- TAMBAH -->

                                        <a
                                            href="keranjang.php?action=plus&id=<?= $id; ?>"
                                            class="qty-btn"
                                            title="Tambah jumlah">

                                            +

                                        </a>

                                    </div>

                                </td>

                                <!-- SUBTOTAL -->

                                <td>

                                    <strong>

                                        Rp <?= number_format(
                                                $subtotal,
                                                0,
                                                ',',
                                                '.'
                                            ); ?>

                                    </strong>

                                </td>

                                <!-- AKSI -->

                                <td>

                                    <a

                                        href="hapus_cart.php?id=<?= $id; ?>"

                                        class="action-delete"

                                        onclick="
                            return confirm(
                            'Hapus buku ini dari keranjang?'
                            )
                            ">

                                        Hapus

                                    </a>

                                </td>

                            </tr>

                        <?php

                        }
                    } else {

                        ?>

                        <tr>

                            <td
                                colspan="5"
                                class="empty-cart">

                                Keranjang masih kosong.

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    <?php

                    if (
                        count($_SESSION['cart']) > 0
                    ) {

                    ?>

                        <!-- =====================
                TOTAL BELANJA
                ====================== -->

                        <tr class="cart-total-row">

                            <td colspan="3">

                                <strong>

                                    Total Belanja

                                </strong>

                            </td>

                            <td colspan="2">

                                <strong class="cart-grand-total">

                                    Rp <?= number_format(
                                            $total,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                </strong>

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                </tbody>

            </table>

        </div>

        <!-- =================================
    BUTTON BAWAH
    ================================= -->

        <?php

        if (
            count($_SESSION['cart']) > 0
        ) {

        ?>

            <div class="cart-actions">

                <a
                    href="katalog.php"
                    class="btn btn-secondary-cart">

                    Lanjut Belanja

                </a>

                <a
                    href="checkout.php"
                    class="btn">

                    Checkout

                </a>

            </div>

        <?php

        } else {

        ?>

            <a
                href="katalog.php"
                class="btn">

                Lihat Katalog

            </a>

        <?php

        }

        ?>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>