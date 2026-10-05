<?php

session_start();

include "../config/koneksi.php";

if (
    !isset($_SESSION['role'])
    ||
    $_SESSION['role'] != "user"
) {

    header("location:../login.php");

    exit;
}

// ==========================================
// CEK KERANJANG
// ==========================================

if (
    !isset($_SESSION['cart'])
    ||
    !is_array($_SESSION['cart'])
    ||
    count($_SESSION['cart']) == 0
) {

    echo "

    <script>

        alert('Keranjang masih kosong');

        window.location='katalog.php';

    </script>

    ";

    exit;
}

// ==========================================
// MIGRASI CART LAMA
// ==========================================

$keys = array_keys(
    $_SESSION['cart']
);

if (
    !empty($keys)
    &&
    $keys === range(
        0,
        count($keys) - 1
    )
) {

    $cartBaru = [];

    foreach (
        $_SESSION['cart']
        as
        $idLama
    ) {

        $idLama = (int) $idLama;

        if ($idLama > 0) {

            if (
                !isset($cartBaru[$idLama])
            ) {

                $cartBaru[$idLama] = 1;
            } else {

                $cartBaru[$idLama]++;
            }
        }
    }

    $_SESSION['cart'] = $cartBaru;
}

$id_user
    =
    (int) $_SESSION['id_user'];

// ==========================================
// HITUNG TOTAL DAN VALIDASI STOK
// ==========================================

$total_checkout = 0;

$checkout_items = [];

foreach (
    $_SESSION['cart']
    as
    $id_buku => $jumlah
) {

    $id_buku = (int) $id_buku;

    $jumlah = (int) $jumlah;

    if (
        $id_buku <= 0
        ||
        $jumlah <= 0
    ) {

        continue;
    }

    $data_buku = mysqli_query(

        $conn,

        "SELECT
            id_buku,
            judul_buku,
            harga,
            stok
        FROM buku
        WHERE id_buku='$id_buku'
        LIMIT 1"

    );

    $buku = mysqli_fetch_assoc(
        $data_buku
    );

    if (!$buku) {

        unset(
            $_SESSION['cart'][$id_buku]
        );

        continue;
    }

    $stok
        =
        (int) $buku['stok'];

    // =====================================
    // CEK JUMLAH TIDAK BOLEH > STOK
    // =====================================

    if (
        $jumlah > $stok
    ) {

        echo "

        <script>

            alert(
            'Jumlah buku "
            .
            addslashes(
                $buku['judul_buku']
            )
            .
            " melebihi stok yang tersedia. Silakan periksa keranjang.'
            );

            window.location='keranjang.php';

        </script>

        ";

        exit;
    }

    $harga
        =
        (int) $buku['harga'];

    $subtotal
        =
        $harga * $jumlah;

    $total_checkout
        +=
        $subtotal;

    $checkout_items[] = [

        'id_buku'
        =>
        $id_buku,

        'judul_buku'
        =>
        $buku['judul_buku'],

        'harga'
        =>
        $harga,

        'jumlah'
        =>
        $jumlah,

        'subtotal'
        =>
        $subtotal

    ];
}

// Jika semua barang ternyata invalid

if (
    count($checkout_items) == 0
) {

    echo "

    <script>

        alert('Keranjang masih kosong');

        window.location='katalog.php';

    </script>

    ";

    exit;
}

// ==========================================
// PROSES CHECKOUT
// ==========================================

