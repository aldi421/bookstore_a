<?php

// Konfigurasi LOCAL (Laragon).
// Jangan commit password database hosting ke GitHub.
$host = "localhost";
$user = "root";
$password = "";
$database = "bookstore";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if (!$conn) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
