<?php

session_start();

if (
    !isset($_SESSION['role'])
    ||
    $_SESSION['role'] != "user"
) {

    header("location:../login.php");

    exit;
}

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if (
    $id > 0
    &&
    isset($_SESSION['cart'])
    &&
    is_array($_SESSION['cart'])
) {

    unset(
        $_SESSION['cart'][$id]
    );
}

header("location:keranjang.php");

exit;
