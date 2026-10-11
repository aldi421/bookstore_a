# LENTERA — BOOKS & STORIES

Redesign visual berbasis kode project `bookstore_a` yang diberikan pengguna. Menggunakan PHP Native, MySQL/MariaDB, CSS dan JavaScript Vanilla.

## Jalankan secara lokal

1. Ekstrak folder `bookstore_a` ke `htdocs/` (XAMPP) atau web root PHP lain.
2. Buat database MySQL bernama **`bookstore_a`**, kemudian import `bookstore.sql` ke database **tersebut** melalui phpMyAdmin. (Dump memiliki komentar historis nama `bookstore`, namun tidak perlu mengganti nama database baru.)
3. Periksa koneksi lokal `config/koneksi.php`: `localhost`, `root`, password kosong, database `bookstore_a` (sesuaikan hanya dengan lingkungan lokal Anda).
4. Buka `http://localhost/bookstore_a/`.
5. Periksa login user dan admin lama, katalog/filter/sort, keranjang, checkout, pesanan, pesan user dan CRUD admin dengan database asli.

## Redesign utama

- Brand LENTERA, tagline "Temukan Buku. Nyalakan Inspirasi." di halaman yang terlihat pengguna.
- Sistem warna forest green + cream, serif editorial untuk judul dan sans serif untuk UI.
- Homepage dengan hero editorial, sampul buku dari database, koleksi unggulan, kategori dinamis, empty state.
- Navbar responsif + sidebar admin mobile drawer dengan tombol dan keyboard Escape.
- Katalog publik dan user dengan pencarian/filter lama serta pengurutan terbaru/harga/A–Z whitelist.
- Login/register split layout, field `name`/tombol `name` dan controller PHP dipertahankan.
- Dashboard admin dengan lima KPI berdasarkan query existing; dashboard user dengan akses cepat.
- Halaman `tentang.php`, `user/profil.php`, `admin/profil.php` (profil read-only).
- CSS utama `css/style.css`, JavaScript utama `js/ui.js`.

## Catatan penting

- Redesign sengaja **tidak mengubah** mekanisme password, query lama, redirect, session, ataupun CRUD. Beberapa mekanisme autentikasi/koneksi lama tetap memerlukan audit keamanan tersendiri sebelum digunakan pada produksi (misalnya password teks biasa dan query login yang belum diprepare).
- `bookstore.sql` dan `config/koneksi.php` tetap mempertahankan kontrak database sebelumnya.
- `images/buku/` dipertahankan apa adanya; cover hero/produk diambil dari database, bukan data dummy.
- Sintaks semua berkas PHP dan sintaks CSS dicek secara statis. Visual Chrome otomatis tidak tersedia karena pembatasan browser pada lingkungan pengujian; uji login, CRUD, dan pembayaran live perlu dilakukan di instalasi Anda yang memiliki MySQL.
- File ZIP ini tidak menyertakan `.git/` agar riwayat Git dan artefak internal tidak ikut dipublikasikan.
