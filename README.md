# LENTERA — Bookstore

Aplikasi toko buku berbasis **PHP Native + MySQL/MariaDB**.

## Menjalankan di Laragon

1. Letakkan folder project di `C:\laragon\www\bookstore_a`.
2. Salin `config/database.example.php` menjadi `config/database.php` jika file tersebut belum ada.
3. Sesuaikan koneksi database lokal di `config/database.php`.
4. Buat database, misalnya `bookstore`, lalu import `bookstore.sql` melalui phpMyAdmin.
5. Buka `http://localhost/bookstore_a/setup_admin.php` untuk membuat admin pertama.
6. Setelah admin dibuat, login dari `login.php`.

> `config/database.php` sengaja di-ignore Git agar password database tidak masuk GitHub.

## Deploy ke InfinityFree

1. Buat hosting account/domain di InfinityFree.
2. Buat MySQL database dari control panel.
3. Catat **MySQL Hostname, Username, Database Name**, dan password hosting/database.
4. Edit `config/database.php` untuk production. Jangan commit file ini ke GitHub.
5. Import `bookstore.sql` melalui phpMyAdmin InfinityFree.
6. Upload **isi folder project** langsung ke document root/`htdocs` domain, bukan folder `bookstore_a` sebagai subfolder jika ingin situs terbuka langsung dari domain utama.
7. Buka `/setup_admin.php`, masukkan `setup_key`, lalu buat akun admin pertama.
8. Login dan tes katalog, keranjang, checkout, serta halaman admin.

### Contoh `config/database.php` di InfinityFree

```php
<?php
return [
    'host' => 'sqlXXX.infinityfree.com',
    'user' => 'if0_XXXXXXXX',
    'password' => 'PASSWORD_HOSTING',
    'database' => 'if0_XXXXXXXX_bookstore',
    'setup_key' => 'BUAT_KUNCI_ACAK_PANJANG_DI_SINI',
];
```

**Jangan menggunakan `localhost` untuk database InfinityFree.** Gunakan hostname yang ditampilkan pada menu MySQL Databases.

## Security baseline

- Password disimpan menggunakan `password_hash()` dan diverifikasi dengan `password_verify()`.
- Query yang menerima input user memakai prepared statements pada jalur autentikasi dan operasi penting.
- Aksi hapus/update admin menggunakan POST + CSRF token.
- Upload cover hanya menerima JPG/PNG/WEBP dan nama file diacak.
- Credential database dipisahkan ke file yang tidak masuk Git.
- SQL dump tidak memuat akun/password, pesan pribadi, atau riwayat transaksi nyata.

## GitHub

Setelah memastikan `git status` tidak menampilkan `config/database.php`:

```bash
git init
git add .
git commit -m "Initial commit - LENTERA bookstore"
git branch -M main
git remote add origin https://github.com/USERNAME/bookstore_a.git
git push -u origin main
```
