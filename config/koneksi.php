<?php

date_default_timezone_set('Asia/Jakarta');

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

require_once __DIR__ . '/helpers.php';

$configFile = __DIR__ . '/database.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Konfigurasi database belum dibuat. Salin config/database.example.php menjadi config/database.php lalu isi koneksi database.');
}

$db_config = require $configFile;

$requiredKeys = ['host', 'user', 'password', 'database'];
foreach ($requiredKeys as $key) {
    if (!array_key_exists($key, $db_config)) {
        http_response_code(500);
        exit('Konfigurasi database tidak lengkap.');
    }
}

$conn = @mysqli_connect(
    (string) $db_config['host'],
    (string) $db_config['user'],
    (string) $db_config['password'],
    (string) $db_config['database']
);

if (!$conn) {
    error_log('LENTERA database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Koneksi database gagal. Periksa konfigurasi database.');
}

mysqli_set_charset($conn, 'utf8mb4');
