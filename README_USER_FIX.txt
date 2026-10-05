LENTERA USER-SIDE STABILITY PATCH

Tujuan patch:
- Memperbaiki Fatal error: Call to undefined function h()
- Memperbaiki beranda user agar selalu tampil
- Memperbaiki halaman Pesanan + Detail Pesanan
- Memperbaiki Kirim Pesan + Pesan Saya
- Menambahkan empty state agar halaman tidak tampak blank saat data kosong
- Checkout sekarang menyimpan harga item ke detail_pesanan agar riwayat order konsisten
- Navbar user diberi link terpisah Kirim Pesan dan Pesan Saya

PENTING:
- Patch TIDAK berisi config/koneksi.php.
- Jadi koneksi/password InfinityFree yang sudah benar tidak akan tertimpa.
- Upload isi ZIP ke folder htdocs dan overwrite file yang namanya sama.
- Untuk localhost, overwrite file yang sama di folder bookstore_a.

File yang ditambahkan/diubah:
config/helpers.php
template/navbar.php
user/index.php
user/pesanan.php
user/detail_pesanan.php
user/contact.php
user/pesan_saya.php
user/checkout.php
