<?php
// Helper khusus halaman admin. Tidak menyimpan credential dan tidak mengubah koneksi database.
if (!isset($conn) || !($conn instanceof mysqli)) {
    throw new RuntimeException('Koneksi database belum tersedia.');
}

if (!function_exists('h')) {
    function h($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('admin_query')) {
    function admin_query(mysqli $conn, string $sql): mysqli_result|bool {
        $result = mysqli_query($conn, $sql);
        if ($result === false) {
            error_log('[LENTERA ADMIN SQL] ' . mysqli_error($conn) . ' | SQL: ' . $sql);
        }
        return $result;
    }
}

if (!function_exists('admin_count')) {
    function admin_count(mysqli $conn, string $table, string $where = '1=1'): int {
        $allowed = ['buku','users','pesanan','kategori','pesan','detail_pesanan','keranjang'];
        if (!in_array($table, $allowed, true)) return 0;
        $q = admin_query($conn, "SELECT COUNT(*) AS total FROM `$table` WHERE $where");
        if (!$q) return 0;
        $row = mysqli_fetch_assoc($q);
        return (int)($row['total'] ?? 0);
    }
}

if (!function_exists('admin_revenue')) {
    function admin_revenue(mysqli $conn): int {
        $sql = "SELECT COALESCE(SUM(total),0) AS total FROM pesanan
                WHERE LOWER(TRIM(COALESCE(status_pembayaran,''))) IN ('sudah bayar','lunas','paid')";
        $q = admin_query($conn, $sql);
        if (!$q) return 0;
        $row = mysqli_fetch_assoc($q);
        return (int)($row['total'] ?? 0);
    }
}

if (!function_exists('rupiah')) {
    function rupiah($value): string {
        return 'Rp ' . number_format((float)$value, 0, ',', '.');
    }
}

if (!function_exists('paid_status')) {
    function paid_status($value): bool {
        return in_array(strtolower(trim((string)$value)), ['sudah bayar','lunas','paid'], true);
    }
}

if (!function_exists('order_status_class')) {
    function order_status_class($value): string {
        $status = strtolower(trim((string)$value));
        if (in_array($status, ['selesai','completed'], true)) return 'badge-selesai';
        if (in_array($status, ['dikirim','shipped'], true)) return 'badge-kirim';
        return 'badge-proses';
    }
}
?>
