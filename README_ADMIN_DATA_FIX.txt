LENTERA ADMIN DATA BUGFIX

File ini memperbaiki pembacaan data admin tanpa mengubah config/koneksi.php.

Perbaikan utama:
- Dashboard count buku, pengguna, pesanan, kategori, pemasukan dibaca langsung dari database.
- Buku/pesanan/pesan memakai LEFT JOIN agar data tidak hilang hanya karena relasi user/kategori bermasalah.
- Pemasukan menerima status pembayaran Sudah Bayar / Lunas / Paid.
- Empty state jika tabel memang kosong.
- Query error dicatat ke PHP error_log, tidak dibuat menjadi halaman kosong.
- Konfirmasi pembayaran dan update status memakai prepared statement / allowlist.

PENTING:
Jika dashboard menampilkan angka 0, artinya tabel di database aktif memang kosong. Patch tidak mengimpor data bookstore.sql secara otomatis.
