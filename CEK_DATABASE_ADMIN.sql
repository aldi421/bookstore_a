-- LENTERA - Cek isi database admin
SELECT COUNT(*) AS total_buku FROM buku;
SELECT COUNT(*) AS total_pengguna FROM users WHERE role='user';
SELECT COUNT(*) AS total_semua_akun FROM users;
SELECT COUNT(*) AS total_pesanan FROM pesanan;
SELECT COUNT(*) AS total_kategori FROM kategori;
SELECT COUNT(*) AS total_pesan FROM pesan;
SELECT COALESCE(SUM(total),0) AS total_pemasukan
FROM pesanan
WHERE LOWER(TRIM(COALESCE(status_pembayaran,''))) IN ('sudah bayar','lunas','paid');

SELECT id_buku, judul_buku, stok FROM buku ORDER BY id_buku DESC LIMIT 10;
SELECT id_user, nama, email, role FROM users ORDER BY id_user DESC LIMIT 10;
SELECT id_pesanan, id_user, tanggal, total, status_pembayaran, status
FROM pesanan ORDER BY id_pesanan DESC LIMIT 10;
