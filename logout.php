<?php
session_start();
// Menghapus seluruh session
session_destroy();
// Mengarahkan kembali ke halaman login
header("location:login.php");
