<?php
session_start();
require '../config/koneksi.php';
require_role('user');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: keranjang.php');
    exit;
}

verify_csrf_or_abort();
$id = (int) ($_POST['id'] ?? 0);

if ($id > 0 && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    unset($_SESSION['cart'][$id]);
}

header('Location: keranjang.php');
exit;