if (
    isset($_POST['checkout'])
) {

    $metode_pembayaran
        =
        isset($_POST['metode_pembayaran'])

        ? trim(
            $_POST['metode_pembayaran']
        )

        : '';

    // Metode pembayaran yang diperbolehkan

    $metodeDiizinkan = [

        'Transfer Bank',

        'E-Wallet',

        'COD'

    ];

    if (
        !in_array(
            $metode_pembayaran,
            $metodeDiizinkan,
            true
        )
    ) {

        echo "

        <script>

            alert(
                'Metode pembayaran tidak valid'
            );

            window.location='checkout.php';

        </script>

        ";

        exit;
    }

    $tanggal
        =
        date('Y-m-d');

    $status
        =
        "Menunggu Pembayaran";

    $status_pembayaran
        =
        "Belum Bayar";

    // Escape data

    $metode_pembayaran_db
        =
        mysqli_real_escape_string(
            $conn,
            $metode_pembayaran
        );

    $status_db
        =
        mysqli_real_escape_string(
            $conn,
            $status
        );

    $status_pembayaran_db
        =
        mysqli_real_escape_string(
            $conn,
            $status_pembayaran
        );

    // ======================================
    // DATABASE TRANSACTION
    // ======================================

    mysqli_begin_transaction(
        $conn
    );

    try {

        // ==================================
        // SIMPAN PESANAN UTAMA
        // ==================================

        $query = mysqli_query(

            $conn,

            "INSERT INTO pesanan

            (

                id_user,

                tanggal,

                total,

                status,

                metode_pembayaran,

                status_pembayaran

            )

            VALUES

            (

                '$id_user',

                '$tanggal',

                '$total_checkout',

                '$status_db',

                '$metode_pembayaran_db',

                '$status_pembayaran_db'

            )"

        );

        if (!$query) {

            throw new Exception(
                mysqli_error($conn)
            );
        }

        $id_pesanan
            =
            mysqli_insert_id(
                $conn
            );

        // ==================================
        // SIMPAN DETAIL PESANAN
        // ==================================

        foreach (
            $checkout_items
            as
            $item
        ) {

            $id_buku
                =
                (int) $item['id_buku'];

            $jumlah
                =
                (int) $item['jumlah'];

            $harga_item
                =
                (int) $item['harga'];

            $query_detail
                =
                mysqli_query(

                    $conn,

                    "INSERT INTO detail_pesanan

                (

                    id_pesanan,

                    id_buku,

                    jumlah,

                    harga

                )

                VALUES

                (

                    '$id_pesanan',

                    '$id_buku',

                    '$jumlah',

                    '$harga_item'

                )"

                );

            if (!$query_detail) {

                throw new Exception(
                    mysqli_error($conn)
                );
            }
        }

        // Jika semuanya berhasil

        mysqli_commit(
            $conn
        );

        // Kosongkan keranjang

        unset(
            $_SESSION['cart']
        );

        echo "

        <script>

            alert(
                'Pesanan berhasil dibuat, silahkan lakukan pembayaran'
            );

            window.location='pesanan.php';

        </script>

        ";

        exit;
    } catch (Exception $e) {

        // Batalkan jika ada query gagal

        mysqli_rollback(
            $conn
        );

        echo "

        <script>

            alert(
                'Checkout gagal. Silakan coba lagi.'
            );

            window.location='keranjang.php';

        </script>

        ";

        exit;
    }
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

        Checkout LENTERA

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

        <div class="card">

            <h1>

                Checkout Pesanan

            </h1>

            <p>

                Silahkan periksa pesanan dan pilih metode pembayaran.

            </p>

            <!-- ====================================
        RINGKASAN PESANAN
        ===================================== -->

            <div class="checkout-summary">

                <h3>

                    Ringkasan Pesanan

                </h3>

                <?php

                foreach (
                    $checkout_items
                    as
                    $item
                ) {

                ?>

                    <div class="checkout-item">

                        <div>

                            <strong>

                                <?= htmlspecialchars(
                                    $item['judul_buku']
                                ); ?>

                            </strong>

                            <small>

                                <?= $item['jumlah']; ?>

                                ×

                                Rp <?= number_format(
                                        $item['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                            </small>

                        </div>

                        <strong>

                            Rp <?= number_format(
                                    $item['subtotal'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                        </strong>

                    </div>

                <?php

                }

                ?>

                <!-- TOTAL -->

                <div class="checkout-total">

                    <span>

                        Total Belanja

                    </span>

                    <strong>

                        Rp <?= number_format(
                                $total_checkout,
                                0,
                                ',',
                                '.'
                            ); ?>

                    </strong>

                </div>

            </div>

            <!-- ====================================
        FORM CHECKOUT
        ===================================== -->

            <form method="POST">

                <label>

                    Metode Pembayaran

                </label>

                <select

                    name="metode_pembayaran"

                    required>

                    <option value="">

                        -- Pilih Pembayaran --

                    </option>

                    <option value="Transfer Bank">

                        Transfer Bank

                    </option>

                    <option value="E-Wallet">

                        E-Wallet

                    </option>

                    <option value="COD">

                        COD

                    </option>

                </select>

                <!-- INFORMASI PEMBAYARAN -->

                <div class="payment-info">

                    <h3>

                        Informasi Pembayaran

                    </h3>

                    <hr>

                    <h4>

                        Kirim Bukti Pembayaran

                    </h4>

                    <p>

                        Setelah melakukan pembayaran,
                        silahkan kirim bukti transfer
                        melalui WhatsApp Admin.

                    </p>

                    <p>

                        Nomor WhatsApp:

                    </p>

                    <h3>

                        📱 0857-6688-9900

                    </h3>

                    <p>

                        Cantumkan nama pemesan dan nomor
                        pesanan agar transaksi dapat segera
                        diproses.

                    </p>

                    <hr>

                    <h4>

                        Transfer Bank

                    </h4>

                    <p>

                        Bank BCA

                    </p>

                    <p>

                        No Rekening:

                        <b>

                            1234567890

                        </b>

                    </p>

                    <p>

                        Atas Nama:

                        <b>

                            LENTERA Indonesia

                        </b>

                    </p>

                    <hr>

                    <h4>

                        E-Wallet

                    </h4>

                    <p>

                        DANA / OVO

                    </p>

                    <p>

                        Nomor:

                        <b>

                            081234567890

                        </b>

                    </p>

                    <hr>

                    <h4>

                        COD (Cash On Delivery)

                    </h4>

                    <p>

                        Pembayaran dilakukan ketika buku
                        sudah diterima.

                    </p>

                </div>

                <br>

                <button

                    class="btn"

                    name="checkout"

                    type="submit">

                    Konfirmasi Pesanan

                </button>

            </form>

        </div>

    </div>

<script src="../js/ui.js"></script>
</body>

</html>